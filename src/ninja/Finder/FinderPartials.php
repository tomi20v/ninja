<?php

namespace ninja;

/**
 * Class FinderPartials - to get mustache partials. now just a simple file loader, should have some smart key-val cache
 *
 * @package ninja
 */
class FinderPartials implements \Mustache_Loader {

	/**
	 * Load a Template by name.
	 *
	 * @throws Mustache_Exception_UnknownTemplateException If a template file is not found.
	 *
	 * @param string $name
	 *
	 * @return string Mustache Template source
	 */
	public function load($name) {
		preg_match('/^Mod([A-Z][a-zA-Z0-9]+)?(\-(.+))?$/', $name, $matches);
		$fname = \Finder::joinPath(
			NINJA_ROOT,
			'src/ninja/Mod',
			\Finder::classToPath($matches[1]),
			'template',
			$name . '.html.mustache'
		);
		return file_get_contents($fname);
	}

}
