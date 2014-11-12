<?php

require('../../../../vendor/autoload.php');

\Maui::instance('default', 'ninja');

$DB = \Maui::instance()->dbDb();
$DB->PageModelCollection->drop();

$JqueryFileServer = new \ModFileservModel(
//	['recursive' => true, 'basePath' => 'assets/js', 'folder' => '../vendor/bower-asset/jquery/src',],
//	['recursive' => true, 'basePath' => 'assets/js', 'folder' => '../vendor/bower-asset/jquery/dist',],
	['recursive' => true, 'basePath' => 'js', 'folder' => '../../vendor/bower-asset/jquery/dist',],
	false
);
$BootstrapFileServerJs = new \ModFileservModel(
//	['recursive' => true, 'basePath' => 'assets/js', 'folder' => '../vendor/bower-asset/bootstrap-css/js',],
	['recursive' => true, 'basePath' => 'js', 'folder' => '../../vendor/bower-asset/bootstrap-css/js',],
	false
);
$BootstrapFileServerCss = new \ModFileservModel(
//	['recursive' => true, 'basePath' => 'assets/css', 'folder' => '../vendor/bower-asset/bootstrap-css/css',],
	['recursive' => true, 'basePath' => 'css', 'folder' => '../../vendor/bower-asset/bootstrap-css/css',],
	false
);
$assetModules = [
	'jqueryFiles' => $JqueryFileServer,
	'bootstrapFilesJs' => $BootstrapFileServerJs,
	'bootstrapFilesCss' => $BootstrapFileServerCss,
];

$DaPageRoot = new \ModPageRootModel([
		'Parent' => null,
		'Root' => null,
		// @todo validation shall find that slug is empty
		'slug' => '',
		'published' => true,
		'doctype' => 'html',
		'domainName' => '.demoapp.site',
		'redirectType' => \ModPageRedirectModel::REDIRECT_TYPE_PERMANENT,
		'redirectTo' => '/',
		'availableLanguages' => array('en',),
//		'Modules' => $assetModules,
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
	], false);

$DaColumnsRow = new \ModContainerModel([
		'cssClasses' => [
			'row',
		],
		'Contents' => [
			'left' => '<div class="col-md-3">Left dummy</div>',
			'middle' => '<div class="col-md-6">Middle dummy</div>',
			'right' => '<div class="col-md-3">Right dummy</div>',
		],
	], false);
//$DaRowContainer = new \ModContainerModel(
//	[
//		'cssClasses' => ['container-fluid'],
//		'Contents' => [
//			$DaColumnsRow
//		],
//	],
//	false
//);

// @todo this shall be a redirect or direct 404 page so if no files found it will still do something meaningful
$DaAssetPage = new \ModPageModel([
	'Parent' => $DaPageRoot,
	'Root' => $DaPageRoot,
	'slug' => 'assets',
	'published' => true,
	'domainName' => '.demoapp.site',
	'Modules' => $assetModules,
], false);
echop($DaAssetPage->save());

$DaPageHome = new \ModPageModel([
		'Parent' => $DaPageRoot,
		'Root' => $DaPageRoot,
		'slug' => 'index.html',
		'published' => true,
		'title' => 'Demo Application home page',
		'Modules' => [
			// @todo I currently cannot save with $DaRowContainer, investigate (seems data is not flattened properly)
//			'columns' => $DaRowContainer,
			'columns' => $DaColumnsRow,
		]
	], false);
echop($DaPageHome->save(true));

$DaPageContact = new \ModPageModel([
		'Parent' => $DaPageHome,
		'Root' => $DaPageRoot,
		'slug' => 'about/contact.html',
		'published' => true,
		'title' => 'Demo Application contact page',
	], false);
echop($DaPageContact->save(true));

////////////////////////////////////////////////////////////////////////////////////////////////////
//  A D M I N
////////////////////////////////////////////////////////////////////////////////////////////////////

$RequireJsFileServer = new \ModFileservModel(
	['recursive' => true, 'basePath' => 'assets/js', 'folder' => '../vendor/bower-asset/requirejs',],
	false
);
$JqWidgetsFileServer = new \ModFileservModel(
	['recursive' => true, 'basePath' => 'assets/js/jqwidgets', 'folder' => 'vendor/jqwidgets',],
	false
);
$assetModules[] = $RequireJsFileServer;
$assetModules[] = $JqWidgetsFileServer;

$adminDaPageRoot = new \ModPageRootModel([
		'Parent' => null,
		'slug' => 'admin',
		'published' => true,
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
	], false);
$adminDaPageRootResult = $adminDaPageRoot->save(true);
echop($adminDaPageRootResult);

$adminDaPageHome = new \ModPageModel([
		'Parent' => $adminDaPageRoot,
		'Root' => $adminDaPageRoot,
		'slug' => 'index.html',
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
	], false);
$adminDaPageHomeResult = $adminDaPageHome->save(true);
echop($adminDaPageHomeResult);

//echop(\SchemaManager::getSchema('ModPageModel'));
//echop($adminDaPageHome);

die('ALL OK');
