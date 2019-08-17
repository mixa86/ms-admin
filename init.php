<?php
session_start();
ini_set('short_open_tag', 'On');
define('ABCPATH', __DIR__);
$url = __DIR__;
$url = str_replace(DIRECTORY_SEPARATOR, '/', $url);
$serv = $_SERVER['DOCUMENT_ROOT'];
$serv = str_replace(DIRECTORY_SEPARATOR, '/', $serv);
$url = str_replace($_SERVER['DOCUMENT_ROOT'], '', $url);
if (!$url || $url[strlen($url)-1] != '/')
	$url .= '/';
if ($url[0] != '/')
	$url = '/'.$url;
$scheme = ((!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 80) || (!empty($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'http')) ? 'http://' : 'https://';
$url = $scheme . $_SERVER['SERVER_NAME'] . $url;
$url = substr($url, 0, -1);
define('ABCURL', $url);
unset($url, $scheme, $serv);
spl_autoload_register(function ($class_name) {
	if (strpos($class_name, 'CALC\\') !== false) {
		$class_name = explode('\\', $class_name);
		$class_name = $class_name[1];
	}
	include_once('classess/' . strtolower($class_name) . '.php');
});
include_once 'config.php';
?>