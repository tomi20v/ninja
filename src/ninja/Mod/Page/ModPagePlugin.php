<?php

namespace ninja;

class ModPagePlugin extends \ModAbstractPlugin {

	/**
	 * @return \string[] I have no plugin methods currently
	 */
	public function getPluginMethods() {
		return [];
	}

	/**
	 * @return \ModNavItem[]
	 */
	public static function modAdminMenuGetItems() {
		return [
			new \ModNavItem([
				'href' => 'page',
				'label' => 'Site structure',
				'icon' => 'apps',
			]),
		];
	}

	/**
	 * @return string[]
	 */
	public static function modAdminGetImports() {
		return [
			// @todo it is bad to refer files directly in the admin module... however I don't have a better fileserv for now
			'/assets/admin/admin-app.html',
			'/assets/admin/admin-app-page.html',
		];
	}

}
