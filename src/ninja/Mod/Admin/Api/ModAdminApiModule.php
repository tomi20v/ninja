<?php

namespace ninja;

class ModAdminApiModule extends \ModAdminModule {

//	public function _beforeRespond($Request) {
//		// @TODO: Change the autogenerated code
//		return parent::_beforeRespond($Request);
//	}

	public function _respond($Request, $hasShifted) {
		if ($Request->getRequestedExtension() !== 'json') {
			throw new \HttpException(\Response::HTTP_NOT_FOUND);
		}
		return parent::_respond($Request, $hasShifted);
	}

}