<?php

namespace ninja;

class SchemaValidatorKeysEither extends \maui\SchemaValidatorKeysEither {

	public function toMeta() {
		return [
			'validator' => [ 'keysEither', $this->_value, $this->getError() ],
		];
	}

}
