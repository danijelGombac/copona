<?php
// Site
$_['site_base'] = HTTP_SERVER;
$_['site_ssl'] = HTTPS_SERVER;

// Url
$_['url_autostart'] = false;

// Database
$_['db_autostart'] = true;
$_['db_type'] = DB_DRIVER; // mpdo, mssql, mysql, mysqli or postgre
$_['db_hostname'] = DB_HOSTNAME;
$_['db_username'] = DB_USERNAME;
$_['db_password'] = DB_PASSWORD;
$_['db_database'] = DB_DATABASE;
$_['db_port'] = DB_PORT;

// Session
$_['session_autostart'] = false;

// Actions
$_['action_pre_action'] = array(
    'startup/session',
    'startup/startup',
    'startup/error',
    'startup/event',
    'startup/maintenance',
    'startup/seo_url'
);

// Action Events
$_['action_event'] = array(
    'view/*/before' => 'event/theme',
    //'controller/*/before'                 => 'event/debug/before',
    //'controller/*/after'                  => 'event/debug/after'
);