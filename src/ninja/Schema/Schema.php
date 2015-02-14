<?php

namespace ninja;

/**
 * Class Schema
 *
 */
class Schema extends \maui\Schema {

	/**
	 * @return array
	 */
	public function toMeta() {
		$meta = [];
		foreach ($this as $eachKey=>$EachField) {
			echop($EachField);
			$meta[$eachKey] = $EachField->toMeta();
		}
		return $meta;
	}

}
