<?php

namespace maui;

class Tester {

	/**
	 * I return path to snapshot dumps
	 * @return string
	 */
	public static function getPath() {
		return NINJA_ROOT . '/snapshot';
	}

	/**
	 * call \Tester::init and pass a map of classes-methods to be stubbed, eg:
	 * \Tester::init([
	 * 		'classA' => ['methodAa', 'methodAb'],
	 * 		'classB' => ['methodB'],
	 * ]);
	 *
	 * @param string[][] $stubs
	 */
	public static function init($stubs) {

		if (empty($stubs)) {
			return;
		}

		umask(0);

		// something totally nasty, just for fun
		$path = static::getPath();
		is_dir($path) or mkdir($path) or die('cannot mkdir');
		is_writable($path) or die('cannot write path');

		// this gonna be nasty anyway
		$code = '';

		foreach ($stubs as $eachClassName=>$eachStubbedMethods) {
			$classPath = $path . '/' . $eachClassName;
			is_dir($classPath) or mkdir ($classPath);

			$ninjaClassName = 'ninja\\' . $eachClassName;
			$code.= <<<EOS

class $eachClassName extends $ninjaClassName {

EOS;

			foreach ($eachStubbedMethods as $eachStubbedMethod) {

				$methodPath = $classPath . '/' . $eachStubbedMethod;
				is_dir($methodPath) or mkdir($methodPath);

				$Method = new \ReflectionMethod($ninjaClassName, $eachStubbedMethod);

				$xStatic = $Method->isStatic() ? 'static ' : '';
				$xPrivate = $Method->isPrivate() ? 'private ' : '';
				$xProtected = $Method->isProtected() ? 'protected ' : '';
				$xPublic = strlen($xPrivate . $xProtected) ? '' : 'public ';

				$numReqParams = $Method->getNumberOfRequiredParameters();
				$numParams = $Method->getNumberOfParameters();

				$params = [];
				for ($i=0; $i<$numReqParams; $i++) {
					$params[] = '$p' . $i;
				}
				for ($i; $i<$numParams; $i++) {
					$params[] = '$p' . $i . '=null';
				}

				$params = implode(', ', $params);
				$code.= <<<EOS

	$xStatic$xPrivate$xProtected${xPublic}function $eachStubbedMethod ($params) {
		static \$counter=0;

		\$ret = call_user_func_array(['$ninjaClassName', '$eachStubbedMethod'], func_get_args());

		\$fname = md5(\$_SERVER['REQUEST_URI']);
		\$fname = '$methodPath/' . \$fname . '-' . \$counter++ . '.php';

		\$requestUri = \$_SERVER['REQUEST_URI'];
		\$othis = str_replace("'", "\\'", serialize(\$this));
		\$method = '$eachStubbedMethod';
		\$params = str_replace("'", "\\'", serialize(func_get_args()));
		\$oret = str_replace("'", "\\'", serialize(\$ret));

		file_put_contents(
			\$fname,
			'<?php return [
	\'requestUri\' => \'' . \$requestUri . '\',
	\'object\' => unserialize(\'' . \$othis . '\'),
	\'method\' => \'' . \$method . '\',
	\'params\' => unserialize(\'' . \$params . '\'),
	\'returned\' => unserialize(\'' . \$oret . '\'),
];
'
		);
		return \$ret;
	}

EOS;

			}

		}

		$code.= <<<EOS

};

EOS;

		echop($code);

		// stubbed class is now in $code
		eval($code);

	}

	/**
	 * I'll assert actual return value of tested method matches saved value
	 * @param $data
	 * @return array|null
	 */
	protected static function _assert($data) {
		// @todo rewrite this using reflectionmethod so protected methods can be tested as well
		$Object = $data['object'];
		$method = $data['method'];
		$params = $data['params'];
		$expected = $data['returned'];

		$ret = call_user_func_array([$Object, $method], $params);
		if ($ret !== $expected) {
			return [
				'expected' => $expected,
				'actual' => $ret,
				'in' => get_class($Object) . '::' . $method,
			];
		}
		return null;
	}

	/**
	 * I'll run all tests. Needs filtering
	 * @return array
	 */
	public static function run() {
		$errors = [];
		$testCnt = 0;
		$classCnt = 0;
		$methodCnt = 0;
		$path = static::getPath();
		$Folder = dir($path);
		while ($className = $Folder->read()) {
			if (in_array($className, ['.', '..']));
			elseif (!is_dir($classPath = $path . '/' . $className));
			else {
				$classCnt++;
				$ClassFolder = dir($classPath);
				while ($methodName = $ClassFolder->read()) {
					if (in_array($methodName, ['.', '..']));
					elseif (!is_dir($methodPath = $classPath . '/' . $methodName));
					else {
						$methodCnt++;
						$MethodFolder = dir($methodPath);
						while ($testFname = $MethodFolder->read()) {
							$fullTestFname = $methodPath . '/' . $testFname;
							if (!is_file($fullTestFname));
							else {
								$testData = require($fullTestFname);
								$result = static::_assert($testData);
								$testCnt++;
								if (!is_null($result)) {
									$result['filename'] = $testFname;
									$errors[] = $result;
								}
							}
						}
					}
				}
			}
		}
		return [
			'success' => count($errors) ? false : true,
			'testCnt' => $testCnt,
			'classCnt' => $classCnt,
			'methodCnt' => $methodCnt,
			'errors' => $errors,
		];
	}

}
