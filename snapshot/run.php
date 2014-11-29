#!/usr/bin/php
<?php

$t0 = microtime(true);

ini_set('display_errors', 1);
error_reporting(E_ALL);

define('TEST_ROOT', dirname(__FILE__));

$Autoloader = require(TEST_ROOT . '/../vendor/autoload.php');

$result = \Tester::run();

echo "\n\n" . $result['testCnt'] . ' tests run in ' . $result['classCnt'] . ' classes ' . $result['methodCnt'] . ' methods' . "\n\n";

if (!$result['success']) {
	echo "ERRORS found...\n\n";
	echop($result['errors'], 0, 2, 4, 0, 0);
	echo "FAIL\n\n";
}
else {
	echo "ALL OK\n\n";
}
