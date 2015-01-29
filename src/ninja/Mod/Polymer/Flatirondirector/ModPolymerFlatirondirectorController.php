<?php

namespace ninja;

class ModPolymerFlatirondirectorController extends \ModPolymerController {

	public function actionIndex($params = null) {
		$this->Asset()
			->addImport('/assets/bower-asset/flatiron-director/flatiron-director.html');
		return parent::actionIndex($params);
	}


}
