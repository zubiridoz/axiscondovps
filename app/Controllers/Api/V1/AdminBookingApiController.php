<?php

namespace App\Controllers\Api\V1;

use CodeIgniter\RESTful\ResourceController;
use App\Models\Tenant\BookingModel;
use App\Models\Tenant\NotificationModel;
use App\Services\TenantService;

class AdminBookingApiController extends ResourceController
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
     * GET /api/v1/admin/bookings/active
     * Reservas activas (pending, approved) de todos los residentes del condominio.
     */
    public function active()
    {
        $tenantId = TenantService::getInstance()->getTenantId();
        if (!$tenantId) {
            return $this->respondError('No hay condominio activo', 401);
        }

        $bookingModel = new BookingModel();
        
        $bookings = $bookingModel
            ->select('bookings.*, amenities.name as amenity_name, amenities.image as amenity_image, users.first_name, users.last_name, units.unit_number')
            ->join('amenities', 'amenities.id = bookings.amenity_id', 'left')
            ->join('users', 'users.id = bookings.user_id', 'left')
            ->join('units', 'units.id = bookings.unit_id', 'left')
            ->where('bookings.condominium_id', $tenantId)
            ->whereIn('bookings.status', ['pending', 'approved', 'rejected'])
            ->orderBy('bookings.start_time', 'DESC')
            ->findAll();

        return $this->respondSuccess(['reservations' => BookingModel::enrichWithHash($bookings)]);
    }



    /**
     * GET /api/v1/admin/bookings/history
     * Historial de reservas de todos los residentes del condominio.
     */
    public function history()
    {
        $tenantId = TenantService::getInstance()->getTenantId();
        if (!$tenantId) {
            return $this->respondError('No hay condominio activo', 401);
        }

        $bookingModel = new BookingModel();
        
        $bookings = $bookingModel
            ->select('bookings.*, amenities.name as amenity_name, amenities.image as amenity_image, users.first_name, users.last_name, units.unit_number')
            ->join('amenities', 'amenities.id = bookings.amenity_id', 'left')
            ->join('users', 'users.id = bookings.user_id', 'left')
            ->join('units', 'units.id = bookings.unit_id', 'left')
            ->where('bookings.condominium_id', $tenantId)
            ->orderBy('bookings.start_time', 'DESC')
            ->findAll();

        return $this->respondSuccess(['reservations' => BookingModel::enrichWithHash($bookings)]);
    }

    /**
     * POST /api/v1/admin/bookings/(:num)/approve
     */
    public function approve($id = null)
    {
        $tenantId = TenantService::getInstance()->getTenantId();
        if (!$tenantId) return $this->respondError('No hay condominio activo', 401);

        if (!$id) return $this->respondError('ID no proporcionado');

        $bookingModel = new BookingModel();
        $booking = $bookingModel->where('condominium_id', $tenantId)->find($id);
        if (!$booking) return $this->respondError('Reserva no encontrada', 404);

        $bookingModel->update($id, ['status' => 'approved']);

        // Notificar usuario
        NotificationModel::notify(
            $tenantId,
            $booking['user_id'],
            'amenidad',
            'Reserva Aprobada',
            "Tu solicitud de reserva ha sido aprobada.",
            ['type' => 'amenity', 'booking_id' => $id]
        );

        return $this->respondSuccess(['message' => 'Reserva aprobada']);
    }

    /**
     * POST /api/v1/admin/bookings/(:num)/reject
     */
    public function reject($id = null)
    {
        $tenantId = TenantService::getInstance()->getTenantId();
        if (!$tenantId) return $this->respondError('No hay condominio activo', 401);

        if (!$id) return $this->respondError('ID no proporcionado');

        $bookingModel = new BookingModel();
        $booking = $bookingModel->where('condominium_id', $tenantId)->find($id);
        if (!$booking) return $this->respondError('Reserva no encontrada', 404);

        $bookingModel->update($id, ['status' => 'rejected']);

        // Notificar usuario
        NotificationModel::notify(
            $tenantId,
            $booking['user_id'],
            'amenidad',
            'Reserva Rechazada',
            "Tristemente, tu solicitud de reserva ha sido rechazada.",
            ['type' => 'amenity', 'booking_id' => $id]
        );

        return $this->respondSuccess(['message' => 'Reserva rechazada']);
    }
}
