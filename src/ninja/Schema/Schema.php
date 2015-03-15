<?php

namespace ninja;

/**
 * Class Schema
 *
 */
class Schema extends \maui\Schema {

	/**
	 * we represent the anti-pattern how to abuse index def constants :)
	 */
	const VALIDATOR_KEY_TYPE = 0;

	/**
	 * we represent the anti-pattern how to abuse index def constants :)
	 */
	const VALIDATOR_KEY_VALUE = 1;

	/**
	 * we represent the anti-pattern how to abuse index def constants :)
	 */
	const VALIDATOR_KEY_ERROR = 2;

	/**
	 * @return array
	 */
	public function toMeta() {
		$meta = [];
		foreach ($this as $eachKey=>$EachField) {
			$eachMeta = $EachField->toMeta();
			if (empty($eachMeta['group'])) {
				$eachMeta['group'] = $EachField->getContext();
			}
			$meta[$eachKey] = $eachMeta;
		}
		return $meta;
	}

}
