<?php

namespace ninja;

class ModUserLoginModel extends \ModAbstractModel {


	protected static $_schema = [
		'@@extends' => 'ModBaseCssModel',
		// max user inactivity before logging out
		'ttl' => [
			'toInt',
		]
	];


}
