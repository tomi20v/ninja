<?php

namespace ninja;

/**
 * Class ModFileservModule - I can serve files from a given folder.
 *
 * Put me in your root page's root for flexibility or create a page with a slug eg. 'assets' to serve these files
 * from that basepath
 *
 * @todo add basepath param?
 * @todo add forced mimetype param
 *
 * @package ninja
 */
class ModFileservModule extends \ModAbstractModule {


	/**
	 * I return a response set up to send the file
	 * @param \Request $Request
	 * @return \Response|null
	 */
	public function _respond($Request, $hasShifted) {

		$requestUri = implode('/', $Request->getRemainingUriParts()) . '.' . $Request->getRequestedExtension();

		$basePath = '';

		if (!empty($this->_Model->basePath)) {
			$basePath = trim($this->_Model->basePath, '/');
			if (substr($requestUri, 0, strlen($basePath)+1) === $basePath . '/') {
				$requestUri = substr($requestUri, strlen($basePath)+1);
			}
		}

		if (!$this->_Model->recursive && strpos($requestUri, '/'));
		else {
			$files = $this->_Model->files;
			$foundFname = null;
			// I have to skip empty array as well
			if (is_array($files) && !empty($files)) {
				foreach ($files as $eachFile) {
					if ($eachFile === $requestUri) {
						$foundFname = realpath(\Finder::joinPath(APP_ROOT, $this->_Model->folder, $requestUri));
					}
				}
			}
			else {
				$foundFname = \Finder::joinPath(APP_ROOT, $this->_Model->folder, $requestUri);
				$foundFname = realpath($foundFname);
				if (!@is_readable($foundFname)) {
					$foundFname = null;
				}
			}

			if (!is_null($foundFname)) {

				$fLastMod = filemtime($foundFname);
				$mimetype = \Finder::guessMimeType($foundFname);

				// apply filter if any
				if (!$this->_Model->Data()->fieldIsEmpty('filter') &&
					is_callable($this->_Model->filter)) {
					$fileContents = call_user_func($this->_Model->filter, $foundFname);
				}
				else {
					$fileContents = file_get_contents($foundFname);
				}

				$Response = new \Response(
					$fileContents,
					\Response::HTTP_OK,
					array(
						'Last-Modified' => gmdate('D, d M Y H:i:s \G\M\T', $fLastMod),
						'Content-Type' => $mimetype,
					)
				);
				$Request->setActionMatched(true);
				$Response->setIsFinal(true);

				return $Response;

			}
		}

		finish:

		return parent::_respond($Request, $hasShifted);

	}

	/**
	 * I'll compile less css if filename ends by .less.css
	 * @param string $fname
	 * @return string compiled or raw content
	 */
	public static function filterLess($fname) {
		if (substr($fname, -9) === '.css.less') {
			$Less = new \lessc;
			return $Less->compileFile($fname);
		}
		else {
			return file_get_contents($fname);
		}
	}

}
