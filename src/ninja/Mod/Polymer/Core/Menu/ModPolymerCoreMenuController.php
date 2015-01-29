<?php

namespace ninja;

class ModPolymerCoreMenuController extends \ModPolymerCoreController {

	/**
	 * I add imports I print in the main template. Call me if your template invokes mine to add my deps
	 */
	public static function addDeps($Asset) {

		$Asset
			->addImport('/assets/bower-asset/core-menu/core-menu.html')
			->addImport('/assets/bower-asset/paper-item/paper-item.html')
			->addImport('/assets/bower-asset/core-icon/core-icon.html');

	}

	public function actionIndex($params = null) {
		// @TODO: Change the autogenerated code
		return parent::actionIndex($params);
	}


}
