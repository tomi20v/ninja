<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

define('APP_ROOT', dirname(__FILE__));

require(APP_ROOT . '/../../vendor/autoload.php');

$Maui = \Maui::instance(\Maui::ENV_DEFAULT, 'ninja');
$Ninja = new \Ninja($Maui);
$Ninja->run();

die('ALL OK');
