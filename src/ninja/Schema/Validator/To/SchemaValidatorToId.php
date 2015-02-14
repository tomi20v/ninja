<?php

namespace ninja;

class SchemaValidatorToId extends \maui\SchemaValidatorToId {

	public function toMeta() {
		return [
			'type' => 'hidden',
		];
	}

}
