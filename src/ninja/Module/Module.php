<?php

namespace ninja;

abstract class Module {

	/**
	 * @var \Module
	 */
	protected $_Parent;

	/**
	 * @var \Module[]
	 */
	protected $_children = array();

	/**
	 * @var \Request object
	 */
	protected $_Request;

	/**
	 * @var \Model this model should hold the coupled data for the module, eg. metas in a page...
	 */
	protected $_Model;

	/**
	 * @var \View
	 */
	protected $_View;

	/**
	 * @var \Controller
	 */
	protected $_Controller;

	public function __construct($Request = null, $Parent = null) {

		$this->_Request = $Request;
		$this->_Parent = $Parent;

	}

	public function run() {

		$result = $this->_getController()->run($this);
		return $result;

	}

}
