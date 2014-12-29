<?php

namespace ninja;

/**
 * Class ModAbstractPlugin - a module's plugin class exposes its hooks to be picked up by other mods
 * 		this class shall have a prototype for all plugin methods
 * @package ninja
 */
abstract class ModAbstractPlugin {

	/**
	 * @return \ModNavItem[] I returns items to be displayed in admin menu
	 */
	public static function modAdminMenuGetItems(){}

}