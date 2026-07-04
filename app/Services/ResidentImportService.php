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
            
            // Default mapping assuming old template without section
            $map = [
                'name' => 0,
                'email' => 1,
                'phone' => 2,
                'unit' => 3,
                'section' => -1,
                'role' => 4
            ];
            
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Skip empty lines and comment lines starting with #
                if (empty($data) || (isset($data[0]) && strpos(trim($data[0]), '#') === 0)) {
                    continue;
                }
                
                // Detect header row
                if (!$headerFound && isset($data[0]) && strtolower(trim($data[0])) === 'nombre') {
                    $headerFound = true;
                    // Detect column indexes dynamically
                    foreach ($data as $index => $colName) {
                        $col = strtolower(trim($colName));
                        $col = str_replace(['ó','ó'], 'o', $col); // remove basic accents
                        
                        if ($col === 'nombre') $map['name'] = $index;
                        if ($col === 'correo') $map['email'] = $index;
                        if ($col === 'telefono' || $col === 'teléfono') $map['phone'] = $index;
                        if ($col === 'unidad') $map['unit'] = $index;
                        if ($col === 'seccion' || $col === 'sección') $map['section'] = $index;
                        if ($col === 'rol') $map['role'] = $index;
                    }
                    continue;
                }
                
                // Data rows
                if (count($data) >= 2) {
                    $rows[] = [
                        'name'    => isset($data[$map['name']]) ? trim($data[$map['name']]) : '',
                        'email'   => isset($data[$map['email']]) ? strtolower(trim($data[$map['email']])) : '',
                        'phone'   => isset($data[$map['phone']]) ? trim($data[$map['phone']]) : '',
                        'unit'    => isset($data[$map['unit']]) ? trim($data[$map['unit']]) : '',
                        'section' => ($map['section'] >= 0 && isset($data[$map['section']])) ? trim($data[$map['section']]) : '',
                        'role'    => isset($data[$map['role']]) ? $this->mapRole(trim($data[$map['role']])) : 'owner'
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
