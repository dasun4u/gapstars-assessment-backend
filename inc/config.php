<?php
/**
 * Created by PhpStorm
 * User: Dasun Dissanayake
 * Date: 2021-11-27
 * Time: 5:13 PM
 */

use DevCoder\DotEnv;

(new DotEnv(ROOT_PATH . '/.env'))->load();

define('APP_ENV', getenv('APP_ENV'));
define('APP_PATH', getenv('APP_PATH'));
define('CSV_PATH', ROOT_PATH . getenv('CSV_PATH'));
define('ON_BOARDING_STEPS', explode(',', getenv('STEPS')));