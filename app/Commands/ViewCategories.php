<?php
namespace App\Commands;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ViewCategories extends BaseCommand {
    protected $group = 'Debug';
    protected $name = 'db:view_categories';

    public function run(array $params) {
        $db = \Config\Database::connect();
        $categories = $db->table('financial_categories')->get()->getResultArray();
        print_r($categories);
    }
}
