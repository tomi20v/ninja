<?php

namespace ninja;

class SchemaValidatorFileReadable extends \maui\SchemaValidatorFileReadable {

	public function toMeta() {
		return [
			'type' => 'picker',
			'subType' => 'file',
		];
	}

}
