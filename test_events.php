<?php
define('FCPATH', __DIR__ . '/public/');
chdir(__DIR__);
require 'public/index.php'; // or spark

// Since index.php runs the app, we should bootstrap manually or just use spark
