<?php

namespace ninja;

class SchemaValidatorKeys extends \maui\SchemaValidatorKeys {

	public function toMeta() {
		return [
			'validator' => [ 'keys', $this->_value, $this->getError(), ],
		];
	}

}
