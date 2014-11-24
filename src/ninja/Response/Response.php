<?php

namespace ninja;

/**
 * Class Response - I currently just extend symfony request class formally.
 * @todo make compatible with Model so I can be loaded/saved easily
 *
 * @package ninja
 */
class Response extends \Symfony\Component\HttpFoundation\Response {

	/**
	 * @var bool|null if this is set true
	 */
	protected $_isFinal = null;

	public function getIsFinal() {
		return $this->_isFinal;
	}

	public function setIsFinal($isFinal) {
		$this->_isFinal = (bool) $isFinal;
		return $this;
	}

	/**
	 * I redefine setContent so that if a View is passed in it does not get generated yet but at response time only
	 *
	 * @param mixed $content Content that can be cast to string
	 * @return Response
	 * @throws \UnexpectedValueException
	 */
	public function setContent($content)
	{
		if (($content !== null) && !is_string($content) && !is_numeric($content) && !is_callable(array($content, '__toString'))) {
			throw new \UnexpectedValueException(sprintf('The Response content must be a string or object implementing __toString(), "%s" given.', gettype($content)));
		}

		$this->content = $content;

		return $this;
	}

}
