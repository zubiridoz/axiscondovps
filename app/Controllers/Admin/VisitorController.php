<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Tenant\VisitorModel;

/**
 * VisitorController
 * 
 * Registro formal de visitantes concurrentes del condominio.
 */
class VisitorController extends BaseController
{
    /**
     * Lista todos los visitantes
     */
    public function index()
    {
        $visitorModel = new VisitorModel();
        $visitors = $visitorModel->orderBy('created_at', 'DESC')->findAll();

        return $this->response->setJSON(['status' => 200, 'data' => $visitors]);
    }

    /**
     * Registra a un nuevo visitante recurrente / primerizo
     */
    public function register()
    {
        $data = [
            'full_name'       => $this->request->getPost('full_name'),
            'document_type'   => $this->request->getPost('document_type'), // INE, Pasaporte
            'document_number' => $this->request->getPost('document_number'),
            'phone'           => $this->request->getPost('phone'),
            'is_banned'       => 0
        ];

        if (empty($data['full_name']) || empty($data['document_number'])) {
            return $this->response->setJSON(['status' => 400, 'error' => 'Nombre y Documento son requeridos']);
        }

        $visitorModel = new VisitorModel();
        $visitorId = $visitorModel->insert($data);

        return $this->response->setJSON(['status' => 201, 'message' => 'Visitante registrado exitosamente', 'id' => $visitorId]);
    }

    /**
     * Búsqueda de visitante por nombre o número de placa/documento
     */
    public function search()
    {
        $query = $this->request->getGet('q');
        
        if (empty($query)) {
            return $this->response->setJSON(['status' => 400, 'error' => 'Parámetro de búsqueda "q" vacío']);
        }

        $visitorModel = new VisitorModel();
        // Usamos where/like limitados automáticamente a este condominio
        $results = $visitorModel->groupStart()
                                ->like('full_name', $query)
                                ->orLike('document_number', $query)
                                ->groupEnd()
                                ->findAll();

        return $this->response->setJSON(['status' => 200, 'data' => $results]);
    }

    /**
     * Muestra el historial (Información del registro del visitante)
     */
    public function history($id = null)
    {
        if (!$id) return $this->response->setJSON(['status' => 400, 'error' => 'ID no proporcionado']);

        $visitorModel = new VisitorModel();
        $visitor = $visitorModel->find($id);

        if (!$visitor) {
            return $this->response->setJSON(['status' => 404, 'error' => 'Visitante no encontrado']);
        }

        return $this->response->setJSON(['status' => 200, 'data' => $visitor]);
    }
}
