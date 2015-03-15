<?php

namespace ninja;

use mixed;

class SchemaValidatorMaxValue extends \maui\SchemaValidatorMaxValue {

	public function toMeta() {
		return [
			'validator' => [ 'maxValue', $this->_value, $this->getError(), ],
		];
	}

}
