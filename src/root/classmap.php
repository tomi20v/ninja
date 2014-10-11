<?php
/**
 * classmap file - extend \maui\* classes into root namespace so editors won't have problem with it
 * NEVER include this file, at realtime Maui aliases its classes into root namespace if they are not defined
 */
die();

abstract class Module extends \ninja\Module {}
class Ninja extends \ninja\Ninja {}
class PageModule extends \ninja\PageModule {}
class PageModel extends \ninja\PageModel {}
class PageController extends \ninja\PageController {}
class Request extends \ninja\Request {}
class Response extends \ninja\Response {}
class PageModelRoot extends \ninja\PageModelRoot {}
class PageModelRedirect extends \ninja\PageModelRedirect {}
class View extends \ninja\View {}

