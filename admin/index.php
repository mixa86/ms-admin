<?php
/**
 * @author Mixail Sayapin
 * https://ms-web.ru
 */
namespace CALC;
require_once '../init.php';

if (!Users::isUserLogged()) {
	Admin::getTemplate('login');
} else {
	header('Location: /trunk/admin/sample');
}

Admin::getFoter();
?>

