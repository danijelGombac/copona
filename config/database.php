<?php
//DB
define('DB_DRIVER', 'mysql');
define('DB_HOSTNAME', 'database');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'root');
define('DB_DATABASE', 'copona');
define('DB_PORT', '3306');
define('DB_PREFIX', 'cp_');
define('DB_CHARSET', 'utf8');
define('DB_COLLATION', 'utf8_unicode_ci');
define('DB_ADAPTER', \Copona\System\Database\Adapters\Eloquent::class);