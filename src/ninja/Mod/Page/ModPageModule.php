<?php

namespace ninja;

class ModPageModule extends \ModAbstractModule {

	/**
	 * @var \ModPageModel
	 */
//	protected $_Model;

	/**
	 * @var \View
	 */
//	protected $_View;

	/**
	 * I override parent implementation to append root's submodules
	 * @return \ModAbstractModule[] $subModules
	 */
	protected function _getSubModuleModels() {
		$subModuleModels = parent::_getSubModuleModels();
		// if not root, and root has modules, add them:
		if ($this->_Model->fieldIsSet('Parent') &&
			$this->_Model->field('Root')->fieldIsSet('Modules')) {
			$moreModuleModels = $this->_Model->field('Root')->Modules;
			if (empty($moreModuleModels));
			elseif (empty($subModuleModels)) {
				$subModuleModels = $moreModuleModels;
			}
			else {
				$subModuleModels->append($moreModuleModels);
			}
		}
		return $subModuleModels;
	}

	/**
	 * I have to update $this->_uriParts as page model loading may consume some uri parts
	 * @return ResponseInterface|void
	 */
//	protected function _beforeRespond($Request) {
//
//		$ret = parent::_beforeRespond($Request);
//
//		$this->_uriParts = $this->_Request->getRemainingUriParts();
//
//		return $ret;
//
//	}
//
}
