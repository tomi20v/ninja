<?php

namespace ninja;

class ModAdminMenuController extends \ModAbstractController {

	/**
	 * I fetch items for modules which have plugin for admin menu
	 * @param null $params
	 * @return null|\Response
	 */
	public function actionIndex($params = null) {

		$pluginMethod = 'modAdminMenuGetItems';
		$adminPlugins = \ModManager::findModsWithPlugin($pluginMethod);
		$adminItems = [];
		foreach ($adminPlugins as $eachAdminPlugin) {
			$eachPluginClassName = 'Mod' . $eachAdminPlugin . 'Plugin';
			$adminItems = array_merge($adminItems, (array)call_user_func([$eachPluginClassName, $pluginMethod]));
		}

		$this->_Module->getModel()->Contents = (array)$this->_Module->getModel()->Contents + ['items'=>$adminItems];

		return parent::actionIndex($params);
	}

}
