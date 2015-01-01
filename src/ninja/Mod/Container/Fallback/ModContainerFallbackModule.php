<?php

namespace ninja;

/**
 * Class ModContainerFallbackModule - a module that returns only the first valid response from submodules.
 * so, if first module returns empty, it processes next, etc. in chain. returns first given response
 * eg. put a reader module and then a list module in a page, and if reader module doesnt match any entry by url and
 * 	returns empty then list module will be called. if reader module matches entry, it will be returned only
 *
 * @package ninja
 */
class ModContainerFallbackModule extends \ModAbstractModule {

	/**
	 * I override original _processSubmodules - only the one first response will be kept (and generated)
	 * @param \Request $Request
	 * @return \maui\ResponeInterface|null
	 */
	protected function _processSubmodules($Request) {

		$subModuleModels = $this->_getSubModuleModels();

		if (!empty($subModuleModels)) {

			$subModules = [];

			$Contents = [];

			foreach ($subModuleModels as $eachKey => $eachSubModuleModel) {
				$SubModule = $this->_getSubModuleFrom($eachSubModuleModel);
				$subModules[$eachKey] = $SubModule;
				$SubRequest = $Request->getClone();
				$Response = $SubModule->respond($SubRequest);

				if ($Response instanceof \ninja\Response) {
					if ($Response->getIsFinal()) {
						return $Response;
					}
					$Response = $Response->getContent();
				}
				$Contents[$eachKey] = $Response;
				// I break on first non empty response
				if (!empty($Response)) {
					break;
				}
			}

			$this->_Model->Modules = $subModules;

			// if no submodule response at all, get first element of Contents
			if (empty($Contents)) {
				$Contents = array_slice((array)$this->_Model->Contents, 0, 1);
			}

			$this->_Model->Contents = $Contents;

		}

		return null;

	}

}
