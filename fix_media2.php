<?php
$file = 'app/Controllers/MediaController.php';
$content = file_get_contents($file);
$content = str_replace(
    '}
            return $this->response->setStatusCode(404);
        }

        // Construir ruta segura sin traversal attacks',
    '}
            if (count($segments) === 1) { // Still 1 segment means file not found in subdirs
                return $this->response->setStatusCode(404);
            }
        } // End of empty($segments) validation

        // Construir ruta segura sin traversal attacks',
    $content
);
file_put_contents($file, $content);
