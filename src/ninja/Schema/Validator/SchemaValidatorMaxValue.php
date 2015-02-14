<?php

namespace ninja;

use mixed;

class SchemaValidatorMaxValue extends \maui\SchemaValidatorMaxValue {

	public function toMeta() {
		return [
			'maxValue' => $this->_value,
		];
	}

}
