<?php

namespace ninja;

/**
 * Class ModNavBarModel
 * @package ninja
 *
 * @property bool $inverse
 * @property string $fixed
 */
class ModNavBarModel extends \ModBaseCssModel {


	protected static $_schema = [
		'@@extends' => 'ModBaseCssModel',
		'inverse' => ['toBool'],
		'fixed' => [
			'toString',
			'in' => ['top','bottom',],
		]
	];


}
