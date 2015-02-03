<?php

namespace ninja;

/**
 * Class ModAbstractPlugin - a module's plugin class exposes its hooks to be picked up by other mods
 * 		this class shall have a prototype for all plugin methods
 * @package ninja
 */
abstract class ModAbstractPlugin {

	/**
	 * @return string[] I shall return a simple list of plugin method names, which are used by that module
	 */
	abstract public function getPluginMethods();

}
