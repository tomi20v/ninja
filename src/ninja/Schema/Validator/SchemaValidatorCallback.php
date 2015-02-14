<?php

namespace ninja;

class SchemaValidatorCallback extends \maui\SchemaValidatorCallback {

	public function toMeta() {
		return [
			'remote' => true,
		];
	}

}
