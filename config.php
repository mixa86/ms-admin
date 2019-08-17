<?php
/**
 * @author Mixail Sayapin
 * https://ms-web.ru
 */

// соли паролей спереди и сзади
define('PASSWORD_SALT_1', 'YTASKkdsfQWLSANLASKOPASsdkfn');
define('PASSWORD_SALT_2', 'slkjdf3298KDFSHqp32rksdflaLK');

$config = array();

// версия скриптов и стилей
$config['compile_version'] = time();

$config['db_host'] = '92.63.96.232';
$config['db_name'] = 'ipocalc';
$config['db_user'] = 'ipocalc';
$config['db_pass'] = '5G1z9S8s9H5s6C9iI6f2D0b42B9n3J3i';
$config['db_prefix'] = 'ipocalc_';


// с этой почты отправляются письма
$config['admin']['support_email'] = 'support@ms-web.ru';
$config['admin']['support_email_pass'] = 'ozqG2USRSSsgqGanqsHJb8K7b1G28N1gRib';

// это название приложения. Выводится где-то.
$config['app_name'] = 'Ипотечный калькулятор';


