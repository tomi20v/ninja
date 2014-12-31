<?php

$t0 = microtime(true);

ini_set('display_errors', 1);
error_reporting(E_ALL);

define('PUB_ROOT', dirname(__FILE__));

$Autoloader = require(PUB_ROOT . '/../../../vendor/autoload.php');
\Finder::setAutoLoader($Autoloader);

//\Tester::init(
//	[
//		'ModAbstractModule' => ['getHmvcUrlPath'],
//	]);
//

$t1 = microtime(true);
$Maui = \Maui::instance(\Maui::ENV_DEFAULT, 'ninja');
$Ninja = new \Ninja($Maui);
$Ninja->run();
$t2 = microtime(true);

//echop(($t2-$t0) . ' sec');
$k = intval(memory_get_peak_usage()/1024);

if (in_array($Ninja->getRequestedExtension(), ['html'])) {
	echo '/*';
	echop(substr(($t2 - $t1), 0, 5) . ' sec, ' . substr($k, 0, -3) . ',' . substr($k, -3) . 'K');
	echo '*/';
}
