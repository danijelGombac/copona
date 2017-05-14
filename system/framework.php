<?php
// Registry
$registry = new Registry();

global $config;
$config->load($application_config);
$registry->set('config', $config);

// Event
$event = new Event($registry);
$registry->set('event', $event);

// Event Register
if ($config->has('action_event')) {
    foreach ($config->get('action_event') as $key => $value) {
        $event->register($key, new Action($value));
    }
}

// Hook
$hook = new Hook($registry);
$registry->set('hook', $hook);

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Request
$registry->set('request', new Request());

// Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$registry->set('response', $response);

// Database
if ($config->get('db_autostart')) {

    $database = new \Copona\System\Database\Database($config->get('db_adapter'), [
        'db_type'         => $config->get('db_type'),
        'db_connect_name' => $config->get('db_connect_name'),
        'db_hostname'     => $config->get('db_hostname'),
        'db_username'     => $config->get('db_username'),
        'db_password'     => $config->get('db_password'),
        'db_database'     => $config->get('db_database'),
        'db_prefix'       => $config->get('db_prefix'),
        'db_port'         => $config->get('db_port'),
        'db_charset'      => $config->get('db_charset'),
        'db_collation'    => $config->get('db_collation'),
    ]);

    $registry->set('db', $database->getAdapter());

    if (!$registry->get('db')->query('SHOW TABLES LIKE \'' . DB_PREFIX . 'setting\'')->rows) {
        //no table setting.
        throw new Exception('Check Config file for correct Database connection!');
    }
}

// Aliases
class_alias(\Copona\System\engine\Model::class, \Model::class);

// Session
$session = new Session();

if ($config->get('session_autostart')) {
    $session->start();
}

$registry->set('session', $session);

// Cache
$registry->set('cache', new Cache($config->get('cache_type'), $config->get('cache_expire')));

// Url
if ($config->get('url_autostart')) {
    $url = new Url($config->get('site_base'), $config->get('site_ssl'), $registry);
    $registry->set('url', $url);
}

// Copona seo urls
if ($config->get('url_autostart')) {
    $registry->set('seourl', new seoUrl($registry));
}

// Language
$language = new Language($config->get('language_default'), $registry);
$language->load($config->get('language_default'));
$registry->set('language', $language);

if ($config->get('url_autostart')) {
// Breadcrumbs
    $breadcrumbs = new Breadcrumbs($registry);
    $registry->set('breadcrumbs', $breadcrumbs);
}
// Document
$registry->set('document', new Document());

// Config Autoload
if ($config->has('config_autoload')) {
    foreach ($config->get('config_autoload') as $value) {
        $loader->config($value);
    }
}

// Language Autoload
if ($config->has('language_autoload')) {
    foreach ($config->get('language_autoload') as $value) {
        $loader->language($value);
    }
}

// Library Autoload
if ($config->has('library_autoload')) {
    foreach ($config->get('library_autoload') as $value) {
        $loader->library($value);
    }
}

// Model Autoload
if ($config->has('model_autoload')) {
    foreach ($config->get('model_autoload') as $value) {
        $loader->model($value);
    }
}

// Front Controller
$controller = new Front($registry);

// Pre Actions
if ($config->has('action_pre_action')) {
    foreach ($config->get('action_pre_action') as $value) {
        $controller->addPreAction(new Action($value));
    }
}

// Dispatch
$controller->dispatch(new Action($config->get('action_router')), new Action($config->get('action_error')));

// Output
$response->setCompression($config->get('config_compression'));
$response->output();
