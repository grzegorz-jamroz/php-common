<?php

use Ifrost\Common\DotEnv;

require dirname(__DIR__) . '/vendor/autoload.php';

(new DotEnv(dirname(__DIR__) . '/.env'))->load();

define('ABSPATH', dirname(__DIR__));
define('DATA_DIRECTORY', ABSPATH . '/data');
define('TESTS_DATA_DIRECTORY', ABSPATH . '/tests/data');
