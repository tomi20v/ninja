<?php

require('../../../vendor/autoload.php');

\Maui::instance('default', 'ninja');

$DB = \Maui::instance()->dbDb();
$DB->PageModelCollection->drop();

//$DaLeftContent = new \ModDummyModel('Left dummy', false);
//$DaMiddleContent = new \ModeDummyModel('Right dummy', false);
//$DaRightContent = new \ModeDummyModel('Right dummy', false);

$DaPageRoot = new \ModPageModelRoot(
	[
		'Parent' => null,
		'Root' => null,
		'published' => true,
		'doctype' => 'html',
		'domainName' => '.demoapp.site',
		'redirectType' => \ModPageModelRedirect::REDIRECT_TYPE_PERMANENT,
		'redirectTo' => '/',
		'availableLanguages' => array('en',),
	],
	false
);
//echop($DaPageRoot);
//$DaPageRootResult = $DaPageRoot->save(false);
//echop($DaPageRootResult);
//echop($DaPageRoot);
//echop($DaPageRoot->getData(\ModelManager::DATA_ALL));
//echop($DaPageRoot->getData(\ModelManager::DATA_ORIGINAL));
//echop($DaPageRoot->getData(\ModelManager::DATA_CHANGED));
//echop(\SchemaManager::getSchema($DaPageRoot)); echop($DaPageRoot); die;
//die('NOW');

$DaColumnsContainer = new \ModContainerModel(
	[
		'Contents' => [
			'left' => '<div>Left dummy</div>',
			'middle' => '<div>Middle dummy</div>',
			'right' => '<div>Right dummy</div>',
		],
	],
	false
);

$DaPageHome = new \ModPageModel(
	[
		'Parent' => $DaPageRoot,
		'Root' => $DaPageRoot,
		'published' => true,
		'title' => 'Demo Application home page',
		'Modules' => [
			'columns' => $DaColumnsContainer,
		]
	]
);
//echop($DaPageHome->flatData()); die;
//echop($DaPageHome); die();
echop($DaPageHome->save(true));

$DaPageContact = new \ModPageModel(
	array(
		'Parent' => $DaPageHome,
		'Root' => $DaPageRoot,
		'published' => true,
		'title' => 'Demo Application contact page',
	)
);
//echop($DaPageContact);
echop($DaPageContact->save(true));
//echop($DaPageContact);

$adminDaPageRoot = new \ModPageModelRoot(
	array(
		'Parent' => null,
		'redirectType' => \ModPageModelRedirect::REDIRECT_TYPE_PERMANENT,
		'redirectTo' => '/dashboard',
		'domainName' => 'admin.demoapp.site',
		'availableLanguages' => array('en',),
	),
	false
);
$adminDaPageRootResult = $adminDaPageRoot->save(false);
echop($adminDaPageRootResult);
//echop($adminDaPageRoot);

die('ALL OK');
