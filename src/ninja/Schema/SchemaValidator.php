<?php

namespace ninja;

/**
 * I add toMeta() functionality
 */
class SchemaValidator extends \maui\SchemaValidator {

	/**
	 * @return mixed[] return meta info for frontend admin engine. default empty array
	 */
	public function toMeta() {
		return [];
	}

}
