<?php

namespace ninja;

class PageModel extends \Model {

	const JS_HEAD = 'HEAD';
	const JS_FOOT = 'FOOT';

	protected static $_schema = array(
		'Parent' => array(
			'class' => 'PageModel',
			'reference' => \SchemaManager::REF_REFERENCE,
		),
		'Root' => array(
			'class' => 'PageModel',
			'reference' => \SchemaManager::REF_REFERENCE,
		),
		'published',
		'doctype' => array(
//			'default' => 'html'
		),
		'title',
		'meta' => array(
			'toArray',
			'keys' => array('name', 'content'),
			'hasMin' => 0,
			'hasMax' => 0,
		),
		'scripts' => array(
			'toArray',
			'keys' => array('place', 'src', 'code'),
			'keysValues' => array('place', array(\PageModel::JS_HEAD, \PageModel::JS_FOOT)),
			'keysEither' => array('src', 'code'),
			'hasMax' => 0,
		),
		'css' => array(
			'toArray',
			'keys' => array('href', 'media', 'onlyIf'),
			'hasMax' => 0,
		),
		'Body' => array(
			'class' => 'HtmlContent',
			'reference' => \SchemaManager::REF_REFERENCE,
			'hasMin' => 1,
			'hasMax' => 1,
		),
		'Content' => array(
			'class' => 'HtmlContent',
			'reference' => \SchemaManager::REF_INLINE,
			'hasMin' => 0,
			'hasMax' => 0,
		),
	);

	public static function getDbCollectionName() {
		return 'PageModelCollection';
	}

	public static function getFromRequest($Request) {

		$PageModelRoot = new \PageModelRoot(array(
			'parent' => null,
//			'domainName' => '.' . $Request->serverName,
			'domainName' => '/(\.)?' . $Request->serverName . '/',
		));

		$PageModelRoot->load();

		if (!$PageModelRoot->isLoaded()) {
//			throw new \Exception404();
			debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS); die('TBI');
		}
		echop($PageModelRoot);


		debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS); die('TBI');
	}

}
