<?php

namespace ninja;

/**
 * Class SchemaFieldAbstract - I add toMeta() functionality
 *
 * @package ninja
 */
abstract class SchemaFieldAbstract extends \maui\SchemaFieldAbstract {

	public function toMeta() {
		$meta = [
			'key' => $this->_key,
			'required' => $this->_required ? true : false,
		];
		foreach ($this->_validators as $EachValidator) {
			// validators that relate to another fields value can be run on server side only
			if ($EachValidator->resolvesToModel()) {
				$meta['remote'] = true;
				continue;
			}
			$eachMeta = $EachValidator->toMeta();
			$meta = array_merge($meta, $eachMeta);
		}
		return $meta;
	}

}
