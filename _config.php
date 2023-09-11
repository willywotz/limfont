<?php
define('DBHOST', 'mysql:dbname=limfont');
define('DBUSER', 'root');
define('DBPASS', '');

define('UPLOADDIR', __DIR__.'/upload');

date_default_timezone_set('Asia/Bangkok');
session_start();

require '_function.php';

// test database connection
db()->query('select 1')->execute();
