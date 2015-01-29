<?php

namespace ninja;

/**
 * Class ModNavItem
 * @package ninja
 *
 * @property string $href
 * @property string $label
 * @property string $icon
 * @property bool $active
 *
 */
class ModNavItem extends \ModBaseCssModel {

	protected static $_schema = [
		'@@extends' => 'ModBaseCssModel',
		'href',
		'label',
		'icon',
		'active' => [
			'toBool',
		],
	];

}
