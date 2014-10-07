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

	public function __construct($Request, $Parent) {

		$this->_Request = $Request;
		$this->_Parent = $Parent;

	}

	/**
	 * only this shall be called, and after creating a module (creating includes setting up by setters)
	 * shall return a response, but anything is acceptable that has a string printout
	 * @return \Response
	 */
	abstract public function respond();

}
