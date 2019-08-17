<?php
/**
 * @author Mixail Sayapin
 * https://ms-web.ru
 */

namespace CALC;
if (!defined('ABCPATH'))
	die;
if (!Users::isUserLogged()) {
	if (!empty($_COOKIE['login']) && !empty($_COOKIE['remember'])) {
		$login = Main::sanitizeEmail($_COOKIE['login']);
		$hash = Main::sanitizeText($_COOKIE['remember']);
		$uinfo = Users::getUser($login);
		if ($uinfo && md5(strrev($uinfo->id . $login . $uinfo->id . $login)) == $hash) {
			Users::login($login, '', true, true);
			header("Refresh:0");
		}
	} else
		header('Location: /admin/');
}
global $config;
?>
<!doctype html>
<html lang="ru">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Администрирование</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">	<link rel="stylesheet" href="<?= ABCURL ?>/admin/css/style.css?v=<?= $config['compile'] ?>">

	<script src="<?= ABCURL ?>/admin/js/jquery-3.4.1.js"></script>
	<script src="<?= ABCURL ?>/admin/js/sweetalert.min.js"></script>
	<script src="<?= ABCURL ?>/admin/js/msweb-um.js?v=<?= $config['compile'] ?>"></script>
	<script src="<?= ABCURL ?>/admin/js/moment-with-locales.js?v=<?= $config['compile'] ?>"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<?
	if (Users::isUserLogged()) {
		echo Main::initScripts();
	} ?>

	<script src="<?= ABCURL ?>/admin/js/main.js?v=<?= $config['compile'] ?>"></script>
	<script src="<?= ABCURL ?>/admin/js/login.js?v=<?= $config['compile'] ?>"></script>

	<?
	if (!empty($GLOBALS['inline_scripts']['header'])) { ?>
			<?
			foreach ($GLOBALS['inline_scripts']['header'] as $key => $inline_script) {
				echo '<script id="msweb-inline-script-in-header-'.$key.'">';
				echo $inline_script['script'];
				if (!empty($inline_script['remove_after_load'])) {
					echo 'document.getElementById(\'msweb-inline-script-in-header-'.$key.'\') && document.getElementById(\'msweb-inline-script-in-header-'.$key.'\').remove()';
				}
				echo "</script>\n";
			}
			?>
		<?
	}
	?>

	<?
	if (!empty($GLOBALS['scripts']['header'])) {
		foreach ($GLOBALS['scripts']['header'] as $script) {
			echo '<script src="' . $script . '"></script>'."\n";
		}
	}
	?>
	<?
	if (!empty($GLOBALS['styles'])) {
		foreach ($GLOBALS['styles'] as $style) {
			echo '<link rel="stylesheet" href="' . $style . '">';
		}
	}
	global $pageId;
	?>
</head>
<body>
<? if (Users::isUserLogged()) { ?>
	<style>
		@media (min-width: 992px) {
			.footer {
				width: calc(100% - 230px);
				margin-left: 230px;
			}

			.content-wrapper {
				margin-left: 230px;
			}
		}
	</style>
	<nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark">
		<a class="navbar-brand" href="#">Панель управления</a>
		<button
			class="navbar-toggler"
			type="button"
			data-toggle="collapse"
			data-target="#navbarCollapse"
			aria-controls="navbarCollapse"
			aria-expanded="false"
			aria-label="Toggle navigation"
		>
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarCollapse">
			<? echo Admin::getMenu(); ?>
			<?
			if ($pageId == 2) {
				?>
				<form class="form-inline ml-3 mt-2 mt-md-0">
					<input class="form-control mr-sm-2" type="text" placeholder="Введите id заказа" onkeyup="HMA.onSearchKeyUp(this);">
				</form>
			<? } ?>
			<button class="btn btn-primary" style="position: absolute; right: 10px;" onclick="userlogout()"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="3" stroke-linecap="square"><path d="M16 17l5-5-5-5M19.8 12H9M10 3H4v18h6"/></svg> Выход</button>
		</div>
	</nav>
<? } ?>
<main class="content-wrapper">
	<div class="container-fluid">