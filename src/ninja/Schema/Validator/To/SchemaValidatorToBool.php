<?php

namespace ninja;

class SchemaValidatorToBool extends \maui\SchemaValidatorToBool {

	public function toMeta() {
		return [
			'type' => ['checkbox',],
		];
	}

}
