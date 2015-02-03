<?php

namespace ninja;

/**
 * Class ModManager
 * @package ninja
 */
class ModManager {

	/**
	 * @var string[] list of all mods
	 */
	protected static $_mods;

	/**
	 * @var string[] list of mods which have a plugin class defined
	 */
	protected static $_modsWithPlugin;

	static function getPluginClassnameByModname($modName) {
		return 'Mod' . $modName . 'Plugin';
	}

	/**
	 * I return all module names which have a $pluginMethod()
	 * @param string $pluginMethod
	 * @return \string[]
	 */
	public static function findModsWithPluginMethod($pluginMethod) {
		$mods = static::findModsWithPlugins();
		foreach ($mods as $eachKey=>$eachMod) {
			if (!method_exists(static::getPluginClassnameByModname($eachMod), $pluginMethod)) {
				unset($mods[$eachKey]);
			}
		}
		$mods = array_merge($mods);
		return $mods;
	}

	/**
	 * @return \string[] I return a list of all modules which have a plugin class
	 */
	public static function findModsWithPlugins() {
		if (is_null(static::$_modsWithPlugin)) {
			$mods = static::findMods();
			foreach ($mods as $eachKey=>$eachMod) {
				if (!class_exists(static::getPluginClassnameByModname($eachMod))) {
					unset($mods[$eachKey]);
				}
			}
			static::$_modsWithPlugin = array_merge($mods);
		}
		return static::$_modsWithPlugin;
	}

	/**
	 * @return string[] I return all installed module names
	 */
	public static function findMods() {
		if (is_null(static::$_mods)) {
			static::$_mods = static::_findMods(\Finder::joinPath(NINJA_ROOT, 'src/ninja/Mod'));
		}
		return static::$_mods;
	}

	/**
	 * recursively find ModXxxModule.php files and return them in array
	 * @param $folder
	 * @return array
	 */
	protected static function _findMods($folder) {

		$dir = dir($folder);
		$mods = [];

		while ($entry = $dir->read()) {
			$entryFullPath = $folder . '/' . $entry;
			if (in_array($entry, ['.', '..']));
			elseif (is_dir($entryFullPath)) {
				$mods = array_merge($mods, static::_findMods($entryFullPath));
			}
			elseif (strpos($entry, 'Abstract') !== false);
			elseif (preg_match('/^Mod([A-Z][A-Za-z0-9]+)Module\.php$/', $entry, $matches)) {
				$mods[] = $matches[1];
			}
		}

		sort($mods);

		return $mods;

	}

}
