<?php

namespace ninja;

class SchemaValidatorToId extends \maui\SchemaValidatorToId {

	public function toMeta() {
		return [
			'type' => 'text',
			'subType' => 'short',
			'infoOnly' => true,
		];
	}

}
