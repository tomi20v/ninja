<?php

namespace ninja;

/**
 * Class ViewEngineMustache - I render modules' mustache templates
 *
 * @package ninja
 */
class ViewEngineMustache extends \ViewEngine {

	/**
	 * @var string fallback for template lookup
	 */
	const DEFAULT_EXTENSION = 'html';

	/**
	 * @var string in custom views set this to a full path to the template
	 */
	protected $_template;

	/**
	 * @var \Model this shall hold all data I need
	 */
	protected $_Model;

	/**
	 * @var \Mustache_Engine
	 */
	protected static $_Engine;

	/**
	 * @param string $template
	 * @param \View $View
	 * @param \Model $Model
	 * @param mixed $Data
	 * @return string
	 */
	protected function _render($templateFileContents, $View, $Model, $Data) {

		$M = static::_getEngine();

		$content = null;

		try {
			$Scope = new \ViewScope($View, $Model, $Data);
			$content = $M->render($templateFileContents, $Scope);
		}
		catch (\Exception $e) {}

		return $content;

	}

	/**
	 * I generate output
	 * @param \View $View
	 * @param \Model $Model
	 * @param mixed[] $data
	 * @return string
	 */
	public function render($View, $Model, $data=null) {

		$templateFileContents = $this->loadTemplate($Model, $this->_templateExtension);

		$content = '';

		if (!is_null($templateFileContents)) {
			try {
				$content = $this->_render($templateFileContents, $View, $Model, $data);
			}
			catch (\Exception $e) {}
		}

		return $content;

	}

	/**
	 * I return the contents of the actual template. load order is:
	 * 		APP_ROOT / <NameOfMod> / temp
	 * @param \ModAbstractModel
	 * @param string $template use this template name instead of what's saved in model and/or guessed by module class
	 * @return null|string
	 * @todo add theme support with defaulting
	 */
	public function loadTemplate($Model, $extension) {

		$templatePath = $this->_template;

		if (empty($templatePath)) {

			$templateFolders = array();

			if (strlen($tmp = $Model->templatePath)) {
				$templateFolders[] = \Finder::joinPath(APP_ROOT, 'default', $tmp);
			}
			// bubble up for template path
			elseif (strlen($tmp = $Model->getBubbler()->templatePath)) {
				$templateFolders[] = \Finder::joinPath(APP_ROOT, 'default', $tmp);
			}

			// app templates
			$templateFolders[] = \Finder::joinPath(
				APP_ROOT,
				'template',
				\Finder::classToPath(\ModAbstractModule::modNameByClassname($Model))
			);

			// I should add the root page model's template path if exists
			$templateFolders[] = \Finder::joinPath(
				NINJA_ROOT,
				'src/ninja/Mod',
				\Finder::classToPath(\ModAbstractModule::modNameByClassname($Model)),
				'template'
			);

			// clean up the templatefolders
			$templateFolders = array_unique($templateFolders);

			$templateNames = array();
			// I respect what's set in the model, and it should not be invalid
			if (strlen($templateName = $Model->template)) {
				$templateNames[] =  $templateName;
			}
			else {
				$templateName = \ModAbstractModule::modNameByClassname(get_class($Model));
				$templateNames[] = $templateName . '.' . $extension;
				if ($extension !== static::DEFAULT_EXTENSION) {
					$templateNames[] = $templateName . '.' . static::DEFAULT_EXTENSION;
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
		static $Engine;
		if (!isset($Engine)) {
			$Engine = new \Mustache_Engine([
				'partials_loader' => new \FinderPartials(),
			]);
		}
		return $Engine;
	}

	public function setTemplate($template) {
		$this->_template = $template;
		return $this;
	}

}
