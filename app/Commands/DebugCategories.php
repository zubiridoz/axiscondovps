<?php
namespace App\Commands;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class DebugCategories extends BaseCommand {
    protected $group = 'Debug';
    protected $name = 'debug:categories';

    public function run(array $params) {
        $db = \Config\Database::connect();
        $fields = $db->getFieldNames('financial_categories');
        CLI::write("Fields: " . implode(', ', $fields), 'cyan');
    }
}
