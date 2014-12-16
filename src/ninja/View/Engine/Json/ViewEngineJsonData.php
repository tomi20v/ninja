<?php

namespace ninja;

/**
 * Class ViewEngineJsonData - encapsulate data for Json responses
 *
 * @package ninja
 */
class ViewEngineJsonData {

	/**
	 * @var bool
	 */
	public $success;

	/**
	 * @var [] result in proper format
	 */
	public $result;

	/**
	 * @var array
	 */
	public $errors;

	/**
	 * @var null
	 */
	public $_meta_;

	/**
	 * I return a ViewEngineJsonData object from various types of param
	 * @param \ViewEngineJsonData|array|mixed $Data
	 * @return \ViewEngineJsonData|null
	 */
	public static function from($Data) {
		if ($Data instanceof ViewEngineJsonData) {
			$ret = $Data;
		}
		elseif (is_array($Data)) {
			$ret = new \ViewEngineJsonData(
				array_key_exists('success', $Data) ? $Data['success'] : null,
				array_key_exists('result', $Data) ? $Data['result'] : null,
				array_key_exists('errors', $Data) ? $Data['errors'] : null,
				array_key_exists('_meta_', $Data) ? $Data['_meta_'] : null
			);
		}
		else {
			$ret = null;
		}
		return $ret;
	}

	public function __construct($success=false, $result=null, $errors=null, $_meta_=null) {
		$this->success = (bool) $success;
		$this->result = $result;
		$this->errors = is_null($errors) ? [] : (array) $errors;
		$this->_meta_ = $_meta_;
	}

}
