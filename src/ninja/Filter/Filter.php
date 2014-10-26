<?php

namespace ninja;

/**
 * Class Filter - I am a decorator for models being used in templates
 *
 * @package ninja
 */
class Filter {

	const MASK_GETTER = '/^get_([a-zA-Z0-9]+)$/';

	/**
	 * @var \View
	 */
	protected $_View;

	/**
	 * @var \Model
	 */
	protected $_Model;

	public function __construct($View, $Model) {

		$this->_View = $View;

		$this->_Model = $Model;

	}

	public function __get($key) {

		if (preg_match(static::MASK_GETTER, $key)) {
			return $this->_View->$key();
		}

		return $this->_Model->field($key);

	}

	public function __isset($key) {

		if (preg_match(static::MASK_GETTER, $key)) {
			return true;
		}

		return $this->_Model->__isset($key);

	}

	public function __call($method, $arguments) {

		return call_user_func([$this->_View, $method], $arguments);

	}

}
