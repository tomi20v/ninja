<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

define('APP_ROOT', dirname(__FILE__));

require(APP_ROOT . '/../../vendor/autoload.php');

\Maui::instance(\Maui::ENV_DEFAULT, 'ninja');

$Request = \Request::instance();
echop($Request);
//$Page = \Router::PageFromRequest($Request);
$Page = new \PageModule($Request, null);
echop($Page);
$Response = $Page->run();

echop($Response);

die('ALL OK');
