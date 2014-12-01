<?php

namespace ninja;

abstract class ModAbstractView extends \View {

	/**
	 * @var \ModAbstractModule reference to my parent
	 */
	protected $_Module;

	/**
	 * @var string[] I hold all keys which have been fetched already
	 */
	protected $_fetchedKeys = array();

	public function __call($method, $arguments) {
		if (preg_match(\Filter::MASK_FETCHER, $method, $matches)) {
			$key = $matches[1];
			if (!in_array($key, $this->_fetchedKeys)) {
				$this->_fetchedKeys[] = $key;
			}
			$Contents = $this->_Model->getField('Contents', \ModelManager::DATA_ALL, true);
			if (isset($Contents[$key])) {
				return $Contents[$key];
			}
		}
		return null;
	}

	/**
	 * @todo this shall be moved to ModAbstractView!?
	 * @param $Module
	 * @param $Model
	 * @param null $template
	 */
	public function __construct($Module, $Model, $template=null, $requestedExtension=null) {
		if (!$Module instanceof \ModAbstractModule ||
			!$Model instanceof \ModAbstractModel) {
			throw new \BadMethodCallException();
		}
		$this->_Module = $Module;
		$this->_Model = $Model;
		$this->_template=$template;
		if (!is_null($requestedExtension)) {
			$this->_requestedExtension = $requestedExtension;
		}
	}

	/**
	 * use {{{get_Contents}}} in your templates to get the Contents array merged
	 * @return string
	 */
	public function get_Contents() {
		// @todo I could implement a depth-based indenting so output would still be nice
		$ret = $this->_Model->getField('Contents', \ModelManager::DATA_ALL, true);
		if (is_array($ret)) {
			$ret = array_diff_key($ret, array_flip($this->_fetchedKeys));
			$ret = implode("\n", $ret);
		}
		return $ret;
	}

	/**
	 * I return a field of the Model and mark it fetched
	 * @param string $key
	 * @return string|mixed
	 */
	public function fetchFromContents($key) {
		$this->_fetchedKeys[] = $key;
		$Contents = $this->_Model->getField('Contents');
		return isset($Contents[$key]) ? $Contents[$key] : null;
	}

	/**
	 * I mark a key fetched so get_Contents won't return it
	 * @param string $key
	 * @return null
	 */
	public function markFetchedFromContents($key) {
		$this->_fetchedKeys[] = $key;
		return null;
	}

	/**
	 * use {{{get_ModelDebug}}} in your template to print the current Model
	 * @return string
	 */
	public function get_ModelDebug() {
		$ret = '$this->_Model:' . \EchoPrinter::echop($this->_Model, true, 0, 3, true, false);
		return $ret;
	}

}

