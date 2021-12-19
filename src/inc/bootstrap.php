<?php

// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

define('ROOT_PATH' , __DIR__ . '/../');
define('VENDOR_PATH', __DIR__ . '/../vendor/');

// Load Composer
require VENDOR_PATH.'autoload.php';

//config includes
require(ROOT_PATH . '../config/config.php');

// Load functions
// require(ROOT_PATH.'/inc/functions.php');
require(ROOT_PATH . '../www/inc/functions.php');

