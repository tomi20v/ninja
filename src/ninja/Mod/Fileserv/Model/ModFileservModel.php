<?php

namespace ninja;

class ModFileservModel extends \ModAbstractModel {


	protected static $_schema = [
		'folder' => [
//			'folderReadable',
		],
		'recursive' => [
			'toBool',
		],
		'files' => [
			'toString',
			'fileReadable' => '=folder',
			'hasMin' => 0,
			'hasMax' => 0,
		]
	];


}
