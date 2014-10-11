<?php

namespace ninja;

class PageModel extends \ModuleModel {

	const JS_HEAD = 'HEAD';
	const JS_FOOT = 'FOOT';

	protected static $_schema = array(
		'@extends' => 'ModuleModel',
		// override parent to set a specific type
		'Parent' => array(
			'class' => 'PageModel',
			'reference' => \SchemaManager::REF_REFERENCE,
		),
		'Root' => array(
			'class' => 'PageModel',
			'reference' => \SchemaManager::REF_REFERENCE,
		),
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
	);

	public static function getDbCollectionName() {
		return 'PageModelCollection';
	}

	/**
	 * @param \Request $Request
	 * @return \PageModelRoot
	 */
	public static function fromRequest($Request) {

		$PageModelRoot = \PageModelRoot::fromRequest($Request);

		$PageModel = new \PageModel();
		$PageModel->Root = $PageModelRoot;
		$PageModel->load();

		return $PageModel;
	}



}
