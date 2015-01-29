<?php

namespace ninja;

class ModPolymerCoreToolbarModel extends \ModPolymerCoreModel {


	protected static $_schema = [
		'@@extends' => 'ModPolymerCoreModel',
		'title',
		'buttons' => [
			'toArray',
			'keys' => ['icon'],
		],
		'extraAttributes',
	];


}
