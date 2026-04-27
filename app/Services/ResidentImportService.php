<?php

namespace App\Services;

class ResidentImportService
{
    /**
     * Parsear CSV/XLSX básico (en este caso procesamos CSV para MVP, asume archivo fgetcsv)
     */
    public function parseCSV(string $filePath): array
    {
        $rows = [];
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            $headerFound = false;
            
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Skip empty lines and comment lines starting with #
                if (empty($data) || (isset($data[0]) && strpos(trim($data[0]), '#') === 0)) {
                    continue;
                }
                // Skip header row (nombre, correo, ...)
                if (!$headerFound && isset($data[0]) && strtolower(trim($data[0])) === 'nombre') {
                    $headerFound = true;
                    continue;
                }
                // Data rows: nombre, correo, telefono, unidad, rol
                if (count($data) >= 2) {
                    $rows[] = [
                        'name' => trim($data[0]),
                        'email' => strtolower(trim($data[1])),
                        'phone' => isset($data[2]) ? trim($data[2]) : '',
                        'unit' => isset($data[3]) ? trim($data[3]) : '',
                        'role' => isset($data[4]) ? $this->mapRole(trim($data[4])) : 'owner'
                    ];
                }
            }
            fclose($handle);
        }
        return $rows;
    }

    /**
     * Mapea el valor de texto del CSV al rol de BD
     */
    private function mapRole(string $inputRole): string
    {
        $input = strtolower($inputRole);
        if (strpos($input, 'propie') !== false) {
            return 'owner';
        }
        if (strpos($input, 'inqui') !== false) {
            return 'tenant';
        }
        if (strpos($input, 'admin') !== false) {
            return 'admin';
        }
        return 'owner'; // Default
    }
}
