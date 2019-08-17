<?php
/**
 * @author Mixail Sayapin
 * https://ms-web.ru
 */

namespace CALC;


class Main
{
	const PERMISSIONS_ADMIN = 1;
	const PERMISSIONS_MANAGER = 2;


	static public function sanitizeEmail($str)
	{
		return filter_var($str, FILTER_SANITIZE_EMAIL);
	}


	static public function getPermissions($userId)
	{
		$db = DB::getDB();
		return @unserialize($db->get_var("SELECT `permissions` FROM " . $db->prefix . "users WHERE id = {?}", array($userId)));
	}

	static public function current_user_can($role, $appId)
	{
		$roles = unserialize($_SESSION['USER']['role']);
		if ($role == 'administrator') {
			return array_key_exists($appId, $roles) && $roles[$appId] == self::PERMISSIONS_ADMIN;
		}
		if ($role == 'manager') {
			return array_key_exists($appId, $roles) && $roles[$appId] == self::PERMISSIONS_MANAGER;
		}
	}


	static public function sanitizeText($str)
	{
		return filter_var($str, FILTER_SANITIZE_STRING);
	}



	static public function getUserInfo()
	{
		return array(
			'user_id' => $_SESSION['USER']['id'],
			'hash' => $_SESSION['USER']['hash']
		);
	}


	static public function primaryMail($mailto, $subject, $content, $withStyles = true)
	{
		global $config;
		if (!$mailto)
			return false;

		$mailSMTP = new \Mailer($config['admin']['support_email'], $config['admin']['support_email_pass'], 'ssl://smtp.yandex.ru', 'MS-Web.RU', 465);
		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=utf-8\r\n"; // кодировка письма
		$headers .= "From: MS-Web.RU <" . $config['admin']['support_email'] . ">\r\n"; // от кого письмо
		$headers .= "To: <" . $mailto . ">\r\n";

		if ($withStyles) {
			$res = $mailSMTP->send($mailto, $subject, "
				<table border='0' cellspacing='0' cellpadding='0' width='100%' style=\"border: 8px;border-style: double;border-color: rgb(7, 97, 132);\">
					<tbody>
						<tr bgcolor='#076184'>
							<td style='max-width: 170px;'>
								<div style='max-width:280px;max-height: 100px;float:left;width: 50%;'>
								<a href='" . HOST . "'>
									<img src='https://ms-web.ru/images/logo2.gif' border='0' style='display:block;max-height: 100px;MAX-width: 240px;width: 100%;'>
								</a>
								</div>
								<div style='min-width:130px;margin:auto;text-align:right;display:block;padding-top:10px;font-weight:bold;'>
								<div style='text-align:right;padding-right:5px;'><span style='color:rgb(255,255,255);font-size:12pt;'>Хостинг</span></div>
								<div style='text-align:right;padding-right:5px;'><span style='color:rgb(255,255,255);font-size:12pt;'>Домены</span></div>
								<div style='text-align:right;padding-right:5px;'><span style='color:rgb(255,255,255);font-size:12pt;'>1С Битрикс</span></div>
								<div style='text-align:right;padding-right:5px;'><span style='color:rgb(255,255,255);font-size:12pt;'>Конструктор сайтов</span></div>
								
								</div>
							</td>
						</tr>
						<tr>
						<td style='padding: 10px;'>
						<br>
							$content
						</td>
						</tr>
						<tr>&nbsp;</tr>
						<tr style='background: #076184;    text-align: center;    color: white;'>
							<td>
								<br>
								  <h3>С уважением, <a target='_blank' href='" . HOST . "' style='color: white;'>MS-Web.RU</a></h3>
								<br>
							</td>
						</tr>
					</tbody>
				</table>
", $headers);
		} else {
			$res = $mailSMTP->send($mailto, $subject, $content, $headers);
		}
		return $res;
	}


	static function initScripts() {
		$str = '
		<script>
			if (!window.ipotechniycalculator)
				ipotechniycalculator = {};
			ipotechniycalculator.url = \''.ABCURL.'\';
		</script>
		';
		return $str;
	}
}