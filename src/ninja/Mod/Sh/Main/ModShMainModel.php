<?php

namespace ninja;

class ModShMainModel extends \ModPolymerModel {

	protected static $_schema = [
		'@@extends' => 'ModPolymerModel',
		'appName',
		'dataUrl',
		'appData',
	];


}
