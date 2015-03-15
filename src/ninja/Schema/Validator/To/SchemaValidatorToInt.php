<?php

namespace ninja;

class SchemaValidatorToInt extends \maui\SchemaValidatorToInt {

	/**
	 * @return array input type: number
	 */
	public function toMeta() {
		return [
			'type' => 'number',
			'subType' => 'int',
		];
	}

}
