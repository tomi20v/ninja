<?php

namespace ninja;

/**
 * I am the main application class that shall wrap everything and be able to run() from scratch
 * facade for Maui and am the main object
 */
class Ninja {

	/**
	 * @var \Maui instance
	 */
	protected $_Maui;

	/**
	 * @var \Request instance
	 */
	protected $_Request;

	/**
	 * construct me to get the application instance
	 * @param \Maui $Maui for clearness you shall supply this
	 * @param \Request $Request main request to execute
	 */
	public function __construct($Maui, $Request=null) {
		$this->_Maui = $Maui;
		$this->_Request = $Request;
	}

	/**
	 * @return \Maui return current Maui instance. Maui shall not be referenced directly
	 */
	public function getMaui() {
		return $this->_Maui;
	}

	/**
	 * @return string return current ENV setting as in Maui::ENV_XXX constants
	 */
	public function env() {
		return $this->_Maui->env();
	}

	/**
	 * I set the given Request to be executed
	 * @param \Request $Request
	 * @return $this
	 */
	public function setRequest($Request) {
		$this->_Request = $Request;
		return $this;
	}

	/**
	 *
	 */
	public function run() {

		try {

			if (!$this->_Request instanceof \Request) {
				$this->_Request = \Request::createFromGlobals();
			}

			$Page = new \PageModule($this->_Request, $this);
			$content = $Page->respond();

			if ($content instanceof \Response) {
				$Response = $content;
			}
			else {
				$Response = new \Response(
					$content,
					\Response::HTTP_OK,
					array('content-type' => 'text/html')
				);
			}
		}
		catch (\HttpException $e) {
			// @todo create response from HttpException
		}
		catch (\Exception $e) {
			// @todo create default 501 response
		}

		$Response->send();

	}

}
