<?php

namespace ninja;

class View {

	/**
	 * @var \Module reference to my parent
	 */
	protected $_Module;

	/**
	 * @var \Model this shall hold all data I need
	 */
	protected $_Model;

	protected $_template;

	/**
	 * @var \Mustache_Engine
	 */
	protected static $_Engine;

	public function __construct($Module, $Model, $template=null) {
		$this->_Module = $Module;
		$this->_Model = $Model;
		$this->_template = $template;
	}

	public function __toString() {
		return $this->render();
	}

	/**
	 * @param $template
	 * @param \Model $Model
	 * @return string
	 */
	public static function _render($template, $data) {

		$M = static::_getEngine();

		try {
			$content = $M->render(file_get_contents($template), $data);
		}
		catch (\Exception $e) {
			$content = '';
		}

		return $content;

	}

	/**
	 * I generate output
	 * @return string
	 */
	public function render() {

		$template = $this->findTemplate($this->_template);

		$content = '';

		if (!is_null($template)) {
			try {
				$content = $this->_render($template, $this->_Model);
			}
			catch (\Exception $e) {}
		}

		return $content;

	}

	/**
	 * I return a full path to a usable template. defaulting to system templates if not found
	 * @param string $template use this template name instead of what's saved in model and/or guessed by module class
	 * @return null|string
	 */
	public function findTemplate($template=null) {

		$templateFolders = array();

		if (strlen($tmp = $this->_Model->templatePath)) {
			$templateFolders[] = APP_ROOT . '/' . trim($tmp, '/');
		}
		// @todo implement bubbler
//		elseif (strlen($tmp = $this->_Model->getBubbler()->templatePath)) {
//
//		}
		$templateFolders[] = APP_ROOT . '/template/default';
		$templateFolders[] = NINJA_ROOT . '/template/default';

		if (!is_null($template)) {
			$templateName = $template;
		}
		elseif (strlen($templateName = $this->_Model->template));
		else {
			$classname = get_class($this->_Module);
			if ($pos = strrpos($classname, '\\')) {
				$classname = substr($classname, $pos+1);
			}
			if (substr($classname, -6) === 'Module') {
				$classname = substr($classname, 0, -6);
			}
			// @todo crack by camelcase
			$classname = ltrim(preg_replace('/([A-Z])/', '/$1', $classname), '/');
			$templateName = $classname;
		}

		$found = false;
		foreach ($templateFolders as $eachTemplateFolder) {
			$templatePath = $eachTemplateFolder . '/' . $templateName . '.html.mustache';
			if (file_exists($templatePath)) {
				$found = true;
				break;
			}
		}

		if (!$found) {
			return null;
		}

		return $templatePath;

	}

	/**
	 * @return \Mustache_Engine I return engine instance
	 */
	protected static function _getEngine() {
		if (!isset(static::$_Engine)) {
			static::$_Engine = new \Mustache_Engine();
		}
		return static::$_Engine;
	}

}
