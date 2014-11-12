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
		'slug' => [
			'toString',
			'required',
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

		// $uriParts shall contain uri parts not in root slug (basepath)
		$uriParts = $Request->getRemainingUriParts();
		$rootUriParts = explode('/', $PageModelRoot->slug);
		for ($i=0; isset($uriParts[$i]) && isset($rootUriParts[$i]) && $uriParts[$i] === $rootUriParts[$i]; $i++) {
			unset($uriParts[$i]);
		}
		$uriParts = array_merge($uriParts);
		$uriPartsCnt = count($uriParts);

		$PageModel = new \ModPageModel();
		$PageModel->Root = $PageModelRoot;

		for ($i=$uriPartsCnt; $i>=0; $i--) {
			// @todo I should set slug, domain, etc here maybeeeee?
			$slug = implode('/', $uriParts);
//			echop('try: ' . $slug);
			$PageModel->slug = $slug;
			$PageModel->load();
			if ($PageModel->isLoaded()) {
//				echop('FOUND!');
				$Request->shiftUriParts($i);
				break;
			}
			array_pop($uriParts);
		}

		if (!$PageModel->isLoaded()) {
			// now what?
//			echop('NOTFOUNDD');
//			die('FU');
			// the request went to root page: it will redirect to index page if set
//			if ($uriPartsCnt !== 0) {
				throw new \HttpException(404);
//			}
//			return $PageModelRoot;
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
