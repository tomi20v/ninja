<?php

namespace ninja;

class ModUserLoginController extends \ModAbstractController {

	public function postIndex($params=null) {
//		$User = \User::fromRequest($this->_Request);
//		$User->save();
		throw new \Exception('TBI');
	}

	public function actionIndex($params = null) {

//		$User = $this->_Request->User();

		return parent::actionIndex($params); // TODO: Change the autogenerated stub
	}

}