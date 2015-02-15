<?php

namespace ninja;

class SchemaValidatorKeys extends \maui\SchemaValidatorKeys {

	public function toMeta() {
		return [
			'keys' => [$this->_value, $this->getError(['{{value}}'=>'']),],
		];
	}

}
