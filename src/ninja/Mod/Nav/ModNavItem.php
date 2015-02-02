<?php

namespace ninja;

/**
 * Class ModNavItem - I define an item in nav menu. Eg. admin menu plugins return instances of me
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
