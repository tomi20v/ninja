<?php

namespace ninja;

/**
 * Class ModelBubbler - I get/set inherited properties traversing tree structure up by 'Parent' field
 *
 * @package maui
 */
class ModelBubbler extends \maui\ModelBubbler {

	protected $_Module;

	public function __construct($Model, $Module) {

		$this->_Model = $Model;
		$this->_Module = $Module;

	}

	/**
	 * I return the first value found on field $key
	 * @param $key
	 * @return $this|Model|null
	 */
	public function bubbleModuleGet($key) {

		return $this->_bubbleModuleGet($this->_Module, $key);

	}

	/**
	 * @param \Model $Model
	 * @param \ModAbstractModule $Module
	 * @param mixed $key
	 */
	protected function _bubbleModuleGet($Module, $key) {

		$Model = $Module->getModel();
		if (($Model instanceof \ninja\ModAbstractModel) && $Model->fieldIsSet($key)) {
			return $Model->$key;
		}

		$ParentModule = $Module->getParent();
		if ($ParentModule instanceof \ninja\ModAbstractModule) {
			return $this->_bubbleModuleGet($ParentModule, $key);
		}

		return null;

	}

}
