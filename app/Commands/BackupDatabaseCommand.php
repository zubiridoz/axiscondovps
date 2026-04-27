<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * BackupDatabaseCommand
 * 
 * Comando de Spark (CLI) para ejecutar copias de seguridad MySQL `mysqldump`.
 * Ideal para ser ejecutado por un crontab diario:
 * 0 2 * * * php /var/www/spark backup:database
 */
class BackupDatabaseCommand extends BaseCommand
{
    protected $group       = 'SaaS Infrastructure';
    protected $name        = 'backup:database';
    protected $description = 'Genera un dump MySQL limpio y retira respaldos antiguos (más de 30 días)';

    public function run(array $params)
    {
        CLI::write("Iniciando generación de Backup Base de Datos SaaS...", 'yellow');

        // Configuración de Servidor de BD
        $db = \Config\Database::connect();
        
        $host = $db->hostname;
        $user = $db->username;
        $pass = $db->password;
        $dbName = $db->database;

        // Rutas y nombres de archivo
        $backupDir = WRITEPATH . 'backups/';
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $filename = 'backup_axiscondo_' . date('Y_m_d_His') . '.sql';
        $filepath = $backupDir . $filename;

        // Armar comando mysqldump
        // NOTA: Se evita inyectar la clave directo en consola si es posible, pero para fines de este script se armará standard.
        // En un entorno de bash muy estricto usar MY_PWD=... o auth config files.
        $passStr = empty($pass) ? '' : "-p'{$pass}'";
        
        $command = sprintf(
            'mysqldump -h %s -u %s %s %s > %s',
            escapeshellarg($host),
            escapeshellarg($user),
            $passStr,
            escapeshellarg($dbName),
            escapeshellarg($filepath)
        );

        $output = [];
        $returnVar = 0;
        
        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            CLI::error("Ha ocurrido un error al intentar crear el dump MySQL (Cod: $returnVar). Valida que mysqldump esté instalado.");
            log_message('critical', "[BACKUPS] Error al general el volcado (Mysqldump Return Var: $returnVar)");
            return;
        }

        // Compresión básica para no abarrotar el disco
        exec("gzip " . escapeshellarg($filepath));
        $finalFile = $filepath . '.gz';

        if (file_exists($finalFile)) {
            $filesize = round(filesize($finalFile) / 1024 / 1024, 2); // MB
            CLI::write("¡Backup comprimido generado exitosamente! ($filesize MB)", 'green');
            CLI::write("Ruta: " . $finalFile);
        }

        // ----------------------------------------------------
        // POLÍTICA DE RETENCIÓN (ROTACIÓN A 30 DÍAS)
        // ----------------------------------------------------
        $this->rotateBackups($backupDir, 30);
    }

    /**
     * Barre la carpeta de backups y purga los archivos mayores a X días.
     */
    private function rotateBackups(string $directory, int $maxDays)
    {
        CLI::write("Ejecutando rotación de backups antiguos (Retención: $maxDays días)...");

        $now = time();
        $cleaned = 0;

        foreach (glob($directory . "backup_axiscondo_*.sql*") as $file) {
            if (is_file($file)) {
                $fileAge = $now - filemtime($file);
                
                // Si el archivo es más viejo que max_days en segundos (Dias * Horas * Minutos * Segundos)
                if ($fileAge > ($maxDays * 24 * 60 * 60)) {
                    unlink($file);
                    $cleaned++;
                    log_message('info', "[BACKUPS] Rotación automática: Archivo expirado borrado -> " . basename($file));
                }
            }
        }

        if ($cleaned > 0) {
            CLI::write("Limpieza finalizada. Eliminados: {$cleaned} backup(s) viejo(s)", 'yellow');
        } else {
            CLI::write("No existen backups para rotar aún.");
        }
    }
}
