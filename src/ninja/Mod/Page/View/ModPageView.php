<?php

namespace ninja;

class ModPageView extends \ModAbstractView {

	public function get_scriptsHead() {
		$scripts = $this->_Model->getField('scripts');
		//echop($scripts);
		foreach ($scripts as $eachKey=>$eachScript) {
			if (isset($eachScript['place']) && ($eachScript['place'] !== \ModPageModel::JS_HEAD)) {
				unset($scripts[$eachKey]);
			}
		}
		return $scripts;
	}

}
