<?php

namespace ninja;

class PageModelRedirect extends \PageModel {

	const REDIRECT_TYPE_PERMANENT = '301';
	const REDIRECT_TYPE_FOUND = '302';
	const REDIRECT_TYPE_TEMPORARY = '307';

	protected static $_schema = array(
		'Parent' => array(
			'class' => 'PageModel',
			'reference' => \SchemaManager::REF_REFERENCE,
		),
		'published' => true,
		'Root' =>  array(
			'class' => 'PageModel',
			'reference' => \SchemaManager::REF_REFERENCE,
		),
		'redirectType' => array(
			'toString',
			'in' => array(self::REDIRECT_TYPE_PERMANENT, self::REDIRECT_TYPE_FOUND, self::REDIRECT_TYPE_TEMPORARY),
		),
		'redirectTo' => array(
			'toString',
		)
	);

}
