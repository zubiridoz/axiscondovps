<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Tenant\SectionModel;

/**
 * SectionController
 * 
 * Gestión de las secciones (torres, clústeres, bloques) del condominio.
 */
class SectionController extends BaseController
{
    /**
     * Lista todas las secciones
     */
    public function index()
    {
        $sectionModel = new SectionModel();
        // findAll() ya filtra automáticamente 'condominium_id' gracias a BaseTenantModel.
        $sections = $sectionModel->findAll();

        return $this->response->setJSON(['status' => 200, 'data' => $sections]);
    }

    /**
     * Crea una nueva sección
     */
    public function create()
    {
        $name = $this->request->getPost('name');

        if (!$this->validate(['name' => 'required'])) {
            return $this->response->setJSON(['status' => 400, 'error' => 'El nombre es requerido']);
        }

        $sectionModel = new SectionModel();
        // El condominium_id se inyectará automáticamente en el beforeInsert del Model
        $sectionId = $sectionModel->insert([
            'name' => $name
        ]);

        return $this->response->setJSON(['status' => 201, 'message' => 'Sección creada', 'id' => $sectionId]);
    }

    /**
     * Edita una sección existente
     */
    public function update($id = null)
    {
        if (!$id) return $this->response->setJSON(['status' => 400, 'error' => 'ID no proporcionado']);

        // Como usamos method spoofing o form data (PUT/PATCH)
        $name = $this->request->getVar('name');

        $sectionModel = new SectionModel();
        
        // Verifica que la sección le pertenezca al Tenant antes de actualizar
        $section = $sectionModel->find($id);
        if (!$section) {
            return $this->response->setJSON(['status' => 404, 'error' => 'Sección no encontrada en este condominio']);
        }

        $sectionModel->update($id, ['name' => $name]);

        return $this->response->setJSON(['status' => 200, 'message' => 'Sección actualizada exitosamente']);
    }

    /**
     * Elimina una sección
     */
    public function delete($id = null)
    {
        if (!$id) return $this->response->setJSON(['status' => 400, 'error' => 'ID no proporcionado']);

        $sectionModel = new SectionModel();
        
        // El BaseTenantModel protege que no se borre una sección de otro edificio
        if ($sectionModel->delete($id)) {
            return $this->response->setJSON(['status' => 200, 'message' => 'Sección eliminada']);
        }

        return $this->response->setJSON(['status' => 500, 'error' => 'No se pudo eliminar la sección']);
    }
}
