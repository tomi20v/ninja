<?php

namespace ninja;

abstract class ModAbstractController {

	/**
	 * @var \Request
	 */
	protected $_Request;

	/**
	 * @var \ModAbstractModule
	 */
	protected $_Module;

	/**
	 * @param \Request $Request
	 * @param \ModAbstractModule $Module
	 */
	public function __construct($Request, $Module) {
		if (!$Request instanceof \Request ||
			!$Module instanceof \ModAbstractModule) {
			throw new \BadMethodCallException();
		}
		$this->_Request = $Request;
		$this->_Module = $Module;
	}

	/**
	 * I will be called right before any action call would happen.
	 * Overwrite and put code here which stands for all methods only
	 */
	public function before() {}

	/**
	 * I am the most simple action implementation
	 * @param null $actionParts
	 * @param null $params
	 * @return null|\Response
	 */
	public function actionIndex($params=null) {
		return $this->buildView();
	}

	/**
	 * @TODO - add a parameter for action name so different actions can build their own template easily, with fallback to default one
	 * I return the default view and provide a way to extend this view creation, or, to skip it (just return null)
	 *
	 * @param mixed $result if sent, I set it as the content
	 * @return \View|null
	 */
	public function buildView($result=null) {
		$viewClassname = 'Mod' . $this->_Module->getModName() . 'View';
		if ($pos = strrpos($viewClassname, '\\')) {
			$viewClassname = substr($viewClassname, $pos+1);
		}
		if (!class_exists($viewClassname)) {
			$viewClassname = 'ModBaseView';
		}
		if (!is_null($result)) {
			$this->_Module->getModel()->Contents->append(['result'=>$result]);
		}
		return new $viewClassname($this->_Module, $this->_Module->getModel(), null, $this->_Request->getRequestedExtension());
	}

}

