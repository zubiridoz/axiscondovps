<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * SetupLocalCommand
 * 
 * Comando empaquetado y automatizado para levantar el ecosistema 
 * AxisCondo en modo Desarrollo/Test.
 *
 * Utilización:
 * > php spark setup:local
 */
class SetupLocalCommand extends BaseCommand
{
    protected $group       = 'SaaS Initialization';
    protected $name        = 'setup:local';
    protected $description = 'Prepara BD vacía con migraciones, purga data y siembra entidades demo funcionales.';

    public function run(array $params)
    {
        CLI::write("==================================================", "yellow");
        CLI::write("  Iniciando Arranque de AXISCONDO (Modo Test) ", "white", "green");
        CLI::write("==================================================", "yellow");

        // 1. Ejecutar las Migraciones Estructurales (Fases 1, 2, 3...)
        CLI::write("\n[1/2] Corriendo Migraciones (Drop + Create)...", "cyan");
        try {
            // Nota: En CI4 para borrar y rehacer se usa migrate:refresh
            // Advertencia: CUIDADO SI EJECUTAS ESTO EN LA NUBE.
            command('migrate:refresh');
            CLI::write("[OK] Migraciones aplicadas.", "green");
        } catch (\Exception $e) {
            CLI::error("Falló la migración: " . $e->getMessage());
            return;
        }

        // 2. Ejecutar el Poblado de la Base de Datos (Seeder)
        CLI::write("\n[2/2] Insertando Datos Demo...", "cyan");
        try {
            command('db:seed LocalTestSeeder');
            CLI::write("[OK] Base de datos sembrada con éxito.", "green");
        } catch (\Exception $e) {
            CLI::error("Falló el seeding: " . $e->getMessage());
            return;
        }

        // 3. Resumen y Credenciales de Operador
        CLI::write("\n==================================================", "yellow");
        CLI::write("  AXISCONDO ESTÁ LISTO PARA NAVEGAR ", "white", "green");
        CLI::write("==================================================", "yellow");
        
        CLI::write("\nCredenciales Maestras de Acceso:", "yellow");
        
        CLI::write("\n👔 SUPERADMIN (SaaS Owner):");
        CLI::write("Usuario:   superadmin@axiscondo.local");
        CLI::write("Password:  password123");
        CLI::write("URL:       /condominet26/superadmin/dashboard");

        CLI::write("\n🏢 ADMIN DEL CONDOMINIO DEMO:");
        CLI::write("Usuario:   admin@demo.com");
        CLI::write("Password:  password123");
        CLI::write("URL:       /condominet26/admin/dashboard");

        CLI::write("\n👤 RESIDENTE PWA (Unidad A101):");
        CLI::write("Usuario:   resident@demo.com");
        CLI::write("Password:  password123");
        CLI::write("URL:       /condominet26/pwa/resident/login.html");

        CLI::write("\n👮 GUARDIA PWA CASETA:");
        CLI::write("Usuario:   guard@demo.com");
        CLI::write("Password:  password123");
        CLI::write("URL:       /condominet26/pwa/security/login.html");

        CLI::write("\n!A probar todos los flujos de código!\n");
    }
}
