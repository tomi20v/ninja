<?php

namespace ninja;

class PageModule extends \Module {

	public function __construct($Request = null, $Parent = null) {

		parent::__construct($Request, $Parent);

		// if I am top object (normally should be), load Page object
		if (is_null($Parent)) {
			$this->_Model = \PageModel::getFromRequest($Request);
		}

	}

}
