<?php

namespace ninja;

/**
 * Class ModManager
 * @package ninja
 */
class ModManager {

	/**
	 * I return all module names which have a $pluginMethod()
	 * @param string $pluginMethod
	 * @return \string[]
	 */
	public static function findModsWithPlugin($pluginMethod) {
		$mods = static::findMods();
		foreach ($mods as $eachKey=>$eachMod) {
			$pluginClassname = 'Mod' . $eachMod . 'Plugin';
			if (!method_exists($pluginClassname, $pluginMethod)) {
				unset($mods[$eachKey]);
			}
		}
		return $mods;
	}

	/**
	 * @return string[] I return all installed module names
	 */
	public static function findMods() {
		$mods = static::_findMods(\Finder::joinPath(NINJA_ROOT, 'src/ninja/Mod'));
		sort($mods);
		return $mods;
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

		return $mods;

	}

}