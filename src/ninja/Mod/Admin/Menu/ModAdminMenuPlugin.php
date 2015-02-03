<?php

namespace ninja;

/**
 * Class ModAdminMenuPlugin
 *
 * @package ninja
 */
abstract class ModAdminMenuPlugin extends \ModAbstractPlugin {

	/**
	 * @return \string[] I have no plugin methods currently
	 * 	modAdminMenuGetItems() - return \ModNavItem objects in array for the admin menu
	 */
	public function getPluginMethods() {
		return [
			'modAdminMenuGetItems',
		];
	}

}
