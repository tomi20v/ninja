<?php

namespace ninja;

class ModPagePlugin extends \ModAbstractPlugin {

	public static function modAdminMenuGetItems()
	{
		return [
			new \ModNavItem([
				'href' => 'page',
				'label' => 'Site structure',
				'icon' => 'apps',
			]),
		];
	}

}
