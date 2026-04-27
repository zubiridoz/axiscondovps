<?php
$file = 'app/Controllers/Admin/MediaController.php';
$content = file_get_contents($file);

$content = str_replace(
    '        // El MediaController de Admin espera que el folder sea explícito, ej: admin/anuncios/archivo/announcements/file.jpg 
        // Si no, le añadimos lógica fallback:
        $fullPath = WRITEPATH . "uploads" . DIRECTORY_SEPARATOR . $filename;
        if (!is_file($fullPath) && count($segments) === 2) {
             // fallback
        }',
    '        // Fallback robusto
        $fullPath = WRITEPATH . "uploads" . DIRECTORY_SEPARATOR . $filename;
        if (!is_file($fullPath)) {
            // Asumir que la carpeta podría no estar en el segment si la URL era directa
            if (count($segments) === 1) {
                // Iterar recursivamente
                try {
                    $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(WRITEPATH . "uploads/"));
                    foreach ($iterator as $info) {
                        if ($info->isFile() && $info->getFilename() === $segments[0]) {
                            $fullPath = $info->getPathname();
                            break;
                        }
                    }
                } catch (\Exception $e) {}
            } elseif (count($segments) === 2) {
                // ej segments = [announcements, ann_69...jpg] pero el archivo quizas esta en announcements/1/ann_69...jpg
                // Iterar recursivamente dentro de esa carpeta
                try {
                    $dirPath = WRITEPATH . "uploads/" . $segments[0] . "/";
                    if (is_dir($dirPath)) {
                        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dirPath));
                        foreach ($iterator as $info) {
                            if ($info->isFile() && $info->getFilename() === $segments[1]) {
                                $fullPath = $info->getPathname();
                                break;
                            }
                        }
                    }
                } catch (\Exception $e) {}
            }
        }',
    $content
);
file_put_contents($file, $content);
