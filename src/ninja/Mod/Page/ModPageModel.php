<?php

namespace ninja;

class ModPageModel extends \ModAbstractModel {

	const JS_HEAD = 'HEAD';
	const JS_FOOT = 'FOOT';

	protected static $_schema = [
		'@@extends' => 'ModBaseCssModel',
		// override parent to set a specific type
		'Parent' => [
			'class' => 'ModPageModel',
			'reference' => \SchemaManager::REF_REFERENCE,
		],
		'Root' => [
			'class' => 'ModPageModel',
			'reference' => \SchemaManager::REF_REFERENCE,
		],
		'doctype' => [
			'default' => 'html'
		],
		'title',
		'meta' => [
			'toList',
			'keys' => ['name', 'content'],
			'hasMin' => 0,
			'hasMax' => 0,
		],
		'scripts' => [
			'toArray',
			'keys' => ['place', 'src', 'code'],
			'keysValues' => ['place', [\ModPageModel::JS_HEAD, \ModPageModel::JS_FOOT]],
			'keysEither' => ['src', 'code'],
			'hasMax' => 0,
		],
		'script' => [
			'toString',
		],
		'css' => [
			'toArray',
			'keys' => ['href', 'media', 'onlyIf'],
			'hasMax' => 0,
		],
	];

	public static function getDbCollectionName() {
		return 'PageModelCollection';
	}

	/**
	 * @param \Request $Request
	 * @return \ModPageRootModel
	 */
	public static function fromRequest($Request) {

		$PageModelRoot = \ModPageRootModel::fromRequest($Request);

		$PageModel = new \ModPageModel();
		$PageModel->Root = $PageModelRoot;
		// @todo I should set slug, domain, etc here maybeeeee?
		$PageModel->load();

		if (!$PageModel->fieldIsSet('_id', \ModelManager::DATA_ORIGINAL, true)) {
			// now what?
			echop($PageModel);
			echop($PageModelRoot);
			die('FU');
		}

		return $PageModel;
	}

	public function getFieldWithRoot($key) {
		$val = $this->getField($key, \ModelManager::DATA_ALL, true);
		$rootVal = null;
		if ($this->fieldIsSet('Root')) {
			$Root  = $this->getField('Root');
			if ($Root->fieldIsSet($key)) {
				$rootVal = $Root->getField($key, \ModelManager::DATA_ALL, true);
			}
		}
		if (!is_null($val) && !is_null($rootVal)) {
			if ($val instanceof \maui\Collection) {
				$ret = $val->prepend($rootVal);
			}
			elseif ($rootVal instanceof \maui\Collection) {
				$ret = $rootVal->append($val);
			}
			else {
				$ret = array_merge((array)$rootVal, (array)$val);
			}
		}
		elseif (!is_null($val)) {
			$ret = $val;
		}
		else {
			$ret = $rootVal;
		}
		return $ret;
	}

}
