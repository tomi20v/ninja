<?php

namespace ninja;

/**
 * Class ModPolymerCoreMenuModel
 *
 * @package ninja
 *
 * @property string $valueAttr eg. 'hash'
 * @property string $selected eg. '{{route}}'
 * @property string $selectedModel eg. '{{selectedPage}}'
 * @property string $repeat eg. '{{ page, i in pages}}'
 * @property string $repeatHash eg. '{{page.hash}}'
 * @property string $repeatLabel eg. '{{page.name}}'
 * @property string $repeatIcon eg. '{{page.icon}}'
 * @property string $onCoreSelect
 *
 */
class ModPolymerCoreMenuModel extends \ModPolymerCoreModel {


	protected static $_schema = [
		'@@extends' => 'ModPolymerCoreModel',
		'valueAttr',
		'selected',
		'selectedModel',
		'repeat',
		'repeatHash' => [
			'toString',
//			'mandatoryWith' => ['repeat'],
		],
		'repeatLabel',
		'repeatIcon',
		'onCoreSelect',
	];


}
