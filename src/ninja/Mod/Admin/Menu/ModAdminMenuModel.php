<?php

namespace ninja;

class ModAdminMenuModel extends \ModBaseCssModel {


	protected static $_schema = [
		'@@extends' => 'ModBaseCssModel',
		'containerEl' => [
			'toString',
			'default' => 'ul',
		],
	];


}
