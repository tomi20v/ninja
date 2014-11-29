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
			'toArray',
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
	public static function findByRequest($Request) {

		$ModPageModelRoot = \ModPageRootModel::findByRequest($Request);
		if (!$ModPageModelRoot->isLoaded()) {
			throw new \HttpException(404);
		}

		// $uriParts shall contain uri parts not in root slug (basepath)
		$uriParts = $Request->getRemainingUriParts();
		$rootUriParts = explode('/', $ModPageModelRoot->slug);
//echop($uriParts); echop($rootUriParts); die;
//		for ($i=0; isset($uriParts[$i]) && isset($rootUriParts[$i]) && $uriParts[$i] === $rootUriParts[$i]; $i++) {
//			unset($uriParts[$i]);
//		}

		$Request->shiftUriParts($rootUriParts);

//		$uriParts = array_merge($uriParts);
		$uriParts = array_merge($Request->getRemainingUriParts());
		$uriPartsCnt = count($uriParts);
//		$uriExtension = '';
		$uriExtension = '.' . $Request->getRequestedExtension();
//		if ($uriPartsCnt) {
//			$last = array_pop($uriParts);
//			// @todo make this smarter
//			if (in_array(substr($last, -5), array('.html', '.json'))) {
//				$uriExtension = substr($last, -5);
//				$last = substr($last, 0, -5);
//			}
//			$uriParts[] = $last;
//		}

		$ModPageModel = new \ModPageModel();
		$ModPageModel->Root = $ModPageModelRoot;

		for ($i=$uriPartsCnt; $i>=0; $i--) {
//			$slug = implode('/', $uriParts) . $uriExtension;
			$slug = implode('/', $uriParts);
			echop('trying: ' . $slug);
			$ModPageModel->slug = $slug;
			$ModPageModel->load();
			if ($ModPageModel->isLoaded()) {
//				$Request->shiftUriParts($i);
				break;
			}
			array_pop($uriParts);
		}

		if (!$ModPageModel->isLoaded()) {
			$ModPageModel = $ModPageModelRoot;
		}

		return $ModPageModel;
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
