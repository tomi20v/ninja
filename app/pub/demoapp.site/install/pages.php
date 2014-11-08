<?php

require('../../../../vendor/autoload.php');

\Maui::instance('default', 'ninja');

$DB = \Maui::instance()->dbDb();
$DB->PageModelCollection->drop();

$JqueryFileServer = new \ModFileservModel(
	['recursive' => true, 'basePath' => 'assets/js', 'folder' => '../vendor/bower-asset/jquery/src',],
	false
);
$JqueryFileServerSizzle = new \ModFileservModel(
	['recursive' => true, 'basePath' => 'assets/js', 'folder' => '../vendor/bower-asset/jquery/external/sizzle/dist',],
	false
);
$BootstrapFileServerJs = new \ModFileservModel(
	['recursive' => true, 'basePath' => 'assets/js', 'folder' => '../vendor/bower-asset/bootstrap/dist/js',],
	false
);
$BootstrapFileServerCss = new \ModFileservModel(
	['recursive' => true, 'basePath' => 'assets/css', 'folder' => '../vendor/bower-asset/bootstrap/dist/css',],
	false
);
$assetModules = [
	'jqueryFiles' => $JqueryFileServer,
	'jqueryFilesSizzle' => $JqueryFileServerSizzle,
	'bootstrapFilesJs' => $BootstrapFileServerJs,
	'bootstrapFilesCss' => $BootstrapFileServerCss,
];

$DaPageRoot = new \ModPageRootModel(
	[
		'Parent' => null,
		'Root' => null,
		'published' => true,
		'doctype' => 'html',
		'domainName' => '.demoapp.site',
		'redirectType' => \ModPageRedirectModel::REDIRECT_TYPE_PERMANENT,
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
		'cssStyle' => 'padding-top:60px;'
	],
	false
);

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
	],
	false
);
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
echop($DaPageContact->save(true));

$RequireJsFileServer = new \ModFileservModel(
	['recursive' => true, 'basePath' => 'assets/js', 'folder' => '../vendor/bower-asset/requirejs',],
	false
);
$JqWidgetsFileServer = new \ModFileservModel(
//	['recursive' => true, 'basePath' => 'assets/js/jqwidgets', 'folder' => '/jqwidgets',],
	['recursive' => true, 'basePath' => 'assets/js/jqwidgets', 'folder' => 'vendor/jqwidgets',],
	false
);
$assetModules[] = $RequireJsFileServer;
$assetModules[] = $JqWidgetsFileServer;

$adminDaPageRoot = new \ModPageRootModel(
	array(
		'Parent' => null,
		'redirectType' => \ModPageRedirectModel::REDIRECT_TYPE_PERMANENT,
		'redirectTo' => '/dashboard',
		'domainName' => 'admin.demoapp.site',
		'availableLanguages' => array('en',),
		'Modules' => $assetModules,
		'scripts' => [
//			['place'=>\ModPageModel::JS_HEAD, 'src'=>'/assets/requirejs/require.js',],
//			['place'=>\ModPageModel::JS_HEAD, 'src'=>'/assets/js/jquery.js',],
//			['place'=>\ModPageModel::JS_HEAD, 'src'=>'/assets/bootstrap/js/bootstrap.js',],
		],
		'css' => [
			['href'=>'/assets/css/bootstrap.css'],
			['href'=>'/assets/css/bootstrap-theme.css'],
		],
		'cssStyle' => 'padding-top:60px;'
	),
	false
);
$adminDaPageRootResult = $adminDaPageRoot->save(true);
echop($adminDaPageRootResult);

$adminDaPageHome = new \ModPageModel(
	[
		'Parent' => $adminDaPageRoot,
		'Root' => $adminDaPageRoot,
		'published' => true,
		'title' => 'Demo ADMIN Application home page',
		'Modules' => [
			'right' => new \ModBaseIncludeModel(
				[ 'template' => 'asd' ],
				false),
		],
		'Contents' => [
//			'left' => '<div id="jqxWidget"><div id="jqxTree"></div><div id="jqxA"></div></div>',
		],
	],
	false
);
$adminDaPageHomeResult = $adminDaPageHome->save(true);
echop($adminDaPageHomeResult);

die('ALL OK');
