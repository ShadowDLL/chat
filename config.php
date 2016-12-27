<?php
require 'environment.php';
define("BASE_URL", "twitter:81");
global $config;
$config = array();
if(ENVIRONMENT == 'development') {
	$config['dbname'] = 'chat';
	$config['host'] = 'localhost';
	$config['dbuser'] = 'root';
	$config['dbpass'] = '';
} else {
	$config['dbname'] = 'chat';
	$config['host'] = 'localhost';
	$config['dbuser'] = 'root';
	$config['dbpass'] = '';
}
