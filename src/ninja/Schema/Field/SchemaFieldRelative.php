<?php

namespace ninja;

class SchemaFieldRelative extends \maui\SchemaFieldRelative {

	public function toMeta() {
		$meta = parent::toMeta();
		$meta['type'] = 'picker';
		$meta['subType'] = 'picker';
		$meta['remote'] = 'true';
		return $meta;
	}

}
