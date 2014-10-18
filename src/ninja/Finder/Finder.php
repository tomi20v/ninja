<?php

namespace ninja;

/**
 * Class Finder - for files. currently the simplest methods, could use quite some optimizations and caching regarding templates.
 * TO BE DETERMINED if it should be a facade to Symfony's finder
 *
 * @package ninja
 */
class Finder {

	public static function instance() {

		static $Instance;

		if (is_null($Instance)) {
			$Instance = new static();
		}

		return $Instance;

	}

	/**
	 * I join path parts by taking care no double // are generated. Path is kept absolute (leading /)
	 * @param string... any number of path parts
	 * @return string
	 */
	public static function joinPath() {

		$args = (array)func_get_args();

		$isAbsolutePath = !empty($args[0]) && is_string($args[0]) && ($args[0][0] === '/');

		foreach ($args as $eachKey=>$eachVal) {
			$args[$eachKey] = trim($eachVal, '/');
			if (empty($args[$eachKey])) {
				unset($args[$eachKey]);
			}
		}

		return ($isAbsolutePath ? '/' : '') . join('/', $args);

	}

	/**
	 * I loop the folders and inside, the filenames and look for an existing file
	 *
	 * @param string[] $folders - should not contain trailing slash /
	 * @param string[] $fileNames
	 * @param string $extension
	 * @return null|string
	 */
	public static function fileByFolders($folders, $fileNames, $extension) {

		foreach ($folders as $eachFolder) {
			foreach ($fileNames as $eachTemplateName) {

				$fullPath = $eachFolder . '/' . $eachTemplateName . $extension;

				if (file_exists($fullPath)) {

					return $fullPath;

				}

			}
		}

		//echop('template not found: ' . echon($templateNames) . ' in: ' . echon($templateFolders));

		return null;

	}

	/**
	 * convert camelcase classname (with namespace) to path, eg. ns\FooBarModel => ns/Foo/Bar/Model
	 * @param string $classname
	 * @return string mixed
	 */
	public static function classToPath($classname) {

		$classname = str_replace('\\', '/', $classname);

		$classname = preg_replace('/([A-Z])/', "/$0", $classname);

		$classname = preg_replace('/\/+/', '/', $classname);

		$classname = trim($classname, '/');

		return $classname;

	}

}
