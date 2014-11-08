<?php

if (!defined('NINJA_ROOT')) {
	define('NINJA_ROOT', dirname(__FILE__));
}
if (!defined('APP_ROOT')) {
	define('APP_ROOT', NINJA_ROOT . '/app');
}

function Ninja_autoload($classname) {
	if (!strrpos($classname, '\\') &&
		class_exists($originalClassname = 'ninja\\' . trim($classname, '\\'))) {
		class_alias($originalClassname, $classname);
	}
}

spl_autoload_register('Ninja_autoload');
