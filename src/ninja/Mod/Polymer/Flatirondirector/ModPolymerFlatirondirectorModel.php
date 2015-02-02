<?php

namespace ninja;

/**
 * Class ModPolymerFlatirondirectorModel
 *
 * @package ninja
 *
 * @property string $route eg. '{{route}}'
 * @property bool $autoHash
 *
 */
class ModPolymerFlatirondirectorModel extends \ModPolymerModel {

	protected static $_schema = [
		'@@extends' => 'ModPolymerModel',
		'route',
		'autoHash' => [
			'toBool',
		],
	];


}
