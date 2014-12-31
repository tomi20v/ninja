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
	 * @return int global app tstamp to be used (for consistency, when needed)
	 */
	public static function tstamp() {
		static $tstamp;
		if (is_null($tstamp)) {
			$tstamp = time();
		}
		return $tstamp;
	}

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

	public function getRequestedExtension() {
		return $this->_Request
			? $this->_Request->getRequestedExtension()
			: null;
	}

	/**
	 * I, as a frontend controller, run the Ninja app
	 */
	public function run() {

		try {

			if (!$this->_Request instanceof \Request) {
				$this->_Request = \Request::createFromGlobals();
			}

			$PageRoot = new \ModPageRootModule($this);
			// this is the entry point of $Request param into the system.
			$Response = $PageRoot->respond($this->_Request);

			// if response is just a compiled template in a string
			if ($Response instanceof \ninja\Response) {
				if (!$Response->getIsFinal() && !$this->_Request->getActionMatched()) {
					throw new \HttpException(404);
				};
			}
			else {
				$Response = new \Response(
					$Response,
					\Response::HTTP_OK,
					array('content-type' => 'text/html')
				);
			}
		}
		catch (\HttpException $e) {
			$message = $e->getMessage() ?: 'Ooops: ' . $e->getStatusCode() . '!';
			$Response = new \Response(
				$message,
				$e->getStatusCode()
			);
		}
		catch (\Exception $e) {
			// @todo do something more meaningful with this?
			echop($e); die('die exception');
		}

		$Response->send();

	}

}
