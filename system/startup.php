<?php
// Version
define('VERSION', '2.3.0.3_rc');
define('COPONA_VERSION', '');

// Error Reporting
error_reporting(E_ALL);

// Composer Autoloader
if (is_file(DIR_SYSTEM . '../vendor/autoload.php')) {
    require_once(DIR_SYSTEM . '../vendor/autoload.php');
} else {
    die('Please, execute composer install');
}

//Errors handler
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

// Config
require_once DIR_PUBLIC . '/system/library/config.php';
$config = new Config();
$config->load('database');
$config->load('default');
$config->load('general');
$config->load('cache');

//Check is admin uri
define('IS_ADMIN', basename(realpath('')) == 'admin' ? true : false);

$server_port = '';
if (isset($_SERVER['SERVER_PORT']) && ($_SERVER['SERVER_PORT'] != 80) && $_SERVER['SERVER_PORT'] != 443) {
    $server_port = ':' . $_SERVER['SERVER_PORT'];
}

//define base url constant
define('DOMAINNAME', isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] . $server_port : null);

$parse_url = parse_url($_SERVER['SCRIPT_NAME']);
define('BASEURI', str_replace(['index.php', '//'], '', $parse_url['path']));

define('BASEURL', DOMAINNAME . BASEURI);

define('BASEURL_CATALOG', (str_replace(['index.php', 'admin', 'core', '//', 'app'], '', BASEURL)));

// Install
if (is_dir(DIR_PUBLIC . '/install/') && defined('DIR_OPENCART') == false) {
    header('Location: install/index.php');
    exit;
}

if(defined('DIR_OPENCART') == false) {
    // HTTP
    define('HTTP_SERVER', 'http://' . BASEURL_CATALOG . (IS_ADMIN ? '/admin/' : ''));
    define('HTTP_CATALOG', 'http://' . BASEURL_CATALOG);

    // HTTPS
    define('HTTPS_SERVER', 'https://' . BASEURL_CATALOG . (IS_ADMIN ? '/admin/' : ''));
    define('HTTPS_CATALOG', 'https://' . BASEURL_CATALOG);

    // DIR
    define('DIR_APPLICATION', DIR_PUBLIC . (IS_ADMIN ? '/admin/' : '/catalog/'));
    define('DIR_LANGUAGE', DIR_PUBLIC . (IS_ADMIN ? '/admin/language/' : '/catalog/language/'));
    define('DIR_TEMPLATE', DIR_PUBLIC . (IS_ADMIN ? '/admin/view/template/' : '/catalog/view/theme/'));
    define('DIR_SYSTEM', DIR_PUBLIC . '/system/');
    define('DIR_IMAGE', DIR_PUBLIC . '/' . PATH_IMAGE);
    define('DIR_CACHE', DIR_PUBLIC . '/' . PATH_CACHE);
    define('DIR_DOWNLOAD', DIR_PUBLIC . '/' . PATH_DOWNLOAD);
    define('DIR_LOGS', DIR_PUBLIC . '/' . PATH_LOGS);
    define('DIR_MODIFICATION', DIR_PUBLIC . '/' . PATH_MODIFICATION);
    define('DIR_UPLOAD', DIR_PUBLIC . '/' . PATH_UPLOAD);
    define('DIR_CONFIG', DIR_PUBLIC . '/config/');
}
// Composer Autoloader
if (is_file(DIR_SYSTEM . '../vendor/autoload.php')) {
    require_once(DIR_SYSTEM . '../vendor/autoload.php');
} else {
    die('Please, execute composer install');
}

//Errors handler
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

// Check Version
if (version_compare(phpversion(), '5.6.4', '<=') == true) {
    exit('PHP5.6+ Required');
}

if (!ini_get('date.timezone')) {
    date_default_timezone_set('UTC');
}

// Windows IIS Compatibility
if (!isset($_SERVER['DOCUMENT_ROOT'])) {
    if (isset($_SERVER['SCRIPT_FILENAME'])) {
        $_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0 - strlen($_SERVER['PHP_SELF'])));
    }
}

if (!isset($_SERVER['DOCUMENT_ROOT'])) {
    if (isset($_SERVER['PATH_TRANSLATED'])) {
        $_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr(str_replace('\\\\', '\\', $_SERVER['PATH_TRANSLATED']), 0, 0 - strlen($_SERVER['PHP_SELF'])));
    }
}

if (!isset($_SERVER['REQUEST_URI'])) {
    $_SERVER['REQUEST_URI'] = substr($_SERVER['PHP_SELF'], 1);

    if (isset($_SERVER['QUERY_STRING'])) {
        $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
    }
}

if (!isset($_SERVER['HTTP_HOST'])) {
    $_SERVER['HTTP_HOST'] = getenv('HTTP_HOST');
}

// Check if SSL
if (!empty($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https') {
    $_SERVER['HTTPS'] = true;
} else {
    $_SERVER['HTTPS'] = false;
}

// Universal Host redirect to correct hostname
if (defined('HTTP_HOST') && defined('HTTPS_HOST') && $_SERVER['HTTP_HOST'] != parse_url(HTTPS_SERVER)['host'] && $_SERVER['HTTP_HOST'] != parse_url(HTTP_SERVER)['host']) {
    header("Location: " . ($_SERVER['HTTPS'] ? HTTPS_SERVER : HTTP_SERVER) . ltrim('/', $_SERVER['REQUEST_URI']));
}

// Modification Override
function modification($filename)
{
    if (defined('DIR_CATALOG')) {
        $file = DIR_MODIFICATION . 'admin/' . substr($filename, strlen(DIR_APPLICATION));
    } elseif (defined('DIR_OPENCART')) {
        $file = DIR_MODIFICATION . 'install/' . substr($filename, strlen(DIR_APPLICATION));
    } else {
        $file = DIR_MODIFICATION . 'catalog/' . substr($filename, strlen(DIR_APPLICATION));
    }

    if (substr($filename, 0, strlen(DIR_SYSTEM)) == DIR_SYSTEM) {
        $file = DIR_MODIFICATION . 'system/' . substr($filename, strlen(DIR_SYSTEM));
    }

    if (is_file($file)) {
        return $file;
    }

    return $filename;
}

function library($class)
{
    $file = DIR_SYSTEM . 'library/' . str_replace('\\', '/', strtolower($class)) . '.php';

    if (is_file($file)) {
        include_once(modification($file));

        return true;
    } else {
        return false;
    }
}

spl_autoload_register('library');
spl_autoload_extensions('.php');

// Engine
require_once(modification(DIR_SYSTEM . 'engine/action.php'));
require_once(modification(DIR_SYSTEM . 'engine/controller.php'));
require_once(modification(DIR_SYSTEM . 'engine/event.php'));
require_once(modification(DIR_SYSTEM . 'engine/hook.php'));
require_once(modification(DIR_SYSTEM . 'engine/front.php'));
require_once(modification(DIR_SYSTEM . 'engine/loader.php'));
require_once(modification(DIR_SYSTEM . 'engine/model.php'));
require_once(modification(DIR_SYSTEM . 'engine/registry.php'));
require_once(modification(DIR_SYSTEM . 'engine/proxy.php'));

// Helper
require_once(DIR_SYSTEM . 'helper/general.php');
require_once(DIR_SYSTEM . 'helper/utf8.php');
require_once(DIR_SYSTEM . 'helper/json.php');
require_once(DIR_SYSTEM . 'helper/debug.php');

function start($application_config)
{
    require_once(DIR_SYSTEM . 'framework.php');
}
