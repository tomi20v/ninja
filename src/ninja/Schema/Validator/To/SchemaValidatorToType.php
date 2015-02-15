<?php

namespace ninja;

class SchemaValidatorToType extends \maui\SchemaValidatorToType {

	/**
	 * @return array input type: picker
	 */
	public function toMeta() {
		return [
			'type' => ['hidden',],
		];
	}

}
