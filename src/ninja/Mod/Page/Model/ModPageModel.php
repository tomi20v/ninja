<?php

namespace ninja;

class ModPageModel extends \ModAbstractModel {

	const JS_HEAD = 'HEAD';
	const JS_FOOT = 'FOOT';

	protected static $_schema = array(
		'@extends' => 'ModAbstractModel',
		// override parent to set a specific type
		'Parent' => array(
			'class' => 'ModPageModel',
			'reference' => \SchemaManager::REF_REFERENCE,
		),
		'Root' => array(
			'class' => 'ModPageModel',
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
			'keysValues' => array('place', array(\ModPageModel::JS_HEAD, \ModPageModel::JS_FOOT)),
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
	 * @return \ModPageModelRoot
	 */
	public static function fromRequest($Request) {

		$PageModelRoot = \ModPageModelRoot::fromRequest($Request);

		$PageModel = new \ModPageModel();
		$PageModel->Root = $PageModelRoot;
		$PageModel->load();

		return $PageModel;
	}



}
