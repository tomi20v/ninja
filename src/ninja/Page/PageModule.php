<?php

namespace ninja;

class PageModule extends \Module {

	/**
	 * @var \Model
	 */
	protected $_Model;

	/**
	 * @var \View
	 */
	protected $_View;

	public function createSubmodules() {

		/**
		 * @var \Module[] $subModules
		 */
		$subModules = $this->_Model->Modules;
		foreach ($subModules as $eachKey=>$eachSubModuleModel) {
			$SubModule = $this->getSubModuleFrom($eachSubModuleModel);
			$SubModule->createSubmodules();
			$subModules[$eachKey] = $SubModule;
		}

	}

	public function before() {

		$this->_Model = \PageModel::fromRequest($this->_Request);

	}

	public function respond() {

		$this->before();

		$this->createSubmodules();

		$subModules = $this->_Model->Modules;
		$Contents = array();
		foreach ($subModules as $eachSubmodule) {
			$Response = $eachSubmodule->respond();
			if ($Response instanceof \Response) {
				goto finish;
			}
			$Contents[] = $Response;
		}

		$this->_Model->Contents = $Contents;

		$this->_View = $this->_getView();

		$Response = $this->_View->render();

		finish:

		return $Response;

	}

	/**
	 * @return \View
	 */
	protected function _getView() {

		$View = new \View($this, $this->_Model);

		return $View;

	}

}
