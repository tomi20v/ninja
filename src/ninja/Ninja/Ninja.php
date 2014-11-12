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
	 * I, as a frontend controller, run the Ninja app
	 */
	public function run() {

		try {

			if (!$this->_Request instanceof \Request) {
				$this->_Request = \Request::createFromGlobals();
			}

			$Page = new \ModPageModule($this->_Request, $this);
			$Response = $Page->respond();

			if ($Response instanceof \ninja\Response);
			else {
				$Response = new \Response(
					$Response,
					\Response::HTTP_OK,
					array('content-type' => 'text/html')
				);
			}
		}
		catch (\Exception $e) {
			// @todo do something more meaningful with this?
			echop($e); die('die exception');
		}

		$Response->send();

	}

}
