<?php

namespace ninja;

abstract class ModuleModel extends \Model {

	protected static $_schema = array(
		// will hold a reference to direct parent module
		'Parent' => array(
			'class' => 'ModuleModel',
			'reference' => \SchemaManager::REF_REFERENCE,
		),
		// override default template filename
		'template',
		// override default template path, relative to NINJA_ROOT
		'templatePath',
		// only published items get ever run
		'published',
		// array of submodules
		'Modules' => array(
			'toArray',
			'class' => 'ModuleModel',
			'reference' => \SchemaManager::REF_INLINE,
			'hasMin' => 0,
			'hasMax' => 0,
		),
//		'content' => array(
//			'toString',
//			// @todo implement this
////			'noSave',
//		),
		'Contents' => array(
			'toArray',
			'toString',
			'hasMin' => 0,
			'hasMax' => 0,
		),
	);

}

