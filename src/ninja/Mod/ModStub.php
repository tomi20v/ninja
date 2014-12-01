<?php
namespace ninja;

class ModStub {

	protected $_classname;

	protected $_Object;

	protected $_stubbedMethods = [];

	public function __call($method, $args) {
		if (in_array($method, $this->_stubbedMethods)) {
			echop('STUBBED: ' . $method);
		}
		return $this->_Object->
	}

	public function __construct() {
		$this->_Object = call_user_func_array([$this->_classname, '__construct'], func_get_args());
	}

	public function stubMethod($method) {
		if (!in_array($method, $this->_stubbedMethods)) {
			$this->_stubbedMethods[] = $method;
		}
		return $this;
	}

}
