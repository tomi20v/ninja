<?php

namespace ninja;

abstract class ModAbstractView extends \View {

	/**
	 * @var string I map methods which match this reachable in template by property name
	 */
	const EXPOSED_METHOD_MASK = '/^(get|fetch|mark)([a-zA-Z0-9]+)(\_[a-zA-Z0-9]+)?$/';

	/**
	 * @var \ModAbstractModule reference to my parent
	 */
	protected $_Module;

	/**
	 * @var string[] I hold all keys which have been fetched already
	 */
	protected $_fetchedKeys = array();

	/**
	 * @var string[] I will not expose these methods to the template
	 */
	protected static $_protectedMethods = ['from', 'render', 'setTemplate'];

	/**
	 * I map my method to property for the template
	 * @param string $name
	 * @return bool
	 */
	function __isset($name) {
		return preg_match(static::EXPOSED_METHOD_MASK, $name, $matches) && method_exists($this, $matches[1] . $matches[2]);
	}

	/**
	 * I map my method to property for the template
	 * @param string $name
	 * @return mixed
	 */
	function __get($name) {
		if (preg_match(static::EXPOSED_METHOD_MASK, $name, $matches)) {
			return call_user_func([$this, $matches[1].$matches[2]], substr($matches[3], 1));
		}
		return null;
	}

	/**
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

	////////////////////////////////////////////////////////////////////////////////
	// methods pullable from template
	////////////////////////////////////////////////////////////////////////////////

	/**
	 * use {{{get_Contents}}} in your templates to get the Contents array merged
	 * @return string
	 */
	public function getContents() {
		$ret = $this->_Model->Data()->getField('Contents', \ModelManager::DATA_ALL, true);
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
	public function fetchContents($key) {
		$this->_fetchedKeys[] = $key;
		$Contents = $this->_Model->getField('Contents');
		return isset($Contents[$key]) ? $Contents[$key] : null;
	}

	/**
	 * I mark a key fetched so get_Contents won't return it
	 * @param string $key
	 * @return null
	 */
	public function markContents($key) {
		$this->_fetchedKeys[] = $key;
		return null;
	}

	/**
	 * use {{{get_ModelDebug}}} in your template to print the current Model
	 * @return string
	 */
	public function getModelDebug() {
		$ret = '$this->_Model:' . \EchoPrinter::echop($this->_Model, true, 0, 3, true, false);
		return $ret;
	}

}

