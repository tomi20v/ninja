<?php

namespace ninja;

/**
 * @TODO make this just a decorator to Request with magics
 * Class Request for http requests
 * currently I just formally extend Symfony's Request object
 */
class Request extends \Symfony\Component\HttpFoundation\Request {

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
	 * @return $this
	 */
	public function shiftUriParts(&$countOrParts) {
		if (is_string($countOrParts)) {
			$countOrParts = explode('/', $countOrParts);
		}
		if (is_array($countOrParts)) {
			do {
				if (reset($countOrParts) !== reset($this->_remainingUriParts)) {
					break;
				}
				array_shift($countOrParts);
				array_shift($this->_remainingUriParts);
			} while (count($countOrParts) && count($this->_remainingUriParts));
		}
		else {
			$this->_remainingUriParts = array_slice($this->_remainingUriParts, intval($countOrParts));
		}

		return $this;
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
		$pattern = '/.(' . implode('|', $this->_routedExtensions) . ')(\/.*|)$/';
		if (preg_match($pattern, $this->getRequestUri(), $matches)) {
			return $matches[1];
		}
		return null;
	}

}
