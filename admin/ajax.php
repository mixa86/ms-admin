<?php
/**
 * @author Mixail Sayapin
 * https://ms-web.ru
 */

/**
 * @author Mixail Sayapin
 * https://ms-web.ru
 */

/**
 * Created by PhpStorm.
 * User: mixail
 * Date: 27.04.19
 * Time: 17:19
 */

namespace CALC;

use Elementor\User;

require_once ('../init.php');

$answer = array(
	'status'=> 200,
	'data'=> array(),
	'error'=> '',
	'message'=> '',
);
try {
	if (!isset($_POST['action'])) {
		throw new \Exception('Bad param');
	}

	switch ($_POST['action']) {
		case 'heartbeat': {
			break;
		}
		case 'login' : {
			$login = isset($_POST['login']) ? Main::sanitizeEmail($_POST['login']) : false;
			$pass = isset($_POST['password']) ? Main::sanitizeText($_POST['password']) : false;
			$remember = isset($_POST['remember']) && $_POST['remember'] != 'false';
			if (!$login || !$pass)
				throw new \Exception(Main::locale('Login and password required'));
			if (!($res = Users:: login($login, $pass, $remember)))
				throw new \Exception('Incorrect login or password');
			break;
		}

		case 'logout' : {
			Users::logout();
			break;
		}

		case 'forgot' : {
			$login = isset($_POST['login']) ? Main::sanitizeEmail($_POST['login']) : false;
			if (!$login)
				throw new \Exception('Incorrect email');
			Users::prepareRequestToChangePassword($login);
			break;
		}

		case 'changepassword' : {
			$newpassword = isset($_POST['newpassword']) ? $_POST['newpassword'] : false;
			$email = isset($_POST['email']) ? $_POST['email'] : false;
			$key = isset($_POST['key']) ? $_POST['key'] : false;
			if (!$newpassword || !$email || !$key)
				throw new \Exception('Bad param');

			if (!Users::checkEmailAndKeyToChangePassword($email, $key))
				throw new \Exception('Bad param');

			Users::changePassword($email, $newpassword);

			break;
		}

		default:
			throw new \Exception('Bad param');
	}
} catch (\Exception $exception) {
	$answer['status'] = $exception->getCode() ? : 400;
	$answer['error'] = $exception->getMessage() ? : '';
	$answer['line'] = $exception->getLine();
}

echo json_encode($answer);

