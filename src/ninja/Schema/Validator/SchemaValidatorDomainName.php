<?php

namespace ninja;

class SchemaValidatorDomainName extends \maui\SchemaValidatorDomainName {

	public function toMeta() {
		return [
			'type' => 'text',
			'validator' => [ 'regexp', static::DOMAIN_PREG, $this->getError(), ],
		];
	}

}
