<?php

namespace App\Controllers\Api\V1;

use CodeIgniter\RESTful\ResourceController;
use App\Models\Tenant\CalendarEventModel;
use App\Models\Tenant\ResidentModel;
use App\Models\Tenant\UnitModel;
use App\Models\Tenant\UserCondominiumRoleModel;
use App\Services\CalendarNotificationService;

/**
 * CalendarController (API V1)
 *
 * Gestión del calendario para la plataforma móvil (PWA/Flutter).
 */
class CalendarController extends ResourceController
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
     * Helper para obtener a todos los usuarios que componen la unidad del residente actual.
     */
    private function getMyUnitUsersIds($userId)
    {
        $residentModel = new ResidentModel();
        
        // 1. Encontrar a qué unidad pertenece el usuario local
        $myResidentRecord = $residentModel->where('user_id', $userId)->first();
        if (!$myResidentRecord || !$myResidentRecord['unit_id']) {
            return [$userId]; // Failsafe: solo su propio ID
        }

        // 2. Extraer todos los user_id de los residentes en esa misma unidad
        $familyResidents = $residentModel->where('unit_id', $myResidentRecord['unit_id'])->findAll();
        
        $ids = [];
        foreach ($familyResidents as $res) {
            if ($res['user_id']) {
                $ids[] = (int) $res['user_id'];
            }
        }
        
        return array_unique($ids);
    }

    /**
     * Helper: Enriquecer un array de eventos con unit_number del creador.
     */
    private function enrichEventsWithUnit(array $events): array
    {
        $residentModel = new ResidentModel();
        $unitModel     = new UnitModel();

        foreach ($events as &$event) {
            $event['creator_unit'] = null;
            if (!empty($event['created_by'])) {
                $res = $residentModel->where('user_id', $event['created_by'])->first();
                if ($res && !empty($res['unit_id'])) {
                    $unit = $unitModel->find($res['unit_id']);
                    if ($unit) {
                        $event['creator_unit'] = $unit['unit_number'] ?? null;
                    }
                }
            }
        }
        return $events;
    }

    /**
     * Helper: Verificar si el usuario actual es Administrador.
     */
    private function isAdmin(int $userId): bool
    {
        $ucrModel = new UserCondominiumRoleModel();
        $role = $ucrModel->where('user_id', $userId)
                         ->where('role_id', 2) // Admin role
                         ->first();
        return $role !== null;
    }

    /**
     * GET /api/v1/calendar
     * Retorna los eventos (Globales + Privados de Unidad)
     */
    public function index()
    {
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No Autorizado', 401);

        $month = (int) ($this->request->getGet('month') ?: date('n'));
        $year  = (int) ($this->request->getGet('year') ?: date('Y'));

        $startOfMonth = sprintf('%04d-%02d-01 00:00:00', $year, $month);
        $endOfMonth   = date('Y-m-t 23:59:59', strtotime($startOfMonth));

        $unitUserIds = $this->getMyUnitUsersIds($userId);

        $eventModel = new CalendarEventModel();
        
        $builder = $eventModel
            ->select('calendar_events.*, users.first_name, users.last_name')
            ->join('users', 'users.id = calendar_events.created_by', 'left');

        $isAdmin = $this->isAdmin($userId);

        if (!$isAdmin) {
            // Filtramos eventos comunitarios (no internos)
            // Y que: (hayan sido creados por mi unidad OR creados por un Admin/Manager)
            $builder->where('calendar_events.is_internal', 0)
                ->groupStart()
                    // Eventos construidos por la unidad actual
                    ->whereIn('calendar_events.created_by', $unitUserIds)
                    // O eventos construidos por Admin = role_id 2
                    ->orGroupStart()
                         ->where('calendar_events.created_by IN (SELECT user_id FROM user_condominium_roles WHERE role_id = 2)', null, false)
                    ->groupEnd()
                ->groupEnd();
        }

        $events = $builder->where('calendar_events.start_datetime >=', $startOfMonth)
            ->where('calendar_events.start_datetime <=', $endOfMonth)
            ->orderBy('calendar_events.start_datetime', 'ASC')
            ->findAll();

        // Enrich events with unit_number of the creator
        $events = $this->enrichEventsWithUnit($events);

        return $this->respondSuccess([
            'events' => $events,
            'month'  => $month,
            'year'   => $year
        ]);
    }

    /**
     * GET /api/v1/calendar/(:num)
     * Detalle específico de un evento
     */
    public function detail($id = null)
    {
        if (!$id) return $this->respondError('ID no proporcionado');

        $userId = $this->request->userId ?? null;

        $eventModel = new CalendarEventModel();
        
        $event = $eventModel
            ->select('calendar_events.*, users.first_name as creator_first_name, users.last_name as creator_last_name')
            ->join('users', 'users.id = calendar_events.created_by', 'left')
            ->find($id);

        if (!$event) return $this->respondError('Evento no encontrado', 404);

        // Enrich with unit_number
        $enriched = $this->enrichEventsWithUnit([$event]);
        $event = $enriched[0];

        // Indicate if current user can delete this event
        $canDelete = false;
        if ($userId) {
            if ((int) $event['created_by'] === (int) $userId) {
                $canDelete = true; // Propio evento
            } elseif ($this->isAdmin($userId)) {
                $canDelete = true; // Admin puede eliminar cualquier evento
            }
        }
        $event['can_delete'] = $canDelete;

        return $this->respondSuccess(['event' => $event]);
    }

    /**
     * POST /api/v1/calendar
     * El residente genera un evento auto-organizado para su unidad.
     */
    public function create()
    {
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No Autorizado', 401);

        $json = $this->request->getJSON(true);
        $title = $json['title'] ?? $this->request->getPost('title');
        
        if (empty($title)) {
            return $this->respondError('El título es obligatorio');
        }

        $allDay = (int) ($json['all_day'] ?? $this->request->getPost('all_day') ?? 0);
        $startDatetime = $json['start_datetime'] ?? $this->request->getPost('start_datetime');
        $endDatetime   = $json['end_datetime']   ?? $this->request->getPost('end_datetime');

        if (empty($startDatetime) || empty($endDatetime)) {
             return $this->respondError('Fechas de inicio y fin requeridas');
        }

        $isAdmin = $this->isAdmin($userId);
        $isInternal = 0;
        
        if ($isAdmin) {
            $isInternal = (int) ($json['is_internal'] ?? $this->request->getPost('is_internal') ?? 0);
        }

        $eventModel = new CalendarEventModel();
        $eventId = $eventModel->insert([
            'title'          => trim($title),
            'description'    => $json['description'] ?? $this->request->getPost('description') ?? null,
            'location'       => $json['location'] ?? $this->request->getPost('location') ?? null,
            'start_datetime' => $startDatetime,
            'end_datetime'   => $endDatetime,
            'all_day'        => $allDay,
            'is_internal'    => $isInternal,
            'created_by'     => $userId, // Auto-asignado a su unidad/admin
        ]);

        if (!$eventId) {
            return $this->respondError('No se pudo crear el evento', 500);
        }

        try {
            if ($isAdmin) {
                // Si el administrador crea el evento y NO es interno, notificar a todos los residentes (igual que el panel web)
                if (!$isInternal) {
                    CalendarNotificationService::notifyResidentsNewEvent($eventId, trim($title), $startDatetime);
                }
            } else {
                // 🔔 Residente crea evento -> Notificar a administradores
                CalendarNotificationService::notifyAdminsNewEvent($userId, trim($title), $startDatetime);
            }
        } catch (\Throwable $e) {
            log_message('error', 'CalendarNotification: ' . $e->getMessage());
        }

        return $this->respondSuccess([
            'message' => 'Evento privado agendado',
            'id'      => $eventId
        ]);
    }

    /**
     * POST o DELETE /api/v1/calendar/(:num)/delete
     * Elimina el evento — solo el creador o un admin puede hacerlo.
     */
    public function delete($id = null)
    {
        if (!$id) return $this->respondError('ID no proporcionado');

        $userId = $this->request->userId ?? null;

        $eventModel = new CalendarEventModel();
        $event = $eventModel->find($id);

        if (!$event) return $this->respondError('Evento no encontrado', 404);

        // Solo el creador o un administrador puede eliminar
        if ((int) $event['created_by'] !== (int) $userId && !$this->isAdmin($userId)) {
            return $this->respondError('No tienes permiso para eliminar este evento', 403);
        }

        $eventModel->delete($id);
        
        return $this->respondSuccess(['message' => 'Evento eliminado de la agenda']);
    }
}
