<?php

namespace maui;

/**
 * Class Router for http requests
 *
 * @package maui
 */
class Router extends \Model {

#	use \maui\TraitNamedInstances;

//	protected static $_schema = array();

	/**
	 * I return default instance
	 */
//	protected static function _instance() {
//	}

	/**
	 * @param \Request $Request
	 */
	public static function PageFromRequest($Request) {
		$loadData = array(
			'_type' => 'PageRoot',
			'domain' => $Request->serverName,
		);
		$Page = new \ModPageModel(); echop ($Page); die('KO');
		$Page = \ModPageModel::loadAsSaved($loadData);
		return $Page;
	}

}
