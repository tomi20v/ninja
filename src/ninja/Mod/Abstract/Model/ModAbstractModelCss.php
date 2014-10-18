<?php

namespace ninja;

abstract class ModAbstractModelCss extends \ModAbstractModel {

	protected static $_schema = [
		'@extends' => 'ModAbstractModel',
		'cssId',
		'cssClasses' => [
			'toString',
			'hasMin' => 0,
			'hasMax' => 0,
		],
		'cssExtra',
		'containerEl' => [
			'toString',
			'default' => 'div',
		],
	];

}

