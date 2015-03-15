<?php

namespace ninja;

class SchemaValidatorMaxLength extends \maui\SchemaValidatorMaxLength {

	public function toMeta() {
		return [
			'validator' => [ 'maxLength', $this->_value, $this->getError(), ],
		];
	}

}
