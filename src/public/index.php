<?php
require_once('../../vendor/autoload.php');
require_once('../app/util/debug.php');
require_once('../app/config/config.php');

// (new \src\app\route\functions\RouterCore);

use \src\app\models\Querys;

Querys::select('produtos', '*', [
    'limit' => 1
]);

// SELECT * FROM `produtos` WHERE id LIKE '2%'
