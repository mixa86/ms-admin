<?php
/**
 * @author Mixail Sayapin
 * https://ms-web.ru
 */
namespace CALC;
require_once '../../init.php';
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
	<link rel="stylesheet" href="/admin/css/style.css?v=<?= $config['compile_version'] ?>">
	<link rel="stylesheet" href="/admin/css/login.css?v=<?= $config['compile_version'] ?>">
	<script src="/admin/js/login.js?v=<?= $config['compile_version'] ?>"></script>
	<link rel="stylesheet" href="/admin/css/animate.css">
	<link rel="stylesheet" href="/admin/css/bootstrap/css/bootstrap.min.css">
	<script src="/admin/js/jquery-3.4.1.js"></script>
	<script src="/admin/css/bootstrap/js/bootstrap.min.js"></script>
	<script src="/admin//js/sweetalert.min.js"></script>
	<script src="/admin/js/msweb-um.js?v=<?= $config['compile_version'] ?>"></script>
	<? echo Main::initScripts(); ?>
</head>
<body>
<?
if (empty($_GET['email']) || empty($_GET['key']) || !Users::checkEmailAndKeyToChangePassword($_GET['email'], $_GET['key'])) { ?>
<script>
	swal({
		icon: 'error',
		text: 'Недопустимые параметры'
	})
</script>
<?
}
else {?>
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
								<h3 style="color: #fff; text-align: center">Смена пароля</h3>
								</div>
								<div class="form-group">
									<label for="newpassword1">New password</label>
									<input type="password" class="form-control" name="password1">
								</div>
								<div class="form-group">
									<label for="exampleInputPassword1">Confirm new password</label>
									<input type="password" class="form-control" name="password2">
								</div>
								<div class="form-group">
									<button type="submit" class="btn btn-default" onclick="changepassword(this.form); return false;">Apply password
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>

			</div><!-- /.col-md-offset-3 col-md-6 -->
		</div><!-- /.row -->
	</div><!-- /.container -->
<?}?>
</body>
</html>
