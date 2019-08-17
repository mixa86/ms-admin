<?php
/**
 * @author Mixail Sayapin
 * https://ms-web.ru
 */

namespace CALC;
if (!session_status())
	session_start();

class Users
{
	/**
	 * @param $login
	 * @param $pass
	 * @param bool $remember
	 * @param bool $onlyLoginRequired только логин. существующий.
	 * @return bool
	 */
	static public function login($login, $pass, $remember = false, $onlyLoginRequired = false)
	{
		if ($onlyLoginRequired || self::checkPassword_($login, $pass)) {
			$user = self::getUser($login);
			$_SESSION['USER']['id'] = $user->id;
			$_SESSION['USER']['login'] = $login;
			$_SESSION['USER']['name'] = $user->user_name ?: '';
			$_SESSION['USER']['surname'] = $user->surname ?: '';
			$_SESSION['USER']['nic'] = $user->nic ?: '';
			$_SESSION['USER']['role'] = $user->permissions;
			$_SESSION['USER']['hash'] = self::createUserSession();
			if ($remember) {
				$cook = md5(strrev($user->id . $login . $user->id . $login));
				setcookie('remember', $cook, time() + 3600 * 24 * 365);
				setcookie('login', $login, time() + 3600 * 24 * 365);
			} else {
				setcookie("remember", "", time() - 3600);
				setcookie("login", "", time() - 3600);
				unset($_COOKIE['remember']);
				unset($_COOKIE['login']);
			}
			return true;
		}
	}

	private static function checkPassword_($login, $password)
	{
		$db = DB::getDB();
		$md5Ppassword = PASSWORD_SALT_1 . $password . PASSWORD_SALT_2; // солим
		$md5Ppassword = md5($md5Ppassword);
		$md5Ppassword = strrev($md5Ppassword);
		$dbPass = $db->get_var("SELECT password FROM " . $db->prefix . "users WHERE login = {?}", array($login));
		return $dbPass == $md5Ppassword;
	}

	/**
	 * @param $login
	 * @return obj | bool
	 */
	static public function getUser($login)
	{
		$db = DB::getDB();
		$res = $db->get_results("SELECT * FROM " . $db->prefix . "users WHERE login = {?}", array($login));
		if (!empty($res))
			foreach ($res as $r)
				return $r;
		return false;
	}

	static function createUserSession()
	{
		$db = DB::getDB();
		$key = md5($_SESSION['USER']['login'] . time() . $_SESSION['USER']['id']);
		$time = time();
		$uid = $_SESSION['USER']['id'];
		$db->query("INSERT INTO " . $db->prefix . "users_sessions (`user_id`, `hash`, `session_time_start`) VALUES ({?}, {?}, {?})", array($uid, $key, $time));
		return $key;
	}

	static public function logout()
	{
		setcookie("remember", "", time() - 3600);
		setcookie("login", "", time() - 3600);
		unset($_COOKIE['remember']);
		unset($_COOKIE['login']);
		unset($_SESSION['USER']);
		session_write_close();
	}

	static public function isUserLogged()
	{
		return !empty($_SESSION['USER']);
	}

	static public function getCurrentUserInfo()
	{
		return !empty($_SESSION['USER']) ? $_SESSION['USER'] : false;
	}

	static public function register($login, $pass)
	{
		$db = DB::getDB();
		$exist = $db->get_var("SELECT id FROM " . $db->prefix . "users WHERE login={?}", array($login));
		if ($exist)
			throw new \Exception('User exist');
		if (strlen($pass) < 6)
			throw new \Exception('Password required at list more than 6 characters');
		$pass = self::generatePassword(null, $pass);
		$db->query("INSERT INTO " . $db->prefix . "users (`login`, `password`, `activated`) VALUES ({?}, {?}, {?} )", array($login, $pass, 0));
	}

	/**
	 * готовит пароль для базы данных
	 * @param $length
	 */
	public static function generatePassword($length = 20, $password = false)
	{
		$password = $password ?: self::pass_gen($length);
		$md5Ppassword = PASSWORD_SALT_1 . $password . PASSWORD_SALT_2; // солим
		$md5Ppassword = md5($md5Ppassword);        //шифруем пароль
		$md5Ppassword = strrev($md5Ppassword);    // для надежности добавим реверс
		return array(
			'password' => $password,
			'md5' => $md5Ppassword
		);
	}

	private static function pass_gen($max)
	{
		$chars = "qazxswedcvfrtgbnhyujmkiop123456789QAZXSWEDCVFRTGBNHYUJMKLP";
		$size = StrLen($chars) - 1;
		$pass_gen = null;
		while ($max--) {
			$pass_gen .= $chars[rand(0, $size)];
		}
		return $pass_gen;
	}

	static public function prepareRequestToChangePassword($email)
	{
		$db = DB::getDB();
		$uid = $db->get_var("SELECT id FROM " . $db->prefix . "users WHERE login={?}", array($email));
		if (!$uid)
			throw new \Exception('Email is incorrect');

		$hash = md5(microtime() . $email . time());
		$time = time();

		$db->query("DELETE FROM " . $db->prefix . "recovery_hashes WHERE user_id = {?}", array($uid));
		$db->query("INSERT INTO " . $db->prefix . "recovery_hashes (`user_id`, `hash`, `time`) VALUES ({?},{?},{?})", array($uid, $hash, $time));
		$url = ABCURL . "/admin/recovery/?email=" . $email . "&key=" . $hash;

		Main::primaryMail($email, 'Восстановление пароля', "
		Кто-то начал процедуру восстановления пароля на сайте " . $_SERVER['HTTP_HOST'] . "<br>
		Для смены пароля пройдите по ссылке:
		<a href=\"$url\">$url</a><br><br><br>
		Если это были не Вы, то проигнорируйте это сообщение.
		");
	}

	static function checkEmailAndKeyToChangePassword($email, $key)
	{
		$email = Main::sanitizeEmail($email);
		$key = Main::sanitizeText($key);
		$db = DB::getDB();
		$uid = $db->get_var("SELECT `user_id` FROM " . $db->prefix . "recovery_hashes WHERE `hash`={?}", $key);
		if (!$uid)
			return;
		$userInfo = self::getUserInfo($uid);
		return $userInfo && isset($userInfo['login']) && $userInfo['login'] == $email;
	}

	static public function getUserInfo($uid)
	{
		$db = DB::getDB();
		return $db->get_row("SELECT * FROM " . $db->prefix . "users WHERE id={?}", (int)$uid);
	}

	static public function changePassword($email, $password)
	{
		$email = Main::sanitizeEmail($email);
		$password = self::generatePassword(null, $password);
		$db = DB::getDB();
		if (!$db->query("UPDATE " . $db->prefix . "users SET `password`={?} WHERE `login` = {?}", array($password['md5'], $email)))
			throw new \Exception('Password not changed');
		$uid = $db->get_var("SELECT id FROM " . $db->prefix . "users WHERE login={?}", array($email));
		$db->query("DELETE FROM " . $db->prefix . "recovery_hashes WHERE user_id = {?}", array($uid));

	}

}