<?php

namespace ninja;

class SchemaValidatorToType extends \maui\SchemaValidatorToType {

	/**
	 * @return array input type: picker
	 */
	public function toMeta() {
		return [
			'type' => 'picker',
			// not sure if this needed or will be served by fieldname automaticly - aiming for that
			//'pickType' => $this->_value,
		];
	}

}
