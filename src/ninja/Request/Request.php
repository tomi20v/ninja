<?php

namespace ninja;

use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * @TODO make this just a decorator to Request with magics???
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
	 * @var string[] original uri parts
	 */
	private $_targetUriParts = null;

	/**
	 * @var string[] original uri parts
	 */
	private $_remainingTargetUriParts = null;

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

	public function __construct(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), $content = null) {
		parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
	}

	/**
	 * set $this->_uriParts and $this->_targetUriParts
	 */
	private function _setUriParts() {
		$uriParts = $this->getRequestUri();
		if ($pos = strpos($uriParts, '?')) {
			$uriParts = substr($uriParts, 0, $pos);
		};
		$this->_targetUriParts = explode('/', trim($uriParts, '/'));
		if ($extension = $this->getRequestedExtension()) {
			$pos = strpos($uriParts, $extension) + strlen($extension);
			$this->_targetUriParts = explode('/', trim(substr($uriParts, 0, $pos), '/'));
			$uriParts = str_replace('.'.$extension, '', $uriParts);
		}
		$this->_uriParts = explode('/', trim($uriParts, '/'));

		$this->_remainingTargetUriParts = $this->_targetUriParts;
		$this->_remainingUriParts = $this->_uriParts;

	}

	/**
	 * I return all request uri parts without query string in a nice array
	 * @return array|string
	 */
	public function getUriParts() {
		if (is_null($this->_uriParts)) {
			$this->_setUriParts();
		}
		return $this->_uriParts;
	}

	/**
	 * @return string get the original (no consume) target uri (with requested extension)
	 */
	public function getTargetUri() {
		return implode('/', $this->_targetUriParts);
	}

	/**
	 * I return actual remaining uri parts
	 * @return array|string
	 */
	public function getRemainingUriParts() {
		if (is_null($this->_remainingUriParts)) {
			$this->_setUriParts();
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
				array_shift($this->_remainingTargetUriParts);
				$ret++;
			} while (count($countOrParts) && count($this->_remainingUriParts));
		}
		else {
			$ret = intval($countOrParts);
			$this->_remainingUriParts = array_slice($this->_remainingUriParts, $ret);
			$this->_remainingTargetUriParts = array_slice($this->_remainingTargetUriParts, $ret);
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
		$NewRequest->_targetUriParts = $NewRequest->_remainingTargetUriParts;
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

		return $Request;

	}

	/**
	 * @return $this
	 */
	public function setActionMatched($actionMatched) {

		$this->_actionMatched = $actionMatched ? true : false;

		return $this;

	}

	/**
	 * @return boolean
	 */
	public function getActionMatched() {

		return $this->_actionMatched;
	}

}
