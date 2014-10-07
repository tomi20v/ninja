<?php

namespace ninja;

class PageModule extends \Module {

	public function respond() {

		$Page = \PageModel::fromRequest($this->_Request);

		echop($Page);

		debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS); die('TBI');
	}

}
