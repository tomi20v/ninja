<?php

namespace ninja;

/**
 * Class ViewScope - I am a decorator for models being used in templates
 *
 * @package ninja
 */
class ViewScope {

	/**
	 * @var \View
	 */
	public $View;

	/**
	 * @var \Model
	 */
	public $Model;

	/**
	 * @var mixed[]
	 */
	public $Data;

	/**
	 * I map Model's attributes for mustache
	 * @param string $key
	 * @return bool
	 */
	public function __isset($key) {
		return $this->Model->Data()->hasField($key);
	}

	/**
	 * I map Model's attributes for mustache
	 * @param string $key
	 * @return mixed
	 */
	public function __get($key) {
		return $this->Model->Data()->getField($key);
	}

	/**
	 * I shall be constructed by the $View
	 * @param \View $View
	 * @param \Model $Model
	 */
	public function __construct($View, $Model, $data) {

		$this->View = $View;

		$this->Model = $Model;

		$this->Data = $data;

	}

	/**
	 * I require a php template (and thus run it) in the scope of myself
	 * @param string $fname
	 * @return string
	 */
	public function requireTemplate($fname) {
		$View = $this->View;
		$Model = $this->Model;
		$Data = $this->Data;
		ob_start();
		@require($fname);
		$result = ob_get_clean();
		return $result;
	}

}
