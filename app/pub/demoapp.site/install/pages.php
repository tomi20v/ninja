<?php

require('../../../../vendor/autoload.php');

\Maui::instance('default', 'ninja');

$DB = \Maui::instance()->dbDb();
$DB->PageModelCollection->drop();

//$DaLeftContent = new \ModDummyModel('Left dummy', false);
//$DaMiddleContent = new \ModeDummyModel('Right dummy', false);
//$DaRightContent = new \ModeDummyModel('Right dummy', false);

$JqueryFileServer = new \ModFileservModel(
	['recursive' => true, 'basePath' => 'assets', 'folder' => '../../bower_components/jquery/dist',],
	false
);
$TwitterFileServer = new \ModFileservModel(
	['recursive' => true, 'basePath' => 'assets', 'folder' => '../../bower_components/bootstrap/dist',],
	false
);
$WebixFileServer = new \ModFileservModel(
	['recursive' => true, 'basePath' => 'assets', 'folder' => '../../bower_components/webix/codebase',],
	false
);
$assetModules = [
	'jqueryFiles' => $JqueryFileServer,
	'twitterFiles' => $TwitterFileServer,
	'webixFiles' => $WebixFileServer,
];
//echop($TwitterFileServer); die;
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
		'Modules' => $assetModules,
		'scripts' => [
			['place'=>\ModPageModel::JS_HEAD, 'src'=>'/assets/jquery.js',],
			['place'=>\ModPageModel::JS_HEAD, 'src'=>'/assets/js/bootstrap.js',],
			['place'=>\ModPageModel::JS_HEAD, 'code'=>'var injected_var;'],
		],
		'css' => [
			['href'=>'/assets/css/bootstrap.css'],
			['href'=>'/assets/css/bootstrap-theme.css'],
		],
		'cssStyle' => 'padding-top:70px;'
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

$DaColumnsRow = new \ModContainerModel(
	[
		'cssClasses' => [
			'row',
		],
		'Contents' => [
			'left' => '<div class="col-md-3">Left dummy</div>',
			'middle' => '<div class="col-md-6">Middle dummy</div>',
			'right' => '<div class="col-md-3">Right dummy</div>',
		],
	],
	false
);
//$DaRowContainer = new \ModContainerModel(
//	[
//		'cssClasses' => ['container-fluid'],
//		'Contents' => [
//			$DaColumnsRow
//		],
//	],
//	false
//);

$DaPageHome = new \ModPageModel(
	[
		'Parent' => $DaPageRoot,
		'Root' => $DaPageRoot,
		'published' => true,
		'title' => 'Demo Application home page',
		'Modules' => [
			// @todo I currently cannot save with $DaRowContainer, investigate (seems data is not flattened properly)
//			'columns' => $DaRowContainer,
			'columns' => $DaColumnsRow,
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
	),
	false
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
		'Modules' => $assetModules,
		'scripts' => [
			['place'=>\ModPageModel::JS_HEAD, 'src'=>'/assets/jquery.js',],
			['place'=>\ModPageModel::JS_HEAD, 'src'=>'/assets/js/bootstrap.js',],
			['place'=>\ModPageModel::JS_HEAD, 'src'=>'/assets/webix.js',],
		],
		'css' => [
			['href'=>'/assets/css/bootstrap.css'],
			['href'=>'/assets/css/bootstrap-theme.css'],
			['href'=>'/assets/css/webix.css'],
		],
	),
	false
);
$adminDaPageRootResult = $adminDaPageRoot->save(false);
echop($adminDaPageRootResult);
//echop($adminDaPageRoot);

$adminDaPageHome = new \ModPageModel(
	[
		'Parent' => $adminDaPageRoot,
		'Root' => $adminDaPageRoot,
		'published' => true,
		'title' => 'Demo ADMIN Application home page',
		'Contents' => ['left' => '<div id="w"></div>',],
	],
	false
);
$adminDaPageHomeResult = $adminDaPageHome->save(false);
echop($adminDaPageHomeResult);

die('ALL OK');
