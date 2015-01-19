<?php

require('../../../../vendor/autoload.php');

\Maui::instance('default', 'ninja');

$DB = \Maui::instance()->dbDb();
$DB->PageModelCollection->drop();
$DB->UserModelCollection->drop();

$bowerFileServer = new \ModFileservModel(
	['recursive'=>true, 'basePath' => 'bower-asset', 'folder' => '../vendor/bower-asset']
);
$adminAssetsServer = new \ModFileservModel(
	['recursive' => true, 'basePath' => 'admin', 'folder' => '../src/ninja/Mod/Admin/assets', 'filter'=>['ModFileservModule','filterLess']],
	false
);
$assetModules = [
	'bowerFiles' => $bowerFileServer,
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
			['place'=>\ModPageModel::JS_HEAD, 'src'=>'/assets/bower-asset/jquery/dist/jquery.js',],
			['place'=>\ModPageModel::JS_HEAD, 'src'=>'/assets/bower-asset/bootstrap/dist/js/bootstrap.js',],
		],
		'links' => [
			['rel'=>'stylesheet', 'href'=>'/assets/bower-asset/bootstrap/dist/css/bootstrap.css'],
			['rel'=>'stylesheet', 'href'=>'/assets/bower-asset/bootstrap/dist/css/bootstrap-theme.css'],
		],
		'cssStyle' => 'padding-top:60px;',
		'extraAttributes' => 'unresolved fullbleed',
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
			['place'=>\ModPageModel::JS_HEAD, 'src'=>'/assets/bower-asset/webcomponentsjs/webcomponents.js'],
			['place'=>\ModPageModel::JS_HEAD, 'src'=>'/assets/bower-asset/requirejs/require.js',],
			['place'=>\ModPageModel::JS_HEAD, 'src'=>'/assets/admin/js/main.js',],
		],
		'links' => [
			['rel'=>'stylesheet', 'href'=>'/assets/admin/css/admin.css.less'],
			['rel'=>'import', 'href'=>'/assets/bower-asset/font-roboto/roboto.html'],
		],
		'extraAttributes' => 'unresolved fullbleed',
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
			'columns' => new \ModContainerModel([
					'template' => 'index.html',
					'templatePath' => 'Admin/template',
					'Modules' => [
						'nav' => new \ModAdminMenuModel([
								'cssClasses' => ['side-nav'],
						]),
					],
			]),
		],
		'Contents' => [
//			'columns' => '<div id="ni-app"></div>',
		],
	]);
$adminDaPageHomeResult = $adminDaPageHome->save(true);
echop($adminDaPageHomeResult);

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
