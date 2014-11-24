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
//	protected $_Request;

	/**
	 * @var \Model this model should hold the coupled data for the module, eg. metas in a page...
	 */
	protected $_Model;

	/**
	 * @var string[] these are the uri parts which I can actually use (as some of them might have been consumed)
	 */
	protected $_uriParts = [];

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
	public function __construct($Parent, $Model=null) {

//		if (!($Request instanceof \Request) ||
//			(!($Parent instanceof \ModAbstractModule) && !($Parent instanceof \Ninja)) ||
//			(!is_null($Model) && !($Model instanceof \Model))) {
//			throw new \BadMethodCallException(echon([$Request, $Parent, $Model]));
//		}

//		if (is_null($uriParts)) {
//			$uriParts = $Request->getRemainingUriParts();
//		}
//
//		$this->_Request = $Request;
		$this->_Parent = $Parent;
		$this->_Model = $Model;
//		$this->_uriParts = $uriParts;
	}

	////////////////////////////////////////////////////////////////////////////////
	//	submodules
	////////////////////////////////////////////////////////////////////////////////

	/**
	 * I am a non-static method since I have to pass myself as parent anyway
	 *
	 * @param \ModAbstractModel $ModModel
	 * @return \ModAbstractModule
	 */
	protected function _getSubModuleFrom($ModModel) {

		$modelClassname = get_class($ModModel);

		if (!($ModModel instanceof \ninja\ModAbstractModel) || (substr($modelClassname, -5) !== 'Model')) {
			throw new \Exception('cannot recognize ModAbstractModel extension, saw: ' . $modelClassname);
		}

		$moduleClassname = substr($modelClassname, 0, -5) . 'Module';

		$SubModule = new $moduleClassname(
			$this,
			$ModModel
		);

		return $SubModule;

	}

	/**
	 * @return ModAbstractModelCollection|null $subModules
	 */
	protected function _getSubModuleModels() {
		$subModuleModels = !$this->_Model->fieldIsEmpty('Modules')
			? $this->_Model->Modules
			: null;
		return $subModuleModels;
	}

	/**
	 * I recursively create submodules and call for their response
	 * @param \Request $Request
	 * @return \maui\ResponeInterface|null
	 */
	protected function _processSubmodules($Request) {

		$subModuleModels = $this->_getSubModuleModels();

		if (!empty($subModuleModels)) {

			$subModules = [];

			/**
			 * @var array $Contents as specified in ModAbstractModel
			 */
			$Contents = $this->_Model->Contents;
			if (empty($Contents)) {
				$Contents = array();
			}
			foreach ($subModuleModels as $eachKey => $eachSubModuleModel) {
				$SubModule = $this->_getSubModuleFrom($eachSubModuleModel);
				$subModules[$eachKey] = $SubModule;
				$SubRequest = $Request->getClone();
				$Response = $SubModule->respond($SubRequest);
				if ($Response instanceof \ninja\Response) {
					if ($Response->getIsFinal()) {
						return $Response;
					}
					$Response = $Response->getContent();
				}
				while (isset($Contents[$eachKey])) {
					$eachKey.= '_';
				}
				$Contents[$eachKey] = $Response;
			}

			$this->_Model->Modules = $subModules;

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
	 * @param \Request
	 * @return void|\ninja\ResponseInterface Return some Response to use it as final response and stop processing
	 */
	protected function _beforeRespond($Request) {

		if (!isset($this->_Model)) {
			$classname = $this->getModClassnameBase() . 'Model';
			$this->_Model = $classname::findByRequest($Request);
		}

	}

	/**
	 * I can be overwritten eg. to display different views based on some input
	 * @return void|\ninja\ResponseInterface Return \Response to use it as final response and stop processing
	 */
	protected function _respond($Request) {
		$this->_View = $this->_getView($Request);
		$Response = $this->_View instanceof \View
			? new \Response($this->_View)
			: null;
		return $Response;
	}

	/**
	 * use this to process generated response
	 * @param \Request
	 * @param \Response
	 * @return void|\ninja\ResponseInterface Return \Response to use it as final response and stop processing
	 */
	protected function _afterRespond($Request, $Response) {}

	/**
	 * @param \Request $Request
	 * @return \Response
	 */
	final public function respond($Request) {

		// set up model if not yet set
		$this->_beforeRespond($Request);

		$Response = $this->_processSubmodules($Request);
		if ($Response instanceof \Response) {
			goto finish;
		}

		$Response = $this->_respond($Request);

		$this->_afterRespond($Request, $Response);

		finish:

		return $Response;
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
