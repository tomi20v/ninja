<?php

define('NINJA_ROOT', dirname(__FILE__));

function Ninja_autoload($classname) {
	if (!strrpos($classname, '\\') &&
		class_exists($originalClassname = 'ninja\\' . trim($classname, '\\'))) {
		class_alias($originalClassname, $classname);
	}
}

spl_autoload_register('Ninja_autoload');
