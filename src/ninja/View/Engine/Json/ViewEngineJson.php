<?php

namespace ninja;

/**
 * Class ViewEngineJson - to render API json responses
 *
 * @package ninja
 */
class ViewEngineJson extends \ViewEngine {

	/**
	 * @var string fallback for template lookup
	 */
	const DEFAULT_EXTENSION = 'json';

	/**
	 * @var string in custom views set this to a full path to the template
	 */
	protected $_template;

	/**
	 * @param \View $View the View will be put in template scope as View
	 * @param \Model $Model the Model will be put in scope. Note this is the View's model and not the actual data to
	 *            work on (though it can contain that as well, especially in Content field)
	 * @param mixed[] $Data will be put in template scope as Data
	 * @return string
	 */
	function render($View, $Model, $Data = null) {

		$templateFileName = $this->getTemplateFname($Model, $this->_templateExtension);

		// @todo put this back
//		$Data = \ViewEngineJsonData::from($Data);

		$Scope = new \ViewScope($View, $Model, $Data);
		return $Scope->requireTemplate($templateFileName);

	}

	/**
	 * get template filename. shall be Mod/Base/template/json.json.php but can be overridden
	 * @param \Model $Model
	 * @param string $extension
	 * @return null|string
	 */
	public function getTemplateFname($Model, $extension) {

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
				\Finder::classToPath('Base')
			);

			// I should add the root page model's template path if exists
			$templateFolders[] = \Finder::joinPath(
				NINJA_ROOT,
				'src/ninja/Mod',
				\Finder::classToPath('Base'),
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
				$templateName = 'json';
				$templateNames[] = $templateName . '.' . $extension;
				if ($extension !== static::DEFAULT_EXTENSION) {
					$templateNames[] = $templateName . '.' . static::DEFAULT_EXTENSION;
				}
			}

			$templateExtension = '.php';

			$templatePath = \Finder::fileByFolders($templateFolders, $templateNames, $templateExtension);

		}

		return $templatePath;

	}

}
