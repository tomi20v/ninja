<?php

namespace ninja;

/**
 * Class SchemaValidatorValidLayers - validates if:
 *  - $Model is an instance of ModPageRootModel and $val is array or collection of ModLayerModels
 *  - $Model is instance of ModAbstracModel (but not root)- validate if $val is string and is in $Model->Root 's 'layers' field
 *
 * @package ninja
 */
class SchemaValidatorValidLayers extends \SchemaValidator {

	private function _getValidValues($Model) {

		$validValues = [];

		if ($Model instanceof \ModAbstractModel) {
			if ($Model->fieldIsSet('Root')) {
				$Root = $Model->Root;
				$Layers = $Root->availableLayers;
				$validValues = [];
				foreach ($Layers as $EachLayer) {
					if ($EachLayer->active) {
						$validValues[] = $EachLayer->label;
					}
				}
			}
		}

		return $validValues;

	}

	public function validate($val, $Model=null) {
		if (is_null($Model));
		// root page module defines the valid layers indeed
		elseif ($Model instanceof \ModPageRootModel) {
			if (!is_array($val) || !$val instanceof \Collection) {
				return false;
			}
			foreach ($val as $eachVal) {
				if (!$val instanceof \ModLayerModel) {
					return false;
				}
			}
			return true;
		}
		elseif ($Model instanceof \ModAbstractModel) {
			return in_array($val, $this->_getValidValues($Model));
		}
		return null;
	}

	public function getError($val, $Model=null) {
		return 'not a valid layer, shall be in {' . implode(', ', $this->_getValidValues($Model)) . ')';
	}

	public function apply(&$val, $Model=null) {
		if (is_array($val)) {
			return \ArrayHelper::containsOnly($val, ['string', 'ModLayerModel']);
		}
	}

	public function filter($val, $Model=null) {
		return $this->validate($val, $Model) ? $val : null;
	}

}
