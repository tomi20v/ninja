<?php

namespace maui;

/**
 * Class Request for http requests
 *
 * @package maui
 *
 * @property $serverAddr
 * @property $serverName
 * @property $protocol
 * @property $method
 * @property $time
 * @property $timeFloat
 * @property $queryString
 * @property $acceptLanguage
 * @property $host
 * @property $referer
 * @property $userAgent
 * @property $https
 * @property $remoteAddr
 * @property $remoteHost
 * @property $remotePort
 * @property $uri
 * @property $pathInfo
 * @property $origPathInfo
 */
class Request extends \Model {

	use \maui\TraitNamedInstances;

	const METHOD_GET = 'GET';
	const METHOD_POST = 'POST';
	const METHOD_HEAD = 'HEAD';
	const METHOD_PUT = 'PUT';

	protected static $_schema = array(
		'serverAddr',
		'serverName',
		'protocol',
		'method',
		'time',
		'timeFloat',
		'queryString',
		'acceptLanguage',
		'host',
		'referer',
		'userAgent',
		'https',
		'remoteAddr',
		'remoteHost',
		'remotePort',
		'uri',
		'pathInfo',
		'origPathInfo',
	);

	/**
	 * I return default instance
	 * @return Request
	 */
	protected static function _instance() {
		/**
		 * @var Request $Request
		 */
		$Request = new static();
		$keys = array(
			'serverAddr' => 'SERVER_ADDR',
			'serverName' => 'SERVER_NAME',
			'protocol' => 'SERVER_PROTOCOL',
			'method' => 'REQUEST_METHOD',
			'time' => 'REQUEST_TIME',
			'timeFloat' => 'REQUEST_TIME_FLOAT',
			'queryString' => 'QUERY_STRING',
			'acceptLanguage' => 'HTTP_ACCEPT_LANGUAGE',
			'host' => 'HTTP_HOST',
			'referer' => 'HTTP_REFERER',
			'userAgent' => 'HTTP_USER_AGENT',
			'https' => 'HTTPS',
			'remoteAddr' => 'REMOTE_ADDR',
			'remoteHost' => 'REMOTE_HOST',
			'remotePort' => 'REMOTE_PORT',
			'uri' => 'REQUEST_URI',
			'pathInfo' => 'PATH_INFO',
			'originalPathInfo' => 'ORIG_PATH_INFO',
		);
		$data = array();
		foreach ($keys as $eachKey => $eachVal) {
			$data[$eachKey] = isset($_SERVER[$eachVal]) ? $_SERVER[$eachVal] : null;
		}
		$Request->apply($data, true);
		return $Request;
	}

}
