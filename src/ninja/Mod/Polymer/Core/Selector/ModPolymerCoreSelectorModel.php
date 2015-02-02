<?php

namespace ninja;

/**
 * Class ModPolymerCoreSelectorModel - I provide attributes and a template to invoke.
 *
 * @package ninja
 *
 * @property string $selected
 * @property bool $multi
 * @property string $valueAttr
 * @property string $selectedClass
 * @property string $selectedProperty
 * @property string $selectedAttribute
 * @property string $selectedItem
 * @property string $selectedModel
 * @property string $selectedIndex
 * @property string $excludedLocalNames
 * @property string $target
 * @property string $itemsSelector
 * @property string $activateEvent
 * @property bool $notap
 *
 */
class ModPolymerCoreSelectorModel extends \ModPolymerCoreModel {

	protected static $_schema = [
		'@@extends' => 'ModPolymerCoreModel',
		'selected',
		'multi' => ['toBool',],
		'valueAttr',
		'selectedClass',
		'selectedProperty',
		'selectedAttribute',
		'selectedItem',
		'selectedModel',
		'selectedIndex',
		'excludedLocalNames',
		'target',
		'itemsSelector',
		'activateEvent',
		'notap' => ['toBool',],
	];

}
