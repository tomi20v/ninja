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
	const MODULE_NAME_PATTERN = '/^Mod([A-Z].*)(Controller|Model|Module|View)$/';

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
	//	helpers
	////////////////////////////////////////////////////////////////////////////////

	/**
	 * I return a mod's name, eg. 'PageRedirect' for 'ModPageRedirectModule'
	 * @param $classname
	 * @return string
	 */
	public static function modNameByClassname($classname) {

		if (is_object($classname)) {
			$classname = get_class($classname);
		}

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
	 * I return my modname, eg. 'PageRedirect' for 'ModPageRedirectModule'
	 * @return string
	 */
	public function getModName() {

		static $modName;

		if (is_null($modName)) {

			$modName = static::modNameByClassname(get_class($this));

		}

		return $modName;

	}

	/**
	 * I am just a shorthand wrapper for Router::getHmvcUrl()
	 * @return string
	 */
	public function getHmvcPath() {
		return \Router::getHmvcPath($this->_Model);
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

//		$ModModel->Parent = $this->_Model;

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

			/**
			 * @var array $Contents as specified in ModAbstractModel
			 */
			$Contents = $this->_Model->Contents;
			if (empty($Contents)) {
				$Contents = array();
			}

			$subModules = [];

			foreach ($subModuleModels as $eachKey => $eachSubModuleModel) {
				$SubModule = $this->_getSubModuleFrom($eachSubModuleModel);
				$subModules[$eachKey] = $SubModule;
				$SubRequest = $Request->getClone();

				$Response = $SubModule->respond($SubRequest);
				if ($Response instanceof \ninja\Response) {
					$Request->setActionMatched(
						$SubRequest->getActionMatched()
					);
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
	 * use this to set up / check data before respond
	 * @param \Request
	 * @return void|\ninja\ResponseInterface Return some Response to use it as final response and stop processing
	 */
	protected function _beforeRespond($Request) {

		if (!isset($this->_Model)) {
			$classname = 'Mod' . $this->getModName() . 'Model';
			$this->_Model = $classname::findByRequest($Request);
		}
		// currently respond() depends on having a Model
		if (!isset($this->_Model)) {
			throw new \HttpRuntimeException();
		}

	}

	/**
	 * @param \Request $Request
	 * @param bool $hasShifted - true if current module has shifted any uri parts (if it has, it is a uri match)
	 * @return mixed|null|\Response
	 */
	protected function _respond($Request, $hasShifted) {

		$Controller = \Router::getController($Request, $this, $this->_Model);

		if ($hasShifted && !$Request->getActionMatched()) {
			$Response = \Router::invokeControllerAction($Controller, $Request);
			$myHmvcUrlPart = $this->_Model->slug . '.' . $Request->getRequestedExtension();
			if ($myHmvcUrlPart == $Request->getTargetUri()) {
				if (count($Request->getRemainingUriParts())) {
					throw new \HttpException(404);
				}
				$Response->setIsFinal(true);
			}
		}
		else {
			$Response = \Router::invokeDefaultAction($Controller, $Request);
		}

		unset($Controller);

		return $Response;

	}

	/**
	 * use this to process generated response
	 * @param \Request
	 * @param \Response
	 * @return \Response to use it as final response and stop processing
	 */
	protected function _afterRespond($Request, $Response) {}

	/**
	 * @param \Request $Request
	 * @return \Response
	 */
	final public function respond($Request) {

		// set up model if not yet set
		$this->_beforeRespond($Request);

		$hasShifted = false;
		// by now I shall have a Model
		if (!$this->_Model->fieldIsEmpty('slug')) {
			$slug = $this->_Model->slug;
			$hasShifted = $Request->shiftUriParts($slug) ? true : false;
		}

		$Response = $this->_processSubmodules($Request);
		if ($Response instanceof \Response) {
			goto finish;
		}

		$Response = $this->_respond($Request, $hasShifted);

		$this->_afterRespond($Request, $Response);

		finish:

		return $Response;
	}

}
