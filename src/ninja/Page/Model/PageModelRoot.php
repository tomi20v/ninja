<?php

namespace ninja;

class PageModelRoot extends \PageModelRedirect {

	protected static $_schema = array(
		'@extends' => array(
			'PageModel',
			'PageModelRedirect',
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

}
