<?php

namespace ninja;

class SchemaValidatorFolderReadable extends \maui\SchemaValidatorFolderReadable {

	public function toMeta() {
		return [
			'type' => 'picker',
			'subType' => 'folder',
		];
	}

}
