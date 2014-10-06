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
		$Page = new \PageModel(); echop ($Page); die('KO');
		$Page = \PageModel::loadAsSaved($loadData);
		return $Page;
	}

}
