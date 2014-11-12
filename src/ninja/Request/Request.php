<?php

namespace ninja;

/**
 * Class Request for http requests
 * currently I just formally extend Symfony's Request object
 */
class Request extends \Symfony\Component\HttpFoundation\Request {

	/**
	 * @var string[] request uri parts without query string in a nice array. Will be consumed by shiftUriParts()
	 */
	private $_remainingUriParts = null;

	/**
	 * I return all request uri parts without query string in a nice array
	 * @return array|string
	 */
	function getUriParts() {
		$uriParts = $this->getRequestUri();
		if ($pos = strpos($uriParts, '?')) {
			$uriParts = substr($uriParts, 0, $pos);
		};
		$uriParts = explode('/', trim($uriParts, '/'));
		return $uriParts;
	}

	/**
	 * I return actual remaining uri parts
	 * @return array|string
	 */
	function getRemainingUriParts() {
		if (is_null($this->_remainingUriParts)) {
			$this->_remainingUriParts = $this->getUriParts();
		}
		return $this->_remainingUriParts;
	}

	/**
	 * @param int $count
	 * @return $this
	 */
	function shiftUriParts($count) {
		$this->_remainingUriParts = array_slice($this->_remainingUriParts, $count);
		return $this;
	}

}
