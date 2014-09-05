<?php

namespace ninja;

class HtmlDocument extends \Model {

	protected static $_schema = array(
		'doctype',
		'content' => array(
			'head' => array(
				'title',
				'meta' => array(
					'reference' => \SchemaManager::REF_INLINE,
					'hasMin' => 0,

				),
				'script' => array(
					'reference' => \SchemaManager::REF_INLINE,
				)
			),
			'body' => array(
				'class' => 'HtmlContent',
				'reference' => \SchemaManager::REF_INLINE,
				'hasMin' => 1,
				'hasMax' => 1,
			)
		)
	);

}
