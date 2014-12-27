<?php

namespace ninja;

class ModPageModule extends \ModAbstractModule {

	/**
	 * I override parent implementation to append root's submodules
	 * @return \ModAbstractModule[] $subModules
	 */
	protected function _getSubModuleModels() {
		$subModuleModels = parent::_getSubModuleModels();
		// if not root, and root has modules, add them:
		if ($this->_Model->Data()->fieldIsSet('Parent') &&
			$this->_Model->Data()->getField('Root')->Data()->fieldIsSet('Modules')) {
			$moreModuleModels = $this->_Model->Data()->getField('Root')->Modules;
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
	 * I implement redirection by page
	 * @param \Request $Request
	 * @return \Response
	 */
	protected function _respond($Request, $hasShifted) {

		// @todo I don't like this here (but was the minimal implementation) - maybe put into controller?
		if ($this->_Model instanceof \ninja\ModPageRedirectModel) {

			if (count($Request->getRemainingUriParts())) {
				throw new \HttpException(404);
			};

			$location = $this->_Model->location;
			if (substr($location, 0, 2) === '~/') {
				$location = '/' . \Finder::joinPath($this->getHmvcPath(), substr($location, 2));
			}

			$Response = new \Response(
				'getting redirected...',
				$this->_Model->redirectType,
				[
					'Location' => $location,
				]
			);

		}
		else {

			$Response = parent::_respond($Request, $hasShifted);
			$Response = \Response::wrap($Response);

		}

		$Response->setIsFinal(true);

		return $Response;

	}

}
