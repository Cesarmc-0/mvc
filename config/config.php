<?php

$_env = parse_ini_file(__DIR__ . '/../.env');

define('SITE_NAME', 'Hotel Resort');
define('SITE_URL',  'http://localhost/mvc/');

define('DB_HOST',    $_env['DB_HOST']    ?? 'localhost');
define('DB_NAME',    $_env['DB_NAME']    ?? '');
define('DB_USER',    $_env['DB_USER']    ?? 'root');
define('DB_PASS',    $_env['DB_PASS']    ?? '');
define('DB_CHARSET',    $_env['DB_CHARSET'] ?? 'utf8mb4');
define('RESEND_API_KEY', $_env['API_KEY']   ?? '');

unset($_env);
