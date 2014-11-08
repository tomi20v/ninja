<?php

namespace ninja;

class ModPageRootModel extends \ModPageRedirectModel {

	protected static $_schema = array(
		'@@extends' => array(
			'ModPageModel',
			'ModPageRedirectModel',
		),
		'domainName' => array(
			'toString',
			'domainName',
		),
		'availableLanguages' => array(
			'toArray',
			'hasMin' => 1,
			'hasMax' => 0,
		),
		'templateFolders' => array(
			'toArray',
			'hasMin' => 0,
			'hasMax' => 0,
		),
	);

	/**
	 * @param \Request $Request
	 * @return static
	 */
	public static function fromRequest($Request) {

		$ModPageModelRoot = static::finder()
			->equals('parent', null)
			->regex('domainName', '/(\.)?' . $Request->getHttpHost() . '/')
			->findOne();

		return $ModPageModelRoot;

	}

}
