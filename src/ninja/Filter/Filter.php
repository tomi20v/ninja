<?php

namespace ninja;

/**
 * Class Filter - I am a decorator for models being used in templates
 *
 * @package ninja
 */
class Filter {

	const MASK_GETTER = '/^get_([a-zA-Z0-9_]+)$/';

	const MASK_FETCHER = '/^fetch_([a-zA-Z0-9_]+)$/';

	const MASK_MARKER = '/^mark_([a-zA-Z0-9_]+)$/';

	/**
	 * @var \View
	 */
	protected $_View;

	/**
	 * @var \Model
	 */
	protected $_Model;

	/**
	 * I shall be constructed by the $View
	 * @param \View $View
	 * @param \Model $Model
	 */
	public function __construct($View, $Model) {

		$this->_View = $View;

		$this->_Model = $Model;

	}

	/**
	 * I return $View::$key() if $key matches MASK_GETTER format, otherwise $Model->$key
	 * @param string $key
	 * @return $this|\maui\Model|null
	 */
	public function __get($key) {

		if (preg_match(static::MASK_GETTER, $key)) {
			return $this->_View->$key();
		}
		elseif (preg_match(static::MASK_FETCHER, $key, $matches)) {
			return $this->_View->fetchFromContents($matches[1]);
		}
		elseif (preg_match(static::MASK_MARKER, $key, $matches)) {
			return $this->_View->markFetchedFromContents($matches[1]);
		}

		$ret = $this->_Model->getField($key, \ModelManager::DATA_ALL, false);

		return $ret;

	}

	/**
	 * mapping for mustache
	 * @param $key
	 * @return bool
	 */
	public function __isset($key) {

		if (preg_match(static::MASK_GETTER, $key)) {
			return true;
		}
		elseif (preg_match(static::MASK_FETCHER, $key)) {
			return true;
		}
		elseif (preg_match(static::MASK_MARKER, $key)) {
			return true;
		}

		$ret = $this->_Model->__isset($key);

		return $ret;

	}

}
