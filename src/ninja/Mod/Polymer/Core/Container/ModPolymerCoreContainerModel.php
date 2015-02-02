<?php

namespace ninja;

/**
 * Class ModPolymerCoreContainerModel - contain anything in any element, with polymer css and layout attributes
 *
 * @package ninja
 *
 * @property string $containerEl used in template for wrapper element
 *
 */
class ModPolymerCoreContainerModel extends \ModPolymerCoreModel {

	protected static $_schema = [
		'@@extends' => 'ModPolymerCoreModel',
		'containerEl' => [
			'toString',
			'default' => 'div',
		],
	];


}
