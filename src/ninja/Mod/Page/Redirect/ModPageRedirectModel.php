<?php

namespace ninja;

/**
 * Class ModPageRedirectModel
 *
 * @package ninja
 *
 * @property \ModPageModel $Parent
 * @property bool $published
 * @property \ModPageModel $Root
 * @property int $redirectType set permanent, found, or temporary redirect
 * @property string $location will redirect to this. form '~/xxx' means
 * 		relative url, so current hmvc path will be prepended
 */
class ModPageRedirectModel extends \ModPageModel {

	const REDIRECT_TYPE_PERMANENT = \Response::HTTP_MOVED_PERMANENTLY;
	const REDIRECT_TYPE_FOUND = \Response::HTTP_FOUND;
	const REDIRECT_TYPE_TEMPORARY = \Response::HTTP_TEMPORARY_REDIRECT;

	protected static $_schema = [
		'Parent' => [
			'class' => 'ModPageModel',
			'reference' => \SchemaManager::REF_REFERENCE,
		],
		'published' => true,
		'Root' =>  [
			'class' => 'ModPageModel',
			'reference' => \SchemaManager::REF_REFERENCE,
		],
		'redirectType' => [
			'toString',
			'in' => [
				self::REDIRECT_TYPE_PERMANENT,
				self::REDIRECT_TYPE_FOUND,
				self::REDIRECT_TYPE_TEMPORARY
			],
		],
		'location' => [
			'toString',
		]
	];

}
