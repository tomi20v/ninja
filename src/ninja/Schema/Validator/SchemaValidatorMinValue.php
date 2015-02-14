<?php

namespace ninja;

class SchemaValidatorMinValue extends \maui\SchemaValidatorMinValue {

	/**
	 * @return array validate minimum value
	 */
	public function toMeta() {
		return [
			'minValue' => $this->_value,
		];
	}

}
