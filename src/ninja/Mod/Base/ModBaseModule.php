<?php

namespace ninja;

class ModBaseModule extends \ModAbstractModule {

	/**
	 * @todo implement me
	 * @param \Request $Request
	 * @return \Response|null
	 */
	public function _beforeRespond($Request) {
		return parent::_beforeRespond($Request);
	}

	/**
	 * @param \Request $Request
	 * @return \Response|null
	 */
	public function _respond($Request) {
		return parent::_respond($Request);
	}


}