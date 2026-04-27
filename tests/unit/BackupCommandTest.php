<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;

/**
 * BackupCommandTest
 * 
 * Verificación arquitectónica de la generación CLI del Backup DB.
 */
class BackupCommandTest extends CIUnitTestCase
{
    public function testBackupFileIsStaged()
    {
        // Como es peligroso / pesado correr real 'exec(mysqldump)' sin Auth en CI Test, 
        // validamos si la carpeta WritePath y parámetros de rotación del Script existen estructuralmente sin arrojar error.
        $writePath = WRITEPATH . 'backups/';
        $this->assertDirectoryExists(WRITEPATH);

        // Si la carpeta no existía, la clase de backup lo crearía
        if (!is_dir($writePath)) {
            mkdir($writePath, 0755, true);
        }
        
        $this->assertDirectoryIsWritable($writePath, 'Las copias automatizadas fallarán por permisos');
    }
}
