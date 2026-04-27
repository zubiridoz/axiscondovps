<?php
$file = 'app/Controllers/MediaController.php';
$content = file_get_contents($file);

$content = str_replace(
    '$dirs = ["staff", "announcements", "avatars", "vehicles", "access", "payments", "condominiums", "condominiums/1", "condominiums/2"];
            $found = false;
            foreach ($dirs as $dir) {
                if (is_file(WRITEPATH . "uploads/" . $dir . "/" . $file)) {
                    $segments = [$dir, $file];
                    $found = true;
                    break;
                }
            }',
    '$dirs = ["staff", "announcements", "avatars", "vehicles", "access", "payments"];
            $found = false;
            foreach ($dirs as $dir) {
                if (is_file(WRITEPATH . "uploads/" . $dir . "/" . $file)) {
                    $segments = [$dir, $file];
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                // Auto-detect by searching the file recursively inside uploads/
                try {
                    $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(WRITEPATH . "uploads/"));
                    foreach ($iterator as $info) {
                        if ($info->isFile() && $info->getFilename() === $file) {
                            $segments = explode("/", str_replace(WRITEPATH . "uploads/", "", str_replace("\\\\", "/", $info->getPathname())));
                            $found = true;
                            break;
                        }
                    }
                } catch (\Exception $e) {}
            }',
    $content
);
file_put_contents($file, $content);
