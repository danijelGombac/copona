<?php
// Error Reporting
error_reporting(E_ALL);

define('DIR_PUBLIC', realpath(__DIR__ . '/../'));

// Check if SSL
if ((isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) || $_SERVER['SERVER_PORT'] == 443) {
    $protocol = 'https://';
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
    $protocol = 'https://';
} else {
    $protocol = 'http://';
}

define('HTTP_SERVER', $protocol . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/.\\') . '/');
define('HTTP_OPENCART', $protocol . $_SERVER['HTTP_HOST'] . rtrim(rtrim(dirname($_SERVER['SCRIPT_NAME']), 'install'), '/.\\') . '/');

// DIR
define('DIR_OPENCART', str_replace('\\', '/', realpath(dirname(__FILE__) . '/../') . '/'));
define('DIR_APPLICATION', str_replace('\\', '/', realpath(dirname(__FILE__))) . '/');
define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');
define('DIR_TEMPLATE', DIR_APPLICATION . 'view/template/');

define('DIR_SYSTEM', DIR_PUBLIC . '/system/');
define('DIR_IMAGE', DIR_APPLICATION . '/image/');
define('DIR_CACHE', DIR_APPLICATION . '/storage/cache/');
define('DIR_DOWNLOAD', DIR_APPLICATION . '/storage/download/');
define('DIR_LOGS', DIR_APPLICATION . '/storage/logs/');
define('DIR_MODIFICATION', DIR_APPLICATION . '/storage/modification/');
define('DIR_UPLOAD', DIR_APPLICATION . '/storage/upload/');

define('DIR_DATABASE', DIR_SYSTEM . 'database/');

// Startup
require_once(DIR_PUBLIC . '/system/startup.php');

start('install');