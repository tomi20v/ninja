<?php

namespace ninja;

class SchemaValidatorKeysValues extends \maui\SchemaValidatorKeysValues {

	public function toMeta() {
		return [
			'keysValues' => ['key'=>$this->_value[0],'values'=>$this->_value[1]],
		];
	}

}
