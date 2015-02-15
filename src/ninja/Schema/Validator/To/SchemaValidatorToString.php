<?php

namespace ninja;

class SchemaValidatorToString extends \maui\SchemaValidatorToString {

	/**
	 * @return array input type: text
	 */
	public function toMeta() {
		return [
			'type' => ['text', 'text',],
		];
	}

}
