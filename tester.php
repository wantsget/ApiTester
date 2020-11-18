#!/usr/bin/env php
<?php

declare(strict_types=1);

ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');

error_reporting(E_ALL);
date_default_timezone_set('Asia/Shanghai');

! defined('BASE_PATH') && define('BASE_PATH', __DIR__ . '/');
! defined('CONFIG_PATH') && define('CONFIG_PATH', BASE_PATH . 'config/');
! defined('API_HOST') && define('API_HOST', '');

require BASE_PATH . '/vendor/autoload.php';

require BASE_PATH . '/core/bootstrap.php';
