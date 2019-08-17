<?php
/**
 * @author Mixail Sayapin
 * https://ms-web.ru
 */
namespace CALC;

if (!defined('ABCPATH'))
	die();
if (!empty($_COOKIE['login']) && !empty($_COOKIE['remember'])) {
	$login = Main::sanitizeEmail($_COOKIE['login']);
	$hash = Main::sanitizeText($_COOKIE['remember']);
	$uinfo = Users::getUser($login);
	if ($uinfo && md5(strrev($uinfo->id . $login . $uinfo->id . $login)) == $hash) {
		Users::login($login, '', true, true);
		header("Refresh:0");
	}
} else {
	global $config;
	?>
	<!DOCTYPE html>
	<!--
		~ @author Mixail Sayapin
		~ https://ms-web.ru
		-->

	<html lang="ru">
	<head>
		<meta charset="UTF-8">
		<title>MS-Dialog - вход/регистрация</title>
		<link rel="stylesheet" href="<?=ABCURL?>/admin/css/style.css?v=<?= $config['compile_version'] ?>">
		<link rel="stylesheet" href="<?=ABCURL?>/admin/css/login.css?v=<?= $config['compile_version'] ?>">
		<link rel="stylesheet" href="<?=ABCURL?>/admin/css/animate.css">
		<link rel="stylesheet" href="<?=ABCURL?>/admin/css/bootstrap/css/bootstrap.min.css">
		<script src="<?=ABCURL?>/admin/js/jquery-3.4.1.js"></script>
		<script src="<?=ABCURL?>/admin/css/bootstrap/js/bootstrap.min.js"></script>
		<script src="<?=ABCURL?>/admin//js/sweetalert.min.js"></script>
		<? echo Main::initScripts();?>
	</head>
	<body>

	<div class="container">
		<div class="row">
			<div class="col-md-offset-3 col-md-6">

				<div class="tab" role="tabpanel">
					<!-- Nav tabs -->
					<a href="<?= ABCURL ?>">
						<div class="logo">
							<?= $config['app_name'] ?>
						</div>
					</a>
					<!-- Tab panes -->
					<div class="tab-content tabs">
						<div role="tabpanel" class="tab-pane fade in active" id="Section1">
							<form class="form-horizontal">
								<div class="form-group">
									<label for="exampleInputEmail1">username</label>
									<input type="email" class="form-control" name="login">
								</div>
								<div class="form-group">
									<label for="exampleInputPassword1">Password</label>
									<input type="password" class="form-control" name="password">
								</div>
								<div class="form-group">
									<div class="main-checkbox">
										<input id="checkbox1" name="check" type="checkbox">
										<label for="checkbox1"></label>
									</div>
									<span class="text">Keep me Signed in</span>
								</div>
								<div class="form-group">
									<button type="submit" class="btn btn-default" onclick="userlogin(this.form); return false;">Sign in
									</button>
								</div>
								<div class="form-group forgot-pass">
									<li role="presentation"><a class="btn btn-default" href="#Section3" aria-controls="profile" role="tab"
									                           data-toggle="tab" onclick="forgotPass()">forgot password</a></li>
								</div>
							</form>
						</div>
						<div role="tabpanel" class="tab-pane fade" id="Section2">
							<form class="form-horizontal">
								<div class="form-group">
									<label>Email address</label>
									<input type="email" class="form-control" name="email">
								</div>
								<div class="form-group">
									<label>Password</label>
									<input type="password" class="form-control" name="regpassword">
								</div>

							</form>
						</div>
						<div role="tabpanel" class="tab-pane fade" id="Section3">
							<form class="form-horizontal">
								<div class="form-group">
									<label>Email address</label>
									<input type="email" class="form-control" name="email">
								</div>
								<div class="form-group">
									<button type="submit" class="btn btn-default" onclick="restore(this.form); return false;">Restore
										password
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>

			</div><!-- /.col-md-offset-3 col-md-6 -->
		</div><!-- /.row -->
	</div><!-- /.container -->

	<script src="js/login.js?v=<?=$config['compile_version']?>"></script>

	</body>
	</html>

<? } ?>
