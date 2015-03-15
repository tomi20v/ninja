<?php

namespace ninja;

/**
 * Class SchemaValidatorKeysRequired - value shall be a list of required keys. use with toArray validator
 *
 * @package ninja
 */
class SchemaValidatorKeysRequired extends \maui\SchemaValidatorKeysRequired {

	public function toMeta() {
		return [
			'validator' => [ 'keysRequired', $this->_value, $this->getError(), ],
		];
	}

}
