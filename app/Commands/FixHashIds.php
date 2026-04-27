<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class FixHashIds extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:fix_hash_ids';
    protected $description = 'Repara hash_ids nulos en la de tabla units.';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        $units = $db->table('units')->select('id, hash_id')->get()->getResultArray();

        $count = 0;
        foreach ($units as $u) {
            if (empty($u['hash_id'])) {
                $hash = bin2hex(random_bytes(12));
                $db->table('units')->where('id', $u['id'])->update(['hash_id' => $hash]);
                $count++;
            }
        }
        CLI::write("Actualizadas $count unidades con nuevo hash_id.", 'green');
    }
}
