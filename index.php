<?php
// Cargar variables de entorno
$env = parse_ini_file(__DIR__ . '/.env');

foreach ($env as $key => $value){
    $_ENV[$key] = $value;
}

require_once("db.php");
require_once('controllers/mainController.php');
?>