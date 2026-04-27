<?php

namespace App\Controllers\Api\V1;

use CodeIgniter\RESTful\ResourceController;
use App\Models\Tenant\BookingModel;
use App\Models\Tenant\ResidentModel;
use App\Models\Tenant\AmenityModel;

/**
 * BookingController (API V1)
 * Gestión transaccional de reservas de amenidades en Flutter.
 */
use App\Models\Tenant\NotificationModel;
use App\Services\TenantService;
class BookingController extends ResourceController
{
    protected function respondSuccess($data = [])
    {
        return $this->respond(['status' => 'success', 'data' => $data]);
    }

    protected function respondError($message, $status = 400)
    {
        return $this->response->setJSON(['status' => 'error', 'message' => $message])->setStatusCode($status);
    }

    /**
     * GET /api/v1/bookings
     * Mis reservas (historial del usuario)
     */
    public function index()
    {
        $userId = $this->request->userId;
        
        // Resolver la unidad ACTUAL del residente para aislar datos
        $residentModel = new ResidentModel();
        $resident = $residentModel->where('user_id', $userId)->first();
        $currentUnitId = $resident['unit_id'] ?? null;

        $bookingModel = new BookingModel();
        
        $builder = $bookingModel
            ->select('bookings.*, amenities.name as amenity_name, amenities.image as amenity_image')
            ->join('amenities', 'amenities.id = bookings.amenity_id', 'left')
            ->where('bookings.user_id', $userId);

        // Filtrar por unidad actual — solo muestra reservas de la unidad vigente
        if ($currentUnitId) {
            $builder->where('bookings.unit_id', $currentUnitId);
        }

        $bookings = $builder->orderBy('bookings.start_time', 'DESC')
                            ->findAll();

        return $this->respondSuccess(['reservations' => BookingModel::enrichWithHash($bookings)]);
    }

    /**
     * GET /api/v1/bookings/active
     * Mis reservas activas/pendientes
     */
    public function active()
    {
        $userId = $this->request->userId;
        
        // Resolver la unidad ACTUAL del residente para aislar datos
        $residentModel = new ResidentModel();
        $resident = $residentModel->where('user_id', $userId)->first();
        $currentUnitId = $resident['unit_id'] ?? null;

        $bookingModel = new BookingModel();
        
        $builder = $bookingModel
            ->select('bookings.*, amenities.name as amenity_name, amenities.image as amenity_image')
            ->join('amenities', 'amenities.id = bookings.amenity_id', 'left')
            ->where('bookings.user_id', $userId);

        // Filtrar por unidad actual — solo muestra reservas de la unidad vigente
        if ($currentUnitId) {
            $builder->where('bookings.unit_id', $currentUnitId);
        }

        $bookings = $builder->whereIn('bookings.status', ['pending', 'approved', 'rejected'])
                            ->orderBy('bookings.start_time', 'DESC')
                            ->findAll();

        return $this->respondSuccess(['reservations' => BookingModel::enrichWithHash($bookings)]);
    }


    /**
     * POST /api/v1/bookings
     * Solicitar o agendar directamente una reserva
     */
    public function create()
    {
        $userId = $this->request->userId;

        $residentModel = new ResidentModel();
        $resident = $residentModel->where('user_id', $userId)->first();
        
        if (!$resident) {
             return $this->respondError('Debes ser residente para reservar amenidades', 403);
        }

        $json = $this->request->getJSON(true);
        $amenityId = $json['amenity_id'] ?? $this->request->getPost('amenity_id');
        $startTime = $json['start_time'] ?? $this->request->getPost('start_time');
        $endTime   = $json['end_time']   ?? $this->request->getPost('end_time');

        if (empty($amenityId) || empty($startTime) || empty($endTime)) {
            return $this->respondError('Faltan datos obligatorios de la reserva');
        }

        // Determinar política de la Amenidad
        $amenityModel = new AmenityModel();
        $amenity = $amenityModel->find($amenityId);
        
        if (!$amenity) {
            return $this->respondError('Amenidad no encontrada', 404);
        }
        
        if (!$amenity['is_reservable']) {
            return $this->respondError('Esta amenidad no está abierta a reservaciones', 400);
        }

        $bookingModel = new BookingModel();

        // Validar doble reserva (Overlapping)
        $existingBooking = $bookingModel
            ->where('amenity_id', $amenityId)
            ->where('status', 'approved')
            ->groupStart()
                ->where('start_time <', $endTime)
                ->where('end_time >', $startTime)
            ->groupEnd()
            ->first();

        if ($existingBooking) {
            return $this->respondError('El horario seleccionado ya no está disponible. Por favor, selecciona otro horario.', 409);
        }

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
                return $this->respondError("Has alcanzado el límite máximo de ({$maxAllowed}) reservas activas para esta amenidad.", 403);
            }
        }

        // Si la configuración "requires_approval" es 0, se aprueba en automático. Si es 1, queda pendiente.
        $requiresApproval = (int) ($amenity['requires_approval'] ?? 1);
        $status = ($requiresApproval === 1) ? 'pending' : 'approved';

        $bookingModel = new BookingModel();
        $bookingId = $bookingModel->insert([
            'amenity_id' => $amenityId,
            'unit_id'    => $resident['unit_id'],
            'user_id'    => $userId,
            'start_time' => $startTime,
            'end_time'   => $endTime,
            'status'     => $status
        ]);

        $message = ($status === 'pending') ? 'Reserva solicitada. Pendiente de aprobación.' : 'Reserva confirmada exitosamente.';

        // Notify Admins
        try {
            $db = \Config\Database::connect();
            $admins = $db->table('user_condominium_roles')
                         ->where('condominium_id', $resident['condominium_id'])
                         ->where('role_id', 2)
                         ->get()->getResultArray();
                         
            $userRow = $db->table('users')->select('first_name, last_name')->where('id', $userId)->get()->getRowArray();
            $unitRow = $db->table('units')->select('unit_number')->where('id', $resident['unit_id'])->get()->getRowArray();
            
            $resName = $userRow ? trim($userRow['first_name'] . ' ' . $userRow['last_name']) : 'Residente';
            $unitNum = $unitRow ? $unitRow['unit_number'] : 'S/N';
            $amenityName = $amenity['name'] ?? 'Amenidad';

            $title = "Nueva Reserva de Amenidad";
            $statusText = ($status === 'pending') ? "ha solicitado" : "ha agendado";
            $body = "{$resName} de la unidad {$unitNum} {$statusText} una reserva para {$amenityName}.";
            
            foreach ($admins as $admin) {
                \App\Models\Tenant\NotificationModel::notify($resident['condominium_id'], $admin['user_id'], 'amenidad', $title, $body);
            }
        } catch (\Exception $e) {
            // Ignorar errores de notificación para no afectar el flujo de reservas
        }

        return $this->respondSuccess([
            'message'    => $message,
            'booking_id' => $bookingId,
            'short_hash' => BookingModel::generateShortHash((int) $bookingId),
            'status'     => $status
        ]);
    }

    /**
     * POST /api/v1/bookings/(:num)/cancel
     * Cancelar una reserva existente del residente autenticado.
     */
    public function cancel($id = null)
    {
        if (!$id) return $this->respondError('ID no proporcionado');

        $userId = $this->request->userId;
        $bookingModel = new BookingModel();
        
        $booking = $bookingModel->find($id);
        
        if (!$booking) {
            return $this->respondError('Reserva no encontrada', 404);
        }

        // Validar propiedad
        if ($booking['user_id'] != $userId) {
            return $this->respondError('No tienes permiso para cancelar esta reserva', 403);
        }
        
        // Regla: no cancelar si ya pasó
        if (strtotime($booking['start_time']) < time()) {
            return $this->respondError('No puedes cancelar una reserva que ya comenzó o caducó', 400);
        }

        $bookingModel->update($id, ['status' => 'cancelled']);
        
        return $this->respondSuccess(['message' => 'Reserva cancelada correctamente.']);
    }

    /**
     * POST /api/v1/bookings/(:num)/approve
     * Aprobar reserva y notificar
     */
    public function approve($id = null)
    {
        if (!$id) return $this->respondError('ID no proporcionado');

        $bookingModel = new BookingModel();
        $booking = $bookingModel->find($id);
        if (!$booking) return $this->respondError('Reserva no encontrada', 404);

        $bookingModel->update($id, ['status' => 'approved']);

        // Notificar usuario
        $condoId = TenantService::getInstance()->getTenantId();
        NotificationModel::notify(
            $condoId,
            $booking['user_id'],
            'amenidad',
            'Reserva Aprobada',
            "Tu solicitud de reserva ha sido aprobada.",
            ['type' => 'amenity', 'booking_id' => $id]
        );

        return $this->respondSuccess(['message' => 'Reserva aprobada']);
    }

    /**
     * POST /api/v1/bookings/(:num)/reject
     * Rechazar reserva y notificar
     */
    public function reject($id = null)
    {
        if (!$id) return $this->respondError('ID no proporcionado');

        $bookingModel = new BookingModel();
        $booking = $bookingModel->find($id);
        if (!$booking) return $this->respondError('Reserva no encontrada', 404);

        $bookingModel->update($id, ['status' => 'rejected']);

        // Notificar usuario
        $condoId = TenantService::getInstance()->getTenantId();
        NotificationModel::notify(
            $condoId,
            $booking['user_id'],
            'amenidad',
            'Reserva Rechazada',
            "Tristemente, tu solicitud de reserva ha sido rechazada.",
            ['type' => 'amenity', 'booking_id' => $id]
        );

        return $this->respondSuccess(['message' => 'Reserva rechazada']);
    }
}
