<?php

namespace ninja;

/**
 * I add toMeta() functionality
 */
class SchemaValidatorUnique extends \maui\SchemaValidatorUnique {

	public function toMeta() {
		return [
			'remote' => true,
		];
	}

}
