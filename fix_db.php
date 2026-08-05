<?php
require 'public/index.php'; // Boot CodeIgniter
$db = \Config\Database::connect();
$builder = $db->table('announcement_attachments');

// Fix Word Documents
$builder->where('file_type', 'image')
        ->groupStart()
            ->like('file_name', '%.doc', 'before')
            ->orLike('file_name', '%.docx', 'before')
            ->orLike('original_name', '%.doc', 'before')
            ->orLike('original_name', '%.docx', 'before')
        ->groupEnd()
        ->update(['file_type' => 'document']);
$affected1 = $db->affectedRows();

// Fix PDFs
$builder->where('file_type', 'image')
        ->groupStart()
            ->like('file_name', '%.pdf', 'before')
            ->orLike('original_name', '%.pdf', 'before')
        ->groupEnd()
        ->update(['file_type' => 'pdf']);
$affected2 = $db->affectedRows();

echo "Fixed $affected1 Word documents and $affected2 PDF documents.\n";
