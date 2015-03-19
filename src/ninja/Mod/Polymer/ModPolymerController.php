<?php

namespace ninja;

abstract class ModPolymerController extends \ModAbstractController {

	/**
	 * I shall call parent::addPolymerDeps() and then add my polymer deps to the $Asset object
	 * @param \ModPageModelAsset $Asset
	 */
	public static function addPolymerDeps($Asset, $basePath) {}

	// @todo implement this for automatic polymer deps adding?
//	public function actionIndex($params = null) {
//		static::addPolymerDeps($this->Asset(), $whatHere?:D)
//		return parent::actionIndex($params);
//	}


}
