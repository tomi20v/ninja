<?php

/**
 *                        slug  remainingUriPts ext.  method  pars  final
 * GET /index.html               [index]
 *      Page              index  [index]         +     GET     +       +
 *       header           -      []              -     GET     -       -
 *        login           login  []              -     GET     -       -
 * POST /index.html              [index]
 *      Page              index  [index]         +     POST    +       +
 *       header           -      []              -     GET     -       -
 *        login           login  []              -     GET     -       -
 * GET /index.html/login         [index,login]
 *      Page              index  [index,login]   +     GET     -       +
 *       header           -      [login]         -     GET     -       -
 *        login           login  [login]         -     GET     +       -
 * POST /index.html/login        [index.login]
 *      Page              index  [index,login]   +     GET     -       +
 *       header           -      [login]         -     GET     -       -
 *        login           login  [login]         -     POST    +       -
 * GET /index/login.html         [index.login]
 *      Page              index  [index,login]   +     GET     -       -
 *       header           -      [login]         +     GET     -       -
 *        login           login  [login]         +     GET     +       +
 * POST /index/login.html        [index.login]
 *      Page              index  [index,login]   +     GET     -       -
 *       header           -      [login]         +     GET     -       -
 *        login           login  [login]         +     POST    +       +
 *
 * after consuming uri parts...
 * response is final, if:
 *      - self has a slug
 *      - extension is not yet consumed
 *      - consumed uri parts + extension matches self forged URI, eg:
 * response gets remaining uri parts, HTTP method, and parameters, if:
 *      - consumed uri parts match self forged URI
 *
 */
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

		$ModModel->Parent = $this->_Model;

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

		/**
		 * @var array $Contents as specified in ModAbstractModel
		 */
		$Contents = $this->_Model->Contents;
		if (empty($Contents)) {
			$Contents = array();
		}

		if (!empty($subModuleModels)) {

			$subModules = [];

			foreach ($subModuleModels as $eachKey => $eachSubModuleModel) {
				$SubModule = $this->_getSubModuleFrom($eachSubModuleModel);
				$subModules[$eachKey] = $SubModule;
				$SubRequest = $Request->getClone();
//echop('getting module ' . echon($SubModule, 1, 0, 1) . ' having slug: ' . $SubModule->_Model->slug . ' with: ' . echon($Request, true,0,1));
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

		}

		$this->_Model->Contents = $Contents;

		return null;

	}

	////////////////////////////////////////////////////////////////////////////////
	//	response
	////////////////////////////////////////////////////////////////////////////////

	/**
	 * I return classname to derive base model (etc) classnames within the mod
	 * @return string
	 */
	public function getModBaseClassname() {

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
			$classname = $this->getModBaseClassname() . 'Model';
			$this->_Model = $classname::findByRequest($Request);
		}
		// currently respond() depends on having a Model
		if (!isset($this->_Model)) {
			throw new \HttpRuntimeException();
		}

	}

	/**
	 * I return default controller instance. should consider remaining route?
	 * @param $Request
	 * @return \ModAbstractController
	 */
	protected function _getController($Request) {

		$controllerClassname = substr(get_class($this), 0, -6) . 'Controller';
		if (!class_exists($controllerClassname)) {
			$controllerClassname = 'ModBaseController';
		}

		$Controller = new $controllerClassname(
			$Request,
			$this->_Model
		);

		return $Controller;

	}

	/**
	 * I can be overwritten eg. to display different views based on some input
	 * @param \Request $Request
	 * @return void|\ninja\ResponseInterface Return \Response to use it as final response and stop processing
	 */
	protected function _respond($Request) {

		$Controller = $this->_getController($Request);

		// try invoking an action only if actual module is routable
		if (!$this->_Model->fieldIsEmpty('slug')
			// @todo check if request matches current module
//			&& $this->currentRequestMatchesThis
			&& (count($Request->getShiftedUriParts()))
		) {
			$result = $Controller->invoke($Request);
		}
		else {
			$result = $Controller->actionIndex($Request);
		}

		return $result;

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
		echop('I am a ' . get_class($this) . ' and my url path is: ' . $this->getHmvcUrlPath());
		// by now I shall have a Model
		if (!$this->_Model->fieldIsEmpty('slug')) {
			$slug = $this->_Model->slug;
			$Request->shiftUriParts($slug);
//			echop('SHIFTED: ' . $this->_Model->slug);
		}

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
//	protected function _getView($Request) {
//		$viewClassname = substr(get_class($this), 0, -6) . 'View';
//		if ($pos = strrpos($viewClassname, '\\')) {
//			$viewClassname = substr($viewClassname, $pos+1);
//		}
//		if (!class_exists($viewClassname)) {
//			$viewClassname = 'ModBaseView';
//		}
//		return new $viewClassname($this, $this->_Model);
//	}

	/**
	 * @return null|string
	 */
	public function getHmvcUrlPath() {

		$url = null;

		if (!$this->_Model->fieldIsEmpty('slug')) {

			$url = $this->_Model->getBubbler()->bubbleGetAll('slug', false);

			$url = implode('/', $url);

		}

		return $url;

	}

}
