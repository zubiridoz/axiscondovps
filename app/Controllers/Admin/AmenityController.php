<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Tenant\AmenityModel;
use App\Models\Tenant\AmenityScheduleModel;
use App\Models\Tenant\AmenityDocumentModel;
use App\Models\Tenant\BookingModel;

/**
 * AmenityController
 * 
 * Gestión de las amenidades del condominio.
 * Rediseñado con wizard multi-paso y URLs hash para SaaS.
 */
class AmenityController extends BaseController
{
    /**
     * Helper: Inicializa el contexto del tenant (modo demo local)
     */
    private function initTenant()
    {
        $demoCondo = (new \App\Models\Tenant\CondominiumModel())->first();
        if ($demoCondo) \App\Services\TenantService::getInstance()->setTenantId((int)$demoCondo['id']);
    }

    /**
     * Genera un hash_id único de 24 caracteres hex
     */
    private function generateHashId(): string
    {
        return bin2hex(random_bytes(12));
    }

    /**
     * Lista todas las amenidades (API)
     */
    public function index()
    {
        $amenityModel = new AmenityModel();
        $amenities = $amenityModel->findAll();

        return $this->response->setJSON(['status' => 200, 'data' => $amenities]);
    }

    /**
     * RENDER HTML MVC - Vista Frontal del Administrador (Directorio)
     */
    public function indexView()
    {
        $this->initTenant();

        $amenityModel = new AmenityModel();
        $amenities = $amenityModel->findAll();

        // KPIs
        $bookingModel = new BookingModel();
        $totalAmenities = count($amenities);
        
        $startOfMonth = date('Y-m-01 00:00:00');
        $endOfMonth   = date('Y-m-t 23:59:59');

        $reservationsThisMonth = $bookingModel
            ->where('start_time >=', $startOfMonth)
            ->where('start_time <=', $endOfMonth)
            ->countAllResults(false);

        $pendingApprovals = $bookingModel
            ->where('status', 'pending')
            ->countAllResults(false);

        // Ingresos generados: sum de amenities.price para reservas aprobadas
        $revenueResult = $bookingModel
            ->select('COALESCE(SUM(amenities.price), 0) as total_revenue')
            ->join('amenities', 'amenities.id = bookings.amenity_id', 'left')
            ->where('bookings.status', 'approved')
            ->where('bookings.start_time >=', $startOfMonth)
            ->where('bookings.start_time <=', $endOfMonth)
            ->first();
        $revenue = $revenueResult['total_revenue'] ?? 0;

        return view('admin/amenities', [
            'amenities'            => $amenities,
            'totalAmenities'       => $totalAmenities,
            'reservationsThisMonth'=> $reservationsThisMonth,
            'pendingApprovals'     => $pendingApprovals,
            'revenue'              => $revenue,
        ]);
    }

    /**
     * RENDER HTML MVC — Vista Read-Only de detalle de amenidad
     * URL: /admin/amenidades/detalle/{hash_id}
     */
    public function showView($hashId = null)
    {
        $this->initTenant();

        if (!$hashId) {
            return redirect()->to(base_url('admin/amenidades'));
        }

        $amenityModel = new AmenityModel();
        $amenity = $amenityModel->where('hash_id', $hashId)->first();

        if (!$amenity) {
            return redirect()->to(base_url('admin/amenidades'))->with('error', 'Amenidad no encontrada');
        }

        // Cargar horarios
        $scheduleModel = new AmenityScheduleModel();
        $schedules = $scheduleModel->getScheduleByAmenity((int)$amenity['id']);

        // Cargar documentos
        $docModel = new AmenityDocumentModel();
        $documents = $docModel->getByAmenity((int)$amenity['id']);

        // Cargar reservas para el calendario
        $bookingModel = new BookingModel();
        $bookings = $bookingModel->where('amenity_id', (int)$amenity['id'])
                                  ->where('status !=', 'cancelled')
                                  ->findAll();

        return view('admin/amenity_show', [
            'amenity'   => $amenity,
            'schedules' => $schedules,
            'documents' => $documents,
            'bookings'  => $bookings,
        ]);
    }

    /**
     * RENDER HTML MVC — Vista Wizard para Crear / Editar Amenidad
     * URL: /admin/amenidades/nueva              → Crear
     * URL: /admin/amenidades/editar/{hash_id}   → Editar
     */
    public function editView($hashId = null)
    {
        $this->initTenant();

        $amenity   = null;
        $schedules = [];
        $documents = [];

        if ($hashId) {
            $amenityModel = new AmenityModel();
            $amenity = $amenityModel->where('hash_id', $hashId)->first();

            if (!$amenity) {
                return redirect()->to(base_url('admin/amenidades'))->with('error', 'Amenidad no encontrada');
            }

            // Cargar horarios
            $scheduleModel = new AmenityScheduleModel();
            $schedules = $scheduleModel->getScheduleByAmenity((int)$amenity['id']);

            // Cargar documentos
            $docModel = new AmenityDocumentModel();
            $documents = $docModel->getByAmenity((int)$amenity['id']);
        }

        return view('admin/amenity_detail', [
            'amenity'   => $amenity,
            'schedules' => $schedules,
            'documents' => $documents,
            'isEdit'    => $amenity !== null,
        ]);
    }

    /**
     * Crea una nueva amenidad vía wizard (POST AJAX con FormData)
     */
    public function createWizard()
    {
        $this->initTenant();

        // ── Paso 1: Información Básica ──
        $data = [
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'is_active'   => 1,
            'hash_id'     => $this->generateHashId(),
        ];

        if (empty($data['name'])) {
            return $this->response->setJSON(['status' => 400, 'error' => 'El nombre es requerido']);
        }

        // Handle image upload
        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $newName = $image->getRandomName();
            $image->move(WRITEPATH . 'uploads/amenities', $newName);
            $data['image'] = $newName;
        }

        // ── Paso 2: Configuración de Reservas ──
        $data['is_reservable']          = $this->request->getPost('is_reservable') ?? 1;
        $data['reservation_interval']   = $this->request->getPost('reservation_interval') ?? '1';
        $data['max_active_reservations']= $this->request->getPost('max_active_reservations') ?? 'unlimited';
        $data['has_cost']               = $this->request->getPost('has_cost') ?? 0;
        $data['price']                  = ($data['has_cost'] == 1) ? ($this->request->getPost('price') ?? 0) : 0;
        $data['requires_approval']      = $this->request->getPost('requires_approval') ?? 0;
        $data['available_from']         = $this->request->getPost('available_from') ?: null;
        $data['blocked_dates']          = $this->request->getPost('blocked_dates') ?: null;
        $data['reservation_message']    = $this->request->getPost('reservation_message') ?: null;

        $amenityModel = new AmenityModel();
        $amenityId = $amenityModel->insert($data);

        if (!$amenityId) {
            return $this->response->setJSON(['status' => 500, 'error' => 'Error al crear la amenidad']);
        }

        // ── Paso 3: Horario Semanal ──
        $scheduleJson = $this->request->getPost('schedule');
        if ($scheduleJson) {
            $schedule = json_decode($scheduleJson, true);
            if (is_array($schedule)) {
                $scheduleModel = new AmenityScheduleModel();
                $scheduleModel->saveFullSchedule((int)$amenityId, $schedule);
            }
        }

        // ── Paso 4: Documentos ──
        $files = $this->request->getFiles();
        if (isset($files['documents'])) {
            $docModel = new AmenityDocumentModel();
            $docTitles = $this->request->getPost('document_titles') ?? [];
            
            foreach ($files['documents'] as $i => $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $fileName = $file->getRandomName();
                    $file->move(WRITEPATH . 'uploads/amenity_documents', $fileName);
                    
                    $docModel->insert([
                        'amenity_id' => $amenityId,
                        'title'      => $docTitles[$i] ?? pathinfo($file->getClientName(), PATHINFO_FILENAME),
                        'filename'   => $fileName,
                        'file_size'  => $file->getSize(),
                        'file_type'  => $file->getClientMimeType(),
                    ]);
                }
            }
        }

        return $this->response->setJSON([
            'status'  => 201,
            'message' => 'Amenidad creada exitosamente',
            'hash_id' => $data['hash_id'],
        ]);
    }

    /**
     * Actualiza una amenidad existente vía wizard (POST AJAX con FormData)
     */
    public function updateWizard($hashId = null)
    {
        $this->initTenant();

        if (!$hashId) {
            return $this->response->setJSON(['status' => 400, 'error' => 'Hash ID no proporcionado']);
        }

        $amenityModel = new AmenityModel();
        $amenity = $amenityModel->where('hash_id', $hashId)->first();

        if (!$amenity) {
            return $this->response->setJSON(['status' => 404, 'error' => 'Amenidad no encontrada']);
        }

        $amenityId = $amenity['id'];

        // ── Paso 1: Información Básica ──
        $data = [
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
        ];

        if (empty($data['name'])) {
            return $this->response->setJSON(['status' => 400, 'error' => 'El nombre es requerido']);
        }

        // Handle image upload
        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $newName = $image->getRandomName();
            $image->move(WRITEPATH . 'uploads/amenities', $newName);
            $data['image'] = $newName;
        }

        // ── Paso 2: Configuración de Reservas ──
        $data['is_reservable']          = $this->request->getPost('is_reservable') ?? 1;
        $data['reservation_interval']   = $this->request->getPost('reservation_interval') ?? '1';
        $data['max_active_reservations']= $this->request->getPost('max_active_reservations') ?? 'unlimited';
        $data['has_cost']               = $this->request->getPost('has_cost') ?? 0;
        $data['price']                  = ($data['has_cost'] == 1) ? ($this->request->getPost('price') ?? 0) : 0;
        $data['requires_approval']      = $this->request->getPost('requires_approval') ?? 0;
        $data['available_from']         = $this->request->getPost('available_from') ?: null;
        $data['blocked_dates']          = $this->request->getPost('blocked_dates') ?: null;
        $data['reservation_message']    = $this->request->getPost('reservation_message') ?: null;

        $amenityModel->update($amenityId, $data);

        // ── Paso 3: Horario Semanal ──
        $scheduleJson = $this->request->getPost('schedule');
        if ($scheduleJson) {
            $schedule = json_decode($scheduleJson, true);
            if (is_array($schedule)) {
                $scheduleModel = new AmenityScheduleModel();
                $scheduleModel->saveFullSchedule((int)$amenityId, $schedule);
            }
        }

        // ── Paso 4: Documentos ──
        $files = $this->request->getFiles();
        if (isset($files['documents'])) {
            $docModel = new AmenityDocumentModel();
            $docTitles = $this->request->getPost('document_titles') ?? [];

            foreach ($files['documents'] as $i => $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $fileName = $file->getRandomName();
                    $file->move(WRITEPATH . 'uploads/amenity_documents', $fileName);

                    $docModel->insert([
                        'amenity_id' => $amenityId,
                        'title'      => $docTitles[$i] ?? pathinfo($file->getClientName(), PATHINFO_FILENAME),
                        'filename'   => $fileName,
                        'file_size'  => $file->getSize(),
                        'file_type'  => $file->getClientMimeType(),
                    ]);
                }
            }
        }

        return $this->response->setJSON([
            'status'  => 200,
            'message' => 'Amenidad actualizada exitosamente',
            'hash_id' => $hashId,
        ]);
    }

    /**
     * Crea una nueva amenidad (legacy simple, kept for backward compat)
     */
    public function create()
    {
        $this->initTenant();

        $data = [
            'name'          => $this->request->getPost('name'),
            'description'   => $this->request->getPost('description'),
            'capacity'      => $this->request->getPost('capacity') ?? 0,
            'is_active'     => $this->request->getPost('is_active') ?? 1,
            'is_reservable' => $this->request->getPost('is_reservable') ?? 1,
            'price'         => $this->request->getPost('price') ?? 0,
            'rules'         => $this->request->getPost('rules'),
            'open_time'     => $this->request->getPost('open_time'),
            'close_time'    => $this->request->getPost('close_time'),
            'hash_id'       => $this->generateHashId(),
        ];

        // Validaciones requeridas
        if (empty($data['name'])) {
            return $this->response->setJSON(['status' => 400, 'error' => 'El nombre es requerido']);
        }

        // Handle image upload
        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $newName = $image->getRandomName();
            $image->move(WRITEPATH . 'uploads/amenities', $newName);
            $data['image'] = $newName;
        }

        $amenityModel = new AmenityModel();
        $amenityId = $amenityModel->insert($data);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 201, 'message' => 'Amenidad creada', 'id' => $amenityId]);
        }
        return redirect()->to(base_url('admin/amenidades'))->with('success', 'Amenidad creada exitosamente');
    }

    /**
     * Edita una amenidad existente
     */
    public function update($id = null)
    {
        $this->initTenant();

        if (!$id) return $this->response->setJSON(['status' => 400, 'error' => 'ID no proporcionado']);

        $data = [
            'name'          => $this->request->getVar('name'),
            'description'   => $this->request->getVar('description'),
            'capacity'      => $this->request->getVar('capacity'),
            'is_active'     => $this->request->getVar('is_active'),
            'is_reservable' => $this->request->getVar('is_reservable'),
            'price'         => $this->request->getVar('price'),
            'rules'         => $this->request->getVar('rules'),
            'open_time'     => $this->request->getVar('open_time'),
            'close_time'    => $this->request->getVar('close_time'),
        ];
        
        $data = array_filter($data, fn($value) => !is_null($value) && $value !== '');

        // Handle image upload
        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $newName = $image->getRandomName();
            $image->move(WRITEPATH . 'uploads/amenities', $newName);
            $data['image'] = $newName;
        }

        $amenityModel = new AmenityModel();
        
        if (!$amenityModel->find($id)) {
            return $this->response->setJSON(['status' => 404, 'error' => 'Amenidad no encontrada']);
        }

        $amenityModel->update($id, $data);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 200, 'message' => 'Amenidad actualizada exitosamente']);
        }
        return redirect()->to(base_url('admin/amenidades'))->with('success', 'Amenidad actualizada');
    }

    /**
     * Elimina (Soft Delete) una amenidad
     */
    public function delete($id = null)
    {
        $this->initTenant();

        if (!$id) return $this->response->setJSON(['status' => 400, 'error' => 'ID no proporcionado']);

        $amenityModel = new AmenityModel();
        
        if ($amenityModel->delete($id)) {
            return $this->response->setJSON(['status' => 200, 'message' => 'Amenidad eliminada']);
        }

        return $this->response->setJSON(['status' => 500, 'error' => 'No se pudo eliminar la amenidad']);
    }

    /**
     * Servir imagen de amenidad
     */
    public function serveImage($filename = null)
    {
        if (!$filename) {
            return $this->response->setStatusCode(404);
        }

        $path = WRITEPATH . 'uploads/amenities/' . $filename;
        if (!file_exists($path)) {
            return $this->response->setStatusCode(404);
        }

        return $this->response
            ->setHeader('Content-Type', mime_content_type($path))
            ->setHeader('Cache-Control', 'public, max-age=86400')
            ->setBody(file_get_contents($path));
    }

    /**
     * Servir documento de amenidad
     */
    public function serveDocument($filename = null)
    {
        if (!$filename) {
            return $this->response->setStatusCode(404);
        }

        $path = WRITEPATH . 'uploads/amenity_documents/' . $filename;
        if (!file_exists($path)) {
            return $this->response->setStatusCode(404);
        }

        return $this->response
            ->setHeader('Content-Type', mime_content_type($path))
            ->setHeader('Cache-Control', 'public, max-age=86400')
            ->setBody(file_get_contents($path));
    }

    /**
     * Eliminar un documento individual de amenidad
     */
    public function deleteDocument($id = null)
    {
        $this->initTenant();

        if (!$id) {
            return $this->response->setJSON(['status' => 400, 'error' => 'ID no proporcionado']);
        }

        $docModel = new AmenityDocumentModel();
        $doc = $docModel->find($id);

        if (!$doc) {
            return $this->response->setJSON(['status' => 404, 'error' => 'Documento no encontrado']);
        }

        // Delete physical file
        $path = WRITEPATH . 'uploads/amenity_documents/' . $doc['filename'];
        if (file_exists($path)) {
            unlink($path);
        }

        $docModel->delete($id);

        return $this->response->setJSON(['status' => 200, 'message' => 'Documento eliminado']);
    }
}
