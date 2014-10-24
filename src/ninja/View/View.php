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
	public static function _render($template, $Model) {

		$M = static::_getEngine();

		try {
			$content = $M->render(file_get_contents($template), $Model);
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

		$template = $this->findTemplate();

		$content = '';

		if (!is_null($template)) {
			try {
//				echop($this->_Model); die;
				$content = $this->_render($template, $this->_Model);
			}
			catch (\Exception $e) {

			}
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
	 * I return a full path to a usable template. defaulting to system templates if not found
	 * @param string $template use this template name instead of what's saved in model and/or guessed by module class
	 * @return null|string
	 */
	public function findTemplate() {

		$templatePath = $this->_template;

		if (empty($templatePath)) {

			$templateFolders = array();

			if (strlen($tmp = $this->_Model->templatePath)) {
				$templateFolders[] = \Finder::joinPath(APP_ROOT, $tmp);
			}
			// bubble up for template path
			elseif (strlen($tmp = $this->_Model->getBubbler()->templatePath)) {
				$templateFolders[] = \Finder::joinPath(APP_ROOT, $tmp);
			}
			// I should add the root page model's template path if exists
			$templateFolders[] = NINJA_ROOT . '/src/ninja/Mod/' . $this->_Module->getModName() . '/template';

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
				$templateNames[] = \Finder::joinPath($modName, \Finder::classToPath($a));
				$b = $Module::moduleNameByClassname(get_class($this->_Module));
				$templateNames[] = \Finder::joinPath($modName, \Finder::classToPath($b));
			}

			$extension = '.html.mustache';

			// for debug
			//echop($templateFolders); echop($templateNames); die;

			$templatePath = \Finder::fileByFolders($templateFolders, $templateNames, $extension);

		}

		return $templatePath;

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

}
