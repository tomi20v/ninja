<?php

namespace ninja;

class ModUserPlugin extends \ModAbstractPlugin {

	/**
	 * @return \string[] I have no plugin methods currently
	 */
	public function getPluginMethods() {
		return [];
	}

	/**
	 * @return \ModNavItem[] I returns items to be displayed in admin nav
	 */
	public static function modAdminMenuGetItems()
	{
		return [
			new \ModNavItem([
				'href' => 'user',
				'label' => 'Users',
				'icon' => 'user',
			]),
		];
	}


}
