#!/usr/bin/php
<?php

require(dirname(__FILE__) . '/../vendor/autoload.php');

$Mod = new Mod();
$Mod->run();

die('ALL DONE' . "\n");


class Mod {

	protected $_classTemplate = <<<EOS
<?php

namespace ninja;

class {{classname}} extends {{basename}} {
	{{body}}
}

EOS;

	protected $_args;

	protected $_switchDryRun = false;

	protected $_switchDoUpdate = false;

	protected function _getModBasePath() {
		return 'src/ninja/Mod';
	}

	public function run() {
		global $argv;

		$args = $argv;

		array_shift($args);

		try {
			while (count($args)) {
				switch (trim(array_shift($args))) {
				case '-u':
					$this->_switchDoUpdate = true;
					break;
				case 'init':
					$this->initMod($args);
					break;
				case 'help':
				default:
					throw new \Exception('you need help.');
				}
			}
		}
		catch (\Exception $e) {
			echo('ERROR: ' . $e->getMessage() . "\n");
			static::help();
		}

	}

	/**
	 * @param $args
	 * @return void
	 * @throws Exception
	 */
	public function initMod(&$args) {

		$modName = array_shift($args);

		if (!preg_match('/^[A-Z][A-Za-z\_0-9]+$/', $modName)) {
			return static::help();
		}

		$modBasePath = static::_getModBasePath();

		$modPath = NINJA_ROOT . '/' . $modBasePath . '/' . $modName;

		if (!$this->_switchDoUpdate && is_dir($modPath)) {
			throw new \Exception('mod folder ' . $modName . ' already exists. You might want to use -u');
		}

		$classes = [
			'Controller',
			'Model',
			'Module',
			'View',
		];

		$foldersToCreate = [
			$modPath,
			$modPath . '/template',
		];
		foreach ($classes as $eachClass) {
			$foldersToCreate[] = $modPath . '/' . $eachClass;
		}

		foreach ($foldersToCreate as $eachFolderToCreate) {
			echo 'creating folder: ' . $eachFolderToCreate . ' ... ';
			if ($this->_switchDryRun || is_dir($eachFolderToCreate)) {
				echo "SKIPPED\n";
				continue;
			}

			mkdir($eachFolderToCreate);
			echo "OK\n";

		};

		$bodies = [
			'Module' => '\tpublic function _beforeRespond() {\n\treturn parent::_beforeRespond();\n\t}\n' .
						'\tpublic function _respond() {\n\treturn parent::_respond();\n\t}\n' .
						'',
			'Model' => '\tprotected static $_schema = [\n\t];',
		];

		$filesToCreate = [];
		foreach ($classes as $eachKey=>$eachClass) {
			$classname = 'Mod' . $modName . $eachClass;
			$basename = '\ModAbstract' . $eachClass;
			$fname = $eachClass . '/' . $classname . '.php';
			$fullFname = $modPath . '/' . $fname;

			echo 'creating file: ' . $fname . ' ... ';

			if ($this->_switchDryRun || file_exists($fullFname)) {
				unset($classes[$$eachKey]);
				echo "SKIPPED\n";
				continue;
			}

			$tr = array(
				'{{classname}}' => $classname,
				'{{basename}}' => $basename,
				'{{body}}' => @$bodies[$eachClass],
			);
			file_put_contents($fullFname, strtr($this->_classTemplate, $tr));
			echo "OK\n";

			$filesToCreate[] = \Finder::joinPath($modBasePath, $modName, $fname);

		};

		$fileList = array_map('escapeshellarg', $filesToCreate);
		echo 'git command you may want to execute:' . "\n\n" .
			'cd ' . escapeshellarg(NINJA_ROOT) . ' ; git add ' . implode(' ', $fileList) . "\n\n";

	}

	public static function help() {
?>
usage: php script/mod.php [-g] [-u] {command}
	mod.php init Name - inits empty module with name Name
	mod.php add Name NewName - adds all skeleton files for a new submodule
	mod.php {module|model|view|controller|template} Name NewName - adds only a
			new module/model/etc skeleton file

switches:
	-d 	dry-run, skip everything just show what it would do
	-u	update, create files which don't exist but do not overwrite existing ones
	-g fname 	create git diff file fname instead of writing the files actually
<?php
	}

}
