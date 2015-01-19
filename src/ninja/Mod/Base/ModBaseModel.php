<?php

namespace ninja;

/**
 * Class ModBaseModel - unused placeholder class
 *
 * @package ninja
 */
class ModBaseModel extends \ModAbstractModel {

	/**
	 * I only extend AbstractModel because $_schema cannot be empty
	 * @var array
	 */
	protected static $_schema = [
		'@@extends' => 'ModAbstractModel',
	];

}
