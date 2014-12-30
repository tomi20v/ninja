<?php

namespace ninja;

class ModAdminMenuController extends \ModAdminController {

	/**
	 * I fetch items for modules which have plugin for admin menu
	 * @param null $params
	 * @return null|\Response
	 */
	public function actionIndex($params = null) {

		$pluginMethod = 'modAdminMenuGetItems';
		$adminPlugins = \ModManager::findModsWithPlugin($pluginMethod);
		$Model = $this->_Module->getModel();

		$adminItems = [];
		foreach ($adminPlugins as $eachAdminPlugin) {
			$eachPluginClassName = 'Mod' . $eachAdminPlugin . 'Plugin';
			$adminItems = array_merge($adminItems, (array)call_user_func([$eachPluginClassName, $pluginMethod]));
		}

		// add items to $Contents
		$Model->Contents = (array)$Model->Contents + ['items'=>$adminItems];

		// add nav js with init
		$this->_addAdminJs('nav');

		return parent::actionIndex($params);
	}

}
