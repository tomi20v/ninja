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

		$requestUri = ltrim($this->_Request->getRequestUri(), '/');
		if (preg_match('/^([^?]+)(\?.*)?$/', $requestUri, $matches)) {
			$requestUri = $matches[1];

			$files = $this->_Model->files;
			$foundFname = null;
			if (is_array($files)) {
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
				// prepare BinaryFileResponse
				$Response = new \Response(
					file_get_contents($foundFname),
					\Response::HTTP_OK,
					array(
						'Last-Modified' => gmdate('D, d M Y H:i:s \G\M\T', $fLastMod),
						'Content-Type' => mime_content_type($foundFname),
					)
				);

				return $Response;

			}
		}

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
