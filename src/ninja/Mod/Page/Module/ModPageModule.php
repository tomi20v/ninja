<?php

namespace ninja;

class ModPageModule extends \ModAbstractModule {

	/**
	 * @var \Model
	 */
	protected $_Model;

	/**
	 * @var \View
	 */
	protected $_View;

	public function x_beforeRespond() {

		$this->_Model = \ModPageModel::fromRequest($this->_Request);

	}

}
