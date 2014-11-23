<?php

namespace ninja;

class View {

	/**
	 * @var string in custom views set this to a full path to the template
	 */
	protected $_template;

	/**
	 * @var \ModAbstractModule reference to my parent
	 */
	protected $_Module;

	/**
	 * @var \Model this shall hold all data I need
	 */
	protected $_Model;

	/**
	 * @var \Mustache_Engine
	 */
	protected static $_Engine;

	public function __construct($Module, $Model, $template=null) {
		$this->_Module = $Module;
		$this->_Model = $Model;
		$this->_template=$template;
	}

	public function __toString() {
		return $this->render();
	}

	/**
	 * @param string $template
	 * @param \Model $Model
	 * @return string
	 */
	protected function _render($templateFileContents, $Model) {

		$M = static::_getEngine();

		try {
			$Filter = new \Filter($this, $Model);
			$content = $M->render($templateFileContents, $Filter);
		}
		catch (\Exception $e) {
			$content = 'FUs';
		}

		return $content;

	}

	/**
	 * I generate output
	 * @return string
	 */
	public function render() {

		$templateFileContents = $this->loadTemplate();

		$content = '';

		if (!is_null($templateFileContents)) {
			try {
				$content = $this->_render($templateFileContents, $this->_Model);
			}
			catch (\Exception $e) {}
		}

		return $content;

	}

	/**
	 * I return template name for a mod class (model or module or controller)
	 * @param $Object
	 * @return string
	 */
	protected function _templateFileByModClass($Object) {

		$templateName = $classname = get_class($Object);

		if ($pos = strrpos($templateName, '\\')) {
			$templateName = substr($templateName, $pos+1);
		}

		// get rid of 'Mod' prefix
		$templateName = substr($templateName, 3);

		$templateName = preg_replace('/^([A-Z][^A-Z]+)(Model|Module)/', '$1', $templateName);

		return $templateName;

	}

	/**
	 * I return the contents of the actual template. load order is:
	 * 		APP_ROOT / <NameOfMod> / temp
	 * @param string $template use this template name instead of what's saved in model and/or guessed by module class
	 * @return null|string
	 * @todo add theme support with defaulting
	 */
	public function loadTemplate() {

		$templatePath = $this->_template;

		if (empty($templatePath)) {

			$templateFolders = array();

			if (strlen($tmp = $this->_Model->templatePath)) {
				$templateFolders[] = \Finder::joinPath(APP_ROOT, 'default', $tmp);
			}
			// bubble up for template path
			elseif (strlen($tmp = $this->_Model->getBubbler()->templatePath)) {
				$templateFolders[] = \Finder::joinPath(APP_ROOT, 'default', $tmp);
			}
			// I should add the root page model's template path if exists
			$templateFolders[] = \Finder::joinPath(
				NINJA_ROOT,
				'src/ninja/Mod',
				\Finder::classToPath($this->_Module->getModName()),
				'template'
			);

			// @todo add support for a theme folder here

			// app templates

			$templateFolders[] = \Finder::joinPath(
				APP_ROOT,
				'template',
				\Finder::classToPath($this->_Module->getModName())
			);

			// clean up the templatefolders
			$templateFolders = array_unique($templateFolders);

			$templateNames = array();
			// I respect what's set in the model, and it should not be invalid
			if (strlen($templateName = $this->_Model->template)) {
				$templateNames[]=  $templateName;
			}
			else {
				$Module = $this->_Module;
				$modName = $Module->getModName();

				$a = $Module::moduleNameByClassname(get_class($this->_Model));
				$templateNames[] = \Finder::joinPath($modName, \Finder::classToPath($a)) . '.html';
				$b = $Module::moduleNameByClassname(get_class($this->_Module));
				if ($a !== $b) {
					$templateNames[] = \Finder::joinPath($modName, \Finder::classToPath($b)) . '.html';
				}
			}

			$extension = '.mustache';

			$templatePath = \Finder::fileByFolders($templateFolders, $templateNames, $extension);

		}

		return @file_get_contents($templatePath);

	}

	/**
	 * @return \Mustache_Engine I return engine instance
	 */
	protected static function _getEngine() {
		if (!isset(static::$_Engine)) {
			static::$_Engine = new \Mustache_Engine([
				'partials_loader' => new \FinderPartials(),
			]);
		}
		return static::$_Engine;
	}

	public function setTemplate($template) {
		$this->_template = $template;
		return $this;
	}

}
