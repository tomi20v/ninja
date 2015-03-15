<?php

namespace ninja;

class SchemaValidatorMinLength extends \maui\SchemaValidatorMinLength {

	public function toMeta() {
		return [
			'validator' => [ 'minLength', $this->_value, $this->getError(), ],
		];
	}

}
