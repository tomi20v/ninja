<?php

namespace ninja;

/**
 * Class ModAdminMenuPlugin
 *
 * @package ninja
 */
abstract class ModAdminMenuPlugin extends \ModAbstractPlugin {

	public static function modAdminMenuGetItems()
	{
		return [
			new \ModNavItem([
				'href' => 'dash',
				'label' => 'Dashboard',
				'icon' => 'dashboard',
			]),
		];
	}

}
