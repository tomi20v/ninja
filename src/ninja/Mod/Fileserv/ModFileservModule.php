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
	 * @return ResponseInterface|\Response|void
	 */
	public function _respond() {

		$requestUri = implode('/', $this->_Request->getRemainingUriParts());

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
				$foundFname = realpath(\Finder::joinPath(APP_ROOT, $this->_Model->folder, $requestUri));
				if (!@is_readable($foundFname)) {
					$foundFname = null;
				}
			}

			if (!is_null($foundFname)) {

				$fLastMod = filemtime($foundFname);
				$mimetype = \Finder::guessMimeType($foundFname);

				$Response = new \Response(
					file_get_contents($foundFname),
					\Response::HTTP_OK,
					array(
						'Last-Modified' => gmdate('D, d M Y H:i:s \G\M\T', $fLastMod),
						'Content-Type' => $mimetype,
					)
				);

				return $Response;

			}
		}

		finish:

		return parent::_respond();

	}

	/**
	 * eliminate render output if file not found
	 * @return null|\View
	 */
	protected function _getView() {
		return null;
	}

}
