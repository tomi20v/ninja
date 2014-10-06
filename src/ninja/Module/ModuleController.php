<?php

namespace ninja;

abstract class ModuleController {

	/**
	 * @param \Module $Module
	 * @return mixed
	 */
	abstract function run($Module);

}

