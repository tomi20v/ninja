<?php

namespace ninja;

/**
 * Class ModRouter - some abstraction related to routing to make ModAbstractModule lighter
 *
 * @package ninja
 */
class ModRouter {

	/**
	 * I return default controller instance. should consider remaining route?
	 * @param \Request $Request
	 * @param \ModAbstractModule $Module
	 * @param \ModAbstractModel $Model
	 * @return \ModAbstractController
	 */
	public static function getController($Request, $Module, $Model) {

		$controllerClassname = 'Mod' . $Module->getModName() . 'Controller';
		if (!class_exists($controllerClassname)) {
			$controllerClassname = 'ModBaseController';
		}

		$Controller = new $controllerClassname(
			$Request,
			$Module,
			$Model
		);

		return $Controller;
	}

	/**
	 * I seek and invoke the longest controller action possible, matching the request method, or falling back to actionXxx
	 * @param \ModAbstractController Controller
	 * @param \Request $Request
	 */
	public static function invokeControllerAction($Controller, $Request) {

		$actionParts = $Request->getRemainingUriParts();
		$remainingActionParts = [];
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
					$params = $Request->request->all();
					$Response = call_user_func([$Controller, $eachAction], $remainingActionParts, $params);
					$Request->setActionMatched();
					break 2;
				}
			}

			$actionPart = array_pop($actionParts);
			array_unshift($remainingActionParts, $actionPart);

		}

		// normally this shall happen for simple modules, without specific actions
		if (is_null($Response)) {
			$Response = call_user_func([$Controller, 'actionIndex'], $remainingActionParts);
		}

		if (!$Response instanceof \Response) {
			$View = $Controller->getView();
			$Response = $View instanceof \View
				? new \Response($View)
				: null;
		}

		return $Response;
	}

	/**
	 * I return the response of the controller's default action
	 * @param $Controller
	 * @param $Request
	 * @return mixed
	 */
	public static function invokeDefaultAction($Controller, $Request) {
		return $Controller->actionIndex($Request);
	}

	/**
	 * I return hmvc url for a given model
	 * @param \ModAbstractModel $Model
	 * @return null|string
	 */
	public static function getHmvcUrl($Model) {

		$url = null;

		if (!$Model->fieldIsEmpty('slug')) {

			$url = $Model->getBubbler()->bubbleGetAll('slug', false);

			$url = implode('/', $url);

		}

		return $url;

	}


}
