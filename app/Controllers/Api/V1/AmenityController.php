<?php

namespace App\Controllers\Api\V1;

use CodeIgniter\RESTful\ResourceController;
use App\Models\Tenant\AmenityModel;
use App\Models\Tenant\AmenityScheduleModel;
use App\Models\Tenant\BookingModel;

/**
 * AmenityController (API V1)
 *
 * Catálogo de amenidades y motor de evaluación de disponibilidad para Flutter.
 */
class AmenityController extends ResourceController
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
     * GET /api/v1/amenities
     * Listado principal del catálogo de amenidades.
     */
    public function index()
    {
        $amenityModel = new AmenityModel();
        // Devolvemos activas (incluyendo las no reservables)
        $amenities = $amenityModel->where('is_active', 1)->findAll();

        return $this->respondSuccess(['amenities' => $amenities]);
    }

    /**
     * GET /api/v1/amenities/(:num)
     * Detalle específico de la amenidad.
     */
    public function detail($id = null)
    {
        if (!$id) return $this->respondError('ID no proporcionado');

        $amenityModel = new AmenityModel();
        $amenity = $amenityModel->where('is_active', 1)->find($id);

        if (!$amenity) return $this->respondError('Amenidad no encontrada', 404);

        $scheduleModel = new \App\Models\Tenant\AmenityScheduleModel();
        $schedules = $scheduleModel->where('amenity_id', $id)->findAll();

        $docModel = new \App\Models\Tenant\AmenityDocumentModel();
        $documents = $docModel->where('amenity_id', $id)->findAll();

        return $this->respondSuccess([
            'amenity'   => $amenity,
            'schedules' => $schedules,
            'documents' => $documents
        ]);
    }

    /**
     * GET /api/v1/amenities/(:num)/availability
     * Motor de disponibilidad mensual para llenar el calendario interactivo en Flutter.
     */
    public function availability($id = null)
    {
        if (!$id) return $this->respondError('ID no proporcionado');

        $month = (int) ($this->request->getGet('month') ?: date('n'));
        $year  = (int) ($this->request->getGet('year') ?: date('Y'));

        $amenityModel = new AmenityModel();
        $amenity = $amenityModel->where('is_active', 1)->find($id);

        if (!$amenity) return $this->respondError('Amenidad no encontrada', 404);

        // Schedule semanal (reglas generales hora apertura/cierre por día)
        $scheduleModel = new AmenityScheduleModel();
        $schedules = $scheduleModel->where('amenity_id', $id)->findAll();
        
        $scheduleMap = [];
        foreach ($schedules as $s) {
            $scheduleMap[(int)$s['day_of_week']] = $s;
        }

        // Consultar reservas vigentes del mes
        $bookingModel = new BookingModel();
        $startOfMonth = sprintf('%04d-%02d-01 00:00:00', $year, $month);
        $endOfMonth   = date('Y-m-t 23:59:59', strtotime($startOfMonth));

        $existingBookings = $bookingModel
            ->where('amenity_id', $id)
            ->where('status !=', 'cancelled')
            ->where('status !=', 'rejected')
            ->where('start_time >=', $startOfMonth)
            ->where('start_time <=', $endOfMonth)
            ->findAll();

        $userId = $this->request->userId ?? 0;
        $activeReservationsCount = 0;
        if ($userId) {
            $bookingModelCount = new BookingModel();
            $activeReservationsCount = $bookingModelCount
                ->where('amenity_id', $id)
                ->where('user_id', $userId)
                ->whereIn('status', ['pending', 'approved'])
                ->where('end_time >', date('Y-m-d H:i:s'))
                ->countAllResults();
        }

        return $this->respondSuccess([
            'amenity' => [
                'id'                      => $amenity['id'],
                'name'                    => $amenity['name'],
                'reservation_interval'    => $amenity['reservation_interval'] ?? '1',
                'max_active_reservations' => $amenity['max_active_reservations'] ?? 'unlimited',
                'has_cost'                => $amenity['has_cost'] ?? 0,
                'price'                   => $amenity['price'] ?? 0,
                'available_from'          => $amenity['available_from'] ?? null,
                'blocked_dates'           => $amenity['blocked_dates']  ?? null,
                'reservation_message'     => $amenity['reservation_message'] ?? null,
                'current_active_reservations' => $activeReservationsCount
            ],
            'schedule' => $scheduleMap,
            'bookings' => BookingModel::enrichWithHash($existingBookings),
            'month'    => $month,
            'year'     => $year,
        ]);
    }
}
