<?php

namespace ninja;

class ModAdminMenuController extends \ModAdminController {

	public static function addPolymerDeps($Asset, $basePath) {

		// this is my true dependency
		\ModPolymerCoreToolbarController::addPolymerDeps($Asset, $basePath);

		// these I add because pages.php's structure does not involve calling addPolymerDeps yet
		\ModPolymerCoreMenuController::addPolymerDeps($Asset, $basePath);
		\ModPolymerCoreAnimatedpagesController::addPolymerDeps($Asset, $basePath);
		\ModPolymerFlatirondirectorController::addPolymerDeps($Asset, $basePath);

		$Asset
			// @todo core-scaffold shall be added by ModPagePolymer when it will be implemented
			->addImport(\Finder::joinPath($basePath, 'core-scaffold/core-scaffold.html'))
			// this too
			->addImport(\Finder::joinPath($basePath, 'font-roboto/roboto.html'))
//			->addImport(\Finder::joinPath($basePath, 'core-ajax/core-ajax.html'))
		;

	}

	/**
	 * I fetch items for modules which have plugin for admin menu
	 * @param null $params
	 * @return null|\Response
	 */
	public function actionIndex($params = null) {

		// @todo this could be abstracted into the plugin class
		$pluginMethod = 'modAdminMenuGetItems';
		$adminPlugins = \ModManager::findModsWithPluginMethod($pluginMethod);
//		$Model = $this->_Module->getModel();

		$adminItems = [];
		foreach ($adminPlugins as $eachAdminPlugin) {
			$adminItems = array_merge(
				$adminItems,
				(array)call_user_func([\ModManager::getPluginClassnameByModname($eachAdminPlugin), $pluginMethod])
			);
		};
		/**
		 * @var \ModNavItem $eachAdminItem
		 */
		foreach ($adminItems as &$eachAdminItem) {
			$eachAdminItem = $eachAdminItem->Data()->getData();
		};

		// add items to $Contents
//		$Model->Contents = (array)$Model->Contents + ['items'=>$adminItems];

		\ModPolymerCoreController::addJsTemplateSelector($this);
		$this->Asset()
			->addJsVar(\ModPageModel::JS_FOOT, 'template.appData', $adminItems);

/*		// NOTE: polymer deps are not added as this mod is temporarly used only for the pages var output
		// never forget calling this if necessary
		$this->addPolymerDeps($this->Asset(), '/assets/bower-asset');

		// add admin deps by plugins
		// @todo this could be abstracted into the plugin class or to the addPolymerDeps() in each mod's controller
		$pluginMethod = 'modAdminGetImports';
		$pluginsWithDeps = \ModManager::findModsWithPluginMethod($pluginMethod);
		$deps = [];
		foreach ($pluginsWithDeps as $eacDepPlugin) {
			$deps = array_merge(
				$deps,
				(array)call_user_func([\ModManager::getPluginClassnameByModname($eacDepPlugin), $pluginMethod])
			);
		}
		$deps = array_unique($deps);

		// @todo I shall use some kind of $basePath here
		$Asset = $this->Asset();
		foreach ($deps as $eachDep) {
			$Asset->addImport($eachDep);
		}
*/
		return parent::actionIndex($params);
	}

}
