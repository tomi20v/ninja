<?php

require('../../../../vendor/autoload.php');

\Maui::instance('default', 'ninja');

$DB = \Maui::instance()->dbDb();
$DB->PageModelCollection->drop();
$DB->UserModelCollection->drop();

$JqueryFileServer = new \ModFileservModel(
	['recursive' => true, 'basePath' => 'jquery', 'folder' => '../vendor/components/jquery',],
	false
);
$BootstrapFileServerJs = new \ModFileservModel(
	['recursive' => true, 'basePath' => 'bootstrap/js', 'folder' => '../vendor/components/bootstrap/js',],
	false
);
$BootstrapFileServerCss = new \ModFileservModel(
	['recursive' => true, 'basePath' => 'bootstrap/css', 'folder' => '../vendor/components/bootstrap/css',],
	false
);
$JqWidgetsFileServer = new \ModFileservModel(
	['recursive' => true, 'basePath' => 'jqwidgets', 'folder' => 'vendor/jqwidgets/jqwidgets',],
	false
);
$requireJsFileServer = new \ModFileservModel([
	'recursive' => true, 'basePath' => 'requirejs', 'folder' => '../vendor/bower-asset/requirejs',
]);
$faFileServerCss = new \ModFileservModel([
	'recursive' => true,
	'basePath' => 'font-awesome',
	'folder' => '../vendor/components/font-awesome',
]);
$adminAssetsServer = new \ModFileservModel(
	['recursive' => true, 'basePath' => 'admin', 'folder' => '../src/ninja/Mod/Admin/assets',],
	false
);
$assetModules = [
	'jqueryFiles' => $JqueryFileServer,
	'bootstrapFilesJs' => $BootstrapFileServerJs,
	'bootstrapFilesCss' => $BootstrapFileServerCss,
	'jqWidgetsFiles' => $JqWidgetsFileServer,
	'requireJsFiles' => $requireJsFileServer,
	'faFilesCss' => $faFileServerCss,
	'adminFiles' => $adminAssetsServer,
];

$DaPageRoot = new \ModPageRootModel([
		'Parent' => null,
		'Root' => null,
		// @todo validation shall find that slug is empty
		'slug' => '',
		'published' => true,
		'domainName' => '.demoapp.site',
		'availableLayers' => [
			'default' => new \ModLayerModel([
					'label' => 'default',
				], false)
		],
		'availableLanguages' => array('en',),
		// .html response data
		'redirectType' => \ModPageRedirectModel::REDIRECT_TYPE_PERMANENT,
		'location' => '/index.html',
		// modules and stuff to be inherited by child pages
		'Modules' => [
			'footer' => new \ModBaseIncludeModel([
					'template' => 'footer.html',
				], false),
			'header' => new \ModBaseIncludeModel([
					'slug' => '',
					'template' => 'header.html',
					'Modules' => [
//						'login' => new \ModContainerExpandableModel([
//							'Modules' => [
//								'trigger' => new \ModUserLoginStatusModel([
////										'slug' => 'hu',
//									], false),
//								'content' => new \ModUserLoginModel([
								'login' => new \ModUserLoginModel([
										'slug' => 'login',
//										'cssClasses' => ['navbar-form'],
									], false),
//							]
//						])
					],
				], false),
		],
		'scripts' => [
			['place'=>\ModPageModel::JS_HEAD, 'src'=>'/assets/jquery/jquery.js',],
			['place'=>\ModPageModel::JS_HEAD, 'src'=>'/assets/bootstrap/js/bootstrap.js',],
		],
		'css' => [
			['href'=>'/assets/bootstrap/css/bootstrap.css'],
			['href'=>'/assets/bootstrap/css/bootstrap-theme.css'],
		],
		'cssStyle' => 'padding-top:60px;',
		'extensionToType' => [
			'html' => 'pages',
			'json' => 'api',
		],
		'typeToViewEngine' => [
			'pages' => 'Mustache',
			'api' => 'Json',
		],
	], false);
//echop($DaPageRoot);
echop($DaPageRoot->save(false));
//echop($DaPageRoot->_id);
//die('OK');

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

// @todo this shall be a redirect or direct 404 page so if no files found it will still do something meaningful
$DaAssetsPage = new \ModPageModel([
	'Parent' => $DaPageRoot,
	'Root' => $DaPageRoot,
	'slug' => 'assets',
	'published' => true,
	'domainName' => '.demoapp.site',
	'Modules' => $assetModules,
], false);
echop($DaAssetsPage->save(false));

$DaPageHome = new \ModPageModel([
		'Parent' => $DaPageRoot,
		'Root' => $DaPageRoot,
		'slug' => 'index',
		'published' => true,
		'title' => 'Demo Application home page',
		'Modules' => [
			// @todo I currently cannot save with $DaRowContainer, investigate (seems data is not flattened properly)
//			'columns' => $DaRowContainer,
			'columns' => $DaColumnsRow,
		]
	], false);
echop($DaPageHome->save(false));

$DaPageContact = new \ModPageModel([
		'Parent' => $DaPageHome,
		'Root' => $DaPageRoot,
		'slug' => 'about/contact.html',
		'published' => true,
		'title' => 'Demo Application contact page',
	], false);
echop($DaPageContact->save(false));

////////////////////////////////////////////////////////////////////////////////////////////////////
//  A D M I N
////////////////////////////////////////////////////////////////////////////////////////////////////

$adminDaPageRoot = new \ModPageRootModel([
		'Parent' => null,
		'Root' => null,
		'slug' => 'admin',
		'published' => true,
		'redirectType' => \ModPageRedirectModel::REDIRECT_TYPE_PERMANENT,
		'location' => '~/index.html',
		'domainName' => 'demoapp.site',
		'scripts' => [
			['place'=>\ModPageModel::JS_HEAD, 'src'=>'/assets/requirejs/require.js',],
			['place'=>\ModPageModel::JS_HEAD, 'src'=>'/assets/admin/js/main.js',],
		],
		'css' => [
			['href'=>'/assets/bootstrap/css/bootstrap.css'],
			['href'=>'/assets/bootstrap/css/bootstrap-theme.css'],
			['href'=>'/assets/jqwidgets/styles/jqx.base.css'],
			['href'=>'/assets/font-awesome/css/font-awesome.css'],
			['href'=>'/assets/admin/css/admin.css'],
		],
		'cssStyle' => 'padding-top:60px; padding-left:225px;'
	]);
$adminDaPageRootResult = $adminDaPageRoot->save(true);
echop($adminDaPageRootResult);

$adminDaPageHome = new \ModPageModel([
		'Parent' => $adminDaPageRoot,
		'Root' => $adminDaPageRoot,
		'slug' => 'index',
		'published' => true,
		'title' => 'Demo ADMIN Application home page',
		'Modules' => [
			'columns' => new \ModNavBarModel([
					'inverse' => true,
					'fixed' => 'top',
					'Modules' => [
						new \ModAdminMenuModel([
							'cssClasses' => ['side-nav'],
						]),
					],
				]),
		],
		'Contents' => [
//			'left' => '<div id="jqxWidget"><div id="jqxTree"></div><div id="jqxA"></div></div>',
		],
	]);
$adminDaPageHomeResult = $adminDaPageHome->save(true);
echop($adminDaPageHomeResult);

//$adminDaPageApi = new \ModPageModel([
//		'Parent' => $adminDaPageRoot,
//		'Root' => $adminDaPageRoot,
//		'slug' => 'api',
//		'published' => true,
//		'title' => 'Demo ADMIN Application API endpoint',
//		'Modules' => [],
//		'Contents' => [
////			'left' => '<div id="jqxWidget"><div id="jqxTree"></div><div id="jqxA"></div></div>',
//		],
//	], false);
//$adminDaPageApiResult = $adminDaPageApi->save(true);
//echop($adminDaPageApiResult);
//
//echop(\SchemaManager::getSchema('ModPageModel'));
//echop($adminDaPageHome);

$User = new \User();
$User->Data()
	->setField('email', 'no@ema.il')
	->setField('password', sha1('123'))
	->Data()->setField('active', true)
;
$result = $User->save();
echop($User); echop($result);


$testPage = new \ModPageModel([
		'Parent' => $DaPageRoot,
		'Root' => $DaPageRoot,
		'slug' => 'test',
		'title' => 'Demo App Test page',
		'Contents' => [
			'<p>test page</p>'
		],
	], false);
$result = $testPage->save();
echop($result);

die('ALL OK');
