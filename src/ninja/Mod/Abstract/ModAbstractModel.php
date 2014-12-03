<?php

namespace ninja;

/**
 * Class ModAbstractModel
 *
 * @package ninja
 *
 * @property \ModAbstractModel $Parent
 * @property bool $published
 * $property string $slug
 * @property string[] $layers
 * @property string $templatePath
 * @property string $template
 * @property \ModAbstractModule[] $Modules
 * @property string[] $Contents
 *
 */
abstract class ModAbstractModel extends \Model {

	protected static $_schema = array(
		// will hold a reference to direct parent module's model
		'Parent' => [
			'class' => 'ModAbstractModel',
			'reference' => \SchemaManager::REF_REFERENCE,
		],
		// only published items get ever run
		'published' => [
			'toBool',
		],
		// for routing
		'slug' => [
			'toString',
			// should not contain any dots
			'regexp' => '/^[^.]+$',
		],
		// page root defines valid layers, other modules will be processed only if no layers set or if  are on an active layer
		'layers' => [
			'toArray',
			'validLayers',
		],
		// override default template path, relative to NINJA_ROOT
		'templatePath',
		// override default template filename
		'template',
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

