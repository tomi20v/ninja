#!/usr/bin/php
<?php

require(dirname(__FILE__) . '/../vendor/autoload.php');

$Mod = new Mod();
$Mod->run();

die('ALL DONE' . "\n");


class Mod {

	const MODE_BASE_PATH = 'src/ninja/Mod';

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

	public function run() {
		global $argv;

		$args = $argv;

		array_shift($args);

		try {
			if (!count($args)) {
				throw new \Exception('you need help.');
			}
			$toRun = null;
			while (count($args)) {
				switch ($arg = trim(array_shift($args))) {
				case '-u':
					$this->_switchDoUpdate = true;
					break;
				case '-d':
					$this->_switchDryRun = true;
					break;
				case 'init':
//					$this->initMod($args);
					$toRun = 'initMod';
					break;
				case 'classmap':
//					$this->classMap($args);
					$toRun = 'classMap';
					break;
				case 'help':
				default:
					if ($toRun) {
						array_unshift($args, $arg);
					}
					break 2;
				}
			}
			if ($toRun) {
				call_user_func([$this, $toRun], $args);
			}
			else {
				throw new \Exception('you need help.');
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
	public function initMod($args) {

		$modName = array_shift($args);

		if (!preg_match('/^[A-Z][A-Za-z\_0-9]+$/', $modName)) {
			return static::help();
		}

		$modPath = \Finder::joinPath(
			NINJA_ROOT,
			static::MODE_BASE_PATH,
			\Finder::classToPath($modName)
		);

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
			'Module' => <<<EOS


	public function _beforeRespond(\$Request) {
		// @TODO: Change the autogenerated code
		return parent::_beforeRespond(\$Request);
	}

	public function _respond(\$Request, \$hasShifted) {
		// @TODO: Change the autogenerated code
		return parent::_respond(\$Request, \$hasShifted);
	}

EOS
			,
			'Model' => <<<EOS

	protected static \$_schema = [
		'@@extends' => '',
	];

EOS
			,
			'Controller' => <<<EOS
	public function actionIndex(\$params = null) {
		// @TODO: Change the autogenerated code
		return parent::actionIndex(\$params);
	}

EOS
			,
		];
		$templateBody = <<<EOS
{{> ModBaseCss-begin }}
{{{View.getContents}}}
{{> ModBaseCss-end }}
EOS;

		$filesToCreate = [];
		foreach ($classes as $eachKey=>$eachClass) {
			$classname = 'Mod' . $modName . $eachClass;
			$basename = '\ModAbstract' . $eachClass;
			$fname = $classname . '.php';
			$relativeFname = \Finder::joinPath(static::MODE_BASE_PATH, $modName, $fname);
			$fullFname = $modPath . '/' . $fname;

			echo 'creating file: ' . $relativeFname . ' ... ';

			if ($this->_switchDryRun || file_exists($fullFname)) {
				unset($classes[$eachKey]);
				echo "SKIPPED\n";
				continue;
			}

			$tr = [
				'{{classname}}' => $classname,
				'{{basename}}' => $basename,
				'{{body}}' => @$bodies[$eachClass],
			];
			file_put_contents($fullFname, strtr($this->_classTemplate, $tr));
			echo "OK\n";

			$filesToCreate[] = $relativeFname;

		};

		$templateFname = $modName . '.html.mustache';
		$relativeTemplateFname = \Finder::joinPath(static::MODE_BASE_PATH, $modName, 'template', $templateFname);
		$fullTemplateFname = \Finder::joinPath($modPath, 'template', $templateFname);
		echo 'creating file: ' . $relativeTemplateFname . ' ... ';
		if ($this->_switchDryRun || file_exists($fullTemplateFname)) {
			echo "SKIPPED\n";
		}
		else {
			file_put_contents($fullTemplateFname, $templateBody);
			$filesToCreate[] = $relativeTemplateFname;
		}

		if (count($filesToCreate)) {
			$fileList = array_map('escapeshellarg', $filesToCreate);
			echo 'git and composer commands you may want to execute:' . "\n\n" .
				'cd ' . escapeshellarg(NINJA_ROOT) . ' ; git add ' . implode(' ', $fileList) . " ; ./composer.phar dump-autoload\n\n";
		}
		else {
			echo 'no further commands needed as no files were created' . "\n\n";
		}
	}

	public function classMap(&$args) {

		$classes = [];

		$template = <<<EOS
<?php
/**
 * Generated by ninja mod tool to help your favourite ide
 */

// make sure this file does not get used
die();


EOS;

		$srcFolderName = NINJA_ROOT . '/src/ninja';
		$this->_iterateFolder($srcFolderName, $classes);

		$content = $template;
		foreach ($classes as $eachClass) {
			if (substr($eachClass, -9) === 'Interface') {
				continue;
			}
			$content.= "class " . $eachClass . " extends \\ninja\\" . $eachClass . " {}\n";
		}

		if ($this->_switchDryRun) {
			echo "classmap.php:\n\n".
				$content . "\n";
		}
		else {
			echo "writing file ... ";
			file_put_contents(NINJA_ROOT . '/src/root/classmap.php', $content);
			echo "OK!\n";
		}

	}

	protected function _iterateFolder($srcFolderName, &$classes) {

		$srcFolder = dir($srcFolderName);

		while ($f = $srcFolder->read()) {
			$fx = \Finder::joinPath($srcFolderName, $f);
			if (is_dir($fx) && !in_array($f, ['.', '..'])) {
				$this->_iterateFolder($fx, $classes);
			}
			elseif (preg_match('/^([A-Z][a-zA-Z0-9]+)\.php$/', $f, $matches)) {
				$classes[] = $matches[1];
			}
		}

	}

	public static function help() {
?>
usage: php script/mod.php [-g] [-u] {command}
	mod.php init Name - inits empty module with name Name. Note name
			can be a submodule
	mod.php action Name ActionName - adds a new action to the
			controller of module Name (not yet)
	mod.php classmap - write a classmap cache to avoid on-the-fly
			file lookup and class aliasing

switches:
	-d 	dry-run, skip everything just show what it would do
	-u	update, create files without overwriting existing ones
	-g 	fname 	create git diff file only, no changes to files  (not yet)
<?php
	}

}
