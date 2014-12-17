<?php

namespace ninja;

/**
 * Class User - maybe I should move this object into a Mod?
 * @todo quite some fields need implementation
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

		if (is_null($Session)) {
			$User = new static();
		}
		else {
			$sessionId = $Session->getId();
			$User = static::finder()
				->equals('sessionId', $sessionId)
				->greaterThan('tstamp', \Ninja::tstamp()-$ttl)
				->findOne();
		}

		return $User;

	}

	public function login($Session, $email, $password) {
		//$sessionId = $Session->getId();
	}

}
