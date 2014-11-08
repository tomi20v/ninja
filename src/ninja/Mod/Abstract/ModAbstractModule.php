<?php

namespace ninja;

abstract class ModAbstractModule {

	/**
	 * pattern of module names, used to find mod name and module name
	 */
	const MODULE_NAME_PATTERN = '/^Mod([A-Z].*)(Controller|Model|Module|View)(.+)?$/';

	/**
	 * @var \ModAbstractModule
	 */
	protected $_Parent;

	/**
	 * @var \ModAbstractModule[]
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

	/**
	 * @param $Request
	 * @param $Parent
	 * @param \Model|\ninja\Ninja|null $Model send Ninja instance for root module, the parent's model for the rest
	 */
	public function __construct($Request, $Parent, $Model=null) {

		if (!($Request instanceof \Request) ||
			(!($Parent instanceof \ModAbstractModule) && !($Parent instanceof \Ninja)) ||
			(!is_null($Model) && !($Model instanceof \Model))) {
			throw new \BadMethodCallException(echon([$Request, $Parent, $Model]));
		}

		$this->_Request = $Request;
		$this->_Parent = $Parent;
		$this->_Model = $Model;
	}

	////////////////////////////////////////////////////////////////////////////////
	//	submodules
	////////////////////////////////////////////////////////////////////////////////

	/**
	 * I am a non-static method since I have to pass myself as parent anyway
	 *
	 * @param \ModAbstractModel $ModModel
	 * @param \Request|null $Request
	 */
	protected function _getSubModuleFrom($ModModel, $Request=null) {

		$modelClassname = get_class($ModModel);

		//if (substr($modelClassname, -11) !== 'ModAbstractModel') {
		if (!is_subclass_of($modelClassname, 'ModAbstractModel') || (substr($modelClassname, -5) !== 'Model')) {
			throw new \Exception('cannot recognize ModAbstractModel extension, saw: ' . $modelClassname);
		}

		$moduleClassname = substr($modelClassname, 0, -5) . 'Module';

		$SubModule = new $moduleClassname(
			func_num_args() == 1 ? $this->_Request : $Request,
			$this,
			$ModModel
		);

		return $SubModule;

	}

	/**
	 * @return string I return the key to be used with this submodule. Currently just the classname, but shall be something
	 * 		more meaningful later
	 */
	public function getSubModuleKey() {

		$subModuleKey = get_class($this);
		if ($pos = strrpos($subModuleKey, '\\')) {
			$subModuleKey = substr($subModuleKey, $pos+1);
		}
		if (substr($subModuleKey, -6) === 'Module') {
			$subModuleKey = substr($subModuleKey, 0, -6);
		}

		return $subModuleKey;

	}

	/**
	 * @return null|ModAbstractModelCollection $subModules
	 */
	protected function _getSubModuleModels() {
		$subModuleModels = null;
		if ($this->_Model->fieldNotNull('Modules')) {
			$subModuleModels = $this->_Model->Modules;
		}
		return $subModuleModels;
	}

	/**
	 * I recursively create submodules and call for their response
	 * @return \maui\ResponeInterface|null
	 */
	protected function _processSubmodules() {

		$subModuleModels = $this->_getSubModuleModels();

		if (!empty($subModuleModels)) {

			foreach ($subModuleModels as $eachKey => $eachSubModuleModel) {
				$SubModule = $this->_getSubModuleFrom($eachSubModuleModel);
				$Response = $SubModule->_processSubmodules();
				if ($Response instanceof \maui\Response) {
					return $Response;
				}
				$subModules[$eachKey] = $SubModule;
			}

			$this->_Model->Modules = $subModules;

			/**
			 * @var array $Contents as specified in ModAbstractModel
			 */
			$Contents = $this->_Model->Contents;

			foreach ($subModules as $eachSubmodule) {
				$Response = $eachSubmodule->respond();
				if ($Response instanceof \ninja\Response) {
					return $Response;
				}
				$Contents[] = $Response;
			}

			$this->_Model->Contents = $Contents;

		}

		return null;

	}

	////////////////////////////////////////////////////////////////////////////////
	//	response
	////////////////////////////////////////////////////////////////////////////////

	/**
	 * @todo make this return the mod base classname only (currently will wrongly return for multi level descendants)
	 * I return classname to derive base model (etc) classnames within the mod
	 * @return string
	 */
	public function getModClassnameBase() {

		$classname = substr(get_class($this), 0, -6);

		return $classname;

	}

	/**
	 * I return a mod's name, eg. 'Page' for 'ModPageModule'
	 * @param $classname
	 * @return string
	 */
	public static function modNameByClassname($classname) {

		$modName = $classname;

		if ($pos = strrpos($modName, '\\')) {
			$modName = substr($modName, $pos+1);
		}

		$modName = preg_match(static::MODULE_NAME_PATTERN, $modName, $matches)
			? $matches[1]
			: '';

		return $modName;

	}

	/**
	 * I return my modname, eg. 'Page' for 'ModPageModule'
	 * @return string
	 */
	public function getModName() {

		static $modName;

		if (is_null($modName)) {

			$modName = static::modNameByClassname(get_class($this));

		}

		return $modName;

	}

	public static function moduleNameByClassname($classname) {

		if ($pos = strrpos($classname, '\\')) {
			$classname = substr($classname, $pos+1);
		}

		$classname = preg_match(static::MODULE_NAME_PATTERN, $classname, $matches)
			? (isset($matches[3]) ? $matches[3] : '')
			: '';

		return $classname;

	}

	public function getModuleName() {

		static $moduleName;

		if (is_null($moduleName)) {

			$moduleName = static::moduleNameByClassname(get_class($this));

		}

		return $moduleName;

	}

	/**
	 * use this to set up / check data before respond
	 * @return void|\ninja\ResponseInterface Return some Response to use it as final response and stop processing
	 */
	protected function _beforeRespond() {

		if (!isset($this->_Model)) {
			$classname = $this->getModClassnameBase() . 'Model';

			$this->_Model = $classname::fromRequest($this->_Request);
		}

	}

	/**
	 * I can be overwritten eg. to display different views based on some input
	 * @return void|\ninja\ResponseInterface Return \Response to use it as final response and stop processing
	 */
	protected function _respond() {}

	/**
	 * use this to process generated response
	 * @return void|\ninja\ResponseInterface Return \Response to use it as final response and stop processing
	 */
	protected function _afterRespond() {}

	/**
	 * only this shall be called, and after creating a module (creating includes setting up by setters)
	 * shall return a response, or anything printable
	 * @return \ninja\ResponseInterface
	 */
	final public function respond() {

		$this->_beforeRespond();

		$Response = $this->_processSubmodules();
		if ($Response instanceof \ninja\Response) {
			goto finish;
		}

		$Response = $this->_respond();
		if ($Response instanceof \ninja\Response) {
			goto finish;
		}

		// default response
		$this->_View = $this->_getView();
		$Response = $this->_View instanceof \View
			? $this->_View->render()
			: null;

		finish:

		return $Response;

	}

	/**
	 * I provide a way to extend view creation, or, to skip it (return null)
	 * @return \View|null
	 */
	protected function _getView() {
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
