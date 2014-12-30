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
		$Model = $this->_Module->getModel();

		$adminItems = [];
		foreach ($adminPlugins as $eachAdminPlugin) {
			$eachPluginClassName = 'Mod' . $eachAdminPlugin . 'Plugin';
			$adminItems = array_merge($adminItems, (array)call_user_func([$eachPluginClassName, $pluginMethod]));
		}

		$Model->Contents = (array)$Model->Contents + ['items'=>$adminItems];

		$this->Asset()->addJsCode(
			\ModPageModel::JS_HEAD,
			'require(["/assets/admin/js/nav"], function(nav) { nav.init(); });'
		);

		return parent::actionIndex($params);
	}

}
