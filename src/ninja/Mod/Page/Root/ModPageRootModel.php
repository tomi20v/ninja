<?php

namespace ninja;

/**
 * Class ModPageRootModel
 *
 * @package ninja
 *
 * @property \ModLayerMode[] $Layers
 * @property string $domainNmae
 * @property string[] $availableLanguages
 * @property string[] $templateFolders
 */
class ModPageRootModel extends \ModPageRedirectModel {

	protected static $_schema = array(
		'@@extends' => array(
			'ModPageModel',
			'ModPageRedirectModel',
		),
		'Layers' => [
			'toArray',
			'class' => 'ModLayerModel',
			'reference' => \SchemaManager::REF_INLINE,
			// @todo when relatives will have validators...
//			'validLayers',
			'hasMax' => 0,
		],
		'domainName' => array(
			'toString',
			'domainName',
		),
		'availableLanguages' => array(
			'toArray',
			'hasMin' => 1,
			'hasMax' => 0,
		),
		'templateFolders' => array(
			'toArray',
			'hasMin' => 0,
			'hasMax' => 0,
		),
	);

	/**
	 * @param \Request $Request
	 * @return static
	 */
	public static function findByRequest($Request) {

		$uriParts = $Request->getRemainingUriParts();
		$uriPartsCnt = count($uriParts);

		// I'll reuse this object
		$Finder = static::finder()
			->equals('parent', null)
			->regex('domainName', '/(\.)?' . $Request->getHttpHost() . '/');

		$ModPageModelRoot = null;
		for ($i=$uriPartsCnt; $i>=0; $i--) {
			$slug = implode('/', $uriParts);
			$ModPageModelRoot = $Finder
				->clear('slug')
				->equals('slug', $slug)
				->findOne();
			if ($ModPageModelRoot->isLoaded()) {
				break;
			}
			array_pop($uriParts);
		}

		if (is_null($ModPageModelRoot) || !$ModPageModelRoot->isLoaded()) {
			throw new \HttpException(404);
		}

		return $ModPageModelRoot;

	}

}
