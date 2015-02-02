<?php

namespace ninja;

/**
 * Class ModPolymerModel - very basic, non polymer related attributes
 *
 * @package ninja
 * @property string $cssId
 * @property string[] $cssClasses
 * @property string $cssStyle
 * @property string $extraAttributes what doesn't fit elsewhere can be put here
 *
 */
abstract class ModPolymerModel extends \ModAbstractModel {

	protected static $_schema = [
		'@@extends' => 'ModAbstractModel',
		'cssId',
		'cssClasses' => [
			'toArray',
			'hasMin' => 0,
			'hasMax' => 0,
		],
		'cssStyle',
		'extraAttributes',
	];


}
