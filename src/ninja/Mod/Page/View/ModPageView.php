<?php

namespace ninja;

class ModPageView extends \ModAbstractView {

	const MASK_WITHROOT = '/^get_withRoot_([a-zA-Z0-9_]+)$/';

	public function __call($method, $arguments) {
		if (preg_match(self::MASK_WITHROOT, $method, $matches)) {
			return $this->_get_withRoot($matches[1]);
		}
		return parent::__call($method, $arguments);
	}

	protected function _get_withRoot($key) {
		return $this->_Model->getFieldWithRoot($key);
	}

	/**s
	 * @todo I should rather implement a universal getFieldWithRoot() in ModPageModel
	 * @return array
	 */
	protected function _getScripts() {
		// I might have to add some filter logic here to check if Root is set and not the same
		$scripts = array_merge((array)$this->_Model->getField('scripts'), (array)$this->_Model->getField('Root')->getField('scripts'));
		$scripts = \Finder::arrayUnique($scripts);
		return $scripts;
	}

	/**
	 * I return scripts belonging to the head (or nowhere)
	 * @return array|null
	 */
	public function get_scriptsHead() {
		$scripts = $this->_getScripts();
		foreach ($scripts as $eachKey=>$eachScript) {
			if (isset($eachScript['place']) && ($eachScript['place'] !== \ModPageModel::JS_HEAD)) {
				unset($scripts[$eachKey]);
			}
		}
		return $scripts;
	}

	/**
	 * I return scripts which belong to page foot
	 * @return array|null
	 */
	public function get_scriptsFoot() {
		$scripts = $this->_getScripts();
		foreach ($scripts as $eachKey=>$eachScript) {
			if (!isset($eachScript['place']) || ($eachScript['place'] !== \ModPageModel::JS_FOOT)) {
				unset($scripts[$eachKey]);
			}
		}
		return $scripts;
	}

	public function get_css() {
		$css = array_merge((array)$this->_Model->getField('css'), (array)$this->_Model->getField('Root')->getField('css'));
		return $css;
	}

}
