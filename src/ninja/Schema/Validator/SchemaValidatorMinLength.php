<?php

namespace ninja;

class SchemaValidatorMinLength extends \maui\SchemaValidatorMinLength {

	public function toMeta() {
		return [
			'minLength' => $this->_value,
		];
	}

}
