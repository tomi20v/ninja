<?php

namespace ninja;

/**
 * Class ModFileservModel
 *
 * @package ninja
 *
 * @property \ModAbstractModel $Parent
 * @property string $folder
 * @property string $basePath
 * @property bool $recursive
 * @property string[] $files
 */
class ModFileservModel extends \ModAbstractModel {


	protected static $_schema = [
		// will hold a reference to direct parent module's model
		'Parent' => [
			'class' => 'ModAbstractModel',
			'reference' => \SchemaManager::REF_REFERENCE,
		],
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
