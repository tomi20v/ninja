<?php

namespace ninja;

/**
 * Class ModPageModel
 *
 * @package ninja
 *
 * @property \ModPageModel $Parent
 * @property \ModPageRootModel $Root
 * @property string $slug
 * @property string $doctype
 * @property string $title
 * @property array[] $meta
 * @property array[] $scripts
 * @property string $script
 * @property array[] $css
 * @property string $baseHref
 *
 */
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
		'links' => [
			'toArray',
			'keys' => ['rel', 'href', 'media', 'onlyIf'],
			'keysValues' => ['rel', ['stylesheet', 'import']],
			'hasMax' => 0,
		],
		'baseHref',
	];

	/**
	 * @var \ModPageModelAsset
	 */
	protected $_Asset;

	public static function getDbCollectionName() {
		return 'PageModelCollection';
	}

	/**
	 * @param \Request $Request
	 * @param \ModPageRootModel $ModPageRootModel
	 * @return \ModPageRootModel
	 */
	public static function findByRequestAndRoot($Request, $ModPageRootModel) {

		// $uriParts shall contain uri parts not in root slug (basepath)
		$rootUriParts = explode('/', $ModPageRootModel->slug);

		$Request->shiftUriParts($rootUriParts);

		$uriParts = array_merge($Request->getRemainingUriParts());
		$uriPartsCnt = count($uriParts);

		$ModPageModel = new \ModPageModel();
		$ModPageModel->Root = $ModPageRootModel;

		for ($i=$uriPartsCnt; $i>=0; $i--) {
			$slug = implode('/', $uriParts);
			$ModPageModel->slug = $slug;
			$ModPageModel->load();
			if ($ModPageModel->isLoaded()) {
				break;
			}
			array_pop($uriParts);
		}

		return $ModPageModel;
	}

	/**
	 * I set base href from $Request
	 * @param \Request $Request
	 * @return $this
	 */
	public function setBaseHref($Request) {
		if ($this->isLoaded()) {
			$baseHref = $Request->getScheme() . '://' . $Request->getHttpHost() . '/' . $this->slug;
			$baseHref = rtrim($baseHref, '/') . '/';
			$this->baseHref = $baseHref;
		}
		return $this;
	}

	/**
	 * I return a field's value, also merging page root's value into
	 * @param string $key
	 * @return $this|array|null
	 * @throws \Exception
	 */
	public function getFieldWithRoot($key) {
		$val = $this->Data()->getField($key, \ModelManager::DATA_ALL, true);
		$rootVal = null;
		if ($this->Data()->fieldIsSet('Root')) {
			$Root  = $this->Data()->getField('Root');
			if ($Root->Data()->fieldIsSet($key)) {
				$rootVal = $Root->Data()->getField($key, \ModelManager::DATA_ALL, true);
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

	/**
	 * I return asset helper. It is always bound to
	 * @return \ModPageModelAsset
	 */
	public function Asset() {
		if (is_null($this->_Asset)) {
			$this->_Asset = new \ModPageModelAsset($this);
		}
		return $this->_Asset;
	}

}
