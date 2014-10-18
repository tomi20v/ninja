<?php

namespace ninja;

abstract class ModAbstractModule {

	/**
	 * pattern of module names, used to find mod name and module name
	 */
	const MODULE_NAME_PATTERN = '/^Mod([A-Z][^A-Z]+)(Model|Module)(.+)?$/';

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

	public function __construct($Request, $Parent, $Model=null) {
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
	 * @param \ModModel $ModModel
	 */
	protected function _getSubModuleFrom($ModModel, $_Request=null) {

		$modelClassname = get_class($ModModel);

		//if (substr($modelClassname, -11) !== 'ModAbstractModel') {
		if (!is_subclass_of($modelClassname, 'ModAbstractModel') || (substr($modelClassname, -5) !== 'Model')) {
			throw new \Exception('cannot recognize ModAbstractModel extension, saw: ' . $modelClassname);
		}

		$moduleClassname = substr($modelClassname, 0, -5) . 'Module';

		$SubModule = new $moduleClassname(
			func_num_args() == 1 ? $this->_Request : $_Request,
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
	 * I recursively create submodules and call for their response
	 * @return \Response|null
	 */
	protected function _processSubmodules() {

		if (!$this->_Model->fieldNotNull('Modules')) {
			return;
		}

		/**
		 * @var \ModAbstractModule[] $subModules
		 */
		$subModules = array();
		$subModuleModels = $this->_Model->Modules;

		foreach ($subModuleModels as $eachKey => $eachSubModuleModel) {
			$SubModule = $this->_getSubModuleFrom($eachSubModuleModel);
			$SubModule->_processSubmodules();
			$subModules[$eachKey] = $SubModule;
		}

		$this->_Model->Modules = $subModules;

		$Contents = $this->_Model->Contents;

		foreach ($subModules as $eachSubmodule) {
			$Response = $eachSubmodule->respond();
			if ($Response instanceof \Response) {
				return $Response;
			}
			$Contents[] = $Response;
		}

		$this->_Model->Contents = $Contents;

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
	 * @return void|\Response Return \Response to use it as final response and stop processing
	 */
	protected function _beforeRespond() {

		if (!isset($this->_Model)) {
			$classname = $this->getModClassnameBase() . 'Model';

			$this->_Model = $classname::fromRequest($this->_Request);
		}

	}

	/**
	 * I can be overwritten eg. to display different views based on some input
	 * @return void|\Response Return \Response to use it as final response and stop processing
	 */
	protected function _respond() {}

	/**
	 * use this to process generated response
	 * @return void|\Response Return \Response to use it as final response and stop processing
	 */
	protected function _afterRespond() {}

	/**
	 * only this shall be called, and after creating a module (creating includes setting up by setters)
	 * shall return a response, or anything printable
	 * @return \Response
	 */
	final public function respond() {

		echo '';

		$this->_beforeRespond();

		$Response = $this->_processSubmodules();
		if ($Response instanceof \Response) {
			goto finish;
		}

		$Response = $this->_respond();
		if ($Response instanceof \Response) {
			goto finish;
		}

		// default response
		$this->_View = new \View($this, $this->_Model);
		$Response = $this->_View->render();

		finish:

		return $Response;

	}

}
