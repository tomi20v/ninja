<?php

namespace ninja;

class ModPageView extends \ModAbstractView {

	public function getWithRoot($key) {
		return $this->_Model->getFieldWithRoot($key);
	}

	/**s
	 * @todo I should rather implement a universal getFieldWithRoot() in ModPageModel
	 * @return array
	 */
	protected function _getScripts() {
		// I might have to add some filter logic here to check if Root is set and not the same
		$scripts = array_merge((array)$this->_Model->getField('scripts'), (array)$this->_Model->getField('Root')->getField('scripts'));
		$scripts = \maui\ArrayHelper::arrayUnique($scripts);
		return $scripts;
	}

	/**
	 * I return scripts belonging to the head (or nowhere)
	 * @return array|null
	 */
	public function getScriptsHead() {
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
	public function getScriptsFoot() {
		$scripts = $this->_getScripts();
		foreach ($scripts as $eachKey=>$eachScript) {
			if (!isset($eachScript['place']) || ($eachScript['place'] !== \ModPageModel::JS_FOOT)) {
				unset($scripts[$eachKey]);
			}
		}
		return $scripts;
	}

	public function getCss() {
		$css = array_merge((array)$this->_Model->getField('css'), (array)$this->_Model->getField('Root')->getField('css'));
		return $css;
	}

}
