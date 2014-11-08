<?php

namespace ninja;

class ModPageModule extends \ModAbstractModule {

	/**
	 * @var \ModPageModel
	 */
	protected $_Model;

	/**
	 * @var \View
	 */
	protected $_View;

	/**
	 * I override parent implementation to append root's submodules
	 * @return \ModAbstractModule[] $subModules
	 */
	protected function _getSubModuleModels() {
		$subModuleModels = parent::_getSubModuleModels();
		if ($this->_Model->field('Root')->fieldIsSet('Modules')) {
			$moreModuleModels = $this->_Model->field('Root')->Modules;
			if (empty($subModuleModels)) {
				$subModuleModels = $moreModuleModels;
			}
			else {
				$subModuleModels->append($moreModuleModels);
			}
		}
		return $subModuleModels;
	}

}
