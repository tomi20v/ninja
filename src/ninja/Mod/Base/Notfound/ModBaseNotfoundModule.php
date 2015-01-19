<?php

namespace ninja;

/**
 * Class ModBaseNotfoundModule - will just set a standard 404 response
 *
 * @package ninja
 */
class ModBaseNotfoundModule extends \ModBaseModule {

	public function _beforeRespond($Request) {
		throw new \HttpException(404);
	}

}
