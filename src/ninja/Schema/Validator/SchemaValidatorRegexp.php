<?php

namespace ninja;


class SchemaValidatorRegexp extends \maui\SchemaValidatorRegexp {

	/**
	 * @return array match regexp. input type text (assumed)
	 */
	public function toMeta() {
		return [
			'type' => 'text',
			'regexp' => $this->_value,
		];
	}
}
