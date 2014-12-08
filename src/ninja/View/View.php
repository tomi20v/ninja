<?php

namespace ninja;

class View {

	const DEFAULT_REQUESTED_EXTENSION = 'html';

	/**
	 * @var string in custom views set this to a full path to the template
	 */
	protected $_template;

	protected $_requestedExtension = '';

	/**
	 * @var \Model this shall hold all data I need
	 */
	protected $_Model;

	/**
	 * @var \Mustache_Engine
	 */
	protected static $_Engine;

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
		}

		return $content;

	}

	/**
	 * I generate output
	 * @return string
	 */
	public function render() {

		$templateFileContents = $this->loadTemplate($this->_requestedExtension);

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
	 * I return the contents of the actual template. load order is:
	 * 		APP_ROOT / <NameOfMod> / temp
	 * @param string $template use this template name instead of what's saved in model and/or guessed by module class
	 * @return null|string
	 * @todo add theme support with defaulting
	 */
	public function loadTemplate($extension) {

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
				\Finder::classToPath(\ModAbstractModule::modNameByClassname($this->_Model)),
				'template'
			);

			// @todo add support for a theme folder here!?

			// app templates
			$templateFolders[] = \Finder::joinPath(
				APP_ROOT,
				'template',
				\Finder::classToPath(\ModAbstractModule::modNameByClassname($this->_Model))
			);

			// clean up the templatefolders
			$templateFolders = array_unique($templateFolders);

			$templateNames = array();
			// I respect what's set in the model, and it should not be invalid
			if (strlen($templateName = $this->_Model->template)) {
				$templateNames[] =  $templateName;
			}
			else {
				$templateName = \ModAbstractModule::modNameByClassname(get_class($this->_Model));
				$templateNames[] = $templateName . '.' . $extension;
				if ($extension !== static::DEFAULT_REQUESTED_EXTENSION) {
					$templateNames[] = $templateName . '.' . static::DEFAULT_REQUESTED_EXTENSION;
				}
			}

			$templateExtension = '.mustache';

			$templatePath = \Finder::fileByFolders($templateFolders, $templateNames, $templateExtension);

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
