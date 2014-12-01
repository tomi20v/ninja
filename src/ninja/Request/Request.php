<?php

namespace ninja;

use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * @TODO make this just a decorator to Request with magics
 * Class Request for http requests
 * currently I just formally extend Symfony's Request object
 */
class Request extends \Symfony\Component\HttpFoundation\Request {

	/**
	 * @var \Request reference or original request object
	 */
	protected $_OriginalRequest = null;

	/**
	 * @var string[] original uri parts
	 */
	private $_uriParts = null;

	/**
	 * @var string[] request uri parts without query string and extension. will be copied when cloning and consumed by shiftUriParts()
	 */
	private $_remainingUriParts = null;

	/**
	 * @var string[] these extensions will be recognized
	 */
	protected $_routedExtensions = [
		'html',
		'json',
		'js',
		'css',
		'xml',
	];

	/**
	 * I'll store the requested extension here
	 * @var null
	 */
	private $_requestedExtension = null;

	/**
	 * @var bool set this is routing matched an action already
	 */
	protected $_actionMatched = false;

	/**
	 * I return all request uri parts without query string in a nice array
	 * @return array|string
	 */
	public function getUriParts() {
		if (is_null($this->_uriParts)) {
			$uriParts = $this->getRequestUri();
			if ($pos = strpos($uriParts, '?')) {
				$uriParts = substr($uriParts, 0, $pos);
			};
			if ($extension = $this->getRequestedExtension()) {
				$uriParts = str_replace('.'.$extension, '', $uriParts);
			}
			$this->_uriParts = explode('/', trim($uriParts, '/'));
		}
		return $this->_uriParts;
	}

	/**
	 * I return actual remaining uri parts
	 * @return array|string
	 */
	public function getRemainingUriParts() {
		if (is_null($this->_remainingUriParts)) {
			$this->_remainingUriParts = $this->getUriParts();
		}
		return $this->_remainingUriParts;
	}

	/**
	 * I return already shifted uri parts
	 * @return string[]
	 */
	public function getShiftedUriParts() {
		$ret = array_slice($this->_uriParts, count($this->_uriParts)-count($this->_remainingUriParts));
		return $ret;
	}

	/**
	 * I remove some parts from $this->_remainingUriParts
	 * @param int|string|array $countOrParts count to shift, or parts to shift in string (slug) or array of its parts
	 * @return int number of parts shifted
	 */
	public function shiftUriParts(&$countOrParts) {
		if (is_string($countOrParts)) {
			$countOrParts = explode('/', $countOrParts);
		}
		if (is_array($countOrParts)) {
			$ret = 0;
			do {
				if (reset($countOrParts) !== reset($this->_remainingUriParts)) {
					break;
				}
				array_shift($countOrParts);
				array_shift($this->_remainingUriParts);
				$ret++;
			} while (count($countOrParts) && count($this->_remainingUriParts));
		}
		else {
			$ret = intval($countOrParts);
			$this->_remainingUriParts = array_slice($this->_remainingUriParts, $ret);
		}

		return $ret;

	}

	/**
	 * I return a clone of myself.
	 * You can save resources if not using HMVC. just redefine me to return $this (so all modules will share the same)
	 * return \Request
	 */
	public function getClone() {
		$NewRequest = clone $this;
		$NewRequest->_uriParts = $NewRequest->_remainingUriParts;
		return $NewRequest;
	}

	/**
	 * @return string|null I recognize basic extensions to be considered (html, json, js, css, xml)
	 */
	public function getRequestedExtension() {
		if (is_null($this->_requestedExtension)) {
			$pattern = '/.(' . implode('|', $this->_routedExtensions) . ')(\/.*|)$/';
			if (preg_match($pattern, $this->getRequestUri(), $matches)) {
				$this->_requestedExtension = $matches[1];
			}
			else {
				$this->_requestedExtension = null;
			}
		}
		return $this->_requestedExtension;
	}

	/**
	 * I sotre reference to original request
	 * @return \Request
	 */
	public static function createFromGlobals() {

		$Request = parent::createFromGlobals();
		$Request->_OriginalRequest = $Request;

		return $Request;

	}

	/**
	 * @return $this
	 */
	public function setActionMatched() {

		$this->_OriginalRequest->_actionMatched = true;

		return $this;

	}

	/**
	 * @return boolean
	 */
	public function getActionMatched() {

		return $this->_OriginalRequest->_actionMatched;
	}

	/**
	 * I return original request method
	 * @return string
	 */
	public function getOriginalMethod() {
		return $this->_OriginalRequest->getMethod();
	}

	/**
	 * I return original request's target uri part, eg. index.html from index.html/login/xxx
	 * @return string
	 */
	public function getOriginalTargetUri() {
		$uriParts = $this->_OriginalRequest->getRequestUri();
		if ($pos = strpos($uriParts, '?')) {
			$uriParts = substr($uriParts, 0, $pos);
		};
		if ($extension = $this->_OriginalRequest->getRequestedExtension()) {
			$uriParts = substr($uriParts, 0, strpos($uriParts, $extension) + strlen($extension));
		}
		return trim($uriParts, '/');
	}

}
