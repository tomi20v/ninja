<?php

namespace ninja;

abstract class ModAbstractView extends \View {

	/**
	 * use {{{getContents}}} in your templates to get the Contents array merged
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

}

