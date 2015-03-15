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
			'validators' => [],
		];
		if ($this->_required) {
			$meta['required'] = true;
		}
		foreach ($this->_validators as $EachValidator) {
			// validators that relate to another fields value can be run on server side only
			if ($EachValidator->resolvesToModel()) {
				$meta['remote'] = true;
				continue;
			}
			$eachMeta = $EachValidator->toMeta();
			//$meta = array_merge($meta, $eachMeta);
			foreach ($eachMeta as $eachKey=>$eachVal) {
				if ($eachKey === 'validator') {
					$meta['validators'][] = [
						\Schema::VALIDATOR_KEY_TYPE => $eachVal[\Schema::VALIDATOR_KEY_TYPE],
						\Schema::VALIDATOR_KEY_VALUE => $eachVal[\Schema::VALIDATOR_KEY_VALUE],
						\Schema::VALIDATOR_KEY_ERROR => $eachVal[\Schema::VALIDATOR_KEY_ERROR],
					];
				}
				else {
					$meta[$eachKey] = $eachVal;
				}
			}
		}
		if (!isset($meta['type'])) {
			$meta['type'] = 'text';
		}
		return $meta;
	}

}
