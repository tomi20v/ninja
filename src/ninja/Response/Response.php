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
	 * if true, response flow is stopped and current Response
	 * @var bool|null if this is set true
	 */
	protected $_isFinal = null;

	/**
	 * I wrap param in a Response object if it is not already an instance of that
	 * @param \Response|mixed $Response
	 * @return \Response
	 */
	public static function wrap($Response) {
		if ($Response instanceof \ninja\Response);
		else {
			$Response = new \Response($Response);
		}
		return $Response;
	}

	/**
	 * I tell if Response is final
	 * will be sent at the end
	 * @return bool|null
	 */
	public function getIsFinal() {
		return $this->_isFinal;
	}

	/**
	 * I set if current Response is final
	 * @param $isFinal
	 * @return $this
	 */
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
