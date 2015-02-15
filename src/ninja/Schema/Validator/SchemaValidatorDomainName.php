<?php

namespace ninja;

class SchemaValidatorDomainName extends \maui\SchemaValidatorDomainName {

	public function toMeta() {
		return [
			'type' => ['text',],
			'regexp' => [static::DOMAIN_PREG, $this->getError(['{{value}}'=>'']),],
		];
	}

}
