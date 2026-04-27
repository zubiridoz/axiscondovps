<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Tenant\ParcelModel;

/**
 * ParcelController
 * 
 * Gestión del Cuarto de Paquetería (Recepción de cajas, sobres, Amazon, MercadoLibre).
 */
class ParcelController extends BaseController
{
    private function ensureTenant()
    {
        $demoCondo = (new \App\Models\Tenant\CondominiumModel())->first();
        if ($demoCondo) \App\Services\TenantService::getInstance()->setTenantId((int)$demoCondo['id']);
    }

    /**
     * RENDER HTML MVC - Vista Principal (solo pendientes)
     */
    public function indexView()
    {
        $this->ensureTenant();
        $db = \Config\Database::connect();
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();

        $parcels = $db->table('parcels')
            ->select('parcels.*, units.unit_number, sections.name as section_name')
            ->join('units', 'units.id = parcels.unit_id', 'left')
            ->join('sections', 'sections.id = units.section_id', 'left')
            ->where('parcels.condominium_id', $tenantId)
            ->orderBy('parcels.created_at', 'DESC')
            ->get()
            ->getResultArray();

        // Enrich each parcel with residents
        foreach ($parcels as &$p) {
            $residents = $db->table('residents')
                ->select('users.id as user_id, users.first_name, users.last_name')
                ->join('users', 'users.id = residents.user_id')
                ->where('residents.unit_id', $p['unit_id'])
                ->where('residents.condominium_id', $tenantId)
                ->where('residents.is_active', 1)
                ->get()
                ->getResultArray();

            $p['residents'] = $residents;
            $names = array_map(fn($r) => trim($r['first_name'] . ' ' . $r['last_name']), $residents);
            $p['resident_names'] = implode(', ', $names) ?: 'Sin Asignar';
        }

        return view('admin/parcels', ['parcels' => $parcels]);
    }

    /**
     * GET /admin/paqueteria/detalle/:id  
     * Devuelve JSON con detalle completo de un paquete
     */
    public function detail($id = null)
    {
        if (!$id) return $this->response->setJSON(['status' => 400, 'error' => 'ID no proporcionado']);
        
        $this->ensureTenant();
        $db = \Config\Database::connect();
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();

        $parcel = $db->table('parcels')
            ->select('parcels.*, units.unit_number, sections.name as section_name')
            ->join('units', 'units.id = parcels.unit_id', 'left')
            ->join('sections', 'sections.id = units.section_id', 'left')
            ->where('parcels.id', $id)
            ->where('parcels.condominium_id', $tenantId)
            ->get()
            ->getRowArray();

        if (!$parcel) {
            return $this->response->setJSON(['status' => 404, 'error' => 'Paquete no encontrado']);
        }

        // Residents
        $residents = $db->table('residents')
            ->select('users.id as user_id, users.first_name, users.last_name')
            ->join('users', 'users.id = residents.user_id')
            ->where('residents.unit_id', $parcel['unit_id'])
            ->where('residents.condominium_id', $tenantId)
            ->where('residents.is_active', 1)
            ->get()
            ->getResultArray();
        
        $names = array_map(fn($r) => trim($r['first_name'] . ' ' . $r['last_name']), $residents);
        $parcel['resident_names'] = implode(', ', $names) ?: 'Sin Asignar';
        $parcel['residents'] = $residents;

        // Photo URL
        if (!empty($parcel['photo_url'])) {
            $fileName = basename($parcel['photo_url']);
            $parcel['photo_full_url'] = base_url("api/v1/security/parcel-photo/{$fileName}");
        }

        // Signature URL
        if (!empty($parcel['signature_url'])) {
            $fileName = basename($parcel['signature_url']);
            $parcel['signature_full_url'] = base_url("api/v1/security/parcel-photo/{$fileName}");
        }

        // Condominium name
        $condo = (new \App\Models\Tenant\CondominiumModel())->find($tenantId);
        $parcel['condominium_name'] = $condo['name'] ?? 'Condominio';

        return $this->response->setJSON(['status' => 200, 'data' => $parcel]);
    }

    /**
     * POST /admin/paqueteria/marcar-entregado/:id
     * Marca un paquete como entregado desde el admin panel
     */
    public function markAsDelivered($id = null)
    {
        if (!$id) return $this->response->setJSON(['status' => 400, 'error' => 'ID no proporcionado']);

        $this->ensureTenant();
        $parcelModel = new ParcelModel();
        $parcel = $parcelModel->find($id);

        if (!$parcel) {
            return $this->response->setJSON(['status' => 404, 'error' => 'Paquete no encontrado']);
        }

        $parcelModel->update($id, [
            'status'         => 'delivered_to_resident',
            'delivered_at'   => date('Y-m-d H:i:s'),
            'picked_up_name' => $this->request->getPost('picked_up_name') ?: 'Entrega desde administración',
        ]);

        return $this->response->setJSON(['status' => 200, 'message' => 'Paquete marcado como entregado']);
    }

    /**
     * GET /admin/paqueteria/comprobante/:id
     * Genera un PDF profesional del comprobante de entrega
     */
    public function downloadReceipt($id = null)
    {
        if (!$id) return $this->response->setStatusCode(400)->setBody('ID no proporcionado');

        $this->ensureTenant();
        $db = \Config\Database::connect();
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();

        $parcel = $db->table('parcels')
            ->select('parcels.*, units.unit_number, sections.name as section_name')
            ->join('units', 'units.id = parcels.unit_id', 'left')
            ->join('sections', 'sections.id = units.section_id', 'left')
            ->where('parcels.id', $id)
            ->where('parcels.condominium_id', $tenantId)
            ->get()
            ->getRowArray();

        if (!$parcel) {
            return $this->response->setStatusCode(404)->setBody('Paquete no encontrado');
        }

        // Residents
        $residents = $db->table('residents')
            ->select('users.first_name, users.last_name')
            ->join('users', 'users.id = residents.user_id')
            ->where('residents.unit_id', $parcel['unit_id'])
            ->where('residents.condominium_id', $tenantId)
            ->where('residents.is_active', 1)
            ->get()
            ->getResultArray();

        $residentNames = array_map(fn($r) => trim($r['first_name'] . ' ' . $r['last_name']), $residents);
        $parcel['resident_names'] = implode(', ', $residentNames) ?: 'Sin Asignar';

        // Condominium
        $condo = (new \App\Models\Tenant\CondominiumModel())->find($tenantId);
        $parcel['condominium_name'] = $condo['name'] ?? 'Condominio';

        // Generate tracking code
        $trackingId = strtoupper(substr(md5($parcel['id'] . ($parcel['created_at'] ?? '')), 0, 12));
        $parcel['tracking_code'] = 'PKG-' . $trackingId;

        // Build HTML for PDF
        $html = $this->buildReceiptHtml($parcel);

        // Use Dompdf if available, else HTML with print button
        if (class_exists('\\Dompdf\\Dompdf')) {
            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('letter', 'portrait');
            $dompdf->render();
            
            return $this->response
                ->setHeader('Content-Type', 'application/pdf')
                ->setHeader('Content-Disposition', 'inline; filename="comprobante-paquete-' . $parcel['id'] . '.pdf"')
                ->setBody($dompdf->output());
        }

        // Fallback: direct HTML output (bypass CI debug toolbar)
        header('Content-Type: text/html; charset=utf-8');
        echo $html;
        exit;
    }

    /**
     * Builds premium HTML for the receipt PDF
     */
    private function buildReceiptHtml(array $p): string
    {
        $statusLabel = 'En caseta';
        $statusColor = '#d97706';
        if (in_array($p['status'] ?? '', ['delivered', 'delivered_to_resident'])) {
            $statusLabel = 'Entregado';
            $statusColor = '#059669';
        }

        $createdFormatted = '';
        if (!empty($p['created_at'])) {
            $dt = new \DateTime($p['created_at']);
            $months = ['','enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
            $days = ['domingo','lunes','martes','miércoles','jueves','viernes','sábado'];
            $createdFormatted = $days[(int)$dt->format('w')] . ', ' . $dt->format('j') . ' de ' . $months[(int)$dt->format('n')] . ' de ' . $dt->format('Y') . ', ' . $dt->format('g:i A');
        }

        $deliveredFormatted = '';
        if (!empty($p['delivered_at'])) {
            $dt = new \DateTime($p['delivered_at']);
            $months = ['','enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
            $days = ['domingo','lunes','martes','miércoles','jueves','viernes','sábado'];
            $deliveredFormatted = $days[(int)$dt->format('w')] . ', ' . $dt->format('j') . ' de ' . $months[(int)$dt->format('n')] . ' de ' . $dt->format('Y') . ', ' . $dt->format('g:i A');
        }

        $generatedAt = date('d/m/Y, H:i:s');
        $condoName = htmlspecialchars($p['condominium_name'] ?? 'Condominio');
        $trackingCode = htmlspecialchars($p['tracking_code'] ?? 'N/A');
        $unitNumber = htmlspecialchars($p['unit_number'] ?? 'N/A');
        $sectionName = htmlspecialchars($p['section_name'] ?? '');
        $residentNames = htmlspecialchars($p['resident_names'] ?? 'N/A');
        $courier = htmlspecialchars($p['courier'] ?? ($p['courier_company'] ?? 'N/A'));
        $parcelType = htmlspecialchars($p['parcel_type'] ?? 'Paquete');
        $quantity = (int)($p['quantity'] ?? 1);
        $pickedUpName = htmlspecialchars($p['picked_up_name'] ?? '');

        $html = <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
    @page { margin: 30px 40px; }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Segoe UI', Arial, Helvetica, sans-serif; color: #1e293b; font-size: 13px; line-height: 1.5; }
    
    .header { background: linear-gradient(135deg, #1e3a5f 0%, #2d5a87 100%); color: white; padding: 28px 32px; border-radius: 10px; margin-bottom: 24px; }
    .header h1 { font-size: 22px; font-weight: 700; margin-bottom: 4px; }
    .header .subtitle { font-size: 13px; color: rgba(255,255,255,0.8); }
    .header .tracking { font-size: 14px; color: rgba(255,255,255,0.95); margin-top: 8px; font-family: 'Consolas', monospace; background: rgba(255,255,255,0.15); padding: 6px 14px; border-radius: 6px; display: inline-block; }
    
    .section { margin-bottom: 20px; }
    .section-title { font-size: 14px; font-weight: 700; color: #1e3a5f; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 2px solid #e2e8f0; display: flex; align-items: center; gap: 8px; }
    .section-title .icon { width: 24px; height: 24px; background: #e0f2fe; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 12px; color: #1e3a5f; }
    
    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .info-item { padding: 10px 14px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0; }
    .info-item .label { font-size: 10px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 3px; }
    .info-item .value { font-size: 14px; font-weight: 600; color: #0f172a; }
    .info-item.full { grid-column: 1 / -1; }
    
    .status-badge { display: inline-block; padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; color: white; }
    
    .timeline { position: relative; padding-left: 24px; }
    .timeline::before { content: ''; position: absolute; left: 7px; top: 8px; bottom: 8px; width: 2px; background: #e2e8f0; }
    .timeline-item { position: relative; margin-bottom: 16px; }
    .timeline-item::before { content: ''; position: absolute; left: -20px; top: 5px; width: 10px; height: 10px; border-radius: 50%; border: 2px solid; }
    .timeline-item.received::before { background: #dbeafe; border-color: #3b82f6; }
    .timeline-item.delivered::before { background: #dcfce7; border-color: #22c55e; }
    .timeline-item .tl-date { font-size: 11px; color: #64748b; font-weight: 500; }
    .timeline-item .tl-event { font-size: 13px; font-weight: 600; color: #1e293b; }
    
    .divider { border: none; border-top: 1px solid #e2e8f0; margin: 20px 0; }
    
    .footer { text-align: center; margin-top: 32px; padding-top: 16px; border-top: 2px solid #e2e8f0; }
    .footer p { font-size: 11px; color: #94a3b8; }
    .footer .brand { font-size: 12px; font-weight: 700; color: #1e3a5f; margin-bottom: 4px; }
</style>
</head>
<body>

<div class="header">
    <h1>Comprobante de Entrega de Paquete</h1>
    <div class="subtitle">{$condoName}</div>
    <div class="tracking">{$trackingCode}</div>
</div>

<div class="section">
    <div class="section-title">
        <span class="icon">🏠</span>
        Información del Destinatario
    </div>
    <div class="info-grid">
        <div class="info-item">
            <div class="label">Unidad</div>
            <div class="value">{$sectionName} {$unitNumber}</div>
        </div>
        <div class="info-item">
            <div class="label">Residentes</div>
            <div class="value">{$residentNames}</div>
        </div>
    </div>
</div>

<div class="section">
    <div class="section-title">
        <span class="icon">📦</span>
        Información del Paquete
    </div>
    <div class="info-grid">
        <div class="info-item">
            <div class="label">Estado</div>
            <div class="value"><span class="status-badge" style="background:{$statusColor};">{$statusLabel}</span></div>
        </div>
        <div class="info-item">
            <div class="label">Transportista</div>
            <div class="value">{$courier}</div>
        </div>
        <div class="info-item">
            <div class="label">Tipo</div>
            <div class="value">{$parcelType}</div>
        </div>
        <div class="info-item">
            <div class="label">Cantidad</div>
            <div class="value">{$quantity}</div>
        </div>
    </div>
</div>

<div class="section">
    <div class="section-title">
        <span class="icon">📋</span>
        Línea de Tiempo
    </div>
    <div class="timeline">
        <div class="timeline-item received">
            <div class="tl-date">{$createdFormatted}</div>
            <div class="tl-event">Paquete recibido en caseta</div>
        </div>
HTML;

        if ($deliveredFormatted) {
            $html .= <<<HTML
        <div class="timeline-item delivered">
            <div class="tl-date">{$deliveredFormatted}</div>
            <div class="tl-event">Entregado a {$pickedUpName}</div>
        </div>
HTML;
        }

        $html .= '    </div>' . "\n";
        $html .= '</div>' . "\n\n";

        $html .= '<div class="footer">' . "\n";
        $html .= '    <div class="brand">' . $condoName . ' &mdash; AxisCondo</div>' . "\n";
        $html .= '    <p>Este es un comprobante oficial de entrega de paquete.</p>' . "\n";
        $html .= '    <p>Generado el ' . $generatedAt . '</p>' . "\n";
        $html .= '</div>' . "\n\n";

        $html .= '<div class="print-actions" style="text-align:center; margin-top:24px; padding:16px;">' . "\n";
        $html .= '    <button onclick="window.print()" style="background:linear-gradient(135deg, #1e3a5f 0%, #2d5a87 100%); color:#fff; border:none; padding:12px 32px; border-radius:8px; font-size:14px; font-weight:600; cursor:pointer; margin-right:8px;">' . "\n";
        $html .= '        &#128424; Imprimir / Guardar como PDF' . "\n";
        $html .= '    </button>' . "\n";
        $html .= '    <button onclick="window.close()" style="background:#f1f5f9; color:#334155; border:1px solid #d0d8e2; padding:12px 24px; border-radius:8px; font-size:14px; font-weight:500; cursor:pointer;">' . "\n";
        $html .= '        Cerrar' . "\n";
        $html .= '    </button>' . "\n";
        $html .= '</div>' . "\n\n";

        $html .= '<style>' . "\n";
        $html .= '    @media print {' . "\n";
        $html .= '        .print-actions { display: none !important; }' . "\n";
        $html .= '        body { margin: 0; }' . "\n";
        $html .= '        .header { -webkit-print-color-adjust: exact; print-color-adjust: exact; }' . "\n";
        $html .= '        .status-badge { -webkit-print-color-adjust: exact; print-color-adjust: exact; }' . "\n";
        $html .= '        .info-item { -webkit-print-color-adjust: exact; print-color-adjust: exact; }' . "\n";
        $html .= '        .timeline-item::before { -webkit-print-color-adjust: exact; print-color-adjust: exact; }' . "\n";
        $html .= '    }' . "\n";
        $html .= '</style>' . "\n\n";

        $html .= '</body>' . "\n";
        $html .= '</html>';

        return $html;
    }
}
