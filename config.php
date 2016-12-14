<?php

require 'environment.php';
define("BASE_URL", "http://orlnet.xyz"); //Constante
global $config;//Será utilizado em outras partes do sistema
$config = array();

if('ENVIRONMENT' == 'development')
{
    $config['dbname'] = 'blog';
    $config['host']   = '127.0.0.1';
    $config['dbuser'] = 'root';
    $config['dbpass'] = '';
}
else
{
    $config['dbname'] = 'u125275386_chat';
    $config['host']   = 'mysql.hostinger.com.br';
    $config['dbuser'] = 'u125275386_root';
    $config['dbpass'] = '123456';
}

