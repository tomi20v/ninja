<?php

namespace ninja;

class ModShMainController extends \ModPolymerController {

	/**
	 * I add imports I print in the main template. Call me if your template invokes mine to add my deps
	 */
	public static function addPolymerDeps($Asset, $basePath) {

		$Asset->addImport(\Finder::joinPath($basePath, 'sh-main.html'));
	}

	public function actionIndex($params = null) {
		static::addPolymerDeps($this->Asset(), '/assets/pool');
		return parent::actionIndex($params);
	}


}
