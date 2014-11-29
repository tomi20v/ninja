<?php

namespace ninja;

abstract class ModAbstractController {

	/**
	 * @var \Request
	 */
	protected $_Request;

	/**
	 * @var \ModAbstractModel $_Model
	 */
	protected $_Model;

	public function __construct($Request, $Model) {
		$this->_Request = $Request;
		$this->_Model = $Model;
	}

	/**
	 * @param string[] $actionParts shall be remaining uri parts from request. maybe no need to send it too?
	 * @param \Request $Request
	 * @param \ModAbstractModule $Module my module
	 * @return null
	 */
	public function invoke($Request) {

		$actionParts = $Request->getRemainingUriParts();
		$method = strtolower($Request->getMethod());
		$actions = [];
		while(count($actionParts)) {
			$actions[] = $method . \ArrayHelper::camelJoin($actionParts);
			$actions[] = 'action' . \ArrayHelper::camelJoin($actionParts);
			array_pop($actionParts);
		}
		$actions[] = $method . 'Index';
		$actions[] = 'actionIndex';
		foreach ($actions as $eachEaction) {
			if (method_exists($this, $eachEaction)) {
				return call_user_func_array([$this, $eachEaction], [$Request]);
			}
		}
		return null;
	}

	public function actionIndex($Request) {
		$View = $this->_getView($Request);
	}

	/**
	 * I provide a way to extend view creation, or, to skip it (return null)
	 * @return \View|null
	 */
	protected function _getView($Request) {
		$viewClassname = substr(get_class($this), 0, -6) . 'View';
		if ($pos = strrpos($viewClassname, '\\')) {
			$viewClassname = substr($viewClassname, $pos+1);
		}
		if (!class_exists($viewClassname)) {
			$viewClassname = 'ModBaseView';
		}
		return new $viewClassname($this, $this->_Model);
	}

}

