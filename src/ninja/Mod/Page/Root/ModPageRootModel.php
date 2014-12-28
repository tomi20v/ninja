<?php

namespace ninja;

/**
 * Class ModPageRootModel
 *
 * @package ninja
 *
 * @property \ModLayerMode[] $Layers
 * @property string $domainName
 * @property string[] $availableLanguages
 * @property string[] $templateFolders
 */
class ModPageRootModel extends \ModPageRedirectModel {

	protected static $_schema = [
		'@@extends' => [
			'ModPageModel',
			'ModPageRedirectModel',
		],
		'availableLayers' => [
			'toArray',
			'class' => 'ModLayerModel',
			'reference' => \SchemaManager::REF_INLINE,
			// @todo when relatives will have validators...
//			'validLayers',
			'hasMax' => 0,
		],
		'domainName' => [
			'toString',
			'domainName',
		],
		'availableLanguages' => [
			'toArray',
			'hasMin' => 1,
			'hasMax' => 0,
		],
		'templateFolders' => [
			'toArray',
			'hasMin' => 0,
			'hasMax' => 0,
		],
		////////////////////////////////////////////////////////////
		// internal
		////////////////////////////////////////////////////////////
		'Page' => [
			// @todo implement noSave
//			'noSave',
			'class' => 'ModPageModel',
		],
		////////////////////////////////////////////////////////////
		// setup
		////////////////////////////////////////////////////////////
		// this will map requested extension to output type
		'extensionToType' => [
			'toArray',
			'in' => ['pages','api'],
		],
		// this will map output type to view engine
		'typeToViewEngine' => [
			'toArray',
//			'validSubclasses' => 'maui\\ViewEngine',
			'keys' => ['pages','api'],
		],
	];

	/**
	 * @param \Request $Request
	 * @return static
	 */
	public static function findByRequest($Request) {

		$uriParts = $Request->getRemainingUriParts();
		$uriPartsCnt = count($uriParts);

		// I'll reuse this object
		$Finder = static::Finder()
			->equals('parent', null)
			->regex('domainName', '/(\.)?' . $Request->getHttpHost() . '/');

		$ModPageModelRoot = null;
		for ($i=$uriPartsCnt; $i>=0; $i--) {
			$slug = implode('/', $uriParts);
			$ModPageModelRoot = $Finder
				->clear('slug')
				->equals('slug', $slug);
			$ModPageModelRoot = $ModPageModelRoot
				->findOne();
			if ($ModPageModelRoot->isLoaded()) {
				break;
			}
			array_pop($uriParts);
		}

		return $ModPageModelRoot;

	}

}
