<?php

namespace ninja;

/**
 * Class ModPageModelAsset - I attach to a Model (shall be instance of ModPageModel), and expose helper methods
 * 		to add assets. Data is still stored in the Model's attributes.
 * I could be used to implement some asset concatenating?
 * @package ninja
 */
class ModPageModelAsset {

	protected $_Model;

	public function __construct($Model) {
		$this->_Model = $Model;
	}

	/**
	 * I add a JS file to be loaded. I won't add a script twice
	 * @param string $place
	 * @param string $src
	 * @return ModPageModelAsset
	 */
	public function addJsFile($place, $src) {
		$script = ['place'=>$place, 'src'=>$src];
		return $this->_addJs($script);
	}

	/**
	 * I add JS code which executes wherever it is inserted
	 * @param string $place
	 * @param string $code
	 * @return ModPageModelAsset
	 */
	public function addJsCode($place, $code) {
		$script = ['place'=>$place, 'code'=>$code];
		return $this->_addJs($script);
	}

	/**
	 * I add JS code which executes on document ready
	 * @param string $code
	 * @return ModPageModelAsset
	 */
	public function addJsOnready($code) {
		$code = '$(function(){ ' . $code . ' });';
		$script = ['place'=>\ModPageModel::JS_HEAD, 'code'=>$code];
		return $this->_addJs($script);
	}

	/**
	 * I am the core of adding a JS asset
	 * @param $script
	 * @return $this
	 */
	protected function _addJs($script) {
		$scripts = $this->_Model->scripts;
		if (!in_array($script, $scripts)) {
			$scripts[] = $script;
			$this->_Model->scripts = $scripts;
		}
		return $this;
	}

	/**
	 * I add a CSS file to be included. I do not add duplicates.
	 * @param string $href
	 * @param null|string $media specify media if needed
	 * @param null|string $onlyIf specify only if (for IE version specific inclusions)
	 * @return $this
	 */
	public function addCss($href, $media=null, $onlyIf=null) {
		$css = ['href'=>$href];
		if (!is_null($media)) {
			$css['media'] = $media;
		}
		if (!is_null($onlyIf)) {
			$css['onlyIf'] = $onlyIf;
		}
		$csss = $this->_Model->css;
		if (!in_array($css, $csss)) {
			$csss[] = $css;
			$this->_Model->css = $csss;
		}
		return $this;
	}

}
