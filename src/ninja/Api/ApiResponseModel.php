<?php

namespace ninja;

class ApiResponseModel extends \Model {

	protected static $_schema = [
		'success' => [
			'toBool',
		],
		'result' => [
			'toArray',
		],
		'errors' => [
			'toType' => 'ApiResponseErrorModel',
		],
		'meta' => [
			'toArray',
		],
		'count' => [
			'toInt',
		],
		'allCount' => [
			'toInt',
		]
	];

}
