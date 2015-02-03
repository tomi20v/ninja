<?php

namespace ninja;

/**
 * Class ModAdminMenuPlugin
 *
 * @package ninja
 */
abstract class ModAdminPlugin extends \ModAbstractPlugin {

	/**
	 * @return \string[]
	 * 	modAdminGetImports() - return list of imports for the admin app
	 */
	public function getPluginMethods() {
		return [
			'modAdminGetImports',
		];
	}

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
