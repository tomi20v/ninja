<?php

$t0 = microtime(true);

ini_set('display_errors', 1);
error_reporting(E_ALL);

define('APP_ROOT', dirname(__FILE__));

require(APP_ROOT . '/../../../vendor/autoload.php');

$t1 = microtime(true);
$Maui = \Maui::instance(\Maui::ENV_DEFAULT, 'ninja');
$Ninja = new \Ninja($Maui);
$Ninja->run();
$t2 = microtime(true);

//echop(($t2-$t0) . ' sec');
//echop(($t2-$t1) . ' sec');
