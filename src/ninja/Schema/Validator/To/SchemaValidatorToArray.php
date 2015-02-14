<?php

namespace ninja;

class SchemaValidatorToArray extends \maui\SchemaValidatorToArray {

	public function toMeta() {
		return [
			'multi' => 'array',
		];
	}

}
