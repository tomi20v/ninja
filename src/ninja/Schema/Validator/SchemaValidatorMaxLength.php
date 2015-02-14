<?php

namespace ninja;

class SchemaValidatorMaxLength extends \maui\SchemaValidatorMaxLength {

	public function toMeta() {
		return [
			'maxLength' => $this->_value,
		];
	}

}
