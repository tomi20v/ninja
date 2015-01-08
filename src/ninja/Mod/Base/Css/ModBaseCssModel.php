<?php

namespace ninja;

/**
 * Class ModBaseCssModel
 *
 * @package ninja
 *
 * @property string $cssId
 * @property string[] $cssClasses
 * @property string $cssStyle - add custom inline style if needed
 * @property string $containerEl - container element (if template includes ModBaseCss-begin and -end), defaults to div
 * @property bool $extraAttributes - specify eg. for polymer elements
 *
 */
class ModBaseCssModel extends \ModAbstractModel {

	protected static $_schema = [
		'@@extends' => 'ModAbstractModel',
		'cssId',
		'cssClasses' => [
			'toArray',
			'hasMin' => 0,
			'hasMax' => 0,
		],
		'cssStyle',
		'containerEl' => [
			'toString',
			'default' => 'div',
		],
		'extraAttributes' => [
			'toString',
		]
	];

}

