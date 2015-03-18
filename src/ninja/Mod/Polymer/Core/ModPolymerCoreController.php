<?php

namespace ninja;

/**
 * Class ModPolymerCoreController - note I am abstract, so no actionIndex
 *
 * @package ninja
 */
abstract class ModPolymerCoreController extends \ModPolymerController {

	/**
	 * adds common select code for manipulating the main template
	 * @return $this
	 */
	public static function addJsTemplateSelector($obj) {
		$obj->Asset()
			->addJsCode(
				\ModPageModel::JS_FOOT,
//				'var template = document.querySelector(\'template[is="auto-binding"]\')'
				'var template = document.querySelector(\'*[role="mainTemplate"]\')'
			);
	}

}
