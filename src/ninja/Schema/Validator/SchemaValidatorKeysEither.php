<?php

namespace ninja;

class SchemaValidatorKeysEither extends \maui\SchemaValidatorKeysEither {

	public function toMeta() {
		return [
			'keysEither' => [$this->_value, $this->getError(['{{value}}'=>''])],
		];
	}

}
