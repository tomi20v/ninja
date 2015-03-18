<?php

namespace ninja;

/**
 * Class ModPolymerCoreModel- I hold common (layout) attributes for polymer elements
 *
 * @package ninja
 *
 * @property bool $layout
 * @property string $layoutDirection
 * @property string $layoutCenter
 * @property string[] $layoutExtra
 * @property string $autoVertical use this for binding eg. '{{var}}' results in vertical?="{{var}}" in template
 * @property string $autoHorizontal see $autoVertical
 *
 */
abstract class ModPolymerCoreModel extends \ModPolymerModel {

	const LAYOUT_HORIZONTAL = 'horizontal';
	const LAYOUT_VERTICAL = 'vertical';

	const LAYOUT_CENTER_CENTER = 'center-center';
	const LAYOUT_CENTER = 'center';
	const LAYOUT_START = 'start';
	const LAYOUT_END = 'end';
	const LAYOUT_JUSTIFIED = 'justified';
	const LAYOUT_CENTER_JUSTIFIED = 'center-justified';
	const LAYOUT_START_JUSTIFIED = 'start-justified';
	const LAYOUT_END_JUSTIFIED = 'end-justified';

	const LAYOUT_BLOCK = 'block';
	const LAYOUT_HIDDEN = 'hidden';
	const LAYOUT_RELATIVE = 'relative';
	const LAYOUT_FIT = 'fit';
	const LAYOUT_FLEX = 'flex';

	protected static $_schema = [
		'@@extends' => 'ModPolymerModel',
		'layout' => [
			'toBool'
		],
		'layoutDirection' => [
			'toString',
			'in' => [
				\ModPolymerCoreModel::LAYOUT_HORIZONTAL,
				\ModPolymerCoreModel::LAYOUT_VERTICAL,
			],
		],
		'layoutCenter' => [
			'toString',
			'in' => [
				\ModPolymerCoreModel::LAYOUT_CENTER_CENTER,
				\ModPolymerCoreModel::LAYOUT_CENTER,
				\ModPolymerCoreModel::LAYOUT_START,
				\ModPolymerCoreModel::LAYOUT_END,
				\ModPolymerCoreModel::LAYOUT_JUSTIFIED,
				\ModPolymerCoreModel::LAYOUT_CENTER_JUSTIFIED,
				\ModPolymerCoreModel::LAYOUT_START_JUSTIFIED,
				\ModPolymerCoreModel::LAYOUT_END_JUSTIFIED,
			],
		],
		'layoutExtra' => [
			'toArray',
			'hasMax' => 0,
			'in' => [
				\ModPolymerCoreModel::LAYOUT_BLOCK,
				\ModPolymerCoreModel::LAYOUT_HIDDEN,
				\ModPolymerCoreModel::LAYOUT_RELATIVE,
				\ModPolymerCoreModel::LAYOUT_FIT,
				\ModPolymerCoreModel::LAYOUT_FLEX,
			]
		],
		'autoVertical',
		'autoHorizontal',
	];


}
