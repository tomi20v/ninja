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
	 * @var \ModAbstractModel $_Model
	 */
	protected $_Model;

	/**
	 * @param \Request $Request
	 * @param \ModAbstractModule $Module
	 * @param \ModAbstractModel $Model
	 */
	public function __construct($Request, $Module, $Model) {
		if (!$Request instanceof \Request ||
			!$Module instanceof \ModAbstractModule ||
			!$Model instanceof \ModAbstractModel) {
			throw new \BadMethodCallException();
		}
		$this->_Request = $Request;
		$this->_Module = $Module;
		$this->_Model = $Model;
	}

	/**
	 * I am the most simple action implementation
	 * @param null $actionParts
	 * @param null $params
	 * @return null|\Response
	 */
	public function actionIndex($actionParts=null, $params=null) {
		$View = $this->getView();
		$Response = $View instanceof \View
			? new \Response($View)
			: null;
		return $Response;
	}

	/**
	 * I return the default view and provide a way to extend this view creation, or, to skip it (just return null)
	 *
	 * @param mixed $result if sent, I set it as the content
	 * @return \View|null
	 */
	public function getView($result=null) {
		//$viewClassname = substr(get_class($this), 0, -6) . 'View';
		$viewClassname = 'Mod' . $this->_Module->getModName() . 'View';
		if ($pos = strrpos($viewClassname, '\\')) {
			$viewClassname = substr($viewClassname, $pos+1);
		}
		if (!class_exists($viewClassname)) {
			$viewClassname = 'ModBaseView';
		}
		if (!is_null($result)) {
			$this->_Model->Contents->append(['result'=>$result]);
		}
		return new $viewClassname($this->_Module, $this->_Model);
	}

}

