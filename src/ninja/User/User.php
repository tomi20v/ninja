<?php

namespace ninja;

/**
 * Class User - maybe I should move this object into a Mod?
 *
 * @package ninja
 */
class User extends \Model {

	const LOGIN_TTL_DEFAULT = 1800;

	protected static $_schema = [
//		'@@extends' => '',
		'email' => [
			'toString',
			// some callback validator to validate email
//			[ 'Validator', 'isEmail' ],
		],
		'password' => [
			'toString',
			'minLength' => 6,
//			['Validator', 'validPassword', ],
		],
		'sessionId' => [
			'toString',
//			[ 'Validator', 'isSessionId' ],
		],
		'tstamp' => [
//			'tstamp',
		],
		'lastLogin' => [
			'toInt',
		],
		'lastPasswordChange' => ['toInt',],
		// after email activation
		'active' => [
			'toBool',
		],
		'isAdmin' => [
			'toBool',
		],
	];

	public static function getDbCollectionName() {
		return 'UserModelCollection';
	}

	/**
	 * @TODO TEST ME
	 * @param \Session $Session
	 */
	public static function fromSession($Session, $ttl=null) {

		if (empty($ttl)) {
			$ttl = static::LOGIN_TTL_DEFAULT;
		}

		$sessionId = $Session->getId();
		$User = \User::finder()
			->equals('sessionId', $sessionId)
			->greaterThan('tstamp', \Ninja::tstamp()-$ttl)
			->findOne();
		return $User;

	}

	/**
	 * @TODO TEST ME
	 * I create a User object for update - I get from POST or GET depending on method
	 * @param \Request $Request
	 */
//	public static function fromRequest($Request) {
//
//		$keysToSet = ['email', 'password', 'isAdmin'];
//		$data = array_intersect_key(
//			$Request->getMethod() === \Request::METHOD_POST ? $Request->request->all() : $Request->query->all(),
//			array_flip($keysToSet)
//		);
//
//		$User = new static($data, false);
//		return $User;
//
//	}

}
