<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Tenant\UnitModel;
use App\Models\Tenant\ResidentModel;
use App\Models\Tenant\SectionModel;
use App\Models\Tenant\UnitNoteModel;
use CodeIgniter\API\ResponseTrait;

/**
 * UnitController
 * 
 * Gestión de las unidades (departamentos, casas, locales) del condominio.
 */
class UnitController extends BaseController
{
    use ResponseTrait;
    /**
     * Lista todas las unidades API
     */
    public function index()
    {
        $unitModel = new UnitModel();
        $units = $unitModel->findAll();

        return $this->response->setJSON(['status' => 200, 'data' => $units]);
    }

    /**
     * Crea una nueva unidad
     */
    public function create()
    {
        $data = [
            'section_id'          => $this->request->getPost('section_id'),
            'unit_number'         => $this->request->getPost('unit_number'),
            'type'                => $this->request->getPost('type') ?? 'apartment',
            'floor'               => $this->request->getPost('floor'),
            'area'                => $this->request->getPost('area'),
            'indiviso_percentage' => $this->request->getPost('indiviso_percentage'),
            'maintenance_fee'     => $this->request->getPost('maintenance_fee') ?? 0
        ];
        
        $unitModel = new UnitModel();
        
        $condoId = (int)(\App\Services\TenantService::getInstance()->getTenantId() ?: 1);
        $capacityCheck = $this->checkPlanCapacity($condoId, 1);
        if (!$capacityCheck['allowed']) {
            return $this->response->setJSON(['status' => 403, 'error' => $capacityCheck['message']]);
        }

        $unitId = $unitModel->insert($data);

        return $this->response->setJSON(['status' => 201, 'message' => 'Unidad creada', 'id' => $unitId]);
    }

    /**
     * Edita una unidad individual
     */
    public function update($id = null)
    {
        if (!$id) return $this->response->setJSON(['status' => 400, 'error' => 'ID no proporcionado']);

        $data = [
            'section_id'          => $this->request->getVar('section_id'),
            'unit_number'         => $this->request->getVar('unit_number'),
            'type'                => $this->request->getVar('type'),
            'floor'               => $this->request->getVar('floor'),
            'area'                => $this->request->getVar('area'),
            'indiviso_percentage' => $this->request->getVar('indiviso_percentage'),
            'maintenance_fee'     => $this->request->getVar('maintenance_fee')
        ];
        
        $data = array_filter($data, fn($value) => !is_null($value) && $value !== '');

        $unitModel = new UnitModel();
        if (!$unitModel->find($id)) {
             return $this->response->setJSON(['status' => 404, 'error' => 'Unidad no encontrada']);
        }

        $unitModel->update($id, $data);

        return $this->response->setJSON(['status' => 200, 'message' => 'Unidad actualizada exitosamente']);
    }

    /**
     * Elimina una unidad
     */
    public function delete($id = null)
    {
        if (!$id) return $this->response->setJSON(['status' => 400, 'error' => 'ID no proporcionado']);

        $unitModel = new UnitModel();
        $unitModel->delete($id);

        return $this->response->setJSON(['status' => 200, 'message' => 'Unidad eliminada']);
    }

    /**
     * Obtiene los residentes asignados a la unidad
     */
    public function viewResidents($id = null)
    {
        if (!$id) return $this->response->setJSON(['status' => 400, 'error' => 'ID de unidad requerido']);
        
        $residentModel = new ResidentModel();
        $residents = $residentModel->select('residents.*, CONCAT(users.first_name, " ", users.last_name) as name, users.email')
                                   ->join('users', 'users.id = residents.user_id')
                                   ->where('unit_id', $id)
                                   ->findAll();
        
        return $this->response->setJSON(['status' => 200, 'unit_id' => $id, 'data' => $residents]);
    }

    /**
     * RENDER HTML MVC - Vista Frontal del Administrador
     */
    public function indexView()
    {
        // [HACK LOCAL] Forzamos el contexto Tenant
        $demoCondo = (new \App\Models\Tenant\CondominiumModel())->first();
        if ($demoCondo) \App\Services\TenantService::getInstance()->setTenantId((int)$demoCondo['id']);

        $unitModel = new UnitModel();
        
        // Obtenemos unidades base con su sección
        $unitsRaw = $unitModel->select('units.*, sections.name as section_name')
                           ->join('sections', 'sections.id = units.section_id', 'left')
                           ->orderBy('units.unit_number', 'ASC')
                           ->findAll();

        // Obtenemos todos los residentes agrupados para evitar las queries N+1
        $residentModel = new ResidentModel();
        $allResidents = $residentModel->select('residents.id as resident_id, residents.unit_id, residents.type, users.id as user_id, users.email, CONCAT(users.first_name, " ", users.last_name) as name')
                                      ->join('users', 'users.id = residents.user_id')
                                      ->findAll();
                                      
        $groupedResidents = [];
        foreach($allResidents as $res) {
            $groupedResidents[$res['unit_id']][] = $res;
        }

        // Mapeamos array final
        $units = [];
        foreach ($unitsRaw as $u) {
            $owners = [];
            $tenants = [];
            
            if (isset($groupedResidents[$u['id']])) {
                foreach ($groupedResidents[$u['id']] as $r) {
                    $resObj = [
                        'resident_id' => $r['resident_id'],
                        'user_id' => $r['user_id'],
                        'name' => $r['name'],
                        'email' => $r['email']
                    ];
                    if ($r['type'] === 'owner') $owners[] = $resObj;
                    else $tenants[] = $resObj;
                }
            }
            
            $u['owners'] = $owners;
            $u['tenants'] = $tenants;
            $units[] = $u;
        }
        
        $sections = (new SectionModel())->findAll();

        return view('admin/units', ['units' => $units, 'sections' => $sections]);
    }

    /**
     * Exporta todas las unidades en formato CSV
     */
    public function exportCSV()
    {
        $unitModel = new UnitModel();
        $units = $unitModel->select('units.unit_number as Nombre, units.maintenance_fee as CuotaMensual, units.indiviso_percentage as Indiviso, sections.name as Seccion')
                           ->join('sections', 'sections.id = units.section_id', 'left')
                           ->orderBy('units.unit_number', 'ASC')
                           ->findAll();

        $filename = 'Plantilla_Unidades_' . date('Y-m-d') . '.csv';

        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; charset=UTF-8");

        $file = fopen('php://output', 'w');
        // UTF-8 BOM para Excel
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
        
        $header = ['nombre', 'cuota mensual', '% indiviso', 'sección'];
        fputcsv($file, $header);

        foreach ($units as $u) {
            fputcsv($file, [
                $u['Nombre'],
                $u['CuotaMensual'],
                $u['Indiviso'] ?? '',
                $u['Seccion'] ?? ''
            ]);
        }

        fclose($file);
        exit;
    }

    /**
     * Importa/Previsualiza CSV
     */
    public function importCSV()
    {
        $file = $this->request->getFile('file_csv');
        if (!$file->isValid()) {
            return $this->response->setJSON(['status' => 400, 'error' => 'Archivo no válido']);
        }

        $unitsPreview = [];
        if (($handle = fopen($file->getTempName(), "r")) !== FALSE) {
            $header = fgetcsv($handle, 1000, ","); // Saltar cabecera
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if(!empty($data[0])){
                    $unitsPreview[] = [
                        'name' => $data[0],
                        'fee'  => $data[1] ?? 0,
                        'floor' => $data[3] ?? '',
                        'section' => $data[4] ?? ''
                    ];
                }
            }
            fclose($handle);
        }

        return $this->response->setJSON(['status' => 200, 'preview' => $unitsPreview]);
    }

    /**
     * Edición masiva
     */
    public function bulkUpdate()
    {
        $updates = $this->request->getJSON(true);
        if(empty($updates['units'])){
            return $this->response->setJSON(['status' => 400, 'error' => 'No hay datos para actualizar']);
        }
        
        $unitModel = new UnitModel();
        // Usamos updateBatch para optimizar
        $unitModel->updateBatch($updates['units'], 'id');
        
        return $this->response->setJSON(['status' => 200, 'message' => count($updates['units']).' unidades actualizadas']);
    }

    // ==========================================
    // MÉTODOS WEB (FORM SUBMITS)
    // ==========================================

    public function createWeb()
    {
        // Forzamos el Tenant por ahora hasta conectar el middleware
        $demoCondo = (new \App\Models\Tenant\CondominiumModel())->first();
        if ($demoCondo) \App\Services\TenantService::getInstance()->setTenantId((int)$demoCondo['id']);

        $data = [
            'condominium_id'      => $demoCondo['id'] ?? 1,
            'section_id'          => $this->request->getPost('section_id') ?: null,
            'unit_number'         => $this->request->getPost('unit_number'),
            'type'                => $this->request->getPost('type') ?? 'apartment',
            'floor'               => $this->request->getPost('floor'),
            'area'                => $this->request->getPost('area') ?: null,
            'indiviso_percentage' => $this->request->getPost('indiviso_percentage') ?: null,
            'maintenance_fee'     => $this->request->getPost('maintenance_fee') ?: 0,
            'occupancy_type'      => $this->request->getPost('occupancy_type') ?? 'owner_occupied',
            'fee_start_month'     => $this->request->getPost('fee_start_month')
        ];
        $unitModel = new UnitModel();

        // VALIDACIÓN PREMIUM: No duplicados (Nombre, Piso, Torre/Sección) en el mismo condominio
        $queryDuplicate = $unitModel->where('unit_number', strtoupper(trim((string)$data['unit_number'])))
                                    ->where('floor', (string)$data['floor'])
                                    ->where('condominium_id', $data['condominium_id']);
        
        if ($data['section_id'] === null) {
            $queryDuplicate->where('section_id IS NULL', null, false);
        } else {
            $queryDuplicate->where('section_id', $data['section_id']);
        }

        if ($queryDuplicate->first()) {
            return redirect()->back()->withInput()->with('swal_error', '¡Ups! Ya existe una unidad con ese nombre en el mismo piso y torre.');
        }

        // VALIDACIÓN DE PLAN (Paywall)
        $capacityCheck = $this->checkPlanCapacity($data['condominium_id'], 1);
        if (!$capacityCheck['allowed']) {
            return redirect()->back()->withInput()->with('plan_limit_error', $capacityCheck['message']);
        }
        
        try {
            $unitModel->insert($data);
            return redirect()->to('/admin/unidades')->with('success', 'Unidad creada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->to('/admin/unidades')->with('error', 'Error al crear la unidad (Posible nomenclatura duplicada).');
        }
    }

    public function updateWeb()
    {
        $id = $this->request->getPost('id');
        if (!$id) return redirect()->to('/admin/unidades')->with('error', 'Identificador de la unidad no proporcionado.');

        // Forzamos el Tenant por ahora
        $demoCondo = (new \App\Models\Tenant\CondominiumModel())->first();
        if ($demoCondo) \App\Services\TenantService::getInstance()->setTenantId((int)$demoCondo['id']);

        $data = [
            'section_id'          => $this->request->getPost('section_id') ?: null,
            'unit_number'         => $this->request->getPost('unit_number'),
            'type'                => $this->request->getPost('type'),
            'floor'               => $this->request->getPost('floor'),
            'area'                => $this->request->getPost('area') ?: null,
            'indiviso_percentage' => $this->request->getPost('indiviso_percentage') ?: null,
            'maintenance_fee'     => $this->request->getPost('maintenance_fee') ?: 0,
            'occupancy_type'      => $this->request->getPost('occupancy_type') ?? 'owner_occupied',
            'fee_start_month'     => $this->request->getPost('fee_start_month')
        ];

        $unitModel = new UnitModel();

        // VALIDACIÓN PREMIUM: No duplicados (Nombre, Piso, Torre/Sección)
        $queryDuplicate = $unitModel->where('unit_number', strtoupper(trim((string)$data['unit_number'])))
                                    ->where('floor', (string)$data['floor'])
                                    ->where('condominium_id', $demoCondo['id'] ?? 1)
                                    ->where('id !=', $id);
        
        if ($data['section_id'] === null) {
            $queryDuplicate->where('section_id IS NULL', null, false);
        } else {
            $queryDuplicate->where('section_id', $data['section_id']);
        }

        if ($queryDuplicate->first()) {
            return redirect()->back()->withInput()->with('swal_error', '¡Ups! Ya existe una unidad con ese nombre en el mismo piso y torre.');
        }
        
        try {
            $db = \Config\Database::connect();
            $db->transStart();
            
            $unitModel->update($id, $data);
            
            // Sync Residents — UNLINK instead of DELETE
            $owners = json_decode($this->request->getPost('owners'), true) ?? [];
            $tenants = json_decode($this->request->getPost('tenants'), true) ?? [];
            
            // 1. Desvincular TODOS los residentes actuales de esta unidad (no eliminar)
            $db->table('residents')
               ->where('unit_id', $id)
               ->update(['unit_id' => null]);
            
            // 2. Re-vincular los que siguen asignados (o crear nuevos)
            $condoId = $demoCondo['id'] ?? 1;
            
            foreach($owners as $owner) {
                if(isset($owner['user_id'])) {
                    // Buscar si ya existe un registro de este usuario en este condominio
                    $existing = $db->table('residents')
                        ->where('user_id', $owner['user_id'])
                        ->where('condominium_id', $condoId)
                        ->get()->getRow();
                    
                    if ($existing) {
                        $db->table('residents')
                           ->where('id', $existing->id)
                           ->update(['unit_id' => $id, 'type' => 'owner', 'is_active' => 1]);
                    } else {
                        $db->table('residents')->insert([
                            'condominium_id' => $condoId,
                            'user_id' => $owner['user_id'],
                            'unit_id' => $id,
                            'type' => 'owner',
                            'is_active' => 1
                        ]);
                    }
                }
            }
            foreach($tenants as $tenant) {
                if(isset($tenant['user_id'])) {
                    $existing = $db->table('residents')
                        ->where('user_id', $tenant['user_id'])
                        ->where('condominium_id', $condoId)
                        ->get()->getRow();
                    
                    if ($existing) {
                        $db->table('residents')
                           ->where('id', $existing->id)
                           ->update(['unit_id' => $id, 'type' => 'tenant', 'is_active' => 1]);
                    } else {
                        $db->table('residents')->insert([
                            'condominium_id' => $condoId,
                            'user_id' => $tenant['user_id'],
                            'unit_id' => $id,
                            'type' => 'tenant',
                            'is_active' => 1
                        ]);
                    }
                }
            }
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                return redirect()->to('/admin/unidades')->with('error', 'Error al actualizar la unidad.');
            }
            
            return redirect()->to('/admin/unidades')->with('success', 'Unidad actualizada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->to('/admin/unidades')->with('error', 'Error al actualizar la unidad: ' . $e->getMessage());
        }
    }

    public function deleteWeb($id)
    {
        $demoCondo = (new \App\Models\Tenant\CondominiumModel())->first();
        if ($demoCondo) \App\Services\TenantService::getInstance()->setTenantId((int)$demoCondo['id']);

        $unitModel = new UnitModel();
        $unit = $unitModel->find($id);

        if (!$unit || $unit['condominium_id'] != ($demoCondo['id'] ?? 1)) {
            return redirect()->to('/admin/unidades')->with('error', 'Unidad no encontrada o no autorizada');
        }

        try {
            $unitModel->delete($id);
            return redirect()->to('/admin/unidades')->with('success', 'Unidad eliminada exitosamente');
        } catch (\Exception $e) {
            return redirect()->to('/admin/unidades')->with('error', 'No se pudo eliminar la unidad: ' . $e->getMessage());
        }
    }

    public function deleteUnitJson($id)
    {
        $demoCondo = (new \App\Models\Tenant\CondominiumModel())->first();
        if ($demoCondo) \App\Services\TenantService::getInstance()->setTenantId((int)$demoCondo['id']);

        $unitModel = new UnitModel();
        $unit = $unitModel->find($id);

        if (!$unit || $unit['condominium_id'] != ($demoCondo['id'] ?? 1)) {
            return $this->failNotFound('Unidad no encontrada o no autorizada');
        }

        try {
            $unitModel->delete($id);
            return $this->respond([
                'status'  => 200,
                'message' => 'Unidad eliminada correctamente'
            ]);
        } catch (\Exception $e) {
            return $this->fail('No se pudo eliminar la unidad: ' . $e->getMessage());
        }
    }

    public function getNotes($unitId)
    {
        $noteModel = new UnitNoteModel();
        $notes = $noteModel->getNotesWithUsers((int)$unitId);
        
        return $this->respond([
            'status' => 200,
            'data'   => $notes
        ]);
    }

    public function addNote()
    {
        $unitId = $this->request->getPost('unit_id');
        $noteText = $this->request->getPost('note');

        if (!$unitId || !$noteText) {
            return $this->fail('Datos incompletos');
        }

        // Obtener Tenant Context
        $demoCondo = (new \App\Models\Tenant\CondominiumModel())->first();
        
        $noteModel = new UnitNoteModel();
        $data = [
            'unit_id'        => $unitId,
            'condominium_id' => $demoCondo['id'] ?? 1,
            'user_id'        => 1, // Mock del admin por ahora (Daniel)
            'note'           => $noteText
        ];

        if ($noteModel->insert($data)) {
            $newNote = $noteModel->getNotesWithUsers((int)$unitId); // Recargar para traer con User info
            return $this->respond([
                'status' => 200,
                'data'   => $newNote[0] // La más reciente por el order DESC
            ]);
        }

        return $this->fail('Error al guardar la nota');
    }

    public function deleteNote()
    {
        $noteId = $this->request->getPost('note_id');
        if (!$noteId) {
            return $this->fail('ID de nota requerido');
        }

        $noteModel = new UnitNoteModel();
        if ($noteModel->delete($noteId)) {
            return $this->respond([
                'status'  => 200,
                'message' => 'Nota eliminada correctamente'
            ]);
        }

        return $this->fail('Error al eliminar la nota');
    }

    public function previewImportCSV()
    {
        $demoCondo = (new \App\Models\Tenant\CondominiumModel())->first();
        if ($demoCondo) \App\Services\TenantService::getInstance()->setTenantId((int)$demoCondo['id']);
        $condoId = $demoCondo['id'] ?? 1;

        $file = $this->request->getFile('file_csv');
        if (!$file->isValid()) {
            return $this->response->setJSON(['status' => 400, 'error' => 'Archivo no válido']);
        }

        $unitModel = new UnitModel();
        $sectionModel = new SectionModel();

        // Existing units for comparison
        $existingUnitsArray = $unitModel->select('units.*, sections.name as section_name')
                                       ->join('sections', 'sections.id = units.section_id', 'left')
                                       ->where('units.condominium_id', $condoId)
                                       ->findAll();
        $existingUnitNumbers = array_column($existingUnitsArray, 'unit_number');
        $existingUnitsMap = [];
        foreach ($existingUnitsArray as $eu) {
            $existingUnitsMap[$eu['unit_number']] = $eu;
        }

        // Section map for name resolution
        $existingSections = $sectionModel->where('condominium_id', $condoId)->findAll();
        $sectionMap = [];
        foreach ($existingSections as $s) {
            $sectionMap[strtolower(trim($s['name']))] = $s;
        }

        $csvUnitNumbers = [];
        $stats = [
            'new' => 0,
            'updated' => 0,
            'nochange' => 0,
            'removed' => 0,
            'total_after' => 0
        ];
        
        $previewData = [];

        if (($handle = fopen($file->getTempName(), "r")) !== FALSE) {
            $header = fgetcsv($handle, 1000, ","); 
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if(!empty(trim($data[0] ?? ''))){
                    $unitNumber = trim($data[0]);
                    $csvUnitNumbers[] = $unitNumber;
                    
                    $csvFee = (string)($data[1] ?? '0');
                    $csvIndiviso = (string)($data[2] ?? '');
                    $csvSection = (string)($data[3] ?? '');
                    
                    $isNew = !isset($existingUnitsMap[$unitNumber]);
                    $changes = [];
                    
                    if ($isNew) {
                        $stats['new']++;
                        $status = 'new';
                    } else {
                        // Compare fields to detect changes
                        $existing = $existingUnitsMap[$unitNumber];
                        
                        $oldFee = (float)$existing['maintenance_fee'];
                        $newFee = (float)str_replace(['$', ','], '', $csvFee);
                        if ($oldFee != $newFee) {
                            $changes[] = "Cuota: $" . number_format($oldFee, 0) . " → $" . number_format($newFee, 0);
                        }
                        
                        $oldIndiviso = (string)($existing['indiviso_percentage'] ?? '');
                        $newIndiviso = $csvIndiviso;
                        if ($oldIndiviso !== $newIndiviso && !($oldIndiviso === '' && $newIndiviso === '')) {
                            $changes[] = "Indiviso: " . ($oldIndiviso ?: '-') . "% → " . ($newIndiviso ?: '-') . "%";
                        }
                        
                        $oldSection = strtolower(trim($existing['section_name'] ?? ''));
                        $newSection = strtolower(trim($csvSection));
                        if ($oldSection !== $newSection) {
                            $changes[] = "Sección: " . ($existing['section_name'] ?: '-') . " → " . ($csvSection ?: '-');
                        }
                        
                        if (empty($changes)) {
                            $stats['nochange']++;
                            $status = 'nochange';
                        } else {
                            $stats['updated']++;
                            $status = 'updated';
                        }
                    }

                    $previewData[] = [
                        'status' => $status,
                        'name' => $unitNumber,
                        'fee' => str_replace(['$', ','], '', $csvFee),
                        'indiviso' => $csvIndiviso ?: '—',
                        'section' => $csvSection ?: '—',
                        'changes' => $changes
                    ];
                }
            }
            fclose($handle);
        }

        // Units to remove
        $removedUnitsCount = 0;
        foreach ($existingUnitNumbers as $eu) {
            if (!in_array($eu, $csvUnitNumbers)) {
                $removedUnitsCount++;
            }
        }
        $stats['removed'] = $removedUnitsCount;
        $stats['total_after'] = count($csvUnitNumbers);

        return $this->response->setJSON([
            'status' => 200, 
            'stats' => $stats, 
            'preview' => $previewData
        ]);
    }

    public function processImportCSV()
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Forzamos el Tenant por ahora
            $demoCondo = (new \App\Models\Tenant\CondominiumModel())->first();
            if ($demoCondo) {
                \App\Services\TenantService::getInstance()->setTenantId((int)$demoCondo['id']);
            }
            $condoId = \App\Services\TenantService::getInstance()->getTenantId();

            $file = $this->request->getFile('file_csv');
            if (!$file || !$file->isValid()) {
                throw new \Exception('Archivo CSV no subido o inválido.');
            }

            $unitModel = new UnitModel();
            $sectionModel = new SectionModel();
            
            // Cargar secciones actuales para mapeo por nombre
            $existingSections = $sectionModel->where('condominium_id', $condoId)->findAll();
            $sectionMap = [];
            foreach ($existingSections as $s) {
                $sectionMap[strtolower(trim($s['name']))] = $s['id'];
            }

            $importedCount = 0;
            $importedIds = [];

            if (($handle = fopen($file->getTempName(), "r")) !== FALSE) {
                $header = fgetcsv($handle, 1000, ","); // Saltar cabecera
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if(!empty($data[0])){
                        // Normalización defensiva
                        $unitNumber = strtoupper(trim((string)$data[0]));
                        
                        // 1. Determinar section_id
                        $sectionId = null;
                        if (isset($data[3]) && !empty(trim((string)$data[3]))) {
                            $sName = strtolower(trim((string)$data[3]));
                            if (isset($sectionMap[$sName])) {
                                $sectionId = $sectionMap[$sName];
                            }
                        }

                        // 2. Comprobar si existe localmente (Búsqueda limpia)
                        $unitModel->builder()->resetQuery();
                        $unitModel->where('unit_number', $unitNumber);
                        
                        if ($sectionId === null) {
                            $unitModel->where('section_id IS NULL', null, false);
                        } else {
                            $unitModel->where('section_id', $sectionId);
                        }
                        
                        // El condominium_id se aplica solo vía BaseTenantModel (applyTenantScope)
                        $existing = $unitModel->first();

                        $unitData = [
                            'condominium_id'      => $condoId,
                            'unit_number'         => $unitNumber,
                            'maintenance_fee'     => (float) str_replace(['$', ','], '', (string)($data[1] ?? 0)),
                            'indiviso_percentage' => (string)($data[2] ?? null),
                            'type'                => 'apartment',
                            'section_id'          => $sectionId
                        ];

                        if($existing) {
                            $unitModel->update($existing['id'], $unitData);
                            $importedIds[] = $existing['id'];
                        } else {
                            // VALIDACIÓN DE PLAN (Paywall) por cada nueva unidad
                            $capacityCheck = $this->checkPlanCapacity($condoId, 1);
                            if (!$capacityCheck['allowed']) {
                                throw new \Exception($capacityCheck['message']);
                            }

                            $newId = $unitModel->insert($unitData);
                            if (!$newId) {
                                throw new \Exception("Error al insertar unidad: $unitNumber. Revise duplicados.");
                            }
                            $importedIds[] = $newId;
                        }
                        $importedCount++;
                    }
                }
                fclose($handle);
                
                // Eliminar unidades que no están en el CSV (Reemplazo completo por ID)
                if (!empty($importedIds)) {
                    $unitModel->builder()->resetQuery();
                    $unitModel->whereNotIn('id', $importedIds)->delete();
                }
            }

            $db->transComplete();
            
            if ($db->transStatus() === FALSE) {
                throw new \Exception('La transacción falló en la base de datos.');
            }

            return redirect()->to('/admin/unidades')
                             ->with('success', "Importación de $importedCount unidades completada con éxito.")
                             ->with('swal_success', "Importación completada con éxito");

        } catch (\Exception $e) {
            $db->transRollback();
            $msg = $e->getMessage();
            if (strpos($msg, 'límite de tu plan') !== false) {
                return redirect()->to('/admin/unidades')->with('plan_limit_error', $msg);
            }
            return redirect()->to('/admin/unidades')->with('error', 'Error en la importación: ' . $msg);
        }
    }

    public function searchUsersWeb()
    {
        $q = $this->request->getGet('q');
        if (!$q || strlen($q) < 2) {
            return $this->response->setJSON(['status' => 400, 'error' => 'Término de búsqueda muy corto']);
        }

        $userModel = new \App\Models\Core\UserModel();
        // Buscar usuarios por nombre, apellido o email
        $users = $userModel->select('id as user_id, first_name, last_name, email')
                           ->groupStart()
                               ->like('first_name', $q)
                               ->orLike('last_name', $q)
                               ->orLike('email', $q)
                           ->groupEnd()
                           ->limit(10)
                           ->find();
                           
        $formatted = [];
        foreach($users as $u) {
            $formatted[] = [
                'user_id' => $u['user_id'],
                'name' => trim($u['first_name'] . ' ' . $u['last_name']),
                'email' => $u['email']
            ];
        }

        return $this->response->setJSON(['status' => 200, 'data' => $formatted]);
    }

    public function createManualUserWeb()
    {
        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');
        $phone = $this->request->getPost('phone');
        
        if (empty($name)) {
            return $this->response->setJSON(['status' => 400, 'error' => 'El nombre es requerido']);
        }

        $userModel = new \App\Models\Core\UserModel();
        
        // Verificamos si existe el correo para no fallar por unique constraint
        if (!empty($email)) {
             $exist = $userModel->where('email', $email)->first();
             if($exist) {
                   return $this->response->setJSON(['status' => 400, 'error' => 'Ya existe un usuario con este correo electrónico']);
             }
        }
        
        // Extraemos nombre y apellido de forma simple
        $parts = explode(' ', trim($name));
        $firstName = $parts[0];
        $lastName = count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : '';
        
        $insertData = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => empty($email) ? null : $email,
            'phone' => empty($phone) ? null : $phone,
            'password_hash' => password_hash('Temporal123!', PASSWORD_BCRYPT), // Contraseña dummy por requerimiento de DB
            'status' => 'active'
        ];

        try {
            $userId = $userModel->insert($insertData, true);
            if($userId) {
                return $this->response->setJSON([
                    'status' => 200, 
                    'data' => [
                        'user_id' => $userId,
                        'name' => $name,
                        'email' => $email ?? ''
                    ]
                ]);
            } else {
                 return $this->response->setJSON(['status' => 500, 'error' => 'No se pudo insertar el usuario']);
            }
        } catch(\Exception $e) {
            return $this->response->setJSON(['status' => 500, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Valida la capacidad del plan de suscripción.
     * Retorna array con ['allowed' => bool, 'message' => string]
     */
    private function checkPlanCapacity(int $condoId, int $unitsToAdd = 1): array
    {
        $db = \Config\Database::connect();
        $condo = $db->table('condominiums')->where('id', $condoId)->get()->getRowArray();
        
        if (empty($condo) || empty($condo['plan_id'])) {
            // Límite generoso por defecto si no hay plan asignado (Legacy/Testing)
            $maxUnits = 50; 
        } else {
            $plan = $db->table('plans')->where('id', $condo['plan_id'])->get()->getRowArray();
            $maxUnits = $plan ? (int)$plan['max_units'] : 50;
        }

        $currentUnits = $db->table('units')->where('condominium_id', $condoId)->countAllResults();

        if (($currentUnits + $unitsToAdd) > $maxUnits) {
            return [
                'allowed' => false,
                'message' => "Has alcanzado el límite de tu plan actual ($currentUnits / $maxUnits unidades). Para añadir más, necesitas mejorar tu plan desde Configuración > Suscripción."
            ];
        }

        return ['allowed' => true];
    }
}
