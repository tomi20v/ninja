<?php

namespace ninja;

class ModBaseIncludeModule extends \ModBaseModule {


	public function _beforeRespond() {
		return parent::_beforeRespond();
	}

	public function _respond() {
		//return parent::_respond();
		$template = $this->_Model->template;

	}


}
