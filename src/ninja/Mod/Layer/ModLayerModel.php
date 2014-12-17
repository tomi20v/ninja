<?php

namespace ninja;

class ModLayerModel extends \ModAbstractModel {

	protected static $_schema = [
		'@@extends' => 'ModAbstractModel',
		'label',
		'conditions' => [
			'toArray',
//			'callback',
		],
		'active' => [
			'toBool',
			// @todo implement this
//			'noSave',
		]
	];


}
