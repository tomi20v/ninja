<?php

namespace ninja;

class ModAdminMenuPlugin extends \ModAbstractPlugin {
	public static function modAdminMenuGetItems()
	{
		return [
			new \ModNavItem([
				'href' => '#',
				'label' => 'Dashboard',
				'faIcon' => 'dashboard',
			]),
		];
	}


}
