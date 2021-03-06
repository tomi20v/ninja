<?php

namespace ninja;

/**
 * Class FinderPartials - to get mustache partials. now just a simple file loader, should have some smart key-val cache
 *
 * @package ninja
 */
class FinderPartials implements \Mustache_Loader {

	/**
	 * @var string[] I'll cache found templates for reuse
	 */
	protected $_templates = [];

	/**
	 * Load a Template by name.
	 * @throws \Mustache_Exception_UnknownTemplateException If a template file is not found.
	 * @param string $name
	 * @return string Mustache Template source
	 */
	public function load($name) {

		$key = md5($name);

		if (!isset($this->_templates[$key])) {
			preg_match('/^([A-Z][a-zA-Z0-9]+)?(\-(.+))?$/', $name, $matches);
			$fname = \Finder::joinPath(
				NINJA_ROOT,
				'src/ninja/Mod',
				\Finder::classToPath($matches[1]),
				'template',
				$name .
				'.html.mustache'
			);
			// note I trim the partial so multiple partials can still be invoked on one line
			$this->_templates[$key] = trim(file_get_contents($fname));
		}

		return $this->_templates[$key];

	}

}
