<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Tenant\CondominiumModel;
use App\Models\Tenant\FinancialCategoryModel;
use App\Models\Tenant\FinancialTransactionModel;
use App\Models\Tenant\UnitModel;
use App\Models\Tenant\NotificationModel;

/**
 * FinanceController
 * 
 * Gestión del motor financiero del Condominio (Ingresos, Egresos, Cuotas Mantenimiento).
 */
class FinanceController extends BaseController
{

    /**
     * Muestra el panel de control de finanzas.
     * Si no está activado, muestra el onboarding.
     * Si está activado, muestra el dashboard.
     */
    public function dashboard()
    {
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        $condoModel = new CondominiumModel();
        $demoCondo = $condoModel->find($tenantId);

        if (!$demoCondo) {
            return redirect()->to('/admin/dashboard')->with('error', 'Condominio no configurado.');
        }

        $isBillingActive = (bool) ($demoCondo['is_billing_active'] ?? false);

        $unitModel = new UnitModel();
        $totalUnits = $unitModel->where('condominium_id', $demoCondo['id'])->countAllResults();
        $unitsWithFee = $unitModel->where('condominium_id', $demoCondo['id'])
            ->where('maintenance_fee IS NOT NULL')
            ->where('maintenance_fee >', 0)
            ->countAllResults();

        if ($totalUnits == 0)
            $totalUnits = 1;

        $vencido = 0;
        $ingresos = 0;
        $gastos = 0;
        $unitDebts = [];
        $incomeByCat = [];
        $expenseByCat = [];
        $trendLabels = [];
        $trendIngresos = [];
        $trendGastos = [];
        $trendVencidos = [];

        if ($isBillingActive) {
            $this->generateMonthlyCharges($demoCondo);

            $db = \Config\Database::connect();
            $condoId = $demoCondo['id'];

            // ── Determine selected month from ?month=YYYY-MM param ──
            $monthParam = $this->request->getGet('month');
            if ($monthParam && preg_match('/^\d{4}-\d{2}$/', $monthParam)) {
                $selectedMonth = $monthParam;
            } else {
                $selectedMonth = date('Y-m');
            }

            $monthStart = $selectedMonth . '-01';
            $monthEnd = date('Y-m-t', strtotime($monthStart));
            $today = date('Y-m-d');
            $currentMonth = date('Y-m');

            // Prev / Next for navigation arrows
            $prevMonth = date('Y-m', strtotime('-1 month', strtotime($monthStart)));
            $nextMonth = date('Y-m', strtotime('+1 month', strtotime($monthStart)));

            // ── Determine billing start month (earliest transaction) ──
            $bRow = $db->query(
                "SELECT DATE_FORMAT(MIN(due_date), '%Y-%m') AS billing_start
                 FROM financial_transactions WHERE condominium_id = ? AND type = 'charge'",
                [$condoId]
            )->getRow();
            $billingStartMonth = $bRow && $bRow->billing_start ? $bRow->billing_start : $currentMonth;

            // ── State flags ──
            $isFutureMonth = ($selectedMonth > $currentMonth);
            $isBeforeBilling = ($selectedMonth < $billingStartMonth);

            // Skip heavy queries for future or pre-billing months
            if ($isFutureMonth || $isBeforeBilling) {
                // Leave all KPI/category/trend vars at zero defaults
            } else {
                // ── KPI: Ingresos del mes (credits cuya categoría es 'income') ──
                $row = $db->query("
                SELECT IFNULL(SUM(ft.amount),0) AS total
                FROM financial_transactions ft
                INNER JOIN financial_categories c ON c.id = ft.category_id
                WHERE ft.condominium_id = ? AND ft.type = 'credit'
                  AND ft.status = 'paid' AND c.type = 'income'
                  AND ft.due_date BETWEEN ? AND ?
            ", [$condoId, $monthStart, $monthEnd])->getRow();
                $ingresos = $row ? (float) $row->total : 0.00;

                // ── KPI: Gastos del mes (credits cuya categoría es 'expense') ──
                $row = $db->query("
                SELECT IFNULL(SUM(ft.amount),0) AS total
                FROM financial_transactions ft
                INNER JOIN financial_categories c ON c.id = ft.category_id
                WHERE ft.condominium_id = ? AND ft.type = 'credit'
                  AND ft.status = 'paid' AND c.type = 'expense'
                  AND ft.due_date BETWEEN ? AND ?
            ", [$condoId, $monthStart, $monthEnd])->getRow();
                $gastos = $row ? (float) $row->total : 0.00;

                // ── KPI: Monto vencido del mes ──
                $builderU = $db->table('units');
                // Calculamos Deuda del mes = Residuos no pagados de los cargos de este mes
                $builderU->select('units.id, units.unit_number as label, 
                SUM(IFNULL(ft.amount, 0) - IFNULL(ft.amount_paid, 0)) as debt,
                SUM(CASE WHEN ft.due_date < "' . $today . '" THEN IFNULL(ft.amount, 0) - IFNULL(ft.amount_paid, 0) ELSE 0 END) as debt_vencida
                ');
                $builderU->join('financial_transactions ft', "ft.unit_id = units.id AND ft.type = 'charge' AND ft.status IN ('pending', 'partial') AND ft.due_date BETWEEN '{$monthStart}' AND '{$monthEnd}'", 'left');
                $builderU->where('units.condominium_id', $condoId);
                $builderU->groupBy('units.id');
                $builderU->orderBy('units.unit_number', 'ASC');
                $unitDebtsRaw = $builderU->get()->getResultArray();

                $vencido = 0.00;
                $unitDebts = [];
                foreach ($unitDebtsRaw as $u) {
                    // Monto vencido reflects ONLY debts past their due_date
                    if ($u['debt_vencida'] > 0) {
                        $vencido += (float) $u['debt_vencida'];
                    }
                    $unitDebts[] = $u;
                }

                // ── Ingresos por Categoría (donut) ──
                $incomeByCat = $db->query("
                SELECT c.name, IFNULL(SUM(ft.amount),0) AS total
                FROM financial_transactions ft
                INNER JOIN financial_categories c ON c.id = ft.category_id
                WHERE ft.condominium_id = ? AND ft.type = 'credit'
                  AND ft.status = 'paid' AND c.type = 'income'
                  AND ft.due_date BETWEEN ? AND ?
                GROUP BY c.id ORDER BY total DESC
            ", [$condoId, $monthStart, $monthEnd])->getResultArray();

                // ── Gastos por Categoría (donut) ──
                $expenseByCat = $db->query("
                SELECT c.name, IFNULL(SUM(ft.amount),0) AS total
                FROM financial_transactions ft
                INNER JOIN financial_categories c ON c.id = ft.category_id
                WHERE ft.condominium_id = ? AND ft.type = 'credit'
                  AND ft.status = 'paid' AND c.type = 'expense'
                  AND ft.due_date BETWEEN ? AND ?
                GROUP BY c.id ORDER BY total DESC
            ", [$condoId, $monthStart, $monthEnd])->getResultArray();

                $mesesCortos = ['Jan' => 'Ene', 'Feb' => 'Feb', 'Mar' => 'Mar', 'Apr' => 'Abr', 'May' => 'May', 'Jun' => 'Jun', 'Jul' => 'Jul', 'Aug' => 'Ago', 'Sep' => 'Sep', 'Oct' => 'Oct', 'Nov' => 'Nov', 'Dec' => 'Dic'];
                // ── Tendencias 6 meses ──
                for ($i = 5; $i >= 0; $i--) {
                    $mStart = date('Y-m-01', strtotime("-$i months", strtotime($monthStart)));
                    $mEnd = date('Y-m-t', strtotime("-$i months", strtotime($monthStart)));
                    $trendLabels[] = strtr(date('M', strtotime($mStart)), $mesesCortos);

                    $r = $db->query("
                    SELECT IFNULL(SUM(ft.amount),0) AS total
                    FROM financial_transactions ft
                    INNER JOIN financial_categories c ON c.id = ft.category_id
                    WHERE ft.condominium_id = ? AND ft.type = 'credit'
                      AND ft.status = 'paid' AND c.type = 'income'
                      AND ft.due_date BETWEEN ? AND ?
                ", [$condoId, $mStart, $mEnd])->getRow();
                    $trendIngresos[] = $r ? (float) $r->total : 0;

                    $r = $db->query("
                    SELECT IFNULL(SUM(ft.amount),0) AS total
                    FROM financial_transactions ft
                    INNER JOIN financial_categories c ON c.id = ft.category_id
                    WHERE ft.condominium_id = ? AND ft.type = 'credit'
                      AND ft.status = 'paid' AND c.type = 'expense'
                      AND ft.due_date BETWEEN ? AND ?
                ", [$condoId, $mStart, $mEnd])->getRow();
                    $trendGastos[] = $r ? (float) $r->total : 0;

                    $r = $db->query("
                    SELECT IFNULL(SUM(amount - IFNULL(amount_paid, 0)), 0) AS total FROM financial_transactions
                    WHERE type = 'charge' AND status IN ('pending', 'partial')
                      AND condominium_id = ? AND due_date BETWEEN ? AND ?
                ", [$condoId, $mStart, $mEnd])->getRow();
                    $trendVencidos[] = $r && $r->total > 0 ? (float) $r->total : 0;
                }

                // ── Cobranza por Unidad (Generado arriba) ──
                // Ya está disponible en $unitDebts. Solo generamos las estadísticas de dashboard.
            } // end else (not future / not before billing)
        }

        $data = [
            'is_billing_active' => $isBillingActive,
            'condo' => $demoCondo,
            'stats_onboarding' => ['total_units' => $totalUnits, 'units_with_fee' => $unitsWithFee],
            'kpis' => ['ingresos' => $ingresos, 'gastos' => $gastos, 'vencido' => $vencido],
            'unit_debts' => $unitDebts,
            'income_by_cat' => $incomeByCat,
            'expense_by_cat' => $expenseByCat,
            'trend_labels' => $trendLabels,
            'trend_ingresos' => $trendIngresos,
            'trend_gastos' => $trendGastos,
            'trend_vencidos' => $trendVencidos,
            'selectedMonth' => $selectedMonth ?? date('Y-m'),
            'prevMonth' => $prevMonth ?? date('Y-m', strtotime('-1 month')),
            'nextMonth' => $nextMonth ?? date('Y-m', strtotime('+1 month')),
            'isFutureMonth' => $isFutureMonth ?? false,
            'isBeforeBilling' => $isBeforeBilling ?? false,
            'billingStartMonth' => $billingStartMonth ?? date('Y-m'),
        ];

        return view('admin/finance/panel_control', $data);
    }


    /**
     * Muestra la vista de Morosidad (Unidades deudoras)
     */
    public function morosidad()
    {
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        $condoModel = new CondominiumModel();
        $demoCondo = $condoModel->find($tenantId);

        if (!$demoCondo) {
            return redirect()->to('/admin/dashboard')->with('error', 'Condominio no configurado.');
        }

        // Generar cargos mensuales pendientes si aplica
        $this->generateMonthlyCharges($demoCondo);

        $db = \Config\Database::connect();
        $condoId = $demoCondo['id'];
        $today = date('Y-m-d');

        // 1. Unidades al corriente y Unidades Morosas
        // Para esto sumamos cargos (positive) y restamos créditos (negative)
        $builderU = $db->table('units');
        $builderU->select('units.id, 
            IFNULL(units.initial_balance, 0) + IFNULL(SUM(CASE WHEN ft.type = "charge" THEN ft.amount WHEN ft.type = "credit" THEN -ft.amount ELSE 0 END), 0) as debt,
            IFNULL(units.initial_balance, 0) + IFNULL(SUM(CASE WHEN ft.type = "charge" AND ft.due_date < "' . $today . '" THEN ft.amount WHEN ft.type = "credit" THEN -ft.amount ELSE 0 END), 0) as debt_vencida
        ');
        $builderU->join('financial_transactions ft', 'ft.unit_id = units.id AND ft.status != "cancelled"', 'left');
        $builderU->where('units.condominium_id', $condoId);
        $builderU->groupBy('units.id');
        $allUnitsData = $builderU->get()->getResultArray();



        $unitsOk = 0;
        $unitsDebt = 0;
        $totalOverdue = 0.00;

        foreach ($allUnitsData as $u) {
            if ($u['debt_vencida'] > 0.01) {
                $unitsDebt++;
                $totalOverdue += (float) $u['debt'];
            } else {
                $unitsOk++;
            }
        }

        // 3. Obtener el listado agrupado por Torres de TODAS las unidades, para que sirva tanto a Cuadrícula como a Tabla.
        // Join con `sections` para el nombre de la torre.
        $builderD = $db->table('units');
        $builderD->select('units.id, units.unit_number as label, units.floor, sec.name as section_name, 
            IFNULL(units.initial_balance, 0) + IFNULL(SUM(CASE WHEN ft.type = "charge" THEN ft.amount WHEN ft.type = "credit" THEN -ft.amount ELSE 0 END), 0) as debt,
            IFNULL(units.initial_balance, 0) + IFNULL(SUM(CASE WHEN ft.type = "charge" AND ft.due_date < "' . $today . '" THEN ft.amount WHEN ft.type = "credit" THEN -ft.amount ELSE 0 END), 0) as debt_vencida
        ');
        $builderD->join('sections sec', 'sec.id = units.section_id', 'left');
        $builderD->join('financial_transactions ft', 'ft.unit_id = units.id AND ft.status != "cancelled"', 'left');
        $builderD->where('units.condominium_id', $condoId);
        $builderD->groupBy('units.id, sec.name');
        $builderD->orderBy('sec.name', 'ASC');
        $builderD->orderBy('units.unit_number', 'ASC');
        $allDetailedUnits = $builderD->get()->getResultArray();

        // Agrupar unidades por Torre. Solo agruparemos para la gráfica, pero mandaremos flat para la tabla
        $groupedUnits = [];
        $flatUnits = []; // Todas se mandan, pero la vista "Tabla de morosas" solo listará las deudoras (debt > 0).

        foreach ($allDetailedUnits as $du) {
            $secName = empty($du['section_name']) ? 'Sin Torre Asignada' : $du['section_name'];
            if (!isset($groupedUnits[$secName])) {
                $groupedUnits[$secName] = [];
            }
            $groupedUnits[$secName][] = $du;
            $flatUnits[] = $du;
        }

        $data = [
            'condo' => $demoCondo,
            'kpis' => [
                'units_ok' => $unitsOk,
                'units_debt' => $unitsDebt,
                'total_overdue' => $totalOverdue
            ],
            'grouped_units' => $groupedUnits,
            'flat_units' => $flatUnits
        ];

        // 4. Obtener categorías para el modal de Editar Transacción
        $builderCat = $db->table('financial_categories');
        $builderCat->where('condominium_id', $demoCondo['id']);
        $builderCat->orderBy('id', 'ASC');
        $data['categories'] = $builderCat->get()->getResultArray();

        return view('admin/finance/morosidad', $data);
    }

    /**
     * Exporta el estado de morosidad a PDF o CSV
     */
    public function exportMorosidad()
    {
        $format = $this->request->getGet('format') ?? 'pdf';

        if ($format === 'pdf') {
            require_once ROOTPATH . 'vendor/autoload.php';
        }

        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        $condoModel = new CondominiumModel();
        $demoCondo = $condoModel->find($tenantId);

        if (!$demoCondo) {
            return redirect()->to('/admin/dashboard')->with('error', 'Condominio no configurado.');
        }

        // Generar cargos mensuales pendientes si aplica
        $this->generateMonthlyCharges($demoCondo);

        $db = \Config\Database::connect();
        $condoId = $demoCondo['id'];

        $today = date('Y-m-d');

        // Obtener el listado de unidades con su deuda actual
        $builderD = $db->table('units');
        $builderD->select('units.id, units.unit_number as label, units.floor, sec.name as section_name, 
            IFNULL(units.initial_balance, 0) + IFNULL(SUM(CASE WHEN ft.type = "charge" THEN ft.amount WHEN ft.type = "credit" THEN -ft.amount ELSE 0 END), 0) as debt,
            IFNULL(units.initial_balance, 0) + IFNULL(SUM(CASE WHEN ft.type = "charge" AND ft.due_date < "' . $today . '" THEN ft.amount WHEN ft.type = "credit" THEN -ft.amount ELSE 0 END), 0) as debt_vencida
        ');
        $builderD->join('sections sec', 'sec.id = units.section_id', 'left');
        $builderD->join('financial_transactions ft', 'ft.unit_id = units.id AND ft.status != "cancelled"', 'left');
        $builderD->where('units.condominium_id', $condoId);
        $builderD->groupBy('units.id, sec.name');
        $builderD->orderBy('sec.name', 'ASC');
        $builderD->orderBy('units.unit_number', 'ASC');
        $allUnits = $builderD->get()->getResultArray();

        $morosos = [];
        $totalOverdue = 0.00;
        foreach ($allUnits as $u) {
            if ($u['debt_vencida'] > 0.01) {
                $morosos[] = $u;
                $totalOverdue += (float) $u['debt'];
            }
        }

        $condominiumName = esc($demoCondo['name']);

   $logoFile = $demoCondo['logo'] ?? '';
        if (!empty($logoFile)) {
            $logoPath = (strpos($logoFile, '/') !== false)
                ? WRITEPATH . 'uploads/' . $logoFile
                : WRITEPATH . 'uploads/condominiums/' . $demoCondo['id'] . '/' . $logoFile;
            $hasLogo = is_file($logoPath);
        } else {
            $logoPath = '';
            $hasLogo = false;
        }

        // Preparar fecha (ej: 26 de Marzo de 2026)
        $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $fechaGen = date('d') . ' de ' . $meses[date('n') - 1] . ' de ' . date('Y');

        if ($format === 'csv') {
            return $this->_exportMorosidadCSV($condominiumName, $fechaGen, count($morosos), $totalOverdue, $allUnits);
        } else {
            return $this->_exportMorosidadPDF($condominiumName, $fechaGen, $hasLogo, $logoPath, count($morosos), $totalOverdue, $allUnits);
        }
    }

    private function _exportMorosidadCSV($condominiumName, $fechaGen, $totalMorosas, $totalOverdue, $allUnits)
    {
        $filename = 'Reporte_Morosidad_' . date('Ymd') . '.csv';

        // Abrir un stream en memoria y forzar codificación UTF-8 con BOM (para que Excel lo lea nativamente en UTF-8)
        $output = fopen('php://temp', 'r+');
        fwrite($output, "\xEF\xBB\xBF");

        // Cabeceras simuladas estilo Excel
        fputcsv($output, ['Reporte de Morosidad']);
        fputcsv($output, ['Comunidad', $condominiumName]);
        fputcsv($output, ['Generado el', $fechaGen]);
        fputcsv($output, []);
        fputcsv($output, ['Resumen']);
        fputcsv($output, ['Total de Unidades Morosas', $totalMorosas]);
        fputcsv($output, ['Monto Total', $totalOverdue]);
        fputcsv($output, []);

        // Cabeceras de tabla
        fputcsv($output, ['Sección', 'Unidad', 'Piso', 'Estatus', 'Saldo']);

        foreach ($allUnits as $u) {
            $debtToPrint = (float) $u['debt'];
            $debtVencida = (float) $u['debt_vencida'];

            $statusText = '';
            if ($debtVencida > 0.01) {
                $statusText = 'Moroso';
            } elseif ($debtToPrint > 0.01) {
                $statusText = 'Al corriente';
            } elseif ($debtToPrint < -0.01) {
                $statusText = 'A favor';
            } else {
                $statusText = 'Sin adeudos';
            }

            fputcsv($output, [
                $u['section_name'] ?? 'General',
                $u['label'],
                $u['floor'],
                $statusText,
                $debtToPrint
            ]);
        }

        rewind($output);
        $csvData = stream_get_contents($output);
        fclose($output);

        return $this->response->setHeader('Content-Type', 'text/csv; charset=utf-8')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($csvData);
    }

    private function _exportMorosidadPDF($condominiumName, $fechaGen, $hasLogo, $logoPath, $totalMorosas, $totalOverdue, $allUnits)
    {
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator('Condominet');
        $pdf->SetAuthor($condominiumName);
        $pdf->SetTitle('Reporte de Morosidad');

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetAutoPageBreak(TRUE, 15);
        $pdf->SetMargins(15, 15, 15);
        $pdf->AddPage();

        // ── 1. Cabecera Premium (Bordes Sutiles y Tono Dominante UI) ──
        $pdf->SetDrawColor(28, 36, 52); // #3F67ACborde superior
        $pdf->SetLineWidth(1.2); // Fuerte detalle visual arriba (SaaS Style header accent)
        $pdf->Line(15, 14, 195, 14);

        $pdf->SetDrawColor(226, 232, 240); // #e2e8f0 borders
        $pdf->SetLineWidth(0.4);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->Rect(15, 14, 180, 24, 'DF'); // Soft bounding box

        if ($hasLogo) {
            $pdf->Image($logoPath, 18, 16.5, 0, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
            $pdf->SetXY(70, 21);
        } else {
            $pdf->SetXY(20, 21);
        }

        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->SetTextColor(28, 36, 52); // #1D4C9D
        $pdf->Cell(0, 8, 'Reporte de Morosidad ' . strtoupper($condominiumName), 0, 1, 'L');

        // ── 2. Fecha Generada ──
        if ($hasLogo) {
            $pdf->SetX(70);
        } else {
            $pdf->SetX(20);
        }
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetTextColor(100, 116, 139);
        $pdf->Cell(0, 5, 'Generado el: ' . $fechaGen, 0, 1, 'L');

        $pdf->SetY(44);

        // ── 3. Resumen ──
        $pdf->SetFont('helvetica', 'B', 13);
        $pdf->SetTextColor(30, 41, 59); // Slate dark #1e293b
        $pdf->Cell(0, 8, 'Resumen', 0, 1, 'L');
        $pdf->SetDrawColor(226, 232, 240);
        $pdf->SetLineWidth(0.5);
        $pdf->Line(15, $pdf->GetY(), 80, $pdf->GetY());
        $pdf->Ln(4);

        $htmlResumen = '
        <table border="1" cellpadding="6" cellspacing="0" style="border: 1px solid #e2e8f0;">
            <thead>
                <tr style="background-color: #1D4C9D; color: #ffffff;">
                    <th width="60%"><b>Métrica</b></th>
                    <th width="40%" align="right"><b>Valor</b></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td width="60%" style="color:#3F67AC;">Total de Unidades Morosas</td>
                    <td width="40%" align="right" style="color:#0f172a;"><b>' . $totalMorosas . '</b></td>
                </tr>
                <tr style="background-color: #f8fafc;">
                    <td width="60%" style="color:#3F67AC;">Monto Total Moroso</td>
                    <td width="40%" align="right" style="color:#0f172a;"><b>MX$' . number_format($totalOverdue, 2) . '</b></td>
                </tr>
            </tbody>
        </table>';

        $pdf->SetFont('helvetica', '', 9);
        $pdf->writeHTML($htmlResumen, true, false, false, false, '');
        $pdf->Ln(6);

        // ── 4. Estado General de Unidades (Añadiendo Sección) ──
        $pdf->SetFont('helvetica', 'B', 13);
        $pdf->SetTextColor(30, 41, 59);
        $pdf->Cell(0, 8, 'Estado General de Unidades', 0, 1, 'L');
        $pdf->Line(15, $pdf->GetY(), 80, $pdf->GetY());
        $pdf->Ln(4);

        // Tabla con nuevo ancho fraccional
        $htmlDetalle = '
        <table border="1" cellpadding="6" cellspacing="0" style="border: 1px solid #e2e8f0; border-collapse:collapse;">
            <thead>
                <tr style="background-color: #1D4C9D; color: #ffffff;">
                    <th width="20%"><b>Sección</b></th>
                    <th width="25%"><b>Unidad</b></th>
                    <th width="10%" align="center"><b>Piso</b></th>
                    <th width="25%" align="center"><b>Estatus</b></th>
                    <th width="20%" align="right"><b>Saldo</b></th>
                </tr>
            </thead>
            <tbody>';

        $rCount = 0;
        foreach ($allUnits as $m) {
            $bg = ($rCount % 2 == 0) ? '#ffffff' : '#f8fafc';

            $seccionText = !empty($m['section_name']) ? esc($m['section_name']) : 'General';
            $debtToPrint = (float) $m['debt'];
            $debtVencida = (float) $m['debt_vencida'];

            $statusText = '';
            $statusColor = '';

            if ($debtVencida > 0.01) {
                $statusText = 'Moroso';
                $statusColor = '#ef4444'; // Red
            } elseif ($debtToPrint > 0.01) {
                $statusText = 'Al corriente';
                $statusColor = '#0284c7'; // Blue
            } elseif ($debtToPrint < -0.01) {
                $statusText = 'A favor';
                $statusColor = '#059669'; // Green
            } else {
                $statusText = 'Sin adeudos';
                $statusColor = '#10b981'; // Light Green
            }

            $htmlDetalle .= '
                <tr style="background-color: ' . $bg . ';">
                    <td width="20%" style="color:#64748b;">' . $seccionText . '</td>
                    <td width="25%" style="color:#1e293b; font-weight:bold;">' . esc($m['label']) . '</td>
                    <td width="10%" align="center" style="color:#64748b;">' . esc($m['floor']) . '</td>
                    <td width="25%" align="center"><span style="color:' . $statusColor . '; font-weight:bold;">' . $statusText . '</span></td>
                    <td width="20%" align="right"><span style="color:' . $statusColor . '; font-weight:bold;">MX$' . number_format(abs($debtToPrint), 2) . '</span></td>
                </tr>';
            $rCount++;
        }

        if (count($allUnits) === 0) {
            $htmlDetalle .= '<tr><td colspan="5" align="center" style="color:#64748b;">No hay unidades registradas.</td></tr>';
        }

        $htmlDetalle .= '</tbody></table>';

        $pdf->SetFont('helvetica', '', 9);
        $pdf->writeHTML($htmlDetalle, true, false, false, false, '');

        // ── 5. Pie de Página Nativo Premium ──
        $numPages = $pdf->getNumPages();
        for ($i = 1; $i <= $numPages; $i++) {
            $pdf->setPage($i);
            $pdf->SetDrawColor(226, 232, 240); // #e2e8f0
            $pdf->Line(15, 260, 195, 260); // Línea sutil en footer
            $pdf->SetXY(15, 263);
            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetTextColor(100, 116, 139);
            $pdf->Cell(60, 5, strtoupper($condominiumName), 0, 0, 'L');
            $pdf->SetXY(75, 263);
            $pdf->Cell(60, 5, 'Página ' . $i . ' de ' . $numPages, 0, 0, 'C');
            $pdf->SetXY(135, 263);
            $pdf->Cell(60, 5, date('d/m/Y'), 0, 0, 'R');
        }

        $pdfContent = $pdf->Output('Reporte_Morosidad.pdf', 'S');
        return $this->response->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="Reporte_Morosidad.pdf"')
            ->setBody($pdfContent);
    }

    /**
     * Muestra la vista "Nuevo Registro" (Creación manual/masiva de cargos y pagos)
     */
    public function nuevoRegistro()
    {
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        $condoModel = new CondominiumModel();
        $demoCondo = $condoModel->find($tenantId);

        if (!$demoCondo) {
            return redirect()->to('/admin/dashboard')->with('error', 'Condominio no configurado.');
        }

        $db = \Config\Database::connect();

        $builderCat = $db->table('financial_categories');
        $builderCat->where('condominium_id', $demoCondo['id']);
        $builderCat->orderBy('id', 'ASC');
        $categories = $builderCat->get()->getResultArray();

        // Define mandatory categories
        $mandatoryIncomes = [
            'Cuota de Mantenimiento',
            'Cargo por Mora',
            'Cargo de Reserva de Amenidad',
            'Multa de Amenidad',
            'Multa de Estacionamiento',
            'Multa de Mascota',
            'Multa por Infracción',
            'Otro Ingreso'
        ];

        $mandatoryExpenses = [
            'Salario del Personal',
            'Mantenimiento y Reparaciones',
            'Servicios Públicos',
            'Suministros',
            'Servicios Profesionales',
            'Seguro',
            'Otro Gasto'
        ];

        $existingIncomes = [];
        $existingExpenses = [];
        foreach ($categories as $c) {
            if ($c['type'] === 'income')
                $existingIncomes[] = $c['name'];
            if ($c['type'] === 'expense')
                $existingExpenses[] = $c['name'];
        }

        $newCategories = [];
        foreach ($mandatoryIncomes as $name) {
            if (!in_array($name, $existingIncomes)) {
                $newCategories[] = ['name' => $name, 'type' => 'income', 'condominium_id' => $demoCondo['id'], 'is_system' => 1];
            }
        }
        foreach ($mandatoryExpenses as $name) {
            if (!in_array($name, $existingExpenses)) {
                $newCategories[] = ['name' => $name, 'type' => 'expense', 'condominium_id' => $demoCondo['id'], 'is_system' => 1];
            }
        }

        if (!empty($newCategories)) {
            $db->table('financial_categories')->insertBatch($newCategories);
            // Refresh categories list
            $categories = $db->table('financial_categories')->where('condominium_id', $demoCondo['id'])->orderBy('id', 'ASC')->get()->getResultArray();
        }

        // Cargar unidades activas para el panel lateral de selección masiva
        $builderU = $db->table('units');
        $builderU->select('units.id, units.unit_number as label, units.maintenance_fee, sec.name as section_name');
        $builderU->join('sections sec', 'sec.id = units.section_id', 'left');
        $builderU->where('units.condominium_id', $demoCondo['id']);
        $builderU->orderBy('sec.name', 'ASC');
        $builderU->orderBy('units.unit_number', 'ASC');
        $unitsRaw = $builderU->get()->getResultArray();

        $groupedUnits = [];
        $totalUnits = 0;
        foreach ($unitsRaw as $u) {
            $sec = empty($u['section_name']) ? 'Otras Unidades' : $u['section_name'];
            if (!isset($groupedUnits[$sec]))
                $groupedUnits[$sec] = [];
            $groupedUnits[$sec][] = $u;
            $totalUnits++;
        }

        $data = [
            'condo' => $demoCondo,
            'categories' => $categories,
            'groupedUnits' => $groupedUnits,
            'totalUnits' => $totalUnits
        ];

        return view('admin/finance/nuevo_registro', $data);
    }

    /**
     * Procesa la solicitud AJAX para guardar nuevos registros (Cargos/Pagos)
     */
    public function storeRegistro()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        $condoModel = new CondominiumModel();
        $demoCondo = $condoModel->find($tenantId);
        if (!$demoCondo) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Condominio no configurado.']);
        }

        $transType = $this->request->getPost('transType'); // income / expense
        $categoriaId = $this->request->getPost('categoryId');
        $destino = $this->request->getPost('destino');
        $transMode = $this->request->getPost('transMode'); // charge / payment / both
        $monto = $this->request->getPost('amount');
        $fecha = $this->request->getPost('date');
        $desc = $this->request->getPost('description');
        $paymentMethod = $this->request->getPost('paymentMethod');
        $unitIds = $this->request->getPost('unitIds');
        if (!is_array($unitIds))
            $unitIds = json_decode((string) $unitIds, true);

        if ($transType !== 'expense' && (empty($unitIds) || !is_array($unitIds))) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No se seleccionaron unidades.']);
        }

        $db = \Config\Database::connect();

        // --- Creación Dinámica de Categoría ---
        if (strpos($categoriaId, 'NEW:') === 0) {
            $catName = substr($categoriaId, 4);
            $builderCat = $db->table('financial_categories');
            $builderCat->insert([
                'condominium_id' => $demoCondo['id'],
                'name' => mb_convert_case(trim($catName), MB_CASE_TITLE, "UTF-8"),
                'description' => 'Creada al registrar un ' . ($transType === 'expense' ? 'Gasto' : 'Ingreso'),
                'type' => $transType,
                'is_system' => 0
            ]);
            $categoriaId = $db->insertID();
        }

        // --- Manejo de Adjuntos Múltiples ---
        $attachmentPaths = [];
        $files = $this->request->getFileMultiple('attachments');

        if ($files) {
            foreach ($files as $file) {
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    // Validar tamaño (5MB)
                    if ($file->getSizeByUnit('mb') > 5) {
                        return $this->response->setJSON(['status' => 'error', 'message' => 'Un archivo excede los 5MB permitidos.']);
                    }

                    $newName = $file->getRandomName();
                    $file->move(WRITEPATH . 'uploads/financial', $newName);
                    $attachmentPaths[] = 'financial/' . $newName;
                }
            }
        }

        $attachmentPath = !empty($attachmentPaths) ? json_encode($attachmentPaths) : null;

        $db = \Config\Database::connect();
        $builderTrans = $db->table('financial_transactions');

        $insertedCount = 0;

        // --- Pre-agrupar paidCharges por unit_id para distribución correcta ---
        $paidChargesRaw = json_decode((string) $this->request->getPost('paidCharges'), true);
        $chargesByUnit = [];
        if (is_array($paidChargesRaw) && count($paidChargesRaw) > 0) {
            foreach ($paidChargesRaw as $pc) {
                $cId = (int) $pc['charge_id'];
                $chargeRow = $db->table('financial_transactions')->select('unit_id')->where('id', $cId)->get()->getRowArray();
                if ($chargeRow && $chargeRow['unit_id']) {
                    $uid = $chargeRow['unit_id'];
                    if (!isset($chargesByUnit[$uid]))
                        $chargesByUnit[$uid] = [];
                    $chargesByUnit[$uid][] = $pc;
                }
            }
        }

        $db->transStart();

        if ($transType === 'expense' && empty($unitIds)) {
            $unitIds = [null];
        }

        foreach ($unitIds as $unitId) {
            $finalAmount = 0.00;

            if ($unitId !== null) {
                // Obtener la unidad actual
                $unitModel = new UnitModel();
                $unit = $unitModel->find($unitId);
                if (!$unit)
                    continue;

                // Determinar Monto Final
                if ($transMode === 'payment' && !empty($chargesByUnit)) {
                    // En modo pago con cargos pendientes seleccionados:
                    // Solo usar el monto de los cargos de ESTA unidad
                    if (!empty($chargesByUnit[$unitId])) {
                        $finalAmount = 0;
                        foreach ($chargesByUnit[$unitId] as $pc) {
                            $finalAmount += (float) $pc['amount'];
                        }
                    } else {
                        // Esta unidad NO tiene cargos pendientes seleccionados â†’ saltar
                        continue;
                    }
                } elseif (!empty($monto) && is_numeric($monto)) {
                    $finalAmount = (float) $monto;
                } else {
                    $finalAmount = (float) $unit['maintenance_fee'];
                }
            } else {
                // Gasto General
                if (!empty($monto) && is_numeric($monto)) {
                    $finalAmount = (float) $monto;
                }
            }

            // Validar
            if ($finalAmount <= 0) {
                continue; // Skip si no hay monto válido
            }

            // CREAR CARGO
            if ($transMode === 'charge' || $transMode === 'both') {
                $dataCharge = [
                    'condominium_id' => $demoCondo['id'],
                    'unit_id' => $unitId,
                    'category_id' => $categoriaId ?: null,
                    'type' => $transType === 'expense' ? 'credit' : 'charge',
                    'amount' => $finalAmount,
                    'due_date' => $fecha ?: date('Y-m-d'),
                    'status' => $transType === 'expense' ? 'paid' : ($transMode === 'both' ? 'paid' : 'pending'),
                    'description' => $desc ?: ($transType === 'expense' ? 'Gasto registrado manualmente' : 'Cargo generado manualmente'),
                    'attachment' => $attachmentPath,
                    'payment_method' => $paymentMethod,
                    'source' => 'manual',
                    'created_at' => $fecha ? $fecha . ' ' . date('H:i:s') : date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                $builderTrans->insert($dataCharge);
                $insertedCount++;
            }

            // REGISTRAR PAGO
            if ($transMode === 'payment' || $transMode === 'both') {
                // El monto del pago es el finalAmount ya calculado correctamente por unidad
                $unitPaymentAmount = $finalAmount;

                $dataPayment = [
                    'condominium_id' => $demoCondo['id'],
                    'unit_id' => $unitId,
                    'category_id' => $categoriaId ?: null,
                    'type' => 'credit',
                    'amount' => $unitPaymentAmount,
                    'due_date' => $fecha ?: date('Y-m-d'),
                    'status' => 'paid',
                    'description' => $desc ?: 'Pago registrado manualmente',
                    'attachment' => $attachmentPath,
                    'payment_method' => $paymentMethod,
                    'source' => 'manual',
                    'created_at' => $fecha ? $fecha . ' ' . date('H:i:s') : date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                $builderTrans->insert($dataPayment);
                $insertedCount++;

                // Lógica Pago a Cuotas Vencidas (usar cargos pre-agrupados por unidad)
                $unitPaidCharges = $chargesByUnit[$unitId] ?? [];
                $explicitlyAllocated = 0.00;

                if (count($unitPaidCharges) > 0) {
                    foreach ($unitPaidCharges as $pc) {
                        $cId = (int) $pc['charge_id'];
                        $pAmount = (float) $pc['amount'];

                        if ($pAmount <= 0)
                            continue;

                        $chargeRow = $db->table('financial_transactions')->where('id', $cId)->where('unit_id', $unitId)->get()->getRowArray();
                        if ($chargeRow) {
                            $explicitlyAllocated += $pAmount;
                            $newPaid = (float) $chargeRow['amount_paid'] + $pAmount;
                            // Tolerancia de centavos
                            $newStatus = ($newPaid >= ((float) $chargeRow['amount'] - 0.01)) ? 'paid' : 'partial';

                            $db->table('financial_transactions')->where('id', $cId)->update([
                                'amount_paid' => $newPaid,
                                'status' => $newStatus,
                                'updated_at' => date('Y-m-d H:i:s')
                            ]);
                        }
                    }
                }

                // Auto-Distribución FIFO (Sobrante o Pago 'Por Lote')
                $remainingToAllocate = $unitPaymentAmount - $explicitlyAllocated;
                if ($remainingToAllocate > 0.01) {
                    $pendingCharges = $db->table('financial_transactions')
                        ->where('unit_id', $unitId)
                        ->where('type', 'charge')
                        ->whereIn('status', ['pending', 'partial'])
                        ->orderBy('due_date', 'ASC')
                        ->get()->getResultArray();

                    foreach ($pendingCharges as $pending) {
                        if ($remainingToAllocate <= 0.01)
                            break;

                        $debtRemaining = (float) $pending['amount'] - (float) $pending['amount_paid'];
                        if ($debtRemaining <= 0)
                            continue;

                        $amountToApply = min($remainingToAllocate, $debtRemaining);
                        $newPaid = (float) $pending['amount_paid'] + $amountToApply;
                        $newStatus = ($newPaid >= ((float) $pending['amount'] - 0.01)) ? 'paid' : 'partial';

                        $db->table('financial_transactions')->where('id', $pending['id'])->update([
                            'amount_paid' => $newPaid,
                            'status' => $newStatus,
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);

                        $remainingToAllocate -= $amountToApply;
                    }
                }
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error de base de datos al guardar los registros.']);
        }

        if (!empty($unitIds)) {
            foreach ($unitIds as $uId) {
                if ($uId !== null) {
                    $this->applyFloatingCredit((int) $uId);
                    $this->recalculateUnitBalances((int) $uId);

                    // Notify residents
                    $residents = $db->table('residents')
                        ->select('DISTINCT(user_id) as user_id')
                        ->where('unit_id', $uId)
                        ->where('condominium_id', $demoCondo['id'])
                        ->where('is_active', 1)
                        ->where('user_id IS NOT NULL')
                        ->get()->getResultArray();

                    foreach ($residents as $r) {
                        if ($transMode === 'charge' || $transMode === 'both') {
                            NotificationModel::notify(
                                $demoCondo['id'],
                                (int) $r['user_id'],
                                'new_charge',
                                'Nueva cuota generada',
                                'Se ha registrado un nuevo cargo en tu estado de cuenta.',
                                ['unit_id' => $uId, 'tipo' => 'nueva_cuota'],
                                true
                            );
                        }
                        if ($transMode === 'payment' || $transMode === 'both') {
                            NotificationModel::notify(
                                $demoCondo['id'],
                                (int) $r['user_id'],
                                'payment_approved',
                                'Pago registrado',
                                'Se ha registrado un pago en tu estado de cuenta.',
                                ['unit_id' => $uId, 'tipo' => 'pago_registrado'],
                                true
                            );
                        }
                    }
                }
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Se crearon ' . $insertedCount . ' registros correctamente.'
        ]);
    }

    /**
     * Muestra la vista de Movimientos Mensuales
     */
    public function movimientos()
    {
        $condoModel = new CondominiumModel();
        $demoCondo = $condoModel->first();

        if (!$demoCondo) {
            return redirect()->to('/admin/dashboard')->with('error', 'Condominio no configurado.');
        }

        $db = \Config\Database::connect();
        $condoId = $demoCondo['id'];

        $selectedMonth = $this->request->getGet('month') ?: date('Y-m');
        @list($y, $m) = explode('-', $selectedMonth);
        if (!$y || !$m) {
            $y = date('Y');
            $m = date('m');
            $selectedMonth = date('Y-m');
        }

        // Obtener categorías para los filtros
        $categoriesRaw = $db->table('financial_categories')->where('condominium_id', $condoId)->get()->getResultArray();

        $db->query("UPDATE financial_transactions SET source = 'auto' WHERE source = 'manual' AND description LIKE 'Cuota de Mantenimiento%' AND condominium_id = ?", [$condoId]);

        $builder = $db->table('financial_transactions ft');
        $builder->select('ft.*, units.unit_number, cats.name as category_name, cats.type as category_type');
        $builder->join('units', 'units.id = ft.unit_id', 'left');
        $builder->join('financial_categories cats', 'cats.id = ft.category_id', 'left');
        $builder->where('ft.condominium_id', $condoId);
        $builder->where('ft.source', 'manual');
        $builder->where('ft.status !=', 'cancelled');
        $builder->where('ft.type !=', 'charge'); // Solo Pagos y Gastos
        $builder->where('MONTH(ft.created_at)', $m);
        $builder->where('YEAR(ft.created_at)', $y);
        $builder->orderBy('ft.created_at', 'ASC');

        $results = $builder->get()->getResultArray();

        $records = [];
        $groupedRecords = [];
        $totalIngresos = 0.00;
        $totalGastos = 0.00;

        foreach ($results as $row) {
            $tipo = ($row['category_type'] === 'expense') ? 'egreso' : 'ingreso';
            $monto = (float) $row['amount'];

            if ($tipo === 'ingreso') {
                $totalIngresos += $monto;
            } else {
                $totalGastos += $monto;
            }

            $catName = $row['category_name'] ?: 'Sin Categoría';

            $rec = [
                'id' => $row['id'],
                'fecha_raw' => $row['created_at'],
                'fecha' => date('d M Y', strtotime($row['created_at'])),
                'fecha_larga' => (new \IntlDateFormatter('es_MX', \IntlDateFormatter::LONG, \IntlDateFormatter::NONE))->format(strtotime($row['created_at'])),
                'descripcion' => $row['description'],
                'categoria' => $catName,
                'category_id' => $row['category_id'],
                'monto' => $monto,
                'tipo' => $tipo,
                'estado' => ($row['status'] === 'paid' || $row['status'] === 'completed') ? 'Completado' : 'Pendiente',
                'unidad' => $row['unit_number'] ?: 'Gasto / Ingreso General',
                'metodo_pago' => $row['payment_method'] ?? 'N/A',
                'adjunto' => $row['attachment'] ?? null,
            ];

            $records[] = $rec;

            if (!isset($groupedRecords[$catName])) {
                $groupedRecords[$catName] = ['records' => [], 'subtotal' => 0.0, 'tipo' => $tipo];
            }
            $groupedRecords[$catName]['records'][] = $rec;
            $groupedRecords[$catName]['subtotal'] += ($tipo === 'ingreso' ? $monto : -$monto);
        }

        $data = [
            'title' => 'Movimientos Mensuales',
            'records' => $records,
            'groupedRecords' => $groupedRecords,
            'total_ingresos' => $totalIngresos,
            'total_gastos' => $totalGastos,
            'total_neto' => $totalIngresos - $totalGastos,
            'selectedMonth' => $selectedMonth,
            'categories' => $categoriesRaw
        ];

        return view('admin/finance/movimientos', $data);
    }

    /**
     * Muestra la vista de Cuotas Extraordinarias
     */
    public function extraordinarias()
    {
        $condoModel = new CondominiumModel();
        $demoCondo = $condoModel->first();

        if (!$demoCondo) {
            return redirect()->to('/admin/dashboard')->with('error', 'Condominio no configurado.');
        }

        // Get Units for the selection modal
        $unitModel = new UnitModel();
        $units = $unitModel->where('condominium_id', $demoCondo['id'])->findAll();

        $extFeeModel = new \App\Models\Tenant\ExtraordinaryFeeModel();
        $transactionModel = new FinancialTransactionModel();

        $fees = $extFeeModel->where('condominium_id', $demoCondo['id'])->orderBy('created_at', 'DESC')->findAll();

        $activeFeesCount = count($fees);
        $totalExpected = 0;
        $totalCollected = 0;

        foreach ($fees as &$fee) {
            $totalExpected += $fee['expected_total'];

            // Find all transactions linked to this fee
            $txs = $transactionModel->where('extraordinary_fee_id', $fee['id'])->findAll();
            $unitsLoaded = count($txs);
            $unitsPaid = 0;
            $collectedAmount = 0;

            foreach ($txs as $tx) {
                if ($tx['status'] === 'paid') {
                    $unitsPaid++;
                    // Assuming a matching credit was generated, sum the exact amount paid towards this
                    $collectedAmount += $tx['amount'];
                }
            }

            $totalCollected += $collectedAmount;

            $fee['units_loaded'] = $unitsLoaded;
            $fee['units_paid'] = $unitsPaid;
            $fee['collected_amount'] = $collectedAmount;

            $fee['collection_rate'] = $fee['expected_total'] > 0
                ? round(($collectedAmount / $fee['expected_total']) * 100, 1)
                : 0;
        }

        $data = [
            'title' => 'Cuotas Extraordinarias',
            'condo' => $demoCondo,
            'units' => $units,
            'fees' => $fees,
            'kpis' => [
                'active' => $activeFeesCount,
                'expected' => $totalExpected,
                'collected' => $totalCollected
            ]
        ];

        return view('admin/finance/extraordinarias', $data);
    }

    /**
     * Guarda una nueva cuota extraordinaria y genera los cargos por unidad.
     */
    public function storeExtraordinaria()
    {
        $condoModel = new CondominiumModel();
        $demoCondo = $condoModel->first();

        $json = $this->request->getJSON();
        if (!$json) {
            return $this->response->setJSON(['success' => false, 'message' => 'Datos inválidos']);
        }

        $title = $json->title ?? '';
        $description = $json->description ?? '';
        $amount = (float) ($json->amount ?? 0);
        $categoryId = $json->category_id ?? null;
        $startDate = $json->start_date ?? date('Y-m-d');
        $dueDate = $json->due_date ?? null;
        $unitIds = $json->unit_ids ?? [];

        if (empty($title) || $amount <= 0 || empty($unitIds)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Faltan datos obligatorios.']);
        }

        $expectedTotal = count($unitIds) * $amount;

        $extFeeModel = new \App\Models\Tenant\ExtraordinaryFeeModel();
        $transactionModel = new FinancialTransactionModel();

        // 1. Create Extraordinary Fee Project
        $feeData = [
            'condominium_id' => $demoCondo['id'],
            'title' => $title,
            'description' => $description,
            'category_id' => $categoryId,
            'amount' => $amount,
            'expected_total' => $expectedTotal,
            'start_date' => $startDate,
            'due_date' => $dueDate
        ];
        $feeId = $extFeeModel->insert($feeData);

        // 2. Generate 'charge' transactions for selected units
        $charges = [];
        foreach ($unitIds as $uId) {
            $charges[] = [
                'condominium_id' => $demoCondo['id'],
                'unit_id' => $uId,
                'category_id' => $categoryId,
                'extraordinary_fee_id' => $feeId,
                'type' => 'charge',
                'amount' => $amount,
                'description' => "Cuota Extraordinaria: " . $title,
                'due_date' => $dueDate ?? date('Y-m-d', strtotime('+30 days')),
                'status' => 'pending'
            ];
        }

        if (!empty($charges)) {
            $transactionModel->insertBatch($charges);

            // Notify residents
            $db = \Config\Database::connect();
            foreach ($unitIds as $uId) {
                $residents = $db->table('residents')
                    ->select('DISTINCT(user_id) as user_id')
                    ->where('unit_id', $uId)
                    ->where('condominium_id', $demoCondo['id'])
                    ->where('is_active', 1)
                    ->where('user_id IS NOT NULL')
                    ->get()->getResultArray();

                foreach ($residents as $r) {
                    NotificationModel::notify(
                        $demoCondo['id'],
                        (int) $r['user_id'],
                        'new_charge',
                        'Nueva cuota extraordinaria',
                        'Se ha generado una nueva cuota extraordinaria: ' . $title,
                        ['unit_id' => $uId, 'tipo' => 'nueva_cuota'],
                        true
                    );
                }
            }
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Cuota extraordinaria generada correctamente.', 'units_loaded' => count($unitIds)]);
    }

    public function detalleExtraordinaria(int $id)
    {
        $condoModel = new CondominiumModel();
        $demoCondo = $condoModel->first();

        if (!$demoCondo) {
            return redirect()->to('/admin/dashboard')->with('error', 'Condominio no configurado.');
        }

        $extFeeModel = new \App\Models\Tenant\ExtraordinaryFeeModel();
        $fee = $extFeeModel->where('condominium_id', $demoCondo['id'])->find($id);

        if (!$fee) {
            return redirect()->to('/admin/finanzas/extraordinarias')->with('error', 'Cuota extraordinaria no encontrada.');
        }

        $db = \Config\Database::connect();

        // Obtener transacciones (Cargos) asociadas a esta cuota, junto con los datos de sus respectivas unidades
        $builder = $db->table('financial_transactions ft');
        $builder->select('ft.*, u.unit_number');
        $builder->join('units u', 'u.id = ft.unit_id', 'left');
        $builder->where('ft.extraordinary_fee_id', $id);
        $builder->where('ft.type', 'charge');
        $builder->orderBy('u.unit_number', 'ASC');
        $charges = $builder->get()->getResultArray();

        // Calculos
        $totalEsperado = 0;
        $totalRecaudado = 0;
        $unidadesPagadas = 0;
        $unidadesTotal = count($charges);

        foreach ($charges as &$charge) {
            $totalEsperado += $charge['amount'];

            // Obtener pagos reales para este cargo
            $payments = $db->table('financial_transactions')
                ->where('extraordinary_fee_id', $charge['extraordinary_fee_id'])
                ->where('unit_id', $charge['unit_id'])
                ->where('type', 'payment')
                ->get()->getResultArray();

            $paidSum = 0;
            foreach ($payments as $p) {
                $paidSum += (float) $p['amount'];
            }
            $charge['paid_amount'] = $paidSum;
            $charge['balance'] = max(0, (float) $charge['amount'] - $paidSum);

            if ($charge['status'] === 'paid' || $charge['status'] === 'completed') {
                $totalRecaudado += $charge['amount'];
                $unidadesPagadas++;
            } elseif ($paidSum > 0) {
                $totalRecaudado += $paidSum;
            }

            // Obtener nombre del residente
            $resident = $db->table('residents r')
                ->select("CONCAT(u2.first_name, ' ', u2.last_name) as full_name")
                ->join('users u2', 'u2.id = r.user_id')
                ->where('r.unit_id', $charge['unit_id'])
                ->orderBy('r.type', 'ASC')
                ->get()->getRowArray();
            $charge['resident_name'] = $resident ? $resident['full_name'] : '';
        }

        $totalPendiente = max(0, $totalEsperado - $totalRecaudado);
        $progresoPercentage = $totalEsperado > 0 ? round(($totalRecaudado / $totalEsperado) * 100, 1) : 0;

        // Categoría (para la vista detalles)
        $catName = 'Sin categoría';
        if ($fee['category_id']) {
            $cat = $db->table('financial_categories')->where('id', $fee['category_id'])->get()->getRowArray();
            if ($cat)
                $catName = $cat['name'];
        }

        $data = [
            'condo' => $demoCondo,
            'fee' => $fee,
            'catName' => $catName,
            'charges' => $charges,
            'stats' => [
                'expected' => $totalEsperado,
                'collected' => $totalRecaudado,
                'pending' => $totalPendiente,
                'progress_perc' => $progresoPercentage,
                'units_paid' => $unidadesPagadas,
                'units_total' => $unidadesTotal
            ]
        ];

        return view('admin/finance/extraordinaria_detalle', $data);
    }

    public function updateExtCharge()
    {
        $json = $this->request->getJSON();
        if (!$json || !isset($json->transaction_id) || !isset($json->amount)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Datos inválidos']);
        }

        $txModel = new FinancialTransactionModel();
        $tx = $txModel->find($json->transaction_id);

        if (!$tx || $tx['type'] !== 'charge') {
            return $this->response->setJSON(['success' => false, 'message' => 'Cargo no encontrado']);
        }

        $newAmount = (float) $json->amount;
        if ($newAmount < 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Monto no puede ser negativo']);
        }

        $txModel->update($tx['id'], ['amount' => $newAmount]);

        // Ajustar el Expected Total de la Cuota Parent
        $extFeeModel = new \App\Models\Tenant\ExtraordinaryFeeModel();
        $fee = $extFeeModel->find($tx['extraordinary_fee_id']);
        if ($fee) {
            $diff = $newAmount - (float) $tx['amount'];
            $newExpected = max(0, (float) $fee['expected_total'] + $diff);
            $extFeeModel->update($fee['id'], ['expected_total' => $newExpected]);
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Monto actualizado exitosamente']);
    }

    public function deleteExtCharge()
    {
        $json = $this->request->getJSON();
        if (!$json || !isset($json->transaction_id)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Datos inválidos']);
        }

        $txModel = new FinancialTransactionModel();
        $tx = $txModel->find($json->transaction_id);

        if (!$tx) {
            return $this->response->setJSON(['success' => false, 'message' => 'Cargo no encontrado']);
        }

        $amountToSubtract = (float) $tx['amount'];
        $feeId = $tx['extraordinary_fee_id'];

        $txModel->delete($tx['id']);

        if ($feeId) {
            $extFeeModel = new \App\Models\Tenant\ExtraordinaryFeeModel();
            $fee = $extFeeModel->find($feeId);
            if ($fee) {
                $newExpected = max(0, (float) $fee['expected_total'] - $amountToSubtract);
                $extFeeModel->update($fee['id'], ['expected_total' => $newExpected]);
            }
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Cargo eliminado permanentemente']);
    }

    /**
     * Registrar pago en un cargo de cuota extraordinaria
     */
    public function payExtCharge()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $chargeId = (int) $this->request->getPost('charge_id');
        $amount = (float) $this->request->getPost('amount');
        $method = $this->request->getPost('method') ?? 'Transferencia Bancaria';
        $date = $this->request->getPost('date') ?? date('Y-m-d');

        if (!$chargeId || $amount <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Datos inválidos. Monto debe ser mayor a 0.']);
        }

        $txModel = new FinancialTransactionModel();
        $charge = $txModel->find($chargeId);

        if (!$charge || $charge['type'] !== 'charge') {
            return $this->response->setJSON(['success' => false, 'message' => 'Cargo no encontrado']);
        }

        $condoModel = new CondominiumModel();
        $demoCondo = $condoModel->first();
        if (!$demoCondo) {
            return $this->response->setJSON(['success' => false, 'message' => 'Condominio no encontrado.']);
        }

        // Handle file upload
        $attachmentPath = null;
        $file = $this->request->getFile('attachment');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/financial', $newName);
            $attachmentPath = 'financial/' . $newName;
        }

        // Obtener categoría del cargo
        $catId = $charge['category_id'] ?? null;

        // Crear la transacción de pago
        $paymentData = [
            'condominium_id' => $demoCondo['id'],
            'unit_id' => $charge['unit_id'],
            'type' => 'payment',
            'amount' => $amount,
            'description' => 'Pago cuota extraordinaria',
            'payment_method' => $method,
            'category_id' => $catId,
            'status' => 'completed',
            'billing_period' => $charge['billing_period'] ?? date('Y-m'),
            'extraordinary_fee_id' => $charge['extraordinary_fee_id'],
            'transaction_date' => $date,
            'attachment' => $attachmentPath,
        ];

        $txModel->insert($paymentData);

        // Verificar si el cargo ya fue totalmente pagado
        $db = \Config\Database::connect();
        $totalPaid = $db->table('financial_transactions')
            ->selectSum('amount')
            ->where('extraordinary_fee_id', $charge['extraordinary_fee_id'])
            ->where('unit_id', $charge['unit_id'])
            ->where('type', 'payment')
            ->get()->getRow();

        $paidTotal = (float) ($totalPaid->amount ?? 0);
        $chargeAmount = (float) $charge['amount'];

        if ($paidTotal >= $chargeAmount) {
            $txModel->update($chargeId, ['status' => 'paid']);
        } elseif ($paidTotal > 0) {
            $txModel->update($chargeId, ['status' => 'partial']);
        }

        // Recalcular saldos
        $this->recalculateUnitBalances((int) $charge['unit_id']);

        $remaining = max(0, $chargeAmount - $paidTotal);

        // Notify residents
        $residents = $db->table('residents')
            ->select('DISTINCT(user_id) as user_id')
            ->where('unit_id', $charge['unit_id'])
            ->where('condominium_id', $demoCondo['id'])
            ->where('is_active', 1)
            ->where('user_id IS NOT NULL')
            ->get()->getResultArray();

        foreach ($residents as $r) {
            NotificationModel::notify(
                $demoCondo['id'],
                (int) $r['user_id'],
                'payment_approved',
                'Pago registrado',
                'Se ha registrado el pago de tu cuota extraordinaria.',
                ['unit_id' => $charge['unit_id'], 'tipo' => 'pago_registrado'],
                true
            );
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Pago registrado exitosamente.',
            'paid_total' => $paidTotal,
            'remaining' => $remaining,
        ]);
    }

    public function deleteExtFee()
    {
        $json = $this->request->getJSON();
        if (!$json || !isset($json->fee_id)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Datos inválidos']);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $db->table('financial_transactions')->where('extraordinary_fee_id', $json->fee_id)->delete();

        $extFeeModel = new \App\Models\Tenant\ExtraordinaryFeeModel();
        $extFeeModel->delete($json->fee_id);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al eliminar la cuota']);
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Cuota eliminada de forma exitosa', 'redirect' => base_url('admin/finanzas/extraordinarias')]);
    }

    public function updateExtFee()
    {
        $json = $this->request->getJSON();
        if (!$json || !isset($json->fee_id) || !isset($json->title)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Datos inválidos']);
        }

        $extFeeModel = new \App\Models\Tenant\ExtraordinaryFeeModel();
        $fee = $extFeeModel->find($json->fee_id);

        if (!$fee) {
            return $this->response->setJSON(['success' => false, 'message' => 'Cuota no encontrada']);
        }

        $updateData = [
            'title' => $json->title,
            'description' => $json->description ?? null,
            'category_id' => $json->category_id ?? null,
            'due_date' => !empty($json->due_date) ? $json->due_date : null
        ];

        // Solo actualizar fecha de creación si es provista
        if (!empty($json->created_at)) {
            $updateData['created_at'] = date('Y-m-d H:i:s', strtotime($json->created_at));
        }

        $extFeeModel->update($fee['id'], $updateData);

        return $this->response->setJSON(['success' => true, 'message' => 'Cuota actualizada correctamente']);
    }

    /**
     * Muestra la vista de Pagos por Unidad (lista real de unidades con saldos)
     */
    public function pagosPorUnidad()
    {
        $condoModel = new CondominiumModel();
        $demoCondo = $condoModel->first();
        if (!$demoCondo)
            return redirect()->to('/admin/dashboard')->with('error', 'Condominio no configurado.');

        $db = \Config\Database::connect();
        $this->generateMonthlyCharges($demoCondo);

        $dueDay = (int) ($demoCondo['billing_due_day'] ?? 10);
        $dueDateStr = date('Y-m-') . str_pad($dueDay, 2, '0', STR_PAD_LEFT);

        $today = date('Y-m-d');
        $units = $db->table('units u')
            ->select('u.id, u.hash_id, u.unit_number, u.maintenance_fee, u.initial_balance,
                      IFNULL(SUM(CASE WHEN ft.type = "charge" AND ft.status != "cancelled" THEN ft.amount ELSE 0 END), 0) AS total_charges,
                      IFNULL(SUM(CASE WHEN ft.type = "charge" AND ft.status != "cancelled" AND ft.due_date < "' . $today . '" THEN ft.amount ELSE 0 END), 0) AS total_overdue_charges,
                      IFNULL(SUM(CASE WHEN ft.type = "credit" AND ft.status = "paid" THEN ft.amount ELSE 0 END), 0) AS total_paid')
            ->join('financial_transactions ft', 'ft.unit_id = u.id AND ft.condominium_id = u.condominium_id', 'left')
            ->where('u.condominium_id', $demoCondo['id'])
            ->groupBy('u.id')
            ->orderBy('u.unit_number', 'ASC')
            ->get()->getResultArray();

        $records = [];
        foreach ($units as $u) {
            $saldo = ((float) $u['initial_balance'] + (float) $u['total_charges']) - (float) $u['total_paid'];
            $saldoVencido = ((float) $u['initial_balance'] + (float) $u['total_overdue_charges']) - (float) $u['total_paid'];
            if ($saldo < -0.01) {
                $estado = 'A favor';
            } else if ($saldo <= 0.01) {
                $estado = 'Sin adeudos';
            } else {
                if ($saldoVencido > 0.01) {
                    $estado = 'Moroso';
                } else {
                    $estado = 'Al corriente';
                }
            }
            $records[] = [
                'id' => $u['id'],
                'hash_id' => $u['hash_id'] ?? $u['id'],
                'unidad' => $u['unit_number'],
                'cuota' => (float) $u['maintenance_fee'],
                'vencimiento' => date('j M', strtotime($dueDateStr)),
                'estado' => $estado,
                'saldo' => $saldo,
            ];
        }

        // Count pending vouchers per unit
        $pendingVouchers = $db->table('payments')
            ->select('unit_id, COUNT(*) as cnt')
            ->where('condominium_id', $demoCondo['id'])
            ->where('status', 'pending')
            ->where('deleted_at IS NULL')
            ->groupBy('unit_id')
            ->get()->getResultArray();
        $pendingByUnit = [];
        foreach ($pendingVouchers as $pv) {
            $pendingByUnit[(int) $pv['unit_id']] = (int) $pv['cnt'];
        }

        // Merge pending count into records
        foreach ($records as &$rec) {
            $rec['pending_vouchers'] = $pendingByUnit[$rec['id']] ?? 0;
        }
        unset($rec);

        return view('admin/finance/pagos_por_unidad', [
            'title' => 'Pagos por Unidad',
            'records' => $records,
            'condo' => $demoCondo,
        ]);
    }

    /**
     * Vista de detalle financiero de una unidad
     */
    public function unitDetail($identifier)
    {
        $condoModel = new CondominiumModel();
        $demoCondo = $condoModel->first();
        if (!$demoCondo)
            return redirect()->to('/admin/dashboard');

        $db = \Config\Database::connect();

        // Obtener lista ordenada de IDs para navegación prev/next
        $allUnits = $db->table('units')
            ->select('id, hash_id, unit_number')
            ->where('condominium_id', $demoCondo['id'])
            ->orderBy('unit_number', 'ASC')
            ->get()->getResultArray();

        // Datos de la unidad principal
        $unit = $db->table('units')->where('condominium_id', $demoCondo['id'])
            ->groupStart()
            ->where('id', $identifier)
            ->orWhere('hash_id', $identifier)
            ->groupEnd()
            ->get()->getRowArray();

        if (!$unit)
            return redirect()->to(base_url('admin/finanzas/pagos-por-unidad'));
        $unitId = $unit['id'];

        $unitIds = array_column($allUnits, 'id');
        $currentIdx = array_search($unitId, $unitIds);
        $prevId = ($currentIdx > 0) ? ($allUnits[$currentIdx - 1]['hash_id'] ?? $allUnits[$currentIdx - 1]['id']) : null;
        $nextId = ($currentIdx < count($unitIds) - 1) ? ($allUnits[$currentIdx + 1]['hash_id'] ?? $allUnits[$currentIdx + 1]['id']) : null;

        // Todos los registros: cargos + pagos CON categoría
        $transactions = $db->table('financial_transactions ft')
            ->select('ft.*, cats.name AS category_name, cats.type AS category_type')
            ->join('financial_categories cats', 'cats.id = ft.category_id', 'left')
            ->where('ft.unit_id', $unitId)
            ->where('ft.condominium_id', $demoCondo['id'])
            ->where('ft.status !=', 'cancelled')
            ->orderBy('ft.due_date', 'ASC')
            ->orderBy('ft.created_at', 'ASC')
            ->get()->getResultArray();

        // KPI 1: Total de créditos (pagos)
        $totalPaid = 0;
        $numPagos = 0;
        $totalCharges = 0;
        $totalOverdueCharges = 0;
        $todayStr = date('Y-m-d');
        $pendingRows = [];

        foreach ($transactions as $t) {
            if ($t['type'] === 'credit' && $t['status'] === 'paid') {
                $totalPaid += (float) $t['amount'];
                $numPagos++;
            }
            if ($t['type'] === 'charge' && $t['status'] !== 'cancelled') {
                $totalCharges += (float) $t['amount'];
                if ($t['due_date'] < $todayStr) {
                    $totalOverdueCharges += (float) $t['amount'];
                }
                if ($t['status'] === 'pending' || $t['status'] === 'partial') {
                    $pendingRows[] = $t;
                }
            }
        }

        $initialBalance = (float) ($unit['initial_balance'] ?? 0);
        $saldoPendiente = $initialBalance + $totalCharges - $totalPaid;
        $saldoVencido = $initialBalance + $totalOverdueCharges - $totalPaid;
        $cuotaMensual = (float) ($unit['maintenance_fee'] ?? 0);

        // KPI 4: Próxima fecha de vencimiento
        $dueDay = (int) ($demoCondo['billing_due_day'] ?? 10);
        $today = new \DateTime();
        $nextDue = new \DateTime(date('Y-m-') . str_pad($dueDay, 2, '0', STR_PAD_LEFT));
        if ($nextDue < $today)
            $nextDue->modify('+1 month');
        $daysLeft = (int) $today->diff($nextDue)->days;

        // Estado de cuenta: running balance
        $statementRows = [];
        $runningBalance = $initialBalance;
        foreach ($transactions as $t) {
            if ($t['type'] === 'charge') {
                $runningBalance += (float) $t['amount'];
            } else {
                $runningBalance -= (float) $t['amount'];
            }
            $statementRows[] = array_merge($t, ['running_balance' => $runningBalance]);
        }

        // Historial de pagos (solo créditos pagados)
        $paymentHistory = array_filter($transactions, fn($t) => $t['type'] === 'credit' && $t['status'] === 'paid');

        // Comprobantes de pago (desde tabla payments)
        $vouchers = $db->table('payments')
            ->where('unit_id', $unit['id'])
            ->where('condominium_id', $demoCondo['id'])
            ->where('deleted_at IS NULL')
            ->orderBy('created_at', 'DESC')
            ->get()->getResultArray();

        // Cargos pendientes (para el dropdown del modal de revisión)
        $pendingCharges = array_filter($transactions, fn($t) => $t['type'] === 'charge' && in_array($t['status'], ['pending', 'partial']));

        // Categorías para el modal de edición
        $categories = $db->table('financial_categories')
            ->where('condominium_id', $demoCondo['id'])
            ->orderBy('name', 'ASC')
            ->get()->getResultArray();

        return view('admin/finance/unit_detail', [
            'title' => 'Unidad ' . $unit['unit_number'],
            'unit' => $unit,
            'condo' => $demoCondo,
            'prevId' => $prevId,
            'nextId' => $nextId,
            'allUnits' => $allUnits,
            'totalPaid' => $totalPaid,
            'numPagos' => $numPagos,
            'saldoPendiente' => $saldoPendiente,
            'saldoVencido' => $saldoVencido,
            'cuotaMensual' => $cuotaMensual,
            'nextDueDate' => $nextDue->format('j \d\e F \d\e Y'),
            'daysLeft' => $daysLeft,
            'pendingRows' => array_values($pendingRows),
            'statementRows' => array_values($statementRows),
            'paymentHistory' => array_values($paymentHistory),
            'vouchers' => array_values($vouchers),
            'pendingCharges' => array_values($pendingCharges),
            'initialBalance' => $initialBalance,
            'categories' => $categories,
        ]);
    }

    /**
     * Actualiza una transacción individual (AJAX POST)
     */
    public function updateTransaction()
    {
        if (!$this->request->isAJAX())
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid request']);

        $transId = (int) $this->request->getPost('id');
        $amount = (float) $this->request->getPost('amount');
        $dueDate = $this->request->getPost('due_date');
        $categoryId = (int) $this->request->getPost('category_id');
        $description = $this->request->getPost('description');

        $condoModel = new CondominiumModel();
        $demoCondo = $condoModel->first();
        if (!$demoCondo)
            return $this->response->setJSON(['status' => 'error', 'message' => 'Condominio no encontrado.']);

        $db = \Config\Database::connect();
        $builder = $db->table('financial_transactions');

        $transaction = $builder->where('id', $transId)->where('condominium_id', $demoCondo['id'])->get()->getRowArray();
        if (!$transaction) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Transacción no encontrada.']);
        }

        // Preservar la hora original de created_at, pero cambiar la fecha a la nueva due_date
        $originalTime = date('H:i:s', strtotime($transaction['created_at']));
        $newCreatedAt = $dueDate . ' ' . $originalTime;

        $updateData = [
            'amount' => $amount,
            'due_date' => $dueDate,
            'created_at' => $newCreatedAt,
            'description' => $description,
            'category_id' => $categoryId,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $paymentMethod = $this->request->getPost('payment_method');
        if ($paymentMethod !== null && $paymentMethod !== '') {
            $updateData['payment_method'] = $paymentMethod;
        }

        $file = $this->request->getFile('attachment');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/financial', $newName);
            $updateData['attachment'] = $newName;
        }

        // Si es un cargo, recalculamos status basado en amount_paid
        if ($transaction['type'] === 'charge') {
            $paid = (float) $transaction['amount_paid'];
            if ($paid >= ($amount - 0.01)) {
                $updateData['status'] = 'paid';
                $updateData['amount_paid'] = $amount; // Cap the amount_paid to release any overpayment to floating credit
            } else if ($paid > 0.01) {
                $updateData['status'] = 'partial';
            } else {
                $updateData['status'] = 'pending';
            }
        }

        $builder->where('id', $transId)->update($updateData);

        // Recalcular saldos de la unidad afectada para garantizar consistencia
        $this->recalculateUnitBalances((int) $transaction['unit_id']);
        $this->applyFloatingCredit((int) $transaction['unit_id']);

        // Si está ligado a una cuota extraordinaria, recalcular el expected_total de la cuota
        if (!empty($transaction['extraordinary_fee_id'])) {
            $feeId = $transaction['extraordinary_fee_id'];
            $newTotalRow = $db->table('financial_transactions')
                ->selectSum('amount')
                ->where('extraordinary_fee_id', $feeId)
                ->where('type', 'charge')
                ->where('status !=', 'cancelled')
                ->get()->getRow();

            if ($newTotalRow) {
                $db->table('extraordinary_fees')
                    ->where('id', $feeId)
                    ->update(['expected_total' => (float) $newTotalRow->amount]);
            }
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Transacción actualizada correctamente.']);
    }

    /**
     * Elimina una transacción y recalcula saldos (AJAX POST)
     */
    public function deleteTransaction()
    {
        if (!$this->request->isAJAX())
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid request']);

        $transId = (int) $this->request->getPost('id');

        $condoModel = new CondominiumModel();
        $demoCondo = $condoModel->first();
        if (!$demoCondo)
            return $this->response->setJSON(['status' => 'error', 'message' => 'Condominio no encontrado.']);

        $db = \Config\Database::connect();
        $builder = $db->table('financial_transactions');

        $transaction = $builder->where('id', $transId)->where('condominium_id', $demoCondo['id'])->get()->getRowArray();
        if (!$transaction) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Transacción no encontrada.']);
        }

        $unitId = (int) $transaction['unit_id'];
        $feeId = $transaction['extraordinary_fee_id'] ?? null;

        // Soft delete (si la tabla lo soporta por FinancialTransactionModel, pero aquí usamos Query Builder)
        // Para asegurar consistencia total, marcamos como 'cancelled' o borramos físicamente si no hay soft delete manual en DB
        $builder->where('id', $transId)->delete();

        // Si hay un comprobante de pago vinculado, eliminarlo
        if (!empty($transaction['attachment'])) {
            $proofUrl = $transaction['attachment'];

            // Soft delete en la tabla de pagos
            $db->table('payments')
                ->where('proof_url', $proofUrl)
                ->where('condominium_id', $demoCondo['id'])
                ->update(['deleted_at' => date('Y-m-d H:i:s')]);

            // Eliminar archivo físico
            $filePath = WRITEPATH . 'uploads/payments/' . $proofUrl;
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }

        // Recalcular saldos de la unidad
        $this->recalculateUnitBalances($unitId);

        // Si era una cuota extraordinaria, actualizar el total esperado
        if ($feeId) {
            $newTotalRow = $db->table('financial_transactions')
                ->selectSum('amount')
                ->where('extraordinary_fee_id', $feeId)
                ->where('type', 'charge')
                ->where('deleted_at', null)
                ->get()->getRow();

            $db->table('extraordinary_fees')
                ->where('id', $feeId)
                ->update(['expected_total' => (float) ($newTotalRow->amount ?? 0)]);
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Transacción eliminada correctamente.']);
    }

    /**
     * Eliminación masiva de transacciones (Movimientos Mensuales)
     */
    public function bulkDeleteTransactions()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid request']);
        }

        $idsJson = $this->request->getPost('ids');
        $ids = json_decode($idsJson, true);

        if (empty($ids) || !is_array($ids)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No se proporcionaron IDs válidos.']);
        }

        $condoModel = new CondominiumModel();
        $demoCondo = $condoModel->first();
        if (!$demoCondo) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Condominio no encontrado.']);
        }

        $db = \Config\Database::connect();
        $builder = $db->table('financial_transactions');

        // Obtener todas las transacciones para recalcular saldos después
        $transactions = $builder
            ->whereIn('id', array_map('intval', $ids))
            ->where('condominium_id', $demoCondo['id'])
            ->get()->getResultArray();

        if (empty($transactions)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No se encontraron transacciones.']);
        }

        // Recopilar unit_ids y fee_ids afectados
        $affectedUnitIds = [];
        $affectedFeeIds = [];
        foreach ($transactions as $t) {
            if (!empty($t['unit_id'])) {
                $affectedUnitIds[(int) $t['unit_id']] = true;
            }
            if (!empty($t['extraordinary_fee_id'])) {
                $affectedFeeIds[(int) $t['extraordinary_fee_id']] = true;
            }
            // Eliminar comprobantes
            if (!empty($t['attachment'])) {
                $proofUrl = $t['attachment'];

                $db->table('payments')
                    ->where('proof_url', $proofUrl)
                    ->where('condominium_id', $demoCondo['id'])
                    ->update(['deleted_at' => date('Y-m-d H:i:s')]);

                $filePath = WRITEPATH . 'uploads/payments/' . $proofUrl;
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }
        }

        // Eliminar todas las transacciones
        $db->table('financial_transactions')
            ->whereIn('id', array_map('intval', $ids))
            ->where('condominium_id', $demoCondo['id'])
            ->delete();

        // Recalcular saldos de cada unidad afectada
        foreach (array_keys($affectedUnitIds) as $unitId) {
            $this->recalculateUnitBalances($unitId);
        }

        // Actualizar cuotas extraordinarias afectadas
        foreach (array_keys($affectedFeeIds) as $feeId) {
            $newTotalRow = $db->table('financial_transactions')
                ->selectSum('amount')
                ->where('extraordinary_fee_id', $feeId)
                ->where('type', 'charge')
                ->where('deleted_at', null)
                ->get()->getRow();

            $db->table('extraordinary_fees')
                ->where('id', $feeId)
                ->update(['expected_total' => (float) ($newTotalRow->amount ?? 0)]);
        }

        $count = count($transactions);
        return $this->response->setJSON([
            'status' => 'success',
            'message' => "{$count} transacción(es) eliminada(s) correctamente.",
            'deleted_count' => $count
        ]);
    }
    /**
     * Motor de consistencia: Resetea y re-asigna pagos de una unidad mediante FIFO
     */
    private function recalculateUnitBalances(int $unitId)
    {
        $db = \Config\Database::connect();
        $condoModel = new CondominiumModel();
        $demoCondo = $condoModel->first();
        if (!$demoCondo)
            return;

        $db->transStart();

        // 1. Resetear todos los cargos de la unidad
        $db->table('financial_transactions')
            ->where('unit_id', $unitId)
            ->where('type', 'charge')
            ->update(['amount_paid' => 0, 'status' => 'pending']);

        // 2. Obtener todos los abonos (pagos) de la unidad, ordenados por fecha
        $payments = $db->table('financial_transactions')
            ->where('unit_id', $unitId)
            ->where('type', 'credit')
            ->where('status', 'paid')
            ->where('deleted_at', null)
            ->orderBy('due_date', 'ASC')
            ->orderBy('created_at', 'ASC')
            ->get()->getResultArray();

        // 3. Re-asignar cada pago usando FIFO
        foreach ($payments as $payment) {
            $amountToAllocate = (float) $payment['amount'];

            // Obtener cargos pendientes/parciales para esta unidad
            $pendingCharges = $db->table('financial_transactions')
                ->where('unit_id', $unitId)
                ->where('type', 'charge')
                ->whereIn('status', ['pending', 'partial'])
                ->where('deleted_at', null)
                ->orderBy('due_date', 'ASC')
                ->orderBy('created_at', 'ASC')
                ->get()->getResultArray();

            foreach ($pendingCharges as $charge) {
                if ($amountToAllocate <= 0.01)
                    break;

                $debtRemaining = (float) $charge['amount'] - (float) $charge['amount_paid'];
                if ($debtRemaining <= 0.01)
                    continue;

                $applied = min($amountToAllocate, $debtRemaining);
                $newPaid = (float) $charge['amount_paid'] + $applied;
                $newStatus = ($newPaid >= ((float) $charge['amount'] - 0.01)) ? 'paid' : 'partial';

                $db->table('financial_transactions')->where('id', $charge['id'])->update([
                    'amount_paid' => $newPaid,
                    'status' => $newStatus,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                $amountToAllocate -= $applied;
            }
        }

        $db->transComplete();
    }

    /**
     * Guarda el saldo inicial de una unidad (AJAX POST)
     */
    public function setInitialBalance()
    {
        if (!$this->request->isAJAX())
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid request']);

        $unitId = (int) $this->request->getPost('unit_id');
        $balance = (float) $this->request->getPost('initial_balance');

        $condoModel = new CondominiumModel();
        $demoCondo = $condoModel->first();
        if (!$demoCondo)
            return $this->response->setJSON(['status' => 'error', 'message' => 'Condominio no encontrado.']);

        $db = \Config\Database::connect();
        $db->table('units')->where('id', $unitId)->where('condominium_id', $demoCondo['id'])->update(['initial_balance' => $balance]);

        return $this->response->setJSON(['status' => 'success', 'message' => 'Saldo inicial actualizado.', 'balance' => $balance]);
    }

    /**
     * API JSON: Summary of a unit's finances for the *current month* (Resumen Financiero Modal).
     */
    public function apiUnitSummary($identifier)
    {
        $condoModel = new CondominiumModel();
        $demoCondo = $condoModel->first();
        if (!$demoCondo)
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Condominio no configurado']);

        $db = \Config\Database::connect();

        $unit = $db->table('units')->where('condominium_id', $demoCondo['id'])
            ->groupStart()
            ->where('id', $identifier)
            ->orWhere('hash_id', $identifier)
            ->groupEnd()
            ->get()->getRowArray();

        if (!$unit)
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Unidad no encontrada']);
        $unitId = $unit['id'];

        $currentMonth = date('Y-m');
        $monthStart = $currentMonth . '-01 00:00:00';
        $monthEnd = date('Y-m-t', strtotime($monthStart)) . ' 23:59:59';

        // All transactions for full history and running balance
        $allTransactions = $db->table('financial_transactions ft')
            ->select('ft.*, cats.name AS category_name, cats.type AS category_type')
            ->join('financial_categories cats', 'cats.id = ft.category_id', 'left')
            ->where('ft.unit_id', $unitId)
            ->where('ft.condominium_id', $demoCondo['id'])
            ->where('ft.status !=', 'cancelled')
            ->orderBy('ft.due_date', 'ASC')
            ->orderBy('ft.created_at', 'ASC')
            ->get()->getResultArray();

        $totalChargesAllTime = 0;
        $totalPaidAllTime = 0;
        $monthlyPaid = 0;
        $monthlyPaidCount = 0;

        $resumenRows = [];
        $paymentHistory = [];

        $initialBalance = (float) ($unit['initial_balance'] ?? 0);
        $runningBalance = $initialBalance;

        // Current month bounds for KPIs
        $currentMonthStart = date('Y-m-01 00:00:00');
        $currentMonthEnd = date('Y-m-t 23:59:59');

        foreach ($allTransactions as $t) {
            if ($t['type'] === 'charge') {
                $totalChargesAllTime += (float) $t['amount'];
                $runningBalance += (float) $t['amount'];
            }
            if ($t['type'] === 'credit' && $t['status'] === 'paid') {
                $totalPaidAllTime += (float) $t['amount'];
                $runningBalance -= (float) $t['amount'];
                $paymentHistory[] = $t;

                // Recolectar lo pagado en el mes actual para los KPIs
                if ($t['created_at'] >= $currentMonthStart && $t['created_at'] <= $currentMonthEnd) {
                    $monthlyPaid += (float) $t['amount'];
                    $monthlyPaidCount++;
                }
            }

            $t['running_balance'] = $runningBalance;
            $resumenRows[] = $t;
        }

        $saldoPendiente = $initialBalance + $totalChargesAllTime - $totalPaidAllTime;

        $dueDay = (int) ($demoCondo['billing_due_day'] ?? 10);
        $today = new \DateTime(date('Y-m-d'));
        $nextDue = \DateTime::createFromFormat('Y-m-d', date('Y-m-') . str_pad($dueDay, 2, '0', STR_PAD_LEFT));
        if ($nextDue < $today)
            $nextDue->modify('+1 month');
        $daysLeft = (int) $today->diff($nextDue)->days;

        $mesesCortosAPI = ['Jan' => 'Ene', 'Feb' => 'Feb', 'Mar' => 'Mar', 'Apr' => 'Abr', 'May' => 'May', 'Jun' => 'Jun', 'Jul' => 'Jul', 'Aug' => 'Ago', 'Sep' => 'Sep', 'Oct' => 'Oct', 'Nov' => 'Nov', 'Dec' => 'Dic'];
        $nextDueStr = strtr($nextDue->format('M'), $mesesCortosAPI) . ' ' . $nextDue->format('j');

        return $this->response->setJSON([
            'status' => 'success',
            'data' => [
                'unit_number' => $unit['unit_number'],
                'total_paid_month' => $monthlyPaid,
                'paid_count_month' => $monthlyPaidCount,
                'saldo_pendiente' => $saldoPendiente,
                'cuota_mensual' => (float) ($unit['maintenance_fee'] ?? 0),
                'next_due_date' => $nextDueStr,
                'days_left' => $daysLeft,
                'resumen' => $resumenRows,
                'historial' => $paymentHistory
            ]
        ]);
    }

    /**
     * API JSON: lista de unidades con saldos
     */
    public function apiUnitList()
    {
        $condoModel = new CondominiumModel();
        $demoCondo = $condoModel->first();
        if (!$demoCondo)
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Condo not found']);

        $db = \Config\Database::connect();
        $today = date('Y-m-d');
        $units = $db->table('units u')
            ->select('u.id, u.unit_number, u.maintenance_fee, u.initial_balance,
                      IFNULL(SUM(CASE WHEN ft.type = "charge" AND ft.status != "cancelled" THEN ft.amount ELSE 0 END), 0) AS total_charges,
                      IFNULL(SUM(CASE WHEN ft.type = "charge" AND ft.status != "cancelled" AND ft.due_date < "' . $today . '" THEN ft.amount ELSE 0 END), 0) AS total_overdue_charges,
                      IFNULL(SUM(CASE WHEN ft.type = "credit" AND ft.status = "paid" THEN ft.amount ELSE 0 END), 0) AS total_paid')
            ->join('financial_transactions ft', 'ft.unit_id = u.id AND ft.condominium_id = u.condominium_id', 'left')
            ->where('u.condominium_id', $demoCondo['id'])
            ->groupBy('u.id')
            ->orderBy('u.unit_number', 'ASC')
            ->get()->getResultArray();

        return $this->response->setJSON(['status' => 'success', 'data' => $units]);
    }

    /**
     * API JSON: detalle financiero de una unidad
     */
    public function apiUnitDetail(int $unitId)
    {
        $condoModel = new CondominiumModel();
        $demoCondo = $condoModel->first();
        if (!$demoCondo)
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Condo not found']);

        $db = \Config\Database::connect();
        $transactions = $db->table('financial_transactions ft')
            ->select('ft.*, cats.name AS category_name')
            ->join('financial_categories cats', 'cats.id = ft.category_id', 'left')
            ->where('ft.unit_id', $unitId)
            ->where('ft.condominium_id', $demoCondo['id'])
            ->orderBy('ft.created_at', 'ASC')
            ->get()->getResultArray();

        return $this->response->setJSON(['status' => 'success', 'data' => $transactions]);
    }


    /**
     * Endpoint API para activar el sistema de facturación.
     * Recibe la fecha de inicio y el día de vencimiento vía AJAX (POST).
     */
    public function activateBilling()
    {
        $startDate = $this->request->getPost('start_date');
        $dueDay = $this->request->getPost('due_day');

        if (!$startDate || !$dueDay) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Por favor completa todos los campos requeridos.'
            ])->setStatusCode(400);
        }

        $condoModel = new CondominiumModel();
        $demoCondo = $condoModel->first();

        if (!$demoCondo) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: Condominio no encontrado.'
            ])->setStatusCode(404);
        }

        // Formato YYYY-MM-01 (accept both YYYY-MM-DD and YYYY-MM)
        $billingStartStr = date('Y-m-01', strtotime($startDate));

        $updateData = [
            'is_billing_active' => 1,
            'billing_start_date' => $billingStartStr,
            'billing_due_day' => (int) $dueDay
        ];

        if ($condoModel->update($demoCondo['id'], $updateData)) {
            // FASE 3: Generar cargos iniciales
            $categoryModel = new FinancialCategoryModel();
            $transactionModel = new FinancialTransactionModel();
            $unitModel = new UnitModel();

            // 1. Buscar o crear la categoría de sistema
            $category = $categoryModel->where('condominium_id', $demoCondo['id'])
                ->where('name', 'Cuota de Mantenimiento')
                ->first();

            if (!$category) {
                $catId = $categoryModel->insert([
                    'condominium_id' => $demoCondo['id'],
                    'name' => 'Cuota de Mantenimiento',
                    'description' => 'Cargo mensual automático por mantenimiento',
                    'type' => 'income',
                    'is_system' => 1
                ]);
            } else {
                $catId = $category['id'];
            }

            // 2. Obtener todas las unidades con cuota
            $units = $unitModel->where('condominium_id', $demoCondo['id'])
                ->where('maintenance_fee IS NOT NULL')
                ->where('maintenance_fee >', 0)
                ->findAll();

            // 3. Fecha de vencimiento armada
            $dueDateStr = date('Y-m-', strtotime($billingStartStr)) . str_pad($dueDay, 2, '0', STR_PAD_LEFT);
            $monthName = date('F Y', strtotime($billingStartStr));

            $db = \Config\Database::connect();
            $db->transStart();

            $targetMonth = date('Y-m', strtotime($billingStartStr));
            foreach ($units as $u) {
                // Verificar si ya existe para evitar duplicados si desactivan y reactivan (buscando por mes/año)
                $txExists = $transactionModel->where('unit_id', $u['id'])
                    ->where('category_id', $catId)
                    ->where('type', 'charge')
                    ->like('due_date', $targetMonth, 'after')
                    ->countAllResults();

                if ($txExists == 0) {
                    $transactionModel->insert([
                        'condominium_id' => $demoCondo['id'],
                        'unit_id' => $u['id'],
                        'category_id' => $catId,
                        'type' => 'charge',
                        'amount' => $u['maintenance_fee'],
                        'description' => 'Cuota de Mantenimiento ' . $monthName,
                        'due_date' => $dueDateStr,
                        'status' => 'pending',
                        'source' => 'auto'
                    ]);
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'El sistema se activó, pero hubo un error al generar los cargos de las unidades.'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Sistema de facturación activado y cargos iniciales generados.'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Ocurrió un error al guardar la configuración.'
        ])->setStatusCode(500);
    }
    /**
     * Genera cargos mensuales automáticos para el mes actual si faltan.
     * Se ejecuta on-access desde dashboard/morosidad.
     */
    private function generateMonthlyCharges(array $demoCondo): void
    {
        if (!($demoCondo['is_billing_active'] ?? false)) {
            return;
        }

        $service = new \App\Services\MonthlyChargeService();
        $service->generateIfNotExists((int) $demoCondo['id'], 'on_access');
    }

    /**
     * Rutina automática que reabsorbe el saldo a favor flotante sobre los cargos nuevos de una unidad
     */
    public function applyFloatingCredit(int $unitId): void
    {
        $db = \Config\Database::connect();

        $unit = $db->table('units')->select('initial_balance')->where('id', $unitId)->get()->getRowArray();
        $initialBalance = (float) ($unit['initial_balance'] ?? 0);

        $creditRow = $db->table('financial_transactions')
            ->select('SUM(amount) as total_credits')
            ->where('unit_id', $unitId)
            ->where('type', 'credit')
            ->where('status', 'paid')
            ->get()->getRowArray();
        $totalCredits = (float) ($creditRow['total_credits'] ?? 0);

        $chargeAllocatedRow = $db->table('financial_transactions')
            ->select('SUM(amount_paid) as total_allocated')
            ->where('unit_id', $unitId)
            ->where('type', 'charge')
            ->where('status !=', 'cancelled')
            ->get()->getRowArray();
        $totalAllocated = (float) ($chargeAllocatedRow['total_allocated'] ?? 0);

        $floatingCredit = $totalCredits - $totalAllocated;
        if ($initialBalance < 0) {
            $floatingCredit += abs($initialBalance); // Sumamos el saldo a favor originario (negativo)
        }

        if ($floatingCredit <= 0.01) {
            return;
        }

        // Iterar cargos adeudados viejos a nuevos
        $pendingCharges = $db->table('financial_transactions')
            ->where('unit_id', $unitId)
            ->where('type', 'charge')
            ->whereIn('status', ['pending', 'partial'])
            ->orderBy('due_date', 'ASC')
            ->orderBy('created_at', 'ASC')
            ->get()->getResultArray();

        foreach ($pendingCharges as $charge) {
            if ($floatingCredit <= 0.01)
                break;

            $debtRemaining = (float) $charge['amount'] - (float) $charge['amount_paid'];
            if ($debtRemaining <= 0)
                continue;

            $amountToApply = min($floatingCredit, $debtRemaining);
            $newPaid = (float) $charge['amount_paid'] + $amountToApply;
            $newStatus = ($newPaid >= ((float) $charge['amount'] - 0.01)) ? 'paid' : 'partial';

            $db->table('financial_transactions')->where('id', $charge['id'])->update([
                'amount_paid' => $newPaid,
                'status' => $newStatus,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $floatingCredit -= $amountToApply;
        }
    }

    /**
     * Lista todas las categorías financieras
     */
    public function getCategories()
    {
        $categoryModel = new FinancialCategoryModel();
        $categories = $categoryModel->findAll();

        return $this->response->setJSON(['status' => 200, 'data' => $categories]);
    }

    /**
     * Crea una nueva categoría financiera (ej: 'Mantenimiento Mensual', 'Multas')
     */
    public function createCategory()
    {
        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'type' => $this->request->getPost('type') ?? 'income', // income o expense
            'is_system' => 0
        ];

        $categoryModel = new FinancialCategoryModel();
        // condominium_id inyectado automáticamente
        $catId = $categoryModel->insert($data);

        return $this->response->setJSON(['status' => 201, 'message' => 'Categoría Creada', 'id' => $catId]);
    }

    /**
     * Lista todas las transacciones financieras
     */
    public function listTransactions()
    {
        $transactionModel = new FinancialTransactionModel();
        $transactions = $transactionModel->orderBy('created_at', 'DESC')->findAll();

        return $this->response->setJSON(['status' => 200, 'data' => $transactions]);
    }

    /**
     * Genera un cargo de cuota (HOA charge) a una unidad
     */
    public function generateCharge()
    {
        // En una implementación real, un cronjob o loop iteraría sobre todas las unidades.
        // Aquí demostramos el endpoint individual (ej: Cargo extraordinario).

        $data = [
            'unit_id' => $this->request->getPost('unit_id'),
            'category_id' => $this->request->getPost('category_id'),
            'type' => 'charge',
            'amount' => $this->request->getPost('amount'),
            'description' => $this->request->getPost('description'),
            'due_date' => $this->request->getPost('due_date'),
            'status' => 'pending' // pending, paid, cancelled
        ];

        // Validaciones pendientes (ej. mount > 0)

        $transactionModel = new FinancialTransactionModel();
        // El BaseTenantModel forzará condominium_id en background
        $chargeId = $transactionModel->insert($data);

        return $this->response->setJSON(['status' => 201, 'message' => 'Cargo generado exitosamente', 'transaction_id' => $chargeId]);
    }

    public function historicos()
    {
        $unitModel = new UnitModel();
        $units = $unitModel->orderBy('unit_number', 'ASC')->findAll();
        return view('admin/finance/historicos', ['units' => $units]);
    }

    public function generateHistoricos()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid request']);
        }

        $payload = $this->request->getJSON(true);
        $charges = $payload['charges'] ?? [];
        $startDate = $payload['start_date'] ?? null;

        if (empty($charges)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'No charges provided']);
        }

        $transactionModel = new FinancialTransactionModel();
        $condoModel = new CondominiumModel();
        $categoryModel = new FinancialCategoryModel();

        $cat = $categoryModel->where('name', 'Cuota de Mantenimiento')->first();
        $categoryId = $cat ? $cat['id'] : 1;

        $demoCondo = $condoModel->first();
        $condoId = $demoCondo['id'] ?? 1;

        $db = \Config\Database::connect();
        $db->transStart();

        $currentDate = date('Y-m-d H:i:s');
        $insertData = [];
        $monthNames = ['01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'];

        foreach ($charges as $c) {
            $monthStr = $c['month'] . '-01';
            $monthPart = substr($monthStr, 5, 2);
            $yearPart = substr($monthStr, 0, 4);
            $descMonth = $monthNames[$monthPart] . ' ' . $yearPart;

            $insertData[] = [
                'condominium_id' => $condoId,
                'type' => 'charge',
                'category_id' => $categoryId,
                'unit_id' => $c['unit_id'],
                'amount' => $c['amount'],
                'description' => 'Cuota de mantenimiento ' . $descMonth,
                'transaction_date' => $monthStr,
                'due_date' => $monthStr,
                'status' => 'pending',
                'source' => 'auto',
                'created_at' => $monthStr . ' 00:00:00',
                'updated_at' => $currentDate,
            ];
        }

        $transactionModel->insertBatch($insertData);

        if ($demoCondo && $startDate) {
            $updateCondo = ['is_billing_active' => 1];
            if (empty($demoCondo['billing_start_date']) || strtotime($startDate . '-01') < strtotime($demoCondo['billing_start_date'])) {
                $updateCondo['billing_start_date'] = $startDate . '-01';
                $updateCondo['billing_due_day'] = 1;
            }
            $condoModel->update($demoCondo['id'], $updateCondo);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Database transaction failed']);
        }

        return $this->response->setJSON(['status' => 200, 'message' => 'Cargos generados exitosamente', 'count' => count($insertData)]);
    }

    public function resetDb()
    {
        $db = \Config\Database::connect();
        $db->query('SET FOREIGN_KEY_CHECKS = 0');
        $db->table('condominiums')->update(['is_billing_active' => 0, 'billing_start_date' => null, 'billing_due_day' => null]);
        $db->table('payments')->truncate();
        $db->table('financial_transactions')->truncate();
        $db->table('financial_categories')->truncate();
        $db->query('SET FOREIGN_KEY_CHECKS = 1');
        return "Reset DB OK. Todas las transacciones, categorías y configuración de facturación eliminadas.";
    }

    public function getPendingCharges()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid request']);
        }

        $db = \Config\Database::connect();
        $mesesES = ['January' => 'Enero', 'February' => 'Febrero', 'March' => 'Marzo', 'April' => 'Abril', 'May' => 'Mayo', 'June' => 'Junio', 'July' => 'Julio', 'August' => 'Agosto', 'September' => 'Septiembre', 'October' => 'Octubre', 'November' => 'Noviembre', 'December' => 'Diciembre'];

        $unitIdsStr = $this->request->getPost('unitIds');
        $ids = array_values(array_filter(array_map('intval', explode(',', (string) $unitIdsStr))));

        if (empty($ids))
            return $this->response->setJSON(['status' => 'success', 'data' => []]);

        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $charges = $db->query("
            SELECT ft.id, ft.unit_id, ft.amount, IFNULL(ft.amount_paid, 0) as amount_paid, (ft.amount - IFNULL(ft.amount_paid, 0)) AS debt_remaining, ft.description, ft.due_date, u.unit_number, IFNULL(s.name, 'Sin Sección') AS section_name
            FROM financial_transactions ft
            JOIN units u ON u.id = ft.unit_id
            LEFT JOIN sections s ON s.id = u.section_id
            WHERE ft.unit_id IN ($placeholders) AND ft.type = 'charge' AND ft.status IN ('pending', 'partial')
            ORDER BY s.name ASC, ft.due_date ASC, u.unit_number ASC
        ", $ids)->getResultArray();

        foreach ($charges as &$c) {
            $c['month_desc'] = strtr(date('F Y', strtotime($c['due_date'])), $mesesES);
            $c['month_num'] = (int) date('m', strtotime($c['due_date']));
            $c['year'] = (int) date('Y', strtotime($c['due_date']));
            $descLower = strtolower($c['description']);
            if (strpos($descLower, 'cuota extraordinaria') !== false) {
                // Cuotas extraordinarias: mantener título completo
                $baseLabel = $c['description'];
            } elseif (strpos($descLower, 'cuota de mantenimiento') !== false || strpos($descLower, 'cuota mantenimiento') !== false) {
                // Cuotas regulares de mantenimiento: simplificar a "Cuota Mes Año"
                $baseLabel = 'Cuota ' . $c['month_desc'];
            } else {
                // Otros cargos (multas, mora, etc.): usar descripción original
                $baseLabel = $c['description'];
            }
            $c['display_label'] = 'U. ' . $c['unit_number'] . ' - ' . $baseLabel;
        }

        return $this->response->setJSON(['status' => 'success', 'data' => $charges]);
    }

    /**
     * RENDER HTML MVC - Vista Frontal del Administrador
     */
    public function indexView()
    {
        // [HACK LOCAL] Forzamos el contexto Tenant
        $demoCondo = (new CondominiumModel())->first();
        if ($demoCondo)
            \App\Services\TenantService::getInstance()->setTenantId((int) $demoCondo['id']);

        $transactionModel = new FinancialTransactionModel();
        // Traemos transacciones con detalles básicos (en prod real irían joins de units)
        $transactions = $transactionModel->orderBy('created_at', 'DESC')->findAll(50);

        return view('admin/finances', ['transactions' => $transactions]);
    }

    /**
     * Sirve archivos desde writable/uploads de forma segura.
     */
    public function serveFile(string $fileName)
    {
        // Prevenir traversal
        $fileName = str_replace(['..', '\\', '/'], '', $fileName);
        $filePath = WRITEPATH . 'uploads/financial/' . $fileName;

        if (!is_file($filePath)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';
        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Cache-Control', 'public, max-age=86400')
            ->setBody(file_get_contents($filePath));
    }

    /**
     * Serve payment proof files from uploads/payments/ directory
     */
    public function servePaymentProof(string ...$segments)
    {
        $path = implode('/', $segments);
        $path = str_replace(['..', '\\\\'], '', $path);
        $filePath = WRITEPATH . 'uploads/payments/' . $path;

        if (!is_file($filePath)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';
        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Cache-Control', 'public, max-age=86400')
            ->setBody(file_get_contents($filePath));
    }


    /**
     * Genera y descarga un recibo de pago en PDF para una transacción de tipo crédito.
     */
    public function downloadPaymentReceipt(int $transactionId)
    {
        // Require vendor autoload for TCPDF
        require_once ROOTPATH . 'vendor/autoload.php';
        helper('number_to_words');

        $condoModel = new CondominiumModel();
        $demoCondo = $condoModel->first();
        if (!$demoCondo)
            return redirect()->to('/admin/dashboard');

        $db = \Config\Database::connect();

        // Obtener transacción
        $transaction = $db->table('financial_transactions ft')
            ->select('ft.*, cats.name AS category_name')
            ->join('financial_categories cats', 'cats.id = ft.category_id', 'left')
            ->where('ft.id', $transactionId)
            ->where('ft.condominium_id', $demoCondo['id'])
            ->where('ft.type', 'credit')
            ->get()->getRowArray();

        if (!$transaction) {
            return redirect()->back()->with('error', 'Transacción no encontrada.');
        }

        // Datos de la unidad
        $unit = $db->table('units')->where('id', $transaction['unit_id'])->get()->getRowArray();
        if (!$unit)
            return redirect()->back();

        // Residente principal de la unidad
        $resident = $db->table('residents r')
            ->select('u.first_name, u.last_name')
            ->join('users u', 'u.id = r.user_id')
            ->where('r.unit_id', $unit['id'])
            ->where('r.is_active', 1)
            ->orderBy('r.type', 'ASC') // propietario primero
            ->get()->getRowArray();

        $residentName = $resident ? trim($resident['first_name'] . ' ' . $resident['last_name']) : '—';

        // Generar número de recibo
        $receiptNum = 'REC-' . strtoupper(substr(md5($transaction['id'] . $transaction['created_at']), 0, 8));

        // Datos formateados
        $amount = (float) $transaction['amount'];
        $amountFmt = 'MX$' . number_format($amount, 2);
        $amountWords = \number_to_words_es($amount);
        $dateFmt = new \IntlDateFormatter('es_MX', \IntlDateFormatter::LONG, \IntlDateFormatter::NONE, null, null, "d 'de' MMMM 'de' yyyy");
        $payDate = $dateFmt->format(strtotime($transaction['due_date'] ?? $transaction['created_at']));
        $emissionDate = $dateFmt->format(time());
        $concept = $transaction['category_name'] ?? 'Cuota de Mantenimiento';
        $payMethod = $transaction['payment_method'] ?? 'Transferencia Bancaria';
        $description = strtoupper($transaction['description'] ?? '—');

        // Dirección del condominio
        $condoName = $demoCondo['name'] ?? 'AxisCondo';
        $condoAddress = $demoCondo['address'] ?? '';

        // ── Crear PDF ──
        $pdf = new \TCPDF('P', 'mm', 'LETTER', true, 'UTF-8', false);
        $pdf->SetCreator('AxisCondo');
        $pdf->SetAuthor($condoName);
        $pdf->SetTitle('Recibo de Pago - ' . $unit['unit_number']);
        $pdf->SetSubject('Recibo de Pago');

        // Eliminar header/footer por defecto
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(20, 15, 20);
        $pdf->SetAutoPageBreak(true, 25);
        $pdf->AddPage();

        // ── Colores ──
        $darkBlue = [29, 76, 157];   // #232d3f
        $greenBar = [71, 85, 105];   // #155740
        $borderGray = [200, 200, 200];
        $textDark = [30, 41, 59];
        $textMuted = [100, 116, 139];
        $yellowBg = [254, 249, 195];
        $yellowBorder = [253, 230, 138];
        $grayBg = [248, 250, 252];

        // ── HEADER BAR ──
        $pdf->SetFillColor(29, 76, 157); // #1D4C9D
        $pdf->Rect(20, 15, 175.6, 36, 'F');

        // Logo
        $logoFile = $demoCondo['logo'] ?? '';
        $logoPath = '';
        $hasLogo = false;
        if (!empty($logoFile)) {
            $logoPath = (strpos($logoFile, '/') !== false)
                ? WRITEPATH . 'uploads/' . $logoFile
                : WRITEPATH . 'uploads/condominiums/' . $demoCondo['id'] . '/' . $logoFile;
            $hasLogo = is_file($logoPath);
        }

        if ($hasLogo) {
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Rect(24, 19, 28, 28, 'F');
            $pdf->Image($logoPath, 25, 20, 26, 26, '', '', '', false, 300, '', false, false, 0, 'CM', false, false);
        } else {
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Rect(24, 19, 28, 28, 'F');
            $pdf->SetFillColor(29, 76, 157);
            $pdf->Rect(28, 29, 20, 4, 'F');
        }

        // Title
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->SetXY(56, 18);
        $pdf->Cell(136, 8, 'RECIBO DE PAGO', 0, 1, 'C');

        // Community name
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->SetXY(56, 27);
        $pdf->Cell(136, 7, 'COMUNIDAD: ' . strtoupper($condoName), 0, 1, 'C');

        // Address
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTextColor(199, 210, 232);
        $pdf->SetXY(56, 35);
        $pdf->Cell(136, 5, strtoupper($condoAddress), 0, 1, 'C');

        // Blue accent line
        $pdf->SetFillColor(63, 103, 172);
        $pdf->Rect(20, 51, 175.6, 1.2, 'F');

        // ── RECIBO DE PAGO + FECHA DE EMISIÓN ──
        $pdf->SetY(56);
        $pdf->SetTextColor(15, 23, 42);
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(175.6, 7, 'Detalle del Recibo', 0, 1, 'L');
        $pdf->SetDrawColor(226, 232, 240);
        $pdf->Line(20, $pdf->GetY(), 195.6, $pdf->GetY());
        $pdf->Ln(2);

        // Número de recibo + Fecha
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetTextColor(100, 116, 139);
        $pdf->Cell(55, 6, 'No. de Recibo', 0, 0, 'L');
        $pdf->SetTextColor(15, 23, 42);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(120.6, 6, $receiptNum, 0, 1, 'L');

        $pdf->SetFillColor(248, 250, 252);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetTextColor(100, 116, 139);
        $pdf->Cell(55, 6, 'Fecha de Emisión', 0, 0, 'L', true);
        $pdf->SetTextColor(15, 23, 42);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(120.6, 6, $emissionDate, 0, 1, 'L', true);

        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetTextColor(100, 116, 139);
        $pdf->Cell(55, 6, 'Estatus', 0, 0, 'L');
        $pdf->SetTextColor(5, 150, 105);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(120.6, 6, 'COMPLETADO', 0, 1, 'L');

        $pdf->Ln(6);

        // ── DOS COLUMNAS: Admin + Condómino ──
        $boxY = $pdf->GetY();

        $pdf->SetTextColor(15, 23, 42);
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->SetXY(20, $boxY);
        $pdf->Cell(85, 7, 'Administración', 0, 0, 'L');
        $pdf->SetXY(110, $boxY);
        $pdf->Cell(85, 7, 'Condómino', 0, 1, 'L');

        $pdf->SetDrawColor(226, 232, 240);
        $pdf->Line(20, $pdf->GetY(), 105, $pdf->GetY());
        $pdf->Line(110, $pdf->GetY(), 195.6, $pdf->GetY());
        $pdf->Ln(3);

        $infoY = $pdf->GetY();

        // Left column
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetTextColor(15, 23, 42);
        $pdf->SetXY(20, $infoY);
        $pdf->Cell(85, 5, strtoupper($condoName), 0, 1, 'L');
        if ($condoAddress) {
            $parts = explode(',', $condoAddress);
            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetTextColor(100, 116, 139);
            $pdf->SetX(20);
            $pdf->Cell(85, 4, trim($parts[0] ?? ''), 0, 1, 'L');
            if (count($parts) > 1) {
                $pdf->SetX(20);
                $pdf->Cell(85, 4, trim(implode(',', array_slice($parts, 1))), 0, 1, 'L');
            }
        }

        // Right column
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetTextColor(15, 23, 42);
        $pdf->SetXY(110, $infoY);
        $pdf->Cell(85, 5, 'Unidad ' . $unit['unit_number'], 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTextColor(100, 116, 139);
        $pdf->SetX(110);
        $pdf->Cell(85, 4, $residentName, 0, 1, 'L');

        // Move below both columns
        $pdf->SetY(max($pdf->GetY(), $infoY + 16));
        $pdf->Ln(6);

        // ── TABLA DE DETALLES ──
        $pdf->SetTextColor(15, 23, 42);
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(175.6, 7, 'Detalle del Pago', 0, 1, 'L');
        $pdf->SetDrawColor(226, 232, 240);
        $pdf->Line(20, $pdf->GetY(), 195.6, $pdf->GetY());
        $pdf->Ln(2);

        $leftCol = 55;
        $rightCol = 120.6;

        // Row helper function
        $drawRow = function ($label, $value, $bold = false, $zebra = false) use ($pdf, $leftCol, $rightCol) {
            if ($zebra) {
                $pdf->SetFillColor(248, 250, 252);
            }
            $pdf->SetFont('helvetica', '', 9);
            $pdf->SetTextColor(100, 116, 139);
            $pdf->Cell($leftCol, 8, '  ' . $label, 0, 0, 'L', $zebra);
            $pdf->SetTextColor(15, 23, 42);
            $pdf->SetFont('helvetica', $bold ? 'B' : '', 9);
            $pdf->Cell($rightCol, 8, $value, 0, 1, 'R', $zebra);
        };

        $drawRow('Fecha de Pago', $payDate, false, false);
        $drawRow('Concepto', $concept, false, true);
        $drawRow('Forma de Pago', $payMethod, false, false);
        $drawRow('Descripción', $description, false, true);

        // Importe row (highlighted)
        $pdf->SetFillColor(240, 253, 244); // green-50
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetTextColor(100, 116, 139);
        $pdf->Cell($leftCol, 10, '  Importe', 0, 0, 'L', true);
        $pdf->SetTextColor(5, 150, 105);
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell($rightCol, 10, $amountFmt, 0, 1, 'R', true);

        $pdf->Ln(2);

        // ── TOTAL BAR ──
        $pdf->SetFillColor(29, 76, 157); // #1D4C9D
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell($leftCol, 11, '    TOTAL (MXN)', 0, 0, 'L', true);
        $pdf->Cell($rightCol, 11, $amountFmt, 0, 1, 'R', true);

        // Amount in words
        $pdf->SetTextColor(100, 116, 139);
        $pdf->SetFont('helvetica', 'I', 7);
        $pdf->Cell(175.6, 6, $amountWords, 0, 1, 'R');

        $pdf->Ln(8);

        // ── AVISO FISCAL ──
        $pdf->SetFillColor(255, 251, 235); // amber-50
        $pdf->SetFont('helvetica', 'B', 7);
        $pdf->SetTextColor(146, 64, 14);
        $noticeY = $pdf->GetY();
        $pdf->Cell(175.6, 6, '  Aviso fiscal: Este recibo no constituye un CFDI y no tiene validez fiscal para efectos de deducción de impuestos.', 0, 1, 'L', true);

        $pdf->Ln(5);

        // ── OBSERVACIONES ──
        $pdf->SetTextColor(15, 23, 42);
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(175.6, 7, 'Observaciones', 0, 1, 'L');
        $pdf->SetDrawColor(226, 232, 240);
        $pdf->Line(20, $pdf->GetY(), 195.6, $pdf->GetY());
        $pdf->Ln(2);

        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTextColor(100, 116, 139);
        $pdf->Cell(175.6, 5, 'Conserve este recibo para sus registros. El pago se refleja en su estado de cuenta.', 0, 1, 'L');

        $pdf->Ln(10);

        // ── PIE ──
        $pdf->SetDrawColor(226, 232, 240);
        $pdf->Line(20, $pdf->GetY(), 195.6, $pdf->GetY());
        $pdf->Ln(3);
        $pdf->SetFont('helvetica', 'B', 7);
        $pdf->SetTextColor(15, 23, 42);
        $pdf->Cell(88, 5, strtoupper($condoName), 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 7);
        $pdf->SetTextColor(100, 116, 139);
        $pdf->Cell(87.6, 5, 'Generado el ' . $emissionDate, 0, 1, 'R');

        // ── OUTPUT ──
        $fileName = 'Recibo de Pago - ' . $unit['unit_number'] . ' - ' . $receiptNum . '.pdf';
        $pdf->Output($fileName, 'D');
        exit;
    }

    /**
     * Genera y descarga un Estado de Cuenta en PDF para una unidad.
     * Usa el mismo formato visual que el recibo de pago.
     */
    public function downloadAccountStatement($identifier, $returnString = false)
    {
        require_once ROOTPATH . 'vendor/autoload.php';

        $condoModel = new CondominiumModel();
        $demoCondo = $condoModel->first();
        if (!$demoCondo)
            return redirect()->to('/admin/dashboard');

        $db = \Config\Database::connect();

        // Datos de la unidad
        $unit = $db->table('units')->where('condominium_id', $demoCondo['id'])
            ->groupStart()
            ->where('id', $identifier)
            ->orWhere('hash_id', $identifier)
            ->groupEnd()
            ->get()->getRowArray();

        if (!$unit)
            return redirect()->back()->with('error', 'Unidad no encontrada.');
        $unitId = $unit['id'];

        // Residente principal
        $resident = $db->table('residents r')
            ->select('u.first_name, u.last_name')
            ->join('users u', 'u.id = r.user_id')
            ->where('r.unit_id', $unit['id'])
            ->where('r.is_active', 1)
            ->orderBy('r.type', 'ASC')
            ->get()->getRowArray();
        $residentName = $resident ? trim($resident['first_name'] . ' ' . $resident['last_name']) : '—';

        // Todas las transacciones ordenadas por fecha
        $transactions = $db->table('financial_transactions ft')
            ->select('ft.*, cats.name AS category_name')
            ->join('financial_categories cats', 'cats.id = ft.category_id', 'left')
            ->where('ft.unit_id', $unitId)
            ->where('ft.condominium_id', $demoCondo['id'])
            ->where('ft.status !=', 'cancelled')
            ->orderBy('ft.due_date', 'ASC')
            ->orderBy('ft.created_at', 'ASC')
            ->get()->getResultArray();

        // Calcular running balance y totales
        $initialBalance = (float) ($unit['initial_balance'] ?? 0);
        $runningBalance = $initialBalance;
        $totalCharges = 0;
        $totalCredits = 0;
        $statementRows = [];

        $todayStr = date('Y-m-d');
        $debt_vencida = $initialBalance;

        foreach ($transactions as $t) {
            if ($t['type'] === 'charge') {
                $runningBalance += (float) $t['amount'];
                $totalCharges += (float) $t['amount'];
                if ($t['due_date'] < $todayStr) {
                    $debt_vencida += (float) $t['amount'];
                }
            } else {
                $runningBalance -= (float) $t['amount'];
                $totalCredits += (float) $t['amount'];
                $debt_vencida -= (float) $t['amount'];
            }
            $t['running_balance'] = $runningBalance;
            $statementRows[] = $t;
        }

        $saldoPendiente = $runningBalance;

        // Datos del condominio
        $condoName = $demoCondo['name'] ?? 'AxisCondo';
        $condoAddress = $demoCondo['address'] ?? '';
        $dateFmt = new \IntlDateFormatter('es_MX', \IntlDateFormatter::LONG, \IntlDateFormatter::NONE, null, null, "d 'de' MMMM 'de' yyyy");
        $emissionDate = $dateFmt->format(time());

        // Meses en español
        $mesesES = ['January' => 'Enero', 'February' => 'Febrero', 'March' => 'Marzo', 'April' => 'Abril', 'May' => 'Mayo', 'June' => 'Junio', 'July' => 'Julio', 'August' => 'Agosto', 'September' => 'Septiembre', 'October' => 'Octubre', 'November' => 'Noviembre', 'December' => 'Diciembre'];

        // ── Crear PDF con Pie de Página Personalizado (Clase Anónima) ──
        $pdf = new class ('P', 'mm', 'LETTER', true, 'UTF-8', false) extends \TCPDF {
            public $condoName = '';
            public $emissionDate = '';
            public function Footer()
            {
                $this->SetY(-15);
                $this->SetDrawColor(220, 220, 220);
                $this->Line(20, $this->GetY(), 195.6, $this->GetY());
                $this->SetY(-13);
                $this->SetFont('helvetica', 'B', 7);
                $this->SetTextColor(80, 80, 80);
                $this->Cell(60, 5, strtoupper($this->condoName), 0, 0, 'L');
                $this->SetFont('helvetica', '', 7);
                $this->Cell(60, 5, 'Generado el ' . $this->emissionDate, 0, 0, 'C');
                $this->Cell(55.6, 5, $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, 0, 'R');
            }
        };

        $pdf->condoName = $condoName;
        $pdf->emissionDate = $emissionDate;

        $pdf->SetCreator('AxisCondo');
        $pdf->SetAuthor($condoName);
        $pdf->SetTitle('Estado de Cuenta - Unidad ' . $unit['unit_number']);
        $pdf->SetSubject('Estado de Cuenta');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(true);
        $pdf->SetMargins(20, 15, 20);
        $pdf->SetAutoPageBreak(true, 20);
        $pdf->AddPage();

        // ── HEADER BAR ──
        $pdf->SetFillColor(29, 76, 157); // #1D4C9D
        $pdf->Rect(20, 15, 175.6, 36, 'F');

        $logoFile = $demoCondo['logo'] ?? '';
        if (!empty($logoFile)) {
            $logoPath = (strpos($logoFile, '/') !== false)
                ? WRITEPATH . 'uploads/' . $logoFile
                : WRITEPATH . 'uploads/condominiums/' . $demoCondo['id'] . '/' . $logoFile;
            $hasLogo = is_file($logoPath);
        } else {
            $logoPath = '';
            $hasLogo = false;
        }

        if ($hasLogo) {
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Rect(24, 19, 28, 28, 'F');
            $pdf->Image($logoPath, 25, 20, 26, 26, '', '', '', false, 300, '', false, false, 0, 'CM', false, false);
        } else {
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Rect(24, 19, 28, 28, 'F');
            $pdf->SetFillColor(29, 76, 157);
            $pdf->Rect(28, 29, 20, 4, 'F');
        }

        // Title
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->SetXY(56, 18);
        $pdf->Cell(136, 8, 'ESTADO DE CUENTA - ' . strtoupper($unit['unit_number']), 0, 1, 'C');

        // Community name
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->SetXY(56, 27);
        $pdf->Cell(136, 7, 'COMUNIDAD: ' . strtoupper($condoName), 0, 1, 'C');

        // Address
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTextColor(199, 210, 232);
        $pdf->SetXY(56, 35);
        $pdf->Cell(136, 5, strtoupper($condoAddress ?? ''), 0, 1, 'C');

        // Blue accent line
        $pdf->SetFillColor(63, 103, 172); // #3F67AC
        $pdf->Rect(20, 51, 175.6, 1.2, 'F');

        $pdf->SetY(56);
        $pdf->SetTextColor(0, 0, 0);

        // ── 1. Información de la Unidad ──
        $pdf->SetTextColor(15, 23, 42);
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(175.6, 7, 'Información de la Unidad', 0, 1, 'L');
        $pdf->SetDrawColor(226, 232, 240);
        $pdf->Line(20, $pdf->GetY(), 195.6, $pdf->GetY());
        $pdf->Ln(2);

        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetTextColor(100, 116, 139);
        $pdf->Cell(55, 6, 'Unidad', 0, 0, 'L');
        $pdf->SetTextColor(15, 23, 42);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(120.6, 6, $unit['unit_number'], 0, 1, 'L');

        $pdf->SetFillColor(248, 250, 252);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetTextColor(100, 116, 139);
        $pdf->Cell(55, 6, 'Fecha', 0, 0, 'L', true);
        $pdf->SetTextColor(15, 23, 42);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(120.6, 6, $emissionDate, 0, 1, 'L', true);

        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetTextColor(100, 116, 139);
        $pdf->Cell(55, 6, 'Propietario(s)', 0, 0, 'L');
        $pdf->SetTextColor(15, 23, 42);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(120.6, 6, $residentName !== '—' ? $residentName : 'Sin propietarios registrados', 0, 1, 'L');

        $pdf->Ln(6);

        // ── 2. Información de Pagos ──
        $pdf->SetTextColor(15, 23, 42);
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(175.6, 7, 'Información de Pagos', 0, 1, 'L');
        $pdf->SetDrawColor(226, 232, 240);
        $pdf->Line(20, $pdf->GetY(), 195.6, $pdf->GetY());
        $pdf->Ln(2);

        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetTextColor(100, 116, 139);
        $pdf->Cell(55, 6, 'Cuota HOA', 0, 0, 'L');
        $pdf->SetTextColor(15, 23, 42);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(120.6, 6, 'MX$' . number_format($unit['maintenance_fee'], 2), 0, 1, 'L');

        $pdf->SetFillColor(248, 250, 252);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetTextColor(100, 116, 139);
        $pdf->Cell(55, 6, 'Fecha de Vencimiento', 0, 0, 'L', true);
        $pdf->SetTextColor(15, 23, 42);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(120.6, 6, 'Día ' . ($demoCondo['billing_due_day'] ?? 10) . ' de cada mes', 0, 1, 'L', true);

        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetTextColor(100, 116, 139);
        $pdf->Cell(55, 6, 'Estado', 0, 0, 'L');

        $d = $saldoPendiente;
        $dv = $debt_vencida;

        if ($dv > 0.01) {
            $pdf->SetTextColor(185, 28, 28);
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->Cell(120.6, 6, 'MOROSO', 0, 1, 'L');
        } elseif ($d > 0.01) {
            $pdf->SetTextColor(29, 78, 216);
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->Cell(120.6, 6, 'AL CORRIENTE', 0, 1, 'L');
        } elseif ($d < -0.01) {
            $pdf->SetTextColor(21, 128, 61);
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->Cell(120.6, 6, 'A FAVOR', 0, 1, 'L');
        } else {
            $pdf->SetTextColor(21, 128, 61);
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->Cell(120.6, 6, 'SIN ADEUDOS', 0, 1, 'L');
        }

        $pdf->Ln(6);

        // ── 3. Resumen de Pagos ──
        $pdf->SetTextColor(15, 23, 42);
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(175.6, 7, 'Resumen de Pagos', 0, 1, 'L');
        $pdf->SetDrawColor(226, 232, 240);
        $pdf->Line(20, $pdf->GetY(), 195.6, $pdf->GetY());
        $pdf->Ln(2);

        $pdf->SetFillColor(240, 253, 244);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetTextColor(100, 116, 139);
        $pdf->Cell(55, 8, '  Total Pagado', 0, 0, 'L', true);
        $pdf->SetTextColor(5, 150, 105);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(120.6, 8, 'MX$' . number_format($totalCredits, 2), 0, 1, 'R', true);

        if ($saldoPendiente > 0.01) {
            $pdf->SetFillColor(254, 242, 242);
            $pdf->SetFont('helvetica', '', 9);
            $pdf->SetTextColor(100, 116, 139);
            $pdf->Cell(55, 8, '  Saldo Pendiente', 0, 0, 'L', true);
            $pdf->SetTextColor(220, 38, 38);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(120.6, 8, 'MX$' . number_format($saldoPendiente, 2), 0, 1, 'R', true);
        } else if ($saldoPendiente < -0.01) {
            $pdf->SetFillColor(240, 253, 244);
            $pdf->SetFont('helvetica', '', 9);
            $pdf->SetTextColor(100, 116, 139);
            $pdf->Cell(55, 8, '  Saldo a favor', 0, 0, 'L', true);
            $pdf->SetTextColor(5, 150, 105);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(120.6, 8, 'MX$' . number_format(abs($saldoPendiente), 2), 0, 1, 'R', true);
        } else {
            $pdf->SetFillColor(240, 253, 244);
            $pdf->SetFont('helvetica', '', 9);
            $pdf->SetTextColor(100, 116, 139);
            $pdf->Cell(55, 8, '  Saldo Pendiente', 0, 0, 'L', true);
            $pdf->SetTextColor(5, 150, 105);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(120.6, 8, 'MX$0.00', 0, 1, 'R', true);
        }

        $pdf->Ln(6);

        // ── 4. Historial de Movimientos ──
        $pdf->SetTextColor(15, 23, 42);
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(175.6, 7, 'Historial de Movimientos', 0, 1, 'L');
        $pdf->SetDrawColor(226, 232, 240);
        $pdf->Line(20, $pdf->GetY(), 195.6, $pdf->GetY());
        $pdf->Ln(2);

        $pdf->SetFillColor(29, 76, 157); // #1D4C9D
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('helvetica', 'B', 7);

        $colW = [20, 22, 63.6, 22, 22, 26]; // Total: 175.6
        $pdf->Cell($colW[0], 7, 'EMISIÓN', 0, 0, 'C', true);
        $pdf->Cell($colW[1], 7, 'VENCIMIENTO', 0, 0, 'C', true);
        $pdf->Cell($colW[2], 7, 'DESCRIPCIÓN', 0, 0, 'C', true);
        $pdf->Cell($colW[3], 7, 'CARGO', 0, 0, 'C', true);
        $pdf->Cell($colW[4], 7, 'PAGO', 0, 0, 'C', true);
        $pdf->Cell($colW[5], 7, 'SALDO', 0, 1, 'C', true);

        // Table Rows
        $pdf->SetFont('helvetica', 'B', 7);
        foreach ($statementRows as $idx => $row) {
            if ($pdf->GetY() > 230) {
                $pdf->AddPage();
                $pdf->SetFillColor(29, 76, 157);
                $pdf->SetTextColor(255, 255, 255);
                $pdf->SetFont('helvetica', 'B', 7);
                $pdf->Cell($colW[0], 7, 'EMISIÓN', 0, 0, 'C', true);
                $pdf->Cell($colW[1], 7, 'VENCIMIENTO', 0, 0, 'C', true);
                $pdf->Cell($colW[2], 7, 'DESCRIPCIÓN', 0, 0, 'C', true);
                $pdf->Cell($colW[3], 7, 'CARGO', 0, 0, 'C', true);
                $pdf->Cell($colW[4], 7, 'PAGO', 0, 0, 'C', true);
                $pdf->Cell($colW[5], 7, 'SALDO', 0, 1, 'C', true);
                $pdf->SetFont('helvetica', 'B', 7);
            }

            $dateRaw = $row['created_at'];
            $dueDateRaw = $row['due_date'] ?? null;

            // Formato compacto DD/MM/YYYY
            $dateStr = date('d/m/Y', strtotime($dateRaw));

            // Si no es cargo (es un pago/abono) o no tiene fecha, lo dejamos vacío
            $dueDateStr = ($row['type'] === 'charge' && $dueDateRaw) ? date('d/m/Y', strtotime($dueDateRaw)) : '—';

            $pdf->SetTextColor(80, 80, 80);
            $pdf->SetDrawColor(241, 245, 249);

            // Zebra striping
            $isZebra = ($idx % 2 === 1);
            if ($isZebra) {
                $pdf->SetFillColor(248, 250, 252);
            }

            $pdf->Cell($colW[0], 7, '  ' . $dateStr, 0, 0, 'C', $isZebra);
            $pdf->Cell($colW[1], 7, '  ' . $dueDateStr, 0, 0, 'C', $isZebra);

            $desc = mb_strtoupper(mb_substr($row['description'] ?? ($row['category_name'] ?? '—'), 0, 60));
            $pdf->SetFont('helvetica', '', 6);
            $pdf->Cell($colW[2], 7, '  ' . $desc, 0, 0, 'L', $isZebra, '', 1);
            $pdf->SetFont('helvetica', 'B', 7);

            $pdf->SetTextColor(50, 50, 50);
            $strCharge = ($row['type'] === 'charge') ? 'MX$' . number_format((float) $row['amount'], 2) : '';
            $pdf->Cell($colW[3], 7, $strCharge . '  ', 0, 0, 'R', $isZebra);

            $strPay = ($row['type'] !== 'charge') ? 'MX$' . number_format((float) $row['amount'], 2) : '';
            $pdf->Cell($colW[4], 7, $strPay . '  ', 0, 0, 'R', $isZebra);

            // Saldo formatting (negative red if owed)
            $rb = (float) $row['running_balance'];
            if ($rb > 0.01) {
                $pdf->SetTextColor(220, 38, 38);
                $pdf->Cell($colW[5], 7, '-MX$' . number_format($rb, 2) . '  ', 0, 1, 'R', $isZebra);
            } else if ($rb < -0.01) {
                $pdf->SetTextColor(5, 150, 105);
                $pdf->Cell($colW[5], 7, 'MX$' . number_format(abs($rb), 2) . '  ', 0, 1, 'R', $isZebra);
            } else {
                $pdf->SetTextColor(50, 50, 50);
                $pdf->Cell($colW[5], 7, 'MX$0.00  ', 0, 1, 'R', $isZebra);
            }
        }

        if (empty($statementRows)) {
            $pdf->SetTextColor(100, 116, 139);
            $pdf->SetFont('helvetica', '', 9);
            $pdf->Cell(175.6, 10, 'No hay movimientos en el registro.', 0, 1, 'C');
        }

        // ── 5. Pagos Pendientes (Solo Morosos) ──
        if ($saldoPendiente > 0) {
            $pendingCharges = array_filter($statementRows, function ($r) {
                $isChargeType = ($r['type'] !== 'payment' && $r['type'] !== 'credit');
                $isPending = ($r['status'] !== 'paid' && $r['status'] !== 'cancelled');

                if (!$isChargeType || !$isPending) {
                    return false;
                }

                $dueDateMillis = isset($r['due_date']) ? strtotime($r['due_date']) : strtotime($r['created_at']);
                $todayMillis = strtotime(date('Y-m-d') . ' 23:59:59');
                $currentMonth = date('Y-m');
                $chargeMonth = date('Y-m', $dueDateMillis);

                return ($chargeMonth <= $currentMonth) || ($dueDateMillis <= $todayMillis);
            });

            if (!empty($pendingCharges)) {
                $pdf->Ln(6);
                $pdf->SetTextColor(220, 38, 38);
                $pdf->SetFont('helvetica', 'B', 11);
                $pdf->Cell(175.6, 7, 'Pagos Pendientes', 0, 1, 'L');
                $pdf->SetDrawColor(254, 202, 202);
                $pdf->Line(20, $pdf->GetY(), 195.6, $pdf->GetY());
                $pdf->Ln(2);

                $pdf->SetFillColor(220, 38, 38); // #dc2626
                $pdf->SetTextColor(255, 255, 255);
                $pdf->SetFont('helvetica', 'B', 7);

                $colP = [45, 60.6, 35, 35];
                $pdf->Cell($colP[0], 7, 'VENCIMIENTO', 0, 0, 'C', true);
                $pdf->Cell($colP[1], 7, 'CONCEPTO', 0, 0, 'C', true);
                $pdf->Cell($colP[2], 7, 'MONTO', 0, 0, 'C', true);
                $pdf->Cell($colP[3], 7, 'TOTAL ACUMULADO', 0, 1, 'C', true);

                $pdf->SetTextColor(50, 50, 50);
                $pdf->SetFont('helvetica', 'B', 7);

                $acumulado = 0;
                $pIdx = 0;
                foreach ($pendingCharges as $pc) {
                    if ($pdf->GetY() > 230) {
                        $pdf->AddPage();
                    }

                    $montoPend = (float) $pc['amount'] - (float) ($pc['amount_paid'] ?? 0);
                    $acumulado += $montoPend;

                    $dateRaw = $pc['due_date'] ?? $pc['created_at'];
                    $dateStr = date('d', strtotime($dateRaw)) . ' de ' . strtolower($mesesES[date('F', strtotime($dateRaw))] ?? date('M', strtotime($dateRaw))) . ' de ' . date('Y', strtotime($dateRaw));
                    $desc = mb_strtoupper(mb_substr($pc['description'] ?? ($pc['category_name'] ?? '—'), 0, 60));

                    $isZebraP = ($pIdx % 2 === 1);
                    if ($isZebraP) {
                        $pdf->SetFillColor(254, 242, 242); // #fef2f2
                    }

                    $pdf->SetFont('helvetica', '', 7);
                    $pdf->Cell($colP[0], 7, '  ' . $dateStr, 0, 0, 'L', $isZebraP);
                    $pdf->SetFont('helvetica', '', 6);
                    $pdf->Cell($colP[1], 7, '  ' . $desc, 0, 0, 'L', $isZebraP, '', 1);
                    $pdf->SetFont('helvetica', 'B', 7);
                    $pdf->SetTextColor(220, 38, 38);
                    $pdf->Cell($colP[2], 7, 'MX$' . number_format($montoPend, 2) . '  ', 0, 0, 'R', $isZebraP);
                    $pdf->Cell($colP[3], 7, 'MX$' . number_format($acumulado, 2) . '  ', 0, 1, 'R', $isZebraP);
                    $pdf->SetTextColor(50, 50, 50);
                    $pIdx++;
                }

                // Total footer
                $pdf->SetFillColor(254, 226, 226);
                $pdf->SetTextColor(220, 38, 38);
                $pdf->SetFont('helvetica', 'B', 9);
                $pdf->Cell(175.6, 8, '  Total Pendiente: MX$' . number_format($saldoPendiente, 2), 0, 1, 'L', true);
            }
        }

        // ── 6. Instrucciones de Pago (Premium Enterprise Design) ──
        @error_reporting(0);
        @ini_set('display_errors', 0);

        $pdf->Ln(4);
        if ($pdf->GetY() > 210) {
            $pdf->AddPage();
        }

        $dueDay = $demoCondo['billing_due_day'] ?? 15;

        // Formatear Tarjeta Bancaria (4 en 4)
        $formattedCard = '';
        if (!empty($demoCondo['bank_card'])) {
            $cardRaw = preg_replace('/\D/', '', $demoCondo['bank_card']);
            $formattedCard = implode(' ', str_split($cardRaw, 4));
        }

        // Estilo de filas bancarias con diseño limpio y compatible
        $bankRowsHtml = '';
        if (!empty($demoCondo['bank_name'])) {
            $bankRowsHtml .= '<tr><td width="40%"><font color="#64748b">Nombre del Banco</font></td><td width="60%"><font color="#1e293b"><b>' . strtoupper($demoCondo['bank_name']) . '</b></font></td></tr>';
        }
        if (!empty($demoCondo['bank_clabe'])) {
            $bankRowsHtml .= '<tr><td width="40%"><font color="#64748b">Cuenta CLABE</font></td><td width="60%"><font color="#059669" size="10.5pt"><b>' . $demoCondo['bank_clabe'] . '</b></font></td></tr>';
        }
        if (!empty($demoCondo['bank_rfc'])) {
            $bankRowsHtml .= '<tr><td width="40%"><font color="#64748b">Numero de Cuenta</font></td><td width="60%"><font color="#1e293b"><b>' . strtoupper($demoCondo['bank_rfc']) . '</b></font></td></tr>';
        }
        if (!empty($formattedCard)) {
            $bankRowsHtml .= '<tr><td width="40%"><font color="#64748b">Tarjeta Bancaria</font></td><td width="60%"><font color="#1e293b"><b>' . $formattedCard . '</b></font></td></tr>';
        }

        $html = '
        <table cellpadding="10" cellspacing="0" style="width: 100%; border: 1px solid #e2e8f0; background-color: #f8fafc;">
            <tr>
                <td width="1.2%" style="background-color: #10b981;"></td>
                <td width="98.8%">
                    <table cellpadding="0" cellspacing="0" style="width: 100%;">
                        <tr>
                            <td>
                                <font size="11.5pt" color="#0f172a"><b>Instrucciones de Pago</b></font><br/>
                                <font size="8.5pt" color="#64748b">Realice su pago antes del día <b>' . $dueDay . '</b> del mes para evitar recargos.</font>
                            </td>
                        </tr>
                        <tr><td height="12"></td></tr>
                        <tr>
                            <td>
                                <table cellpadding="0" cellspacing="0" style="width: 100%;">
                                    <tr>
                                        <td width="35%" valign="top">
                                            <font size="9.5pt" color="#1e293b"><b>Información para Transferencia (SPEI)</b></font>
                                        </td>
                                        <td width="65%">
                                            <table cellpadding="3" cellspacing="0" style="width: 100%;">
                                                ' . $bankRowsHtml . '
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr><td height="12"></td></tr>
                        <tr>
                            <td>
                                <table cellpadding="8" cellspacing="0" style="width: 100%; background-color: #fffbeb; border: 1px solid #fef3c7;">
                                    <tr>
                                        <td style="color: #92400e; font-size: 8.5pt; line-height: 1.4;">
                                            <font color="#b45309"><b>Importante:</b></font> Una vez realizado el movimiento, es indispensable cargar su comprobante en la aplicación o enviarlo a administración para su conciliación.
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>';

        $pdf->SetTextColor(50, 50, 50);
        $pdf->writeHTMLCell(175.6, 0, 18, $pdf->GetY(), $html, 0, 1, false, true, 'L', true);

        // ── OUTPUT (Final Shield) ──
        $fileName = 'Estado de Cuenta - Unidad ' . $unit['unit_number'] . '.pdf';

        if ($returnString) {
            return $pdf->Output($fileName, 'S');
        }

        if (ob_get_length())
            ob_end_clean();

        header('Content-Description: File Transfer');
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        $pdf->Output($fileName, 'D');
        exit;
    }

    /**
     * API: Obtiene info de la unidad para el modal de cargo por mora.
     * Retorna residentes y períodos pendientes (meses con cargos pending/partial).
     */
    public function moraUnitInfo($identifier)
    {
        $condoModel = new CondominiumModel();
        $demoCondo = $condoModel->first();
        if (!$demoCondo)
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Condo not found']);

        $db = \Config\Database::connect();

        $unit = $db->table('units')->where('condominium_id', $demoCondo['id'])
            ->groupStart()
            ->where('id', $identifier)
            ->orWhere('hash_id', $identifier)
            ->groupEnd()
            ->get()->getRowArray();

        if (!$unit)
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Unidad no encontrada']);
        $unitId = $unit['id'];

        // Residentes de la unidad
        $residents = $db->table('residents r')
            ->select('u.first_name, u.last_name')
            ->join('users u', 'u.id = r.user_id')
            ->where('r.unit_id', $unitId)
            ->where('r.is_active', 1)
            ->orderBy('r.type', 'ASC')
            ->get()->getResultArray();

        $residentNames = [];
        foreach ($residents as $r) {
            $residentNames[] = trim($r['first_name'] . ' ' . $r['last_name']);
        }

        // Obtener meses con cargos pendientes/parciales para esta unidad
        $mesesES = ['01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'];

        try {
            $pendingCharges = $db->query("
                SELECT DISTINCT DATE_FORMAT(due_date, '%Y-%m') AS period
                FROM financial_transactions
                WHERE unit_id = ? AND condominium_id = ? AND type = 'charge' AND status IN ('pending','partial')
                  AND due_date < CURDATE()
                ORDER BY period DESC
            ", [$unitId, $demoCondo['id']])->getResultArray();
        } catch (\Exception $e) {
            $pendingCharges = [];
        }

        $periods = [];
        foreach ($pendingCharges as $pc) {
            $parts = explode('-', $pc['period']);
            $label = ($mesesES[$parts[1]] ?? $parts[1]) . ' De ' . $parts[0];
            $periods[] = ['value' => $pc['period'], 'label' => $label];
        }

        // Si no hay períodos pendientes, ofrecer el mes actual
        if (empty($periods)) {
            $currentMonth = date('Y-m');
            $parts = explode('-', $currentMonth);
            $label = ($mesesES[$parts[1]] ?? $parts[1]) . ' De ' . $parts[0];
            $periods[] = ['value' => $currentMonth, 'label' => $label];
        }

        return $this->response->setJSON([
            'residents' => implode(', ', $residentNames) ?: null,
            'periods' => $periods,
        ]);
    }

    /**
     * API POST: Aplica un cargo por mora a una unidad.
     * Crea una transacción de tipo 'charge' con la categoría 'Cargo por Mora'.
     */
    public function applyMoraCharge()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid request']);
        }

        $json = $this->request->getJSON();
        $unitIdentifier = $json->unit_id ?? '';
        $amount = (float) ($json->amount ?? 0);
        $period = $json->period ?? '';
        $motivo = $json->motivo ?? '';

        if (!$unitIdentifier || $amount <= 0 || !$period) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos incompletos: unidad, monto y período son requeridos.'
            ]);
        }

        $condoModel = new CondominiumModel();
        $demoCondo = $condoModel->first();
        if (!$demoCondo) {
            return $this->response->setJSON(['success' => false, 'message' => 'Condominio no encontrado.']);
        }

        $db = \Config\Database::connect();

        // Verificar que la unidad existe
        $unit = $db->table('units')
            ->where('condominium_id', $demoCondo['id'])
            ->groupStart()
            ->where('id', $unitIdentifier)
            ->orWhere('hash_id', $unitIdentifier)
            ->groupEnd()
            ->get()->getRowArray();

        if (!$unit) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unidad no encontrada.']);
        }
        $unitId = (int) $unit['id'];

        // Buscar o crear la categoría "Cargo por Mora"
        $categoryModel = new FinancialCategoryModel();
        $moraCat = $categoryModel->where('condominium_id', $demoCondo['id'])
            ->where('name', 'Cargo por Mora')
            ->first();

        if (!$moraCat) {
            $moraCatId = $categoryModel->insert([
                'condominium_id' => $demoCondo['id'],
                'name' => 'Cargo por Mora',
                'description' => 'Cargo por pago tardío',
                'type' => 'income',
                'is_system' => 1,
            ]);
        } else {
            $moraCatId = $moraCat['id'];
        }

        // Meses en español para la descripción
        $mesesES = ['01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'];
        $parts = explode('-', $period);
        $monthLabel = ($mesesES[$parts[1] ?? ''] ?? '') . ' ' . ($parts[0] ?? '');
        $description = 'Cargo por Mora - ' . $monthLabel;
        if ($motivo) {
            $description .= ' (' . $motivo . ')';
        }

        $dueDate = $period . '-01';

        $transactionModel = new FinancialTransactionModel();
        $txnId = $transactionModel->insert([
            'condominium_id' => $demoCondo['id'],
            'unit_id' => $unitId,
            'category_id' => $moraCatId,
            'type' => 'charge',
            'amount' => $amount,
            'description' => $description,
            'due_date' => $dueDate,
            'status' => 'pending',
            'source' => 'manual',
        ]);

        if ($txnId) {
            $this->applyFloatingCredit($unitId);
            $this->recalculateUnitBalances($unitId);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Cargo por mora aplicado exitosamente.',
                'transaction_id' => $txnId,
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error al crear el cargo. Intente de nuevo.',
        ]);
    }

    /**
     * Descarga el reporte financiero del mes seleccionado en formato PDF.
     */
    public function descargarReporteMensual($returnString = false)
    {
        $condoModel = new CondominiumModel();
        $demoCondo = $condoModel->first();

        if (!$demoCondo) {
            return redirect()->to('/admin/dashboard')->with('error', 'Condominio no configurado.');
        }

        $db = \Config\Database::connect();
        $condoId = $demoCondo['id'];

        $selectedMonth = $this->request->getGet('month') ?: date('Y-m');
        @list($y, $m) = explode('-', $selectedMonth);
        if (!$y || !$m) {
            $y = date('Y');
            $m = date('m');
            $selectedMonth = date('Y-m');
        }

        $monthStart = $selectedMonth . '-01';
        $monthEnd = date('Y-m-t', strtotime($monthStart));

        // Meses en español
        $mesesES = ['01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'];
        $mesGenerado = mb_strtolower($mesesES[$m] ?? '') . ' de ' . $y;
        $fechaReporte = date('d/m/Y');

        // Recolectar datos
        // 1. Ingresos
        $row = $db->query("
            SELECT IFNULL(SUM(ft.amount),0) AS total
            FROM financial_transactions ft
            INNER JOIN financial_categories c ON c.id = ft.category_id
            WHERE ft.condominium_id = ? AND ft.type = 'credit'
              AND ft.status = 'paid' AND c.type = 'income'
              AND ft.created_at BETWEEN ? AND ?
        ", [$condoId, $monthStart . ' 00:00:00', $monthEnd . ' 23:59:59'])->getRow();
        $totalIngresos = $row ? (float) $row->total : 0.00;

        // 2. Gastos
        $row = $db->query("
            SELECT IFNULL(SUM(ft.amount),0) AS total
            FROM financial_transactions ft
            INNER JOIN financial_categories c ON c.id = ft.category_id
            WHERE ft.condominium_id = ? AND ft.type = 'credit'
              AND ft.status = 'paid' AND c.type = 'expense'
              AND ft.created_at BETWEEN ? AND ?
        ", [$condoId, $monthStart . ' 00:00:00', $monthEnd . ' 23:59:59'])->getRow();
        $totalGastos = $row ? (float) $row->total : 0.00;

        $utilidadNeta = $totalIngresos - $totalGastos;

        // 3. Unidades (Cobranza y Status)
        $todayStr = date('Y-m-d');
        $builderU = $db->table('units');
        $builderU->select('units.id, 
            IFNULL(units.initial_balance, 0) + IFNULL(SUM(CASE WHEN ft.type = "charge" THEN ft.amount WHEN ft.type = "credit" THEN -ft.amount ELSE 0 END), 0) as debt,
            IFNULL(units.initial_balance, 0) + IFNULL(SUM(CASE WHEN ft.type = "charge" AND ft.due_date < "' . $todayStr . '" THEN ft.amount WHEN ft.type = "credit" THEN -ft.amount ELSE 0 END), 0) as debt_vencida,
            units.maintenance_fee');
        $builderU->join('financial_transactions ft', "ft.unit_id = units.id AND ft.status != 'cancelled' AND ft.due_date <= '{$monthEnd}'", 'left');
        $builderU->where('units.condominium_id', $condoId);
        $builderU->groupBy('units.id');
        $unitDebtsRaw = $builderU->get()->getResultArray();

        $unidadesTotales = count($unitDebtsRaw);
        $unidadesMorosas = 0;
        $unidadesCorriente = 0;
        $unidadesFavor = 0;
        $unidadesSinDeuda = 0;
        $ingresosEsperados = 0;

        foreach ($unitDebtsRaw as $u) {
            $ingresosEsperados += (float) $u['maintenance_fee'];
            $d = (float) $u['debt'];
            $dv = (float) $u['debt_vencida'];

            if ($dv > 0.01) {
                $unidadesMorosas++;
            } elseif ($d > 0.01) {
                $unidadesCorriente++;
            } elseif ($d < -0.01) {
                $unidadesFavor++;
            } else {
                $unidadesSinDeuda++;
            }
        }

        $ingresosRecaudados = $totalIngresos; // Para simplificar
        $unidadesNoMorosas = $unidadesCorriente + $unidadesFavor + $unidadesSinDeuda;
        $eficienciaCobranza = ($ingresosEsperados > 0) ? ($ingresosRecaudados / $ingresosEsperados) * 100 : 0;
        $tasaCobranza = ($unidadesTotales > 0) ? ($unidadesNoMorosas / $unidadesTotales) * 100 : 0;

        // Ingresos por Categoria
        $incomeByCat = $db->query("
            SELECT c.name, COUNT(ft.id) AS count, IFNULL(SUM(ft.amount),0) AS total
            FROM financial_transactions ft
            INNER JOIN financial_categories c ON c.id = ft.category_id
            WHERE ft.condominium_id = ? AND ft.type = 'credit'
              AND ft.status = 'paid' AND c.type = 'income'
              AND ft.created_at BETWEEN ? AND ?
            GROUP BY c.id ORDER BY total DESC
        ", [$condoId, $monthStart . ' 00:00:00', $monthEnd . ' 23:59:59'])->getResultArray();

        // Gastos por Categoria
        $expenseByCat = $db->query("
            SELECT c.name, COUNT(ft.id) AS count, IFNULL(SUM(ft.amount),0) AS total
            FROM financial_transactions ft
            INNER JOIN financial_categories c ON c.id = ft.category_id
            WHERE ft.condominium_id = ? AND ft.type = 'credit'
              AND ft.status = 'paid' AND c.type = 'expense'
              AND ft.created_at BETWEEN ? AND ?
            GROUP BY c.id ORDER BY total DESC
        ", [$condoId, $monthStart . ' 00:00:00', $monthEnd . ' 23:59:59'])->getResultArray();

        // 4. Transacciones
        $builder = $db->table('financial_transactions ft');
        $builder->select('ft.*, units.unit_number, cats.name as category_name, cats.type as category_type');
        $builder->join('units', 'units.id = ft.unit_id', 'left');
        $builder->join('financial_categories cats', 'cats.id = ft.category_id', 'left');
        $builder->where('ft.condominium_id', $condoId);
        $builder->where('ft.source', 'manual');
        $builder->where('ft.status !=', 'cancelled');
        $builder->where('ft.type !=', 'charge'); // Solo Pagos y Gastos
        $builder->where('MONTH(ft.created_at)', $m);
        $builder->where('YEAR(ft.created_at)', $y);
        $builder->orderBy('ft.created_at', 'ASC');
        $transactions = $builder->get()->getResultArray();

       $logoFile = $demoCondo['logo'] ?? '';
        if (!empty($logoFile)) {
            $logoPath = (strpos($logoFile, '/') !== false)
                ? WRITEPATH . 'uploads/' . $logoFile
                : WRITEPATH . 'uploads/condominiums/' . $demoCondo['id'] . '/' . $logoFile;
            $hasLogo = is_file($logoPath);
        } else {
            $logoPath = '';
            $hasLogo = false;
        }

        // TCPDF no resuelve bien paths absolutos del filesystem dentro de <img src="..."> en writeHTML().
        // Convertimos el archivo a base64 data URI para inyectarlo inline.
        if ($hasLogo) {
            $imgBinary = @file_get_contents($logoPath);
            if ($imgBinary !== false) {
                $logoSrc = '@' . base64_encode($imgBinary);
            } else {
                $hasLogo = false;
                $logoSrc = 'https://ui-avatars.com/api/?name=C+N&background=ffffff&color=000&bold=true';
            }
        } else {
            $logoSrc = 'https://ui-avatars.com/api/?name=C+N&background=ffffff&color=000&bold=true';
        }

        $addressStr = $demoCondo['address'] ?? '';
        $parts = array_values(array_filter(array_map('trim', explode(',', $addressStr)), static fn($item) => $item !== ''));
        $city = $parts[1] ?? 'Sin definir';
        $state = $parts[2] ?? 'Sin definir';

        $locationStr = trim($city . ', ' . $state, ', ');
        if ($locationStr === 'Sin definir, Sin definir' || empty($locationStr)) {
            $locationStr = 'Ciudad no definida';
        }

        require_once ROOTPATH . 'vendor/autoload.php';
        $pdf = new class (PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false) extends \TCPDF {
            public $fechaReporte;
            public $condoNameFooter = '';
            public function Footer()
            {
                $this->SetY(-12);
                $this->SetDrawColor(226, 232, 240);
                $this->Line(15, $this->GetY(), 195, $this->GetY());
                $this->SetY(-10);
                $this->SetFont('helvetica', '', 7);
                $this->SetTextColor(148, 163, 184);
                $this->Cell(60, 5, strtoupper($this->condoNameFooter), 0, 0, 'L');
                $this->SetFont('helvetica', '', 7);
                $this->Cell(60, 5, 'Generado el ' . $this->fechaReporte . ' | AxisCondo', 0, 0, 'C');
                $this->Cell(60, 5, $this->getAliasNumPage() . ' / ' . $this->getAliasNbPages(), 0, 0, 'R');
            }
        };
        $pdf->fechaReporte = $fechaReporte;
        $pdf->condoNameFooter = $demoCondo['name'] ?? 'AxisCondo';

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('AxisCondo');
        $pdf->SetTitle('Reporte Financiero ' . $mesGenerado);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(true);
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(TRUE, 18);

        $pdf->AddPage();

        // ── HEADER BAR (Native TCPDF drawing) ──
        // Blue header area (full width)
        $pdf->SetFillColor(29, 76, 157); // #1D4C9D
        $pdf->Rect(15, 15, 180, 36, 'F');

        // Logo with white background
        if ($hasLogo) {
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Rect(19, 19, 28, 28, 'F');
            $pdf->Image($logoPath, 20, 20, 26, 26, '', '', '', false, 300, '', false, false, 0, 'CM', false, false);
        } else {
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Rect(19, 19, 28, 28, 'F');
            $pdf->SetFillColor(29, 76, 157);
            $pdf->Rect(23, 29, 20, 4, 'F');
        }

        // Title: REPORTE FINANCIERO - MES AÑO
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->SetXY(51, 18);
        $pdf->Cell(141, 8, 'REPORTE FINANCIERO - ' . mb_strtoupper($mesGenerado, 'UTF-8'), 0, 1, 'C');

        // Subtitle: COMUNIDAD: NOMBRE
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->SetXY(51, 27);
        $pdf->Cell(141, 7, 'COMUNIDAD: ' . mb_strtoupper($demoCondo['name'] ?? '', 'UTF-8'), 0, 1, 'C');

        // Address
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTextColor(199, 210, 232); // #c7d2e8
        $pdf->SetXY(51, 35);
        $pdf->Cell(141, 5, mb_strtoupper($addressStr, 'UTF-8'), 0, 1, 'C');

        // Blue accent line below header
        $pdf->SetFillColor(63, 103, 172); // #3F67AC
        $pdf->Rect(15, 51, 180, 1.2, 'F');

        $pdf->SetY(56);
        $pdf->SetTextColor(0, 0, 0);

        // Continue with HTML content
        $html_body = '
        <style>
            .section-title { font-size: 11pt; font-weight: bold; color: #0f172a; margin-top: 18px; margin-bottom: 8px; letter-spacing: 0.02em; }
            .table-data { width: 100%; border-collapse: collapse; }
            .table-data th, .table-data td { padding: 7px 10px; font-size: 8.5pt; }
            .table-data td { border-bottom: 1px solid #f1f5f9; color: #334155; }
            .th-dark { background-color: #1D4C9D; color: #ffffff; font-weight: bold; text-align: center; font-size: 7.5pt; text-transform: uppercase; letter-spacing: 0.05em; padding: 8px 10px; }
            .text-success { color: #059669; font-weight: bold; }
            .text-danger { color: #dc2626; font-weight: bold; }
            .center { text-align: center; }
            .right { text-align: right; }
        </style>
        
        <h3 class="section-title">Resumen Ejecutivo</h3>
        <table width="100%" cellpadding="0" cellspacing="0"><tr><td style="background-color: #e2e8f0; height: 1px; line-height: 1px; font-size: 1px;">&nbsp;</td></tr></table>
        <br>
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="32%">
                    <table width="100%" cellpadding="10" bgcolor="#f0fdf4" style="border: 1px solid #bbf7d0;">
                        <tr><td>
                            <div style="font-size: 7pt; color: #16a34a; text-transform: uppercase; letter-spacing: 0.1em; font-weight: bold;">INGRESOS TOTALES</div>
                            <div style="font-size: 16pt; font-weight: bold; color: #059669;">MX$' . number_format($totalIngresos, 2) . '</div>
                        </td></tr>
                    </table>
                </td>
                <td width="2%"></td>
                <td width="32%">
                    <table width="100%" cellpadding="10" bgcolor="#fef2f2" style="border: 1px solid #fecaca;">
                        <tr><td>
                            <div style="font-size: 7pt; color: #dc2626; text-transform: uppercase; letter-spacing: 0.1em; font-weight: bold;">GASTOS TOTALES</div>
                            <div style="font-size: 16pt; font-weight: bold; color: #dc2626;">MX$' . number_format($totalGastos, 2) . '</div>
                        </td></tr>
                    </table>
                </td>
                <td width="2%"></td>
                <td width="32%">
                    <table width="100%" cellpadding="10" bgcolor="' . ($utilidadNeta >= 0 ? '#f0fdf4' : '#fef2f2') . '" style="border: 1px solid ' . ($utilidadNeta >= 0 ? '#bbf7d0' : '#fecaca') . ';">
                        <tr><td>
                            <div style="font-size: 7pt; color: #0f172a; text-transform: uppercase; letter-spacing: 0.1em; font-weight: bold;">UTILIDAD NETA</div>
                            <div style="font-size: 16pt; font-weight: bold; color: ' . ($utilidadNeta >= 0 ? '#059669' : '#dc2626') . ';">MX$' . number_format($utilidadNeta, 2) . '</div>
                        </td></tr>
                    </table>
                </td>
            </tr>
        </table>
        <br>
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="32%">
                    <table width="100%" cellpadding="10" bgcolor="#f8fafc" style="border: 1px solid #e2e8f0;">
                        <tr><td>
                            <div style="font-size: 7pt; color: #64748b; text-transform: uppercase; letter-spacing: 0.1em; font-weight: bold;">Tasa de Cobranza</div>
                            <div style="font-size: 16pt; font-weight: bold; color: #0f172a;">' . number_format($tasaCobranza, 2) . '%</div>
                        </td></tr>
                    </table>
                </td>
                <td width="2%"></td>
                <td width="32%">
                    <table width="100%" cellpadding="10" bgcolor="#f8fafc" style="border: 1px solid #e2e8f0;">
                        <tr><td>
                            <div style="font-size: 7pt; color: #64748b; text-transform: uppercase; letter-spacing: 0.1em; font-weight: bold;">Unidades Totales</div>
                            <div style="font-size: 16pt; font-weight: bold; color: #0f172a;">' . $unidadesTotales . '</div>
                        </td></tr>
                    </table>
                </td>
                <td width="2%"></td>
                <td width="32%">
                    <table width="100%" cellpadding="10" bgcolor="#f8fafc" style="border: 1px solid #e2e8f0;">
                        <tr><td>
                            <div style="font-size: 7pt; color: #64748b; text-transform: uppercase; letter-spacing: 0.1em; font-weight: bold;">Período</div>
                            <div style="font-size: 11pt; font-weight: bold; color: #0f172a;">' . $mesGenerado . '</div>
                        </td></tr>
                    </table>
                </td>
            </tr>
        </table>
        
        <br>
        <h3 class="section-title">Análisis de Ingresos</h3>
        <table width="100%" cellpadding="0" cellspacing="0"><tr><td style="background-color: #e2e8f0; height: 1px; line-height: 1px; font-size: 1px;">&nbsp;</td></tr></table>';

    if (empty($incomeByCat)) {
            $html_body .= '
            <br>
            <table width="100%" cellpadding="20"><tr><td>
                <table width="100%" cellpadding="15" bgcolor="#f8fafc" style="border: 1px dashed #cbd5e1;">
                    <tr>
                        <td align="center">
                            <span style="font-weight: bold; font-size: 12pt; color: #64748b;">Sin Datos de Ingresos</span><br><br>
                            <span style="font-size: 9pt; color: #94a3b8;">No se registraron ingresos durante este período.</span>
                        </td>
                    </tr>
                </table>
            </td></tr></table>
            <br>
            ';
            $pdf->writeHTML($html_body, true, false, true, false, '');
            $cy = $pdf->GetY() + 15;
        } else {
            $html_body .= '
            <br>
            <table class="table-data" cellpadding="8">
                <tr>
                    <th width="40%" class="th-dark" style="text-align: left;">Categoría</th>
                    <th width="20%" class="th-dark">Cantidad</th>
                    <th width="20%" class="th-dark">Monto</th>
                    <th width="20%" class="th-dark">% del Total</th>
                </tr>';

            $totalTxIncome = 0;
            $rowIdx = 0;
            foreach ($incomeByCat as $iCat) {
                $totalTxIncome += $iCat['count'];
                $pct = ($totalIngresos > 0) ? ($iCat['total'] / $totalIngresos) * 100 : 0;
                $bgRow = ($rowIdx % 2 === 0) ? '#ffffff' : '#f8fafc';
                $html_body .= '<tr bgcolor="' . $bgRow . '">
                    <td width="40%" style="color: #334155; font-weight: 500;">' . esc($iCat['name']) . '</td>
                    <td width="20%" class="center" style="color: #64748b;">' . $iCat['count'] . '</td>
                    <td width="20%" class="right" style="color: #059669; font-weight: bold;">MX$' . number_format($iCat['total'], 2) . '</td>
                    <td width="20%" class="right" style="color: #64748b;">' . number_format($pct, 2) . '%</td>
                </tr>';
                $rowIdx++;
            }

            $html_body .= '
                <tr bgcolor="#f0fdf4">
                    <td width="40%" style="border-top: 2px solid #059669;"><b style="color: #059669;">TOTAL INGRESOS</b></td>
                    <td width="20%" class="center" style="border-top: 2px solid #059669;"><b style="color: #059669;">' . $totalTxIncome . '</b></td>
                    <td width="20%" class="right" style="border-top: 2px solid #059669;"><b style="color: #059669;">MX$' . number_format($totalIngresos, 2) . '</b></td>
                    <td width="20%" class="right" style="border-top: 2px solid #059669;"><b style="color: #059669;">100%</b></td>
                </tr>
            </table>
            <br>
            ';

            $html_body .= '<h3 class="center" style="font-size: 10pt; color: #334155;">Distribución de Ingresos</h3><br>';
            $pdf->writeHTML($html_body, true, false, true, false, '');

            // Simular pastel con poligonos
            if ($pdf->GetY() > 210) {
                $pdf->AddPage();
            }
            $cy = $pdf->GetY() + 15;
            $pieColors = [
                [59, 130, 246], // blue
                [16, 185, 129], // emerald
                [245, 158, 11], // amber
                [236, 72, 153], // pink
                [139, 92, 246], // violet
                [14, 165, 233], // sky
                [249, 115, 22]  // orange
            ];

            $startAngle = 0;
            $cIdx = 0;
            $legendHtml = '<table cellpadding="4">';

            foreach ($incomeByCat as $iCat) {
                $pct = ($totalIngresos > 0) ? ($iCat['total'] / $totalIngresos) : 0;
                $angle = $pct * 360;

                if ($angle > 0) {
                    $endAngle = $startAngle + $angle;
                    $c = $pieColors[$cIdx % count($pieColors)];
                    $pdf->SetFillColor($c[0], $c[1], $c[2]);

                    // Asegurar que si es exacto 360 no haya glitch y trace un circulo entero
                    if ($angle >= 359.9) {
                        $pdf->Circle(60, $cy, 20, 0, 360, 'F');
                    } else {
                        $pdf->PieSector(60, $cy, 20, $startAngle, $endAngle, 'F');
                    }
                    $startAngle = $endAngle;
                }

                $cHex = sprintf("#%02x%02x%02x", $pieColors[$cIdx % count($pieColors)][0], $pieColors[$cIdx % count($pieColors)][1], $pieColors[$cIdx % count($pieColors)][2]);
                $legendHtml .= '<tr><td width="15px" bgcolor="' . $cHex . '"></td><td>' . esc($iCat['name']) . ': MX$' . number_format($iCat['total'], 2) . '</td></tr>';
                $cIdx++;
            }
            $legendHtml .= '</table>';

            $pdf->setXY(100, $cy - 10);
            $pdf->writeHTML($legendHtml, true, false, true, false, '');

            if ($pdf->GetY() < $cy + 25) {
                $pdf->SetY($cy + 25);
            }
        }

        $pdf->AddPage();

        $html2 = '
        <style>
            .section-title { font-size: 11pt; font-weight: bold; color: #0f172a; margin-top: 18px; margin-bottom: 8px; letter-spacing: 0.02em; }
            .table-data { width: 100%; border-collapse: collapse; }
            .table-data th, .table-data td { padding: 7px 10px; font-size: 8.5pt; }
            .table-data td { border-bottom: 1px solid #f1f5f9; color: #334155; }
            .th-dark { background-color: #1D4C9D; color: #ffffff; font-weight: bold; text-align: center; font-size: 7.5pt; text-transform: uppercase; letter-spacing: 0.05em; padding: 8px 10px; }
            .text-success { color: #059669; font-weight: bold; }
            .text-danger { color: #dc2626; font-weight: bold; }
            .center { text-align: center; }
            .right { text-align: right; }
        </style>
        
        <h3 class="section-title">Análisis de Gastos</h3>
        <table width="100%" cellpadding="0" cellspacing="0"><tr><td style="background-color: #e2e8f0; height: 1px; line-height: 1px; font-size: 1px;">&nbsp;</td></tr></table>';

        if (empty($expenseByCat)) {
            $html2 .= '
            <br>
            <table width="100%" cellpadding="20"><tr><td>
                <table width="100%" cellpadding="15" bgcolor="#f8fafc" style="border: 1px dashed #cbd5e1;">
                    <tr>
                        <td align="center">
                            <span style="font-weight: bold; font-size: 12pt; color: #64748b;">Sin Datos de Gastos</span><br><br>
                            <span style="font-size: 9pt; color: #94a3b8;">No se registraron gastos durante este período.</span>
                        </td>
                    </tr>
                </table>
            </td></tr></table>
            <br>
            ';
            $pdf->writeHTML($html2, true, false, true, false, '');
            $cy2 = $pdf->GetY() + 15;
        } else {
            $html2 .= '
            <br>
            <table class="table-data" cellpadding="8">
                <tr>
                    <th width="40%" class="th-dark" style="text-align: left;">Categoría</th>
                    <th width="20%" class="th-dark">Cantidad</th>
                    <th width="20%" class="th-dark">Monto</th>
                    <th width="20%" class="th-dark">% del Total</th>
                </tr>';

            $totalTxExpense = 0;
            $rowIdx = 0;
            foreach ($expenseByCat as $eCat) {
                $totalTxExpense += $eCat['count'];
                $pct = ($totalGastos > 0) ? ($eCat['total'] / $totalGastos) * 100 : 0;
                $bgRow = ($rowIdx % 2 === 0) ? '#ffffff' : '#f8fafc';
                $html2 .= '<tr bgcolor="' . $bgRow . '">
                    <td width="40%" style="color: #334155; font-weight: 500;">' . esc($eCat['name']) . '</td>
                    <td width="20%" class="center" style="color: #64748b;">' . $eCat['count'] . '</td>
                    <td width="20%" class="right" style="color: #dc2626; font-weight: bold;">MX$' . number_format($eCat['total'], 2) . '</td>
                    <td width="20%" class="right" style="color: #64748b;">' . number_format($pct, 2) . '%</td>
                </tr>';
                $rowIdx++;
            }

            $html2 .= '
                <tr bgcolor="#fef2f2">
                    <td width="40%" style="border-top: 2px solid #dc2626;"><b style="color: #dc2626;">TOTAL GASTOS</b></td>
                    <td width="20%" class="center" style="border-top: 2px solid #dc2626;"><b style="color: #dc2626;">' . $totalTxExpense . '</b></td>
                    <td width="20%" class="right" style="border-top: 2px solid #dc2626;"><b style="color: #dc2626;">MX$' . number_format($totalGastos, 2) . '</b></td>
                    <td width="20%" class="right" style="border-top: 2px solid #dc2626;"><b style="color: #dc2626;">100%</b></td>
                </tr>
            </table>
            <br>
            <h3 class="center" style="font-size: 10pt; color: #334155;">Distribución de Gastos</h3><br>';

            $pdf->writeHTML($html2, true, false, true, false, '');
            if ($pdf->GetY() > 210) {
                $pdf->AddPage();
            }
            $cy2 = $pdf->GetY() + 15;

            $startAngle = 0;
            $cIdx = 0;
            $legendHtml2 = '<table cellpadding="4">';

            foreach ($expenseByCat as $eCat) {
                $pct = ($totalGastos > 0) ? ($eCat['total'] / $totalGastos) : 0;
                $angle = $pct * 360;

                if ($angle > 0) {
                    $endAngle = $startAngle + $angle;
                    $c = $pieColors[$cIdx % count($pieColors)]; // Reuse $pieColors initialized in incomes
                    $pdf->SetFillColor($c[0], $c[1], $c[2]);

                    if ($angle >= 359.9) {
                        $pdf->Circle(60, $cy2, 20, 0, 360, 'F');
                    } else {
                        $pdf->PieSector(60, $cy2, 20, $startAngle, $endAngle, 'F');
                    }
                    $startAngle = $endAngle;
                }

                $cHex = sprintf("#%02x%02x%02x", $pieColors[$cIdx % count($pieColors)][0], $pieColors[$cIdx % count($pieColors)][1], $pieColors[$cIdx % count($pieColors)][2]);
                $legendHtml2 .= '<tr><td width="15px" bgcolor="' . $cHex . '"></td><td>' . esc($eCat['name']) . ': MX$' . number_format($eCat['total'], 2) . '</td></tr>';
                $cIdx++;
            }
            $legendHtml2 .= '</table>';

            $pdf->setXY(100, $cy2 - 10);
            $pdf->writeHTML($legendHtml2, true, false, true, false, '');

            if ($pdf->GetY() < $cy2 + 25) {
                $pdf->SetY($cy2 + 25);
            }
        }

        $html2_pt2 = '
        <br>
        <h3 class="section-title">Estado de Pagos por Unidad</h3>
        <table width="100%" cellpadding="0" cellspacing="0"><tr><td style="background-color: #e2e8f0; height: 1px; line-height: 1px; font-size: 1px;">&nbsp;</td></tr></table>
        <br>
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="23.5%">
                    <table width="100%" cellpadding="10" bgcolor="#eff6ff" style="border: 1px solid #bfdbfe;">
                        <tr><td align="center">
                            <div style="font-size: 7pt; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.1em; font-weight: bold;">Al Corriente</div>
                            <div style="font-size: 22pt; font-weight: bold; color: #1d4ed8;">' . $unidadesCorriente . '</div>
                        </td></tr>
                    </table>
                </td>
                <td width="2%"></td>
                <td width="23.5%">
                    <table width="100%" cellpadding="10" bgcolor="#f0fdf4" style="border: 1px solid #bbf7d0;">
                        <tr><td align="center">
                            <div style="font-size: 7pt; color: #059669; text-transform: uppercase; letter-spacing: 0.1em; font-weight: bold;">Sin Adeudos</div>
                            <div style="font-size: 22pt; font-weight: bold; color: #15803d;">' . $unidadesSinDeuda . '</div>
                        </td></tr>
                    </table>
                </td>
                <td width="2%"></td>
                <td width="23.5%">
                    <table width="100%" cellpadding="10" bgcolor="#f0fdf4" style="border: 1px solid #bbf7d0;">
                        <tr><td align="center">
                            <div style="font-size: 7pt; color: #059669; text-transform: uppercase; letter-spacing: 0.1em; font-weight: bold;">A Favor</div>
                            <div style="font-size: 22pt; font-weight: bold; color: #15803d;">' . $unidadesFavor . '</div>
                        </td></tr>
                    </table>
                </td>
                <td width="2%"></td>
                <td width="23.5%">
                    <table width="100%" cellpadding="10" bgcolor="#fef2f2" style="border: 1px solid #fecaca;">
                        <tr><td align="center">
                            <div style="font-size: 7pt; color: #dc2626; text-transform: uppercase; letter-spacing: 0.1em; font-weight: bold;">Morosas</div>
                            <div style="font-size: 22pt; font-weight: bold; color: #b91c1c;">' . $unidadesMorosas . '</div>
                        </td></tr>
                    </table>
                </td>
            </tr>
        </table>
        
        <br>
        <h3 class="section-title">Eficiencia de Cobranza</h3>
        <table width="100%" cellpadding="0" cellspacing="0"><tr><td style="background-color: #e2e8f0; height: 1px; line-height: 1px; font-size: 1px;">&nbsp;</td></tr></table>
        <br>
        <table width="100%" cellpadding="9" style="border: 1px solid #e2e8f0;">
            <tr bgcolor="#f8fafc">
                <td width="65%" style="border-bottom: 1px solid #e2e8f0; font-size: 9pt; color: #334155;"><b>Ingresos Esperados</b></td>
                <td width="35%" align="right" style="border-bottom: 1px solid #e2e8f0; font-size: 9pt;"><b style="color: #0f172a;">MX$' . number_format($ingresosEsperados, 2) . '</b></td>
            </tr>
            <tr>
                <td width="65%" style="border-bottom: 1px solid #e2e8f0; font-size: 9pt; color: #334155;"><b>Ingresos Recaudados</b></td>
                <td width="35%" align="right" style="border-bottom: 1px solid #e2e8f0; font-size: 9pt;"><b style="color: #059669;">MX$' . number_format($ingresosRecaudados, 2) . '</b></td>
            </tr>
            <tr bgcolor="#f8fafc">
                <td width="65%" style="font-size: 9pt; color: #334155;"><b>Eficiencia de Cobranza</b></td>
                <td width="35%" align="right" style="font-size: 9pt;"><b style="color: ' . ($eficienciaCobranza >= 85 ? '#059669' : '#dc2626') . ';">' . number_format($eficienciaCobranza, 2) . '%</b></td>
            </tr>
        </table>
        ';

        $pdf->writeHTML($html2_pt2, true, false, true, false, '');

        // Check if we need a page break before transactions
        if ($pdf->GetY() > 220) {
            $pdf->AddPage();
        } else {
            $pdf->AddPage();
        }

        $html3 = '
        <style>
            .section-title { font-size: 11pt; font-weight: bold; color: #0f172a; margin-top: 18px; margin-bottom: 8px; letter-spacing: 0.02em; }
            .table-data { width: 100%; border-collapse: collapse; }
            .th-dark { background-color: #1D4C9D; color: #ffffff; font-weight: bold; text-align: center; font-size: 7.5pt; text-transform: uppercase; letter-spacing: 0.05em; padding: 8px 10px; }
            .table-data td { padding: 7px 10px; font-size: 8pt; border-bottom: 1px solid #f1f5f9; color: #334155; }
        </style>
        
        <h3 class="section-title">Transacciones del Período - ' . $mesGenerado . '</h3>
        <table width="100%" cellpadding="0" cellspacing="0"><tr><td style="background-color: #e2e8f0; height: 1px; line-height: 1px; font-size: 1px;">&nbsp;</td></tr></table>
        <br>
        <table class="table-data" cellpadding="8">
            <tr>
                <th width="14%" class="th-dark" style="text-align: left;">Fecha</th>
                <th width="12%" class="th-dark">Tipo</th>
                <th width="10%" class="th-dark">Unidad</th>
                <th width="18%" class="th-dark">Categoría</th>
                <th width="28%" class="th-dark" style="text-align: left;">Descripción</th>
                <th width="18%" class="th-dark">Monto</th>
            </tr>';

        $txIdx = 0;
        foreach ($transactions as $t) {
            $isExpense = ($t['category_type'] === 'expense');
            $tipoLabel = $isExpense
                ? '<span style="color: #dc2626; font-weight: bold; font-size: 7.5pt;">GASTO</span>'
                : '<span style="color: #059669; font-weight: bold; font-size: 7.5pt;">INGRESO</span>';
            $montoColor = $isExpense ? '#dc2626' : '#059669';
            $montoPrefix = $isExpense ? '-' : '+';
            $montoLabel = '<span style="color: ' . $montoColor . '; font-weight: bold;">' . $montoPrefix . ' MX$' . number_format($t['amount'], 2) . '</span>';

            $mesesCortos = ['Jan' => 'Ene', 'Feb' => 'Feb', 'Mar' => 'Mar', 'Apr' => 'Abr', 'May' => 'May', 'Jun' => 'Jun', 'Jul' => 'Jul', 'Aug' => 'Ago', 'Sep' => 'Sep', 'Oct' => 'Oct', 'Nov' => 'Nov', 'Dec' => 'Dic'];
            $fechaTx = date('d', strtotime($t['created_at'])) . ' de ' . ($mesesCortos[date('M', strtotime($t['created_at']))] ?? date('M', strtotime($t['created_at']))) . ' de ' . date('Y', strtotime($t['created_at']));
            $bgRow = ($txIdx % 2 === 0) ? '#ffffff' : '#f8fafc';
            $html3 .= '<tr bgcolor="' . $bgRow . '">
                <td width="14%" style="color: #64748b; font-size: 7.5pt;">' . $fechaTx . '</td>
                <td width="12%" align="center">' . $tipoLabel . '</td>
                <td width="10%" align="center" style="font-weight: 600; color: #0f172a;">' . esc($t['unit_number'] ?? '—') . '</td>
                <td width="18%" style="color: #475569; font-size: 7.5pt;">' . esc($t['category_name'] ?? 'Sin Categoría') . '</td>
                <td width="28%" style="color: #64748b; font-size: 7.5pt;">' . esc($t['description'] ?? '') . '</td>
                <td width="18%" align="right">' . $montoLabel . '</td>
            </tr>';
            $txIdx++;
        }

        $html3 .= '</table>
        <br><br>
        <h3 class="section-title">Observaciones</h3>
        <table width="100%" cellpadding="0" cellspacing="0"><tr><td style="background-color: #e2e8f0; height: 1px; line-height: 1px; font-size: 1px;">&nbsp;</td></tr></table>
        <br>
        <table width="100%" cellpadding="10" style="border: 1px solid #e2e8f0;">';

     // Reglas
        if ($tasaCobranza < 85) {
            $html3 .= '<tr><td style="border-bottom: 1px solid #f1f5f9; font-size: 9pt; color: #dc2626;">La tasa de cobranza esta por debajo del 85%. Se recomienda implementar acciones de cobranza.</td></tr>';
        } else {
            $html3 .= '<tr><td style="border-bottom: 1px solid #f1f5f9; font-size: 9pt; color: #059669;">La tasa de cobranza es excelente, manteniendo buenas finanzas sanas.</td></tr>';
        }

        if ($utilidadNeta > 0) {
            $html3 .= '<tr><td style="border-bottom: 1px solid #f1f5f9; font-size: 9pt; color: #059669;">El periodo muestra utilidades positivas.</td></tr>';
        } else if ($utilidadNeta < 0) {
            $html3 .= '<tr><td style="border-bottom: 1px solid #f1f5f9; font-size: 9pt; color: #dc2626;">El periodo muestra utilidades negativas. Revisa tus gastos.</td></tr>';
        } else {
            $html3 .= '<tr><td style="border-bottom: 1px solid #f1f5f9; font-size: 9pt; color: #d97706;">El periodo no reporta utilidad ni perdida.</td></tr>';
        }

        if ($unidadesMorosas > 0) {
            $html3 .= '<tr><td style="font-size: 9pt; color: #d97706;">' . $unidadesMorosas . ' unidades requieren seguimiento de cobranza.</td></tr>';
        } else {
            $html3 .= '<tr><td style="font-size: 9pt; color: #059669;">Todas las unidades se encuentran al corriente.</td></tr>';
        }
        $html3 .= '</table>';

        $pdf->writeHTML($html3, true, false, true, false, '');

        if ($returnString) {
            return $pdf->Output('Reporte_Financiero_' . $selectedMonth . '.pdf', 'S');
        }
        $pdf->Output('Reporte_Financiero_' . $selectedMonth . '.pdf', 'I');
        exit;
    }
    /**
     * POST admin/finanzas/comprobante/review
     * Aprobar o rechazar un comprobante de pago subido por un residente.
     */
    public function reviewPayment()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid request']);
        }

        $paymentId = (int) $this->request->getPost('payment_id');
        $action = $this->request->getPost('action'); // 'approve' or 'reject'
        $amount = (float) $this->request->getPost('amount');
        $method = $this->request->getPost('payment_method') ?: 'transfer';
        $paymentDate = $this->request->getPost('payment_date') ?: date('Y-m-d');
        $chargeId = (int) $this->request->getPost('charge_id'); // transacción cargo a aplicar
        $adminNotes = $this->request->getPost('admin_notes') ?: '';

        $condoModel = new CondominiumModel();
        $demoCondo = $condoModel->first();
        if (!$demoCondo) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Condominio no encontrado.']);
        }

        $db = \Config\Database::connect();

        // Obtener el payment
        $payment = $db->table('payments')
            ->where('id', $paymentId)
            ->where('condominium_id', $demoCondo['id'])
            ->get()->getRowArray();

        if (!$payment) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Comprobante no encontrado.']);
        }

        if ($payment['status'] !== 'pending') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Este comprobante ya fue procesado.']);
        }

        $unitId = (int) $payment['unit_id'];

        // Buscar residente para notificar
        $resident = $db->table('residents r')
            ->select('r.user_id, u.unit_number')
            ->join('units u', 'u.id = r.unit_id')
            ->where('r.unit_id', $unitId)
            ->where('r.condominium_id', $demoCondo['id'])
            ->get()->getRowArray();

        if ($action === 'approve') {
            // Mapeo método para financial_transactions
            $methodMap = [
                'transfer' => 'Transferencia Bancaria',
                'cash' => 'Efectivo',
                'card' => 'Tarjeta',
                'check' => 'Cheque',
            ];
            $displayMethod = $methodMap[$method] ?? 'Transferencia Bancaria';

            // Crear crédito (pago) en financial_transactions
            $creditData = [
                'condominium_id' => $demoCondo['id'],
                'unit_id' => $unitId,
                'type' => 'credit',
                'amount' => $amount,
                'description' => 'PAGO - COMPROBANTE APROBADO',
                'due_date' => $paymentDate,
                'status' => 'paid',
                'payment_method' => $displayMethod,
                'attachment' => $payment['proof_url'] ?? null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            // Asignar categoría de Cuota de Mantenimiento
            $catRow = $db->table('financial_categories')
                ->where('condominium_id', $demoCondo['id'])
                ->where('name', 'Cuota de Mantenimiento')
                ->get()->getRowArray();
            if ($catRow) {
                $creditData['category_id'] = $catRow['id'];
            }

            $db->table('financial_transactions')->insert($creditData);
            $newTxId = $db->insertID();

            // Si se seleccionó un cargo específico, marcar como pagado
            if ($chargeId > 0) {
                $charge = $db->table('financial_transactions')
                    ->where('id', $chargeId)
                    ->where('unit_id', $unitId)
                    ->get()->getRowArray();

                if ($charge) {
                    $chargeAmount = (float) $charge['amount'];
                    $newStatus = ($amount >= $chargeAmount) ? 'paid' : 'partial';
                    $db->table('financial_transactions')->where('id', $chargeId)->update([
                        'status' => $newStatus,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }

            // Actualizar payment como aprobado
            $db->table('payments')->where('id', $paymentId)->update([
                'status' => 'approved',
                'amount' => $amount,
                'payment_method' => $method,
                'transaction_id' => $newTxId,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            // Recalcular saldos
            $this->recalculateUnitBalances($unitId);

            // Notificar al residente (in-app + push)
            if ($resident && !empty($resident['user_id'])) {
                $approveTitle = 'Pago aprobado';
                $approveBody = 'Tu comprobante de pago por $' . number_format($amount, 2) . ' ha sido aprobado por la administración.';
                $approveData = ['payment_id' => $paymentId, 'amount' => $amount, 'tipo' => 'pago_aprobado'];

                // In-app notification
                NotificationModel::notify(
                    $demoCondo['id'],
                    (int) $resident['user_id'],
                    'payment_approved',
                    $approveTitle,
                    $approveBody,
                    $approveData
                );

            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Comprobante aprobado exitosamente. Se creó el crédito y se notificó al residente.',
            ]);

        } elseif ($action === 'reject') {
            // Rechazar
            $db->table('payments')->where('id', $paymentId)->update([
                'status' => 'rejected',
                'notes' => $adminNotes ?: 'Rechazado por la administración',
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            // Notificar al residente (in-app + push)
            if ($resident && !empty($resident['user_id'])) {
                $rejectTitle = 'Pago rechazado';
                $rejectBody = 'Tu comprobante de pago ha sido rechazado.';
                if ($adminNotes) {
                    $rejectBody .= ' Motivo: ' . $adminNotes;
                }
                $rejectData = ['payment_id' => $paymentId, 'tipo' => 'pago_rechazado'];

                // In-app notification
                NotificationModel::notify(
                    $demoCondo['id'],
                    (int) $resident['user_id'],
                    'payment_rejected',
                    $rejectTitle,
                    $rejectBody,
                    $rejectData
                );

            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Comprobante rechazado. Se notificó al residente.',
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Acción no válida.']);
    }

    /**
     * POST admin/finanzas/comprobante/delete
     * Eliminar un comprobante de pago.
     */
    public function deletePaymentVoucher()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid request']);
        }

        $paymentId = (int) $this->request->getPost('payment_id');

        $condoModel = new CondominiumModel();
        $demoCondo = $condoModel->first();

        $db = \Config\Database::connect();
        $payment = $db->table('payments')
            ->where('id', $paymentId)
            ->where('condominium_id', $demoCondo['id'])
            ->get()->getRowArray();

        if (!$payment) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Comprobante no encontrado.']);
        }

        $db->table('payments')->where('id', $paymentId)->update([
            'deleted_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Comprobante eliminado correctamente.',
        ]);
    }
}

