<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Tenant\BookingModel;
use App\Models\Tenant\AmenityModel;

/**
 * BookingController
 * 
 * Gestión de las reservas de amenidades del condominio.
 */
class BookingController extends BaseController
{
    /**
     * Helper: Inicializa el contexto del tenant (modo demo local)
     */
    private function initTenant()
    {
        $demoCondo = (new \App\Models\Tenant\CondominiumModel())->first();
        if ($demoCondo) \App\Services\TenantService::getInstance()->setTenantId((int)$demoCondo['id']);
        return $demoCondo;
    }

    /**
     * RENDER HTML MVC - Vista de Reservas
     */
    public function bookingsView()
    {
        $condo = $this->initTenant();
        $condoId = $condo ? $condo['id'] : 1;

        $bookingModel = new BookingModel();
        $amenityModel = new AmenityModel();
        $db = \Config\Database::connect();

        // Reservas enriquecidas con amenidad, usuario, unidad, sección y rol
        $bookings = $db->table('bookings b')
            ->select('b.*, 
                      a.name as amenity_name, a.image as amenity_image, a.hash_id as amenity_hash,
                      u.first_name, u.last_name, u.avatar,
                      un.unit_number, 
                      s.name as section_name,
                      r.name as role_name')
            ->join('amenities a', 'a.id = b.amenity_id', 'left')
            ->join('users u', 'u.id = b.user_id', 'left')
            ->join('units un', 'un.id = b.unit_id', 'left')
            ->join('sections s', 's.id = un.section_id', 'left')
            ->join('user_condominium_roles ucr', 'ucr.user_id = b.user_id AND ucr.condominium_id = b.condominium_id', 'left')
            ->join('roles r', 'r.id = ucr.role_id', 'left')
            ->where('b.condominium_id', $condoId)
            ->where('b.deleted_at IS NULL')
            ->orderBy('b.created_at', 'DESC')
            ->get()
            ->getResultArray();

        // Amenidades reservables para el selector del modal
        $amenities = $amenityModel->where('is_reservable', 1)->where('is_active', 1)->findAll();

        // KPIs - Reseteamos el query builder entre cada conteo
        $pending  = $bookingModel->where('condominium_id', $condoId)->where('status', 'pending')->countAllResults();
        $approved = $bookingModel->where('condominium_id', $condoId)->where('status', 'approved')->countAllResults();
        $rejected = $bookingModel->where('condominium_id', $condoId)->where('status', 'rejected')->countAllResults();

        $todayStart = date('Y-m-d 00:00:00');
        $todayEnd   = date('Y-m-d 23:59:59');

        // Reservas creadas hoy
        $todayBookings = $bookingModel->where('condominium_id', $condoId)
            ->where('created_at >=', $todayStart)
            ->where('created_at <=', $todayEnd)
            ->countAllResults();

        return view('admin/bookings', [
            'bookings'      => BookingModel::enrichWithHash($bookings),
            'amenities'     => $amenities,
            'pending'       => $pending,
            'approved'      => $approved,
            'rejected'      => $rejected,
            'todayBookings' => $todayBookings,
        ]);
    }

    /**
     * API: Obtener usuarios para el selector del modal (admins + residentes)
     */
    public function getUsersForSelector()
    {
        $condo = $this->initTenant();
        $condoId = $condo ? $condo['id'] : 1;
        $q = trim((string) $this->request->getGet('q'));
        $db = \Config\Database::connect();

        // Admins (role_id = 2)
        $adminQuery = $db->table('user_condominium_roles ucr')
            ->select('u.id as user_id, u.first_name, u.last_name, u.avatar, "ADMIN" as role_label, NULL as unit_number, NULL as section_name, NULL as unit_id')
            ->join('users u', 'u.id = ucr.user_id')
            ->where('ucr.condominium_id', $condoId)
            ->where('ucr.role_id', 2);

        if ($q !== '') {
            $adminQuery->groupStart()
                ->like('u.first_name', $q)
                ->orLike('u.last_name', $q)
                ->orLike('u.email', $q)
                ->groupEnd();
        }
        $admins = $adminQuery->limit(8)->get()->getResultArray();

        // Residentes (role_id = 4) con unidad y sección
        $residentQuery = $db->table('residents res')
            ->select('u.id as user_id, u.first_name, u.last_name, u.avatar, "RESIDENT" as role_label, un.unit_number, s.name as section_name, res.unit_id')
            ->join('users u', 'u.id = res.user_id')
            ->join('units un', 'un.id = res.unit_id', 'left')
            ->join('sections s', 's.id = un.section_id', 'left')
            ->where('res.condominium_id', $condoId)
            ->where('res.is_active', 1);

        if ($q !== '') {
            $residentQuery->groupStart()
                ->like('u.first_name', $q)
                ->orLike('u.last_name', $q)
                ->orLike('un.unit_number', $q)
                ->groupEnd();
        }
        $residents = $residentQuery->limit(8)->get()->getResultArray();

        return $this->response->setJSON([
            'status' => 200,
            'admins' => $admins,
            'residents' => $residents,
        ]);
    }

    /**
     * API: Disponibilidad de una amenidad (schedule + bookings existentes)
     */
    public function getAmenityAvailability($amenityId = null)
    {
        $this->initTenant();

        if (!$amenityId) {
            return $this->response->setJSON(['status' => 400, 'error' => 'ID de amenidad requerido']);
        }

        $amenityModel = new AmenityModel();
        $amenity = $amenityModel->find($amenityId);
        if (!$amenity) {
            return $this->response->setJSON(['status' => 404, 'error' => 'Amenidad no encontrada']);
        }

        $month = (int) ($this->request->getGet('month') ?: date('n'));
        $year  = (int) ($this->request->getGet('year') ?: date('Y'));

        // Schedule semanal
        $scheduleModel = new \App\Models\Tenant\AmenityScheduleModel();
        $schedules = $scheduleModel->where('amenity_id', $amenityId)->findAll();

        // Mapear schedule por día de la semana
        $scheduleMap = [];
        foreach ($schedules as $s) {
            $scheduleMap[(int)$s['day_of_week']] = $s;
        }

        // Reservas existentes del mes
        $bookingModel = new BookingModel();
        $startOfMonth = sprintf('%04d-%02d-01 00:00:00', $year, $month);
        $endOfMonth   = date('Y-m-t 23:59:59', strtotime($startOfMonth));

        $existingBookings = $bookingModel
            ->where('amenity_id', $amenityId)
            ->where('status !=', 'cancelled')
            ->where('status !=', 'rejected')
            ->where('start_time >=', $startOfMonth)
            ->where('start_time <=', $endOfMonth)
            ->findAll();

        // Generar slots disponibles por fecha
        $interval = $amenity['reservation_interval'] ?? '1'; // "1"-"6" horas o "full_day"
        
        return $this->response->setJSON([
            'status'   => 200,
            'amenity'  => [
                'id'                       => $amenity['id'],
                'name'                     => $amenity['name'],
                'image'                    => $amenity['image'],
                'reservation_interval'     => $interval,
                'max_active_reservations'  => $amenity['max_active_reservations'] ?? 'unlimited',
                'has_cost'                 => $amenity['has_cost'] ?? 0,
                'price'                    => $amenity['price'] ?? 0,
                'description'              => $amenity['description'] ?? '',
                'available_from'           => $amenity['available_from'] ?? null,
                'blocked_dates'            => $amenity['blocked_dates'] ?? null,
            ],
            'schedule' => $scheduleMap,
            'bookings' => BookingModel::enrichWithHash($existingBookings),
            'month'    => $month,
            'year'     => $year,
        ]);
    }

    /**
     * Crea una nueva reserva (Admin = auto-approved, Residente = pending)
     */
    public function create()
    {
        $condo = $this->initTenant();
        $condoId = $condo ? $condo['id'] : 1;

        // Acepta tanto JSON como form POST
        $json = $this->request->getJSON(true);
        $amenityId = $json['amenity_id'] ?? $this->request->getPost('amenity_id');
        $userId    = $json['user_id']    ?? $this->request->getPost('user_id');
        $unitId    = $json['unit_id']    ?? $this->request->getPost('unit_id');
        $startTime = $json['start_time'] ?? $this->request->getPost('start_time');
        $endTime   = $json['end_time']   ?? $this->request->getPost('end_time');

        if (!$amenityId || !$userId || !$startTime || !$endTime) {
            return $this->response->setJSON(['status' => 400, 'error' => 'Faltan campos obligatorios']);
        }

        $amenityModel = new AmenityModel();
        $amenity = $amenityModel->find($amenityId);
        if (!$amenity) {
             return $this->response->setJSON(['status' => 404, 'error' => 'Amenidad no encontrada']);
        }

        $bookingModel = new BookingModel();

        // Validar Límite Máximo de Reservas Activas
        $maxReservations = $amenity['max_active_reservations'] ?? 'unlimited';
        if ($maxReservations !== 'unlimited' && is_numeric($maxReservations)) {
            $maxAllowed = (int)$maxReservations;
            $activeCount = $bookingModel
                ->where('amenity_id', $amenityId)
                ->where('user_id', $userId)
                ->whereIn('status', ['pending', 'approved'])
                ->where('end_time >', date('Y-m-d H:i:s'))
                ->countAllResults();

            if ($activeCount >= $maxAllowed) {
                return $this->response->setJSON(['status' => 403, 'error' => "Este usuario ha alcanzado el límite máximo de ({$maxAllowed}) reservas activas para esta amenidad."]);
            }
        }

        // Admin creates = auto-approved
        $data = [
            'condominium_id' => $condoId,
            'amenity_id'     => $amenityId,
            'unit_id'        => $unitId ?: null,
            'user_id'        => $userId,
            'start_time'     => $startTime,
            'end_time'       => $endTime,
            'status'         => 'approved',
        ];

        $bookingModel = new BookingModel();
        $bookingId = $bookingModel->insert($data);

        if (!$bookingId) {
            return $this->response->setJSON(['status' => 500, 'error' => 'Error al crear la reserva']);
        }

        return $this->response->setJSON(['status' => 201, 'message' => 'Reserva creada exitosamente', 'id' => $bookingId]);
    }

    /**
     * Aprueba una reserva
     */
    public function approve($id = null)
    {
        $this->initTenant();

        if (!$id) return $this->response->setJSON(['status' => 400, 'error' => 'ID no proporcionado']);

        $bookingModel = new BookingModel();
        if (!$bookingModel->find($id)) {
            return $this->response->setJSON(['status' => 404, 'error' => 'Reserva no encontrada']);
        }

        $bookingModel->update($id, ['status' => 'approved']);

        try {
            $booking = $bookingModel->find($id);
            $db = \Config\Database::connect();
            $amenity = $db->table('amenities')->select('name')->where('id', $booking['amenity_id'])->get()->getRowArray();
            $amenityName = $amenity ? $amenity['name'] : 'Amenidad';
            
            // Format start time correctly: "d/m/Y a las H:i"
            $fmtDate = date('d/m/Y', strtotime($booking['start_time']));
            $fmtTime = date('H:i', strtotime($booking['start_time']));
            
            $body = "Tu solicitud de reserva para {$amenityName} el {$fmtDate} a las {$fmtTime} ha sido aprobada.";
            \App\Models\Tenant\NotificationModel::notify($booking['condominium_id'], $booking['user_id'], 'amenidad', 'Reserva Aprobada', $body);
        } catch (\Exception $e) {}

        return $this->response->setJSON(['status' => 200, 'message' => 'Reserva aprobada exitosamente']);
    }

    /**
     * Rechaza una reserva
     */
    public function reject($id = null)
    {
        $this->initTenant();

        if (!$id) return $this->response->setJSON(['status' => 400, 'error' => 'ID no proporcionado']);

        $bookingModel = new BookingModel();
        if (!$bookingModel->find($id)) {
            return $this->response->setJSON(['status' => 404, 'error' => 'Reserva no encontrada']);
        }

        $bookingModel->update($id, ['status' => 'rejected']);

        try {
            $booking = $bookingModel->find($id);
            $db = \Config\Database::connect();
            $amenity = $db->table('amenities')->select('name')->where('id', $booking['amenity_id'])->get()->getRowArray();
            $amenityName = $amenity ? $amenity['name'] : 'Amenidad';
            
            $fmtDate = date('d/m/Y', strtotime($booking['start_time']));
            $fmtTime = date('H:i', strtotime($booking['start_time']));
            
            $body = "Tu solicitud de reserva para {$amenityName} el {$fmtDate} a las {$fmtTime} no pudo ser aprobada.";
            \App\Models\Tenant\NotificationModel::notify($booking['condominium_id'], $booking['user_id'], 'amenidad', 'Reserva Rechazada', $body);
        } catch (\Exception $e) {}

        return $this->response->setJSON(['status' => 200, 'message' => 'Reserva rechazada']);
    }

    /**
     * Elimina una reserva (soft delete)
     */
    public function delete($id = null)
    {
        $this->initTenant();

        if (!$id) return $this->response->setJSON(['status' => 400, 'error' => 'ID no proporcionado']);

        $bookingModel = new BookingModel();
        $booking = $bookingModel->find($id);
        if (!$booking) {
            return $this->response->setJSON(['status' => 404, 'error' => 'Reserva no encontrada']);
        }

        if ($bookingModel->delete($id)) {
            return $this->response->setJSON(['status' => 200, 'message' => 'Reserva eliminada exitosamente']);
        }

        return $this->response->setJSON(['status' => 500, 'error' => 'No se pudo eliminar la reserva']);
    }

    /**
     * Cancela una reserva
     */
    public function cancel($id = null)
    {
        $this->initTenant();

        if (!$id) return $this->response->setJSON(['status' => 400, 'error' => 'ID no proporcionado']);

        $bookingModel = new BookingModel();
        if (!$bookingModel->find($id)) {
            return $this->response->setJSON(['status' => 404, 'error' => 'Reserva no encontrada']);
        }

        $bookingModel->update($id, ['status' => 'cancelled']);
        return $this->response->setJSON(['status' => 200, 'message' => 'Reserva cancelada']);
    }

    /**
     * RENDER HTML MVC - Vista de Estadísticas
     */
    public function statistics()
    {
        $condo = $this->initTenant();
        $tenantId = $condo ? $condo['id'] : \App\Services\TenantService::getInstance()->getTenantId();

        $bookingModel = new BookingModel();
        $amenityModel = new AmenityModel();

        $period = $this->request->getGet('period') ?: 'month';
        
        $startDate = date('Y-m-01 00:00:00');
        $endDate = date('Y-m-t 23:59:59');

        if ($period === 'week') {
            $startDate = date('Y-m-d 00:00:00', strtotime('monday this week'));
            $endDate = date('Y-m-d 23:59:59', strtotime('sunday this week'));
        } elseif ($period === 'quarter') {
            $current_quarter = ceil(date('n') / 3);
            $startDate = date('Y-m-d 00:00:00', strtotime(date('Y') . '-' . (($current_quarter * 3) - 2) . '-1'));
            $endDate = date('Y-m-t 23:59:59', strtotime(date('Y') . '-' . ($current_quarter * 3) . '-1'));
        } elseif ($period === 'year') {
            $startDate = date('Y-01-01 00:00:00');
            $endDate = date('Y-12-31 23:59:59');
        }

        $amenities = $amenityModel->findAll();
        $db = \Config\Database::connect();

        $totalBookings    = $db->table('bookings')->where('condominium_id', $tenantId)->where('start_time >=', $startDate)->where('start_time <=', $endDate)->countAllResults();
        $approvedBookings = $db->table('bookings')->where('condominium_id', $tenantId)->where('status', 'approved')->where('start_time >=', $startDate)->where('start_time <=', $endDate)->countAllResults();
        $pendingBookings  = $db->table('bookings')->where('condominium_id', $tenantId)->where('status', 'pending')->where('start_time >=', $startDate)->where('start_time <=', $endDate)->countAllResults();
        $rejectedBookings = $db->table('bookings')->where('condominium_id', $tenantId)->where('status', 'rejected')->where('start_time >=', $startDate)->where('start_time <=', $endDate)->countAllResults();

        $approvalRate = $totalBookings > 0 ? round(($approvedBookings / $totalBookings) * 100) : 0;
        $rejectionRate = $totalBookings > 0 ? round(($rejectedBookings / $totalBookings) * 100) : 0;

        $mostPopularResult = $db->table('bookings')
            ->select('amenities.name, COUNT(*) as total')
            ->join('amenities', 'amenities.id = bookings.amenity_id', 'left')
            ->where('bookings.condominium_id', $tenantId)
            ->where('bookings.start_time >=', $startDate)
            ->where('bookings.start_time <=', $endDate)
            ->groupBy('bookings.amenity_id')
            ->orderBy('total', 'DESC')
            ->get()->getRowArray();
        $mostPopular = $mostPopularResult['name'] ?? '-';

        $revenueResult = $db->table('bookings')
            ->select('COALESCE(SUM(amenities.price), 0) as total_revenue')
            ->join('amenities', 'amenities.id = bookings.amenity_id', 'left')
            ->where('bookings.condominium_id', $tenantId)
            ->where('bookings.status', 'approved')
            ->where('bookings.start_time >=', $startDate)
            ->where('bookings.start_time <=', $endDate)
            ->get()->getRowArray();
        $totalRevenue = $revenueResult['total_revenue'] ?? 0;

        $daysInPeriod = round((strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24));
        if ($daysInPeriod == 0) $daysInPeriod = 1;
        $totalSlots = count($amenities) * $daysInPeriod;
        $utilizationRate = $totalSlots > 0 ? round(($totalBookings / $totalSlots) * 100) : 0;

        $trendData = $db->table('bookings')
            ->select("DATE(start_time) as date, status, COUNT(*) as total")
            ->where('condominium_id', $tenantId)
            ->where('start_time >=', $startDate)
            ->where('start_time <=', $endDate)
            ->groupBy('DATE(start_time), status')
            ->orderBy('date', 'ASC')
            ->get()->getResultArray();

        $byAmenity = $db->table('bookings')
            ->select('amenities.name, bookings.status, COUNT(*) as total')
            ->join('amenities', 'amenities.id = bookings.amenity_id', 'left')
            ->where('bookings.condominium_id', $tenantId)
            ->where('bookings.start_time >=', $startDate)
            ->where('bookings.start_time <=', $endDate)
            ->groupBy('bookings.amenity_id, bookings.status')
            ->get()->getResultArray();

        $performanceData = [];
        foreach ($amenities as $a) {
            $aId = $a['id'];
            $aTotal     = $db->table('bookings')->where('condominium_id', $tenantId)->where('amenity_id', $aId)->where('start_time >=', $startDate)->where('start_time <=', $endDate)->countAllResults();
            $aApproved  = $db->table('bookings')->where('condominium_id', $tenantId)->where('amenity_id', $aId)->where('status', 'approved')->where('start_time >=', $startDate)->where('start_time <=', $endDate)->countAllResults();
            $aPending   = $db->table('bookings')->where('condominium_id', $tenantId)->where('amenity_id', $aId)->where('status', 'pending')->where('start_time >=', $startDate)->where('start_time <=', $endDate)->countAllResults();
            $aRejected  = $db->table('bookings')->where('condominium_id', $tenantId)->where('amenity_id', $aId)->where('status', 'rejected')->where('start_time >=', $startDate)->where('start_time <=', $endDate)->countAllResults();
            $aRate      = $aTotal > 0 ? round(($aApproved / $aTotal) * 100) : 0;
            
            $aRevResult = $db->table('bookings')
                ->select('COALESCE(SUM(amenities.price), 0) as rev')
                ->join('amenities', 'amenities.id = bookings.amenity_id', 'left')
                ->where('bookings.condominium_id', $tenantId)
                ->where('bookings.amenity_id', $aId)
                ->where('bookings.status', 'approved')
                ->where('bookings.start_time >=', $startDate)
                ->where('bookings.start_time <=', $endDate)
                ->get()->getRowArray();

            $aApprovalTimeData = $db->table('bookings')->select("created_at, updated_at")->where('condominium_id', $tenantId)->where('amenity_id', $aId)->where('status', 'approved')->where('start_time >=', $startDate)->where('start_time <=', $endDate)->get()->getResultArray();
            $aAvgM = 0; $aCount = 0;
            foreach ($aApprovalTimeData as $b) {
                if (!empty($b['created_at']) && !empty($b['updated_at'])) {
                    $diff = strtotime($b['updated_at']) - strtotime($b['created_at']);
                    $aAvgM += max(0, $diff / 60);
                    $aCount++;
                }
            }
            $avgM = $aCount > 0 ? round($aAvgM / $aCount) : 0;
            $aAvgTimeD = $avgM >= 60 ? floor($avgM/60) . 'h ' . ($avgM%60) . 'm' : $avgM . 'm';
            if ($aCount == 0) $aAvgTimeD = '-';

            $performanceData[] = [
                'name'          => $a['name'],
                'total'         => $aTotal,
                'approved'      => $aApproved,
                'pending'       => $aPending,
                'rejected'      => $aRejected,
                'approval_rate' => $aRate,
                'revenue'       => $aRevResult['rev'] ?? 0,
                'avg_time'      => $aAvgTimeD
            ];
        }

        $heatmapRaw = $db->table('bookings')
            ->select("DAYOFWEEK(start_time) as dow, HOUR(start_time) as hr, COUNT(*) as total")
            ->where('condominium_id', $tenantId)
            ->where('start_time >=', $startDate)
            ->where('start_time <=', $endDate)
            ->groupBy('dow, hr')
            ->get()->getResultArray();
        
        $heatmap = [];
        foreach ($heatmapRaw as $h) {
            $heatmap[$h['dow']][$h['hr']] = (int)$h['total'];
        }

        $approvalTimeData = $db->table('bookings')
            ->select("created_at, updated_at")
            ->where('condominium_id', $tenantId)
            ->where('status', 'approved')
            ->where('start_time >=', $startDate)
            ->where('start_time <=', $endDate)
            ->get()->getResultArray();

        $totalMinutes = 0;
        $timeCount = 0;
        $timeDistribution = ['<1h' => 0, '1-4h' => 0, '4-12h' => 0, '12-24h' => 0, '1-2d' => 0, '>2d' => 0];
        foreach ($approvalTimeData as $b) {
            if (!empty($b['created_at']) && !empty($b['updated_at'])) {
                $diff = strtotime($b['updated_at']) - strtotime($b['created_at']);
                $minutes = max(0, $diff / 60);
                $totalMinutes += $minutes;
                $timeCount++;

                $hours = $diff / 3600;
                if ($hours < 1) $timeDistribution['<1h']++;
                elseif ($hours < 4) $timeDistribution['1-4h']++;
                elseif ($hours < 12) $timeDistribution['4-12h']++;
                elseif ($hours < 24) $timeDistribution['12-24h']++;
                elseif ($hours < 48) $timeDistribution['1-2d']++;
                else $timeDistribution['>2d']++;
            }
        }
        $avgAllMinutes = $timeCount > 0 ? round($totalMinutes / $timeCount) : 0;
        $avgTimeDisplay = $avgAllMinutes >= 60 ? floor($avgAllMinutes/60) . 'h ' . ($avgAllMinutes%60) . 'm' : $avgAllMinutes . 'm';
        if ($timeCount == 0) $avgTimeDisplay = '0m';

        return view('admin/amenities_statistics', [
            'period'           => $period,
            'totalBookings'    => $totalBookings,
            'approvalRate'     => $approvalRate,
            'rejectionRate'    => $rejectionRate,
            'pendingBookings'  => $pendingBookings,
            'mostPopular'      => $mostPopular,
            'totalRevenue'     => $totalRevenue,
            'utilizationRate'  => $utilizationRate,
            'trendData'        => $trendData,
            'byAmenity'        => $byAmenity,
            'performanceData'  => $performanceData,
            'heatmap'          => $heatmap,
            'timeDistribution' => $timeDistribution,
            'avgTimeDisplay'   => $avgTimeDisplay,
            'amenities'        => $amenities,
        ]);
    }
}
