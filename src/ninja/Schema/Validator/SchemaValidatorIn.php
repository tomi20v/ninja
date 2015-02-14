<?php

namespace ninja;

class SchemaValidatorIn extends \maui\SchemaValidatorIn {

	public function toMeta() {
		return [
			'in' => $this->_value,
		];
	}

}
