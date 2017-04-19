<?php
// Version
define('VERSION', '2.3.0.3_rc');
define('COPONA_VERSION', '');

define('DIR_PUBLIC', realpath(__DIR__));

// Startup
require_once(DIR_PUBLIC . '/system/startup.php');

start('catalog');