<?php

namespace ninja;

abstract class ModPolymerController extends \ModAbstractController {

	/**
	 * I shall call parent::addPolymerDeps() and then add my polymer deps to the $Asset object
	 * @param \ModPageModelAsset $Asset
	 */
	public static function addPolymerDeps($Asset, $basePath) {}

//	public function actionIndex($params = null) {
//		// @TODO: Change the autogenerated code
//		return parent::actionIndex($params);
//	}


}
