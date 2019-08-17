<?php
/**
 * @author Mixail Sayapin
 * https://ms-web.ru
 */

namespace CALC;

require_once '../../init.php';
if (!Users::isUserLogged())
	header('Location: ' . ABCURL . '/admin/');
$pageId = 1;
Admin::addInlineScript('
	ipotechniycalculator.pageId = ' . $pageId . ';
', false);

Admin::includeScripts('manage-amentities');
Admin::includeScripts('editor/jquery.cleditor.min');
Admin::includeScripts('editor/jquery.cleditor.icon.min');
Admin::includeScripts('editor/jquery.cleditor.table.min');
Admin::includeScripts('selector/js/select2.full');
Admin::includeStyle('jquery.cleditor');
Admin::includeStyle('select2');

Admin::getHeader();

$actions = array(
	'manage-amentities',
	'edit-amentity',
	'add-amentity'
);

$action = isset($_GET['action']) && in_array($_GET['action'], $actions) ? $_GET['action'] : 'sample';
unset($actions);

Admin::getTemplate($action);

Admin::getFoter();
?>