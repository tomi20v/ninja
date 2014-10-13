<?php
/**
 * classmap file - extend \maui\* classes into root namespace so editors won't have problem with it
 * NEVER include this file, at realtime Maui aliases its classes into root namespace if they are not defined
 */
die();

class Finder extends \ninja\Finder {}
abstract class ModAbstractModule extends \ninja\ModAbstractModule {}
class Ninja extends \ninja\Ninja {}
class ModPageModule extends \ninja\ModPageModule {}
class ModPageModel extends \ninja\ModPageModel {}
class ModPageController extends \ninja\ModPageController {}
class Request extends \ninja\Request {}
class Response extends \ninja\Response {}
abstract class ModAbstractController extends \ninja\ModAbstractController {}
abstract class ModAbstractModel extends \ninja\ModAbstractModel {}
abstract class ModAbstractModule extends \ninja\ModAbstractModule {}
class ModPageModelRoot extends \ninja\ModPageModelRoot {}
class ModPageModelRedirect extends \ninja\ModPageModelRedirect {}
class View extends \ninja\View {}

