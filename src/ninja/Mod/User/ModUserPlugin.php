<?php

namespace ninja;

class ModUserPlugin extends \ModAbstractPlugin {

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
