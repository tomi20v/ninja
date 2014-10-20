<?php

namespace ninja;

abstract class ModAbstractModelCss extends \ModAbstractModel {

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
	];

}

