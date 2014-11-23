<?php

namespace ninja;

abstract class ModAbstractModel extends \Model {

	protected static $_schema = array(
		// will hold a reference to direct parent module
		'Parent' => [
			'class' => 'ModAbstractModel',
			'reference' => \SchemaManager::REF_REFERENCE,
		],
		// only published items get ever run
		'published' => [
			'toBool',
		],
		// for routing
		'slug',
		// page root defines valid layers, other modules will be processed only if no layers set or if  are on an active layer
		'Layers' => [
			'toArray',
			'validLayers',
		],
		// override default template filename
		'template',
		// override default template path, relative to NINJA_ROOT
		'templatePath',
		// array of submodules
		'Modules' => [
			'toArray',
			'class' => 'ModAbstractModel',
			'reference' => \SchemaManager::REF_INLINE,
			'hasMin' => 0,
			'hasMax' => 0,
		],
		'Contents' => [
			'toArray',
			'toString',
			'hasMin' => 0,
			'hasMax' => 0,
		],
	);

}

