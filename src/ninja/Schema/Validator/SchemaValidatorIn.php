<?php

namespace ninja;

class SchemaValidatorIn extends \maui\SchemaValidatorIn {

	public function toMeta() {
		return [
			'validator' => [ 'in',  $this->_value, $this->getError(), ],
		];
	}

}
