<?php

namespace ninja;

/**
 * Class ApiResponseErrorModel - just a data container model to ensure array format
 *
 * @package ninja
 */
class ApiResponseErrorModel extends \Model {

	const ERROR_DEFAULT = 'unknownError';

	protected static $_schema = [
		'type' => [
			'toString',
		],
		'key' => [
			'toString',
			'required',
		],
		'error' => [
			'toString',
		],
		'params' => [
			'toArray',
		]
	];

	public function __construct($type, $key, $error=null, $params=null) {
		$this->type = is_null($type) ? '_' : $type;
		$this->key = $key;
		if (!is_null($error)) {
			$this->error = $error;
		}
		if (!is_null($params)) {
			$this->params = $params;
		}
	}

	/**
	 * I return my array representation with defaulted values if necessary
	 * @return mixed[]
	 */
	public function toArray() {
		return [
			'type' => $this->type,
			'key' => $this->key,
			'error' => $this->Data()->fieldIsSet('error') ? $this->error : static::ERROR_DEFAULT,
			'params' => $this->Data()->fieldIsSet('params') ? $this->params : null,
		];
	}

	/**
	 * I aggregate an array of errors into a structure grouped by type and key
	 * @param ApiResponseErrorModel[] $errors
	 * @return array[][][]
	 */
	public static function aggregateErrors($errors) {
		$ret = [];
		foreach ($errors as $eachError) {
			$ret[$eachError['type']][$eachError['key']] = [$eachError['error'], $eachError['params']];
		}
		return $ret;
	}

}
