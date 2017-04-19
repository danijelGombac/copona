<?php
define('DIR_PUBLIC', realpath(__DIR__));

// Startup
require_once(DIR_PUBLIC . '/system/startup.php');

start('catalog');