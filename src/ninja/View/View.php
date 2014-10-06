<?php

namespace ninja;

class View {

	/**
	 * @var \Module reference to my parent
	 */
	protected $_Module;

	protected $_template;

	protected $_data = array();

	public function __construct($Module) {
		$this->_Module = $Module;
	}

	public function __get($key) {
		if (array_key_exists($key, $this->_data)) {
			return $this->get($key);
		}
		throw new \Exception();
	}

	public function __set($key, $val) {
		return $this->set($key, $val);
	}

	public function get($key) {
		return isset($this->_data[$key]) ? $this->_data[$key] : null;
	}

	public function set($key, $val) {
		$this->_data[$key] = $val;
		return $this;
	}

	public function render() {
		$template = $this->_findTemplate();
	}

	public function _findTemplate() {

	}

}
