<?php

namespace ninja;

use ModAbstractModule;

class ModPageRootModule extends \ModPageModule {

	/**
	 * I return only the page object for processing, rest of modules are for inheritance only
	 * @return \ModAbstractModule[] return page only if it's been loaded. return empty array if request matched exactly
	 * 	the root module only
	 */
	protected function _getSubModuleModels() {

		$SubModuleModels = $this->_Model->Page->isLoaded()
			? [$this->_Model->Page]
			: [];

		return $SubModuleModels;

	}

	/**
	 * @param \Request $Request
	 * @return \Response
	 * @throws \HttpException
	 */
	protected function _beforeRespond($Request) {

		parent::_beforeRespond($Request); // TODO: Change the autogenerated stub

		if (!$this->_Model->isLoaded()) {
			throw new \HttpException(404);
		}

		// store a reference in my model. remember, at this point it might not be loaded (if not found).
		$PageModel = \ModPageModel::findByRequestAndRoot($Request, $this->_Model);
		if (!$PageModel->isLoaded() && count($Request->getRemainingUriParts())) {
			// I chould check if a module in root object matches the next uri part but that should be done recursively
			//  since a model with an empty slug still can contain a submodule with a matching slug
			throw new \HttpException(404);
		}
		$this->_Model->Page = $PageModel;

	}

}
