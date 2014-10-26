<?php

namespace ninja;

abstract class ModAbstractView extends \View {

	public function __call($method, $arguments) {
		return null;
	}

	/**
	 * use {{{get_Contents}}} in your templates to get the Contents array merged
	 * @return string
	 */
	public function get_Contents() {
		// @todo I could implement a depth-based indenting so output would still be nice
		$ret = $this->_Model->getField('Contents', \ModelManager::DATA_ALL, true);
		if (is_array($ret)) {
			$ret = implode("\n", $ret);
		}
		return $ret;
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

