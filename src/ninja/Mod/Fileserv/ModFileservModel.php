<?php

namespace ninja;

class ModFileservModel extends \ModAbstractModel {


	protected static $_schema = [
		'folder' => [
			'folderReadable',
		],
		'basePath' => [
			'toString',
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
