<?php

namespace ninja;

/**

 * Class Router - some abstraction related to routing to make ModAbstractModule
 * lighter. I shall create Controller and invoke its action. I return a Response
 * object.
 * @todo I shall implement stuff like module traversing based in a given
 * criteria ('find a module that exposes user data'), but I might take another
 * approach
 *
 * @package ninja
 */
class Router {

	/**
	 * I return default controller instance. should consider remaining route?
	 * @param \Request $Request
	 * @param \ModAbstractModule $Module
	 * @param \ModAbstractModel $Model
	 * @return \ModAbstractController
	 */
	public static function getController($Request, $Module) {

		$controllerClassname = 'Mod' . $Module->getModName() . 'Controller';
		if (!class_exists($controllerClassname)) {
			$controllerClassname = 'ModBaseController';
		}

		$Controller = new $controllerClassname(
			$Request,
			$Module
		);

		return $Controller;

	}

	/**
	 * I seek and invoke the longest controller action possible, matching the request method, or falling back to actionXxx
	 * @param \ModAbstractController Controller
	 * @param \Request $Request
	 * @return \Response
	 */
	public static function invokeControllerAction($Controller, $Request) {

		$actionParts = $Request->getRemainingUriParts();
		$requestMethod = strtolower($Request->getMethod());
		$Response = null;

		if (empty($actionParts)) {
			$actionParts[] = 'index';
		}
		while (count($actionParts)) {

			$actions = [];

			$actions[] = $requestMethod . \ArrayHelper::camelJoin($actionParts);
			$actions[] = 'action' . \ArrayHelper::camelJoin($actionParts);

			foreach ($actions as $eachAction) {
				// maybe this would be faster with a reflectionclass and getting all methods then just searching in the array?
				if (method_exists($Controller, $eachAction)) {
					$Request->shiftUriParts($actionParts);
					$Controller->before();
					$params = $Request->request->all();
					$Response = call_user_func([$Controller, $eachAction], $params);
					$Request->setActionMatched(true);
					break 2;
				}
			}

			array_pop($actionParts);

		}

		// normally this shall happen for simple modules, without specific actions
		if (is_null($Response)) {
			$Response = call_user_func([$Controller, 'actionIndex']);
		}

		if (!$Response instanceof \Response) {
			$Response = new \Response($Response);
		}

		return $Response;

	}

	/**
	 * I return the response of the controller's default action
	 * @param \ModAbstractController $Controller
	 * @param \Request $Request
	 * @return \Response
	 */
	public static function invokeDefaultAction($Controller, $Request) {
		return \Response::wrap($Controller->actionIndex($Request));
	}

	/**
	 * I return hmvc url for a given model
	 * @param \ModAbstractModel $Model
	 * @return null|string
	 */
	public static function getHmvcPath($Model) {

		$url = null;

		if (!$Model->Data()->fieldIsEmpty('slug')) {

			$url = $Model->Bubbler()->bubbleGetAll('slug', false);

			$url = implode('/', $url);

		}

		return $url;

	}

	/**
	 * I return a hmvc uri (without domain) with extension targeted at TargetModel
	 * @param $Model
	 * @param $TargetModel
	 * @param $extension
	 * @throws \Exception
	 */
	public static function getHmvcUri($Model, $TargetModel, $extension) {
		throw new \Exception('TBI');
	}


}
