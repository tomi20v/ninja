<?php

namespace ninja;

class ModPageView extends \ModAbstractView {

	/**
	 * I return scripts belonging to the head (or nowhere)
	 * @return array|null
	 */
	public function get_scriptsHead() {
		$scripts = $this->_Model->getField('scripts');
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
		$scripts = $this->_Model->getField('scripts');
		foreach ($scripts as $eachKey=>$eachScript) {
			if (!isset($eachScript['place']) || ($eachScript['place'] !== \ModPageModel::JS_FOOT)) {
				unset($scripts[$eachKey]);
			}
		}
		return $scripts;
	}

}
