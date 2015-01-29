<?php

namespace ninja;

class View {

//	const DEFAULT_REQUESTED_EXTENSION = 'html';

	/**
	 * @var string in custom views set this to a full path to the template
	 */
	protected $_template;

	protected $_requestedExtension = '';

	/**
	 * @var \Model this shall hold all data I need
	 */
	protected $_Model;

	public function __toString() {
		return $this->render();
	}

	public static function from($Model, $template, $requestedExtension=null) {

	}

	/**
	 * I generate output
	 * @return string
	 */
	public function render($data=null) {

		$Engine = \ViewEngine::fromModel($this->_Model, $this->_requestedExtension);

		$content = '';

		try {
			$content = $Engine->render($this, $this->_Model, $data);
		}
		catch (\Exception $e) {};

		return $content;

	}

	public function setTemplate($template) {
		$this->_template = $template;
		return $this;
	}

}
