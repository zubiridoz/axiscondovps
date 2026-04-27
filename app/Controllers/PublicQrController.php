<?php

namespace App\Controllers;

use App\Models\Tenant\QrCodeModel;
use App\Models\Tenant\CondominiumModel;
use App\Models\Tenant\UnitModel;
use App\Models\Core\UserModel;

class PublicQrController extends BaseController
{
    public function show($token)
    {
        $qrModel = new QrCodeModel();
        
        $qr = $qrModel->where('token', $token)->first();
        
        if (!$qr) {
            return view('errors/html/error_404', ['message' => 'El Pase QR no existe o ha sido eliminado.']);
        }
        
        // Extract Condominium
        $condoModel = new CondominiumModel();
        // Global system doesn't filter Condominium itself usually, but set tenant to be safe
        $condo = $condoModel->find($qr['condominium_id']);
        
        // Now set the tenant scope so Unit operations work correctly
        \App\Services\TenantService::getInstance()->setTenantId((int)$qr['condominium_id']);
        
        $unitModel = new UnitModel();
        $unit = $qr['unit_id'] ? $unitModel->find($qr['unit_id']) : null;
        
        // The UserModel is global / system level
        $userModel = new UserModel();
        $owner = $qr['created_by'] ? $userModel->find($qr['created_by']) : null;

        // Computar vigencia en tiempo real
        $now = new \DateTime('now', new \DateTimeZone('America/Mexico_City'));
        $validFrom = new \DateTime($qr['valid_from'], new \DateTimeZone('America/Mexico_City'));
        $validUntil = new \DateTime($qr['valid_until'], new \DateTimeZone('America/Mexico_City'));
        $isSingleEntry = ((int)($qr['usage_limit'] ?? 1) === 1);

        $isValidNow = true;
        $validityMessage = '';

        if (in_array($qr['status'], ['revoked', 'used', 'expired'])) {
            $isValidNow = false;
            $validityMessage = $qr['status'] === 'used' ? 'Este pase ya fue utilizado' : 'Este pase fue revocado o ha expirado';
        } elseif ($isSingleEntry) {
            $todayStr = $now->format('Y-m-d');
            $entryDateStr = $validFrom->format('Y-m-d');
            if ($todayStr < $entryDateStr) {
                $isValidNow = false;
                $validityMessage = 'Válido a partir del ' . $validFrom->format('d/m/Y');
            } elseif ($todayStr > $entryDateStr) {
                $isValidNow = false;
                $validityMessage = 'La fecha de acceso ya pasó';
            }
        } else {
            if ($now < $validFrom) {
                $isValidNow = false;
                $validityMessage = 'Válido a partir del ' . $validFrom->format('d/m/Y H:i');
            } elseif ($now > $validUntil) {
                $isValidNow = false;
                $validityMessage = 'El periodo de acceso ha finalizado';
            }
        }

        $data = [
            'qr'               => $qr,
            'condominium'      => $condo,
            'unit'             => $unit,
            'owner'            => $owner,
            'qrData'           => $token,
            'isValidNow'       => $isValidNow,
            'validityMessage'  => $validityMessage,
            'isSingleEntry'    => $isSingleEntry,
        ];

        return view('public/qr_pass', $data);
    }
}
