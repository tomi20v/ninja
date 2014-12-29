<?php

namespace ninja;

/**
 * Class ViewEngine - view engines should extend me and I provide a factory to get the proper one
 *
 * @package ninja
 */
abstract class ViewEngine {

	/**
	 * @var string fallback for template lookup
	 */
	const DEFAULT_EXTENSION = '';

	/**
	 * @var string base extension (engine will add its own suffix as well)
	 */
	protected $_templateExtension;

	/**
	 * @var string[] default mapping. should be moved to some kind of config?
	 */
	protected static $_defaultExtensionToType = [
		'html' => 'pages',
		'json' => 'api',
	];

	/**
	 * @var string[] defult mapping. should be moved to some kind of config?
	 */
	protected static $_defaultTypeToViewEngine = [
		'pages' => 'Mustache',
		'api' => 'Json',
	];

	/**
	 * @param \ModAbstractModel $Model
	 * @param string $templateExtension
	 */
	public static function fromModel($Model, $templateExtension) {

		$Bubbler = $Model->Bubbler();

		$type = '';
		if (!empty($templateExtension)) {
			$extensionToType = $Bubbler->bubbleModuleGet('extensionToType')
				?: static::$_defaultExtensionToType;
			if (isset($extensionToType[$templateExtension])) {
				$type = $extensionToType[$templateExtension];
			}
		}

		$engine = '';
		if (!empty($type)) {
			$typeToExtension = $Bubbler->bubbleModuleGet('typeToViewEngine')
				?: static::$_defaultTypeToViewEngine;
			if (isset($typeToExtension[$type])) {
				$engine = $typeToExtension[$type];
			}
		}

		// create default null view
		if (empty($engine)) {
			$engine = 'Null';
		}

		$engineClassName = 'ViewEngine' . ucfirst(strtolower($engine));

		$Engine = new $engineClassName($templateExtension);

		return $Engine;

	}

	public function __construct($templateExtension) {
		$this->_templateExtension = $templateExtension;
	}

	/**
	 * @param \View $View the View will be put in template scope as View
	 * @param \Model $Model the Model will be put in scope. Note this is the View's model and not the actual data to
	 * 			work on (though it can contain that as well, especially in Content field)
	 * @param mixed[] $Data will be put in template scope as Data
	 * @return string
	 */
	abstract function render($View, $Model, $Data=null);

}
