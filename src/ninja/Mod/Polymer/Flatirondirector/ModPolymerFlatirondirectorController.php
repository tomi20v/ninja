<?php

namespace ninja;

class ModPolymerFlatirondirectorController extends \ModPolymerController {

	public static function addPolymerDeps($Asset, $basePath) {

		parent::addPolymerDeps($Asset, $basePath);

		$Asset
			->addImport(\Finder::joinPath($basePath, 'flatiron-director/flatiron-director.html'))
			;

	}

//	public function actionIndex($params = null) {
//		$this->Asset()
//			->addImport('/assets/bower-asset/flatiron-director/flatiron-director.html');
//		return parent::actionIndex($params);
//	}

}
