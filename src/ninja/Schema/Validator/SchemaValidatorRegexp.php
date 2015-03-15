<?php

namespace ninja;


class SchemaValidatorRegexp extends \maui\SchemaValidatorRegexp {

	/**
	 * @var string simple regexp to check against 38 basic characters. created for nice url parts
	 */
	const STRING_ALNUM38 = '/^[a-z0-9_\-]*$/';

	/**
	 * @var string simple regexp to check against 64 basic characters. like STRING_ALNUM38 but uppercase included
	 */
	const STRING_ALNUM64 = '/^[a-zA-Z0-9_\-]*$/';

	/**
	 * @return array match regexp. input type text (assumed)
	 */
	public function toMeta() {
		return [
			'type' => 'text',
			'validator' => [ 'regexp', $this->_value, $this->getError(), ],
		];
	}

}
