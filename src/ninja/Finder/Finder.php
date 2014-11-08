<?php

namespace ninja;

/**
 * Class Finder - for files. currently the simplest methods, could use quite some optimizations and caching regarding templates.
 * TO BE DETERMINED if it should be a facade to Symfony's finder
 *
 * @package ninja
 */
class Finder {

	/**
	 * I'll use this tiny table if possible for mime lookups
	 * @var array
	 */
	protected static $_mimeTypes = [
		'css' => 'text/css',
		'js' => 'application/javascript',
		'jsonp' => 'application/javascript',
		'json' => 'application/json',
		'flv' => 'video/x-flv',
		'svg' => 'image/svg+xml',
		'ttf' => 'application/x-font-ttf',
		'ttc' => 'application/x-font-ttf',
		'otf' => 'font/opentype',
		'ico' => 'image/x-icon',
		'htc' => 'text/x-component',
		'rss' => 'application/xml',
		'atom' => 'application/xml',
		'xml' => 'application/xml',
		'rdf' => 'application/xml',
		'webapp' => 'application/x-web-app-manifest+json',
		'vcf' => 'text/x-vcard',
		'swf' => 'application/x-shockwave-flash',
	];

	/**
	 * @var \Composer\Autoload\ClassLoader
	 */
	protected static $_AutoLoader;

	/**
	 * @param $AutoLoader I set autoloader instance to find subclasses etc
	 */
	public static function setAutoLoader($AutoLoader) {
		static::$_AutoLoader = $AutoLoader;
	}

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

		$ret = ($isAbsolutePath ? '/' : '') . join('/', $args);

		return $ret;

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

			$eachFolder = realpath($eachFolder);
			if ($eachFolder === false) {
				continue;
			}

			foreach ($fileNames as $eachTemplateName) {

				$fullPath = $eachFolder . '/' . $eachTemplateName . $extension;

				if (file_exists($fullPath)) {

					return $fullPath;

				}

			}
		}

//		echop('template not found: ' . echon($fileNames) . ' in: ' . echon($folders));

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

	public static function getSubclasses($classname) {
		throw new \Exception('TBI');
	}

	/**
	 * I return true if $classnameOrObject is subclass of, is of, or implements $classnameOrInterfaceOrObject
	 * @param $classnameOrObject
	 * @param $classnameOrInterfaceOrObject
	 * @return bool
	 */
	public static function classIsA($classnameOrObject, $classnameOrInterfaceOrObject) {
		if (empty($classnameOrObject) || empty($classnameOrInterfaceOrObject)) {
			return false;
		}
		if (is_object($classnameOrObject)) {
			$classnameOrObject = get_class($classnameOrObject);
		}
		if (is_object($classnameOrInterfaceOrObject)) {
			$classnameOrInterfaceOrObject = get_class($classnameOrInterfaceOrObject);
		}

		$pat = "/^[a-zA-Z0-9\\\\]+$/";
		foreach ([$classnameOrObject, $classnameOrInterfaceOrObject] as &$eachClassnameOrObject) {
			if (is_object($eachClassnameOrObject)) {
				$eachClassnameOrObject = get_class($eachClassnameOrObject);
			}
			if (!is_string($eachClassnameOrObject)) {
				return false;
			}
			if (!preg_match($pat, $eachClassnameOrObject)) {
				return false;
			}
		}

		if (is_subclass_of($classnameOrObject, $classnameOrInterfaceOrObject));
		elseif (in_array($classnameOrInterfaceOrObject, class_implements($classnameOrObject)));
		else {
			return false;
		}
		return true;
	}

	/**
	 * I return guessed mime type to avoid normally broken get_mime_type/finfo
	 * @param $fname
	 * @return null
	 */
	public static function guessMimeType($fname) {
		$ret = null;
		$fname = basename($fname);
		if (preg_match('/\.([a-zA-Z0-9]+)$/', $fname, $matches)) {
			$extension = $matches[1];
			if (isset(static::$_mimeTypes[$extension])) {
				$ret = static::$_mimeTypes[$extension];
			}
		}
		// maybe I should still fall back to Symfony or plain PHP if not found?
		return $ret;
	}

	/**
	 * I keep only unique entries in an array. Comparison is recursive but not done recursively.
	 * @param $arr
	 * @return array
	 */
	public static function arrayUnique($arr) {
		$serialized = array();
		foreach ($arr as $eachKey=>$eachVal) {
			$serialized[$eachKey] = serialize($eachVal);
		}
		$ret = array_intersect_key($arr, array_unique($serialized));
		return $ret;
	}

}
