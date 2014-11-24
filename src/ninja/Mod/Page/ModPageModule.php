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

	protected function _respond($Request) {
		// @todo I don't like this here (but was the fastest implementation)
		if ($this->_Model instanceof \ModPageRedirectModel) {
			$Response = new \Response(
				'getting redirected...',
				$this->_Model->redirectType,
				[
					'Location' => $this->_Model->redirectTo,
				]
			);
			$Response->setIsFinal(true);
			return $Response;
		}
		return parent::_respond($Request);
	}

}
