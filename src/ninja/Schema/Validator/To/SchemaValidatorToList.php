<?php

namespace ninja;

class SchemaValidatorToList extends \maui\SchemaValidatorToList {

	public function toMeta() {
		return [
			'multi' => 'list',
		];
	}

}
