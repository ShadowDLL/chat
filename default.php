<?php
session_start();
require "config.php";
//AutoLoad para carregar as classes necessárias
spl_autoload_register(function($class){
    if (strpos($class, "Controller") > -1)
    {
        if (file_exists("controllers/".$class.".php")) {
            require "controllers/".$class.".php";
        }
    }
    else if (file_exists("models/".$class.".php"))
    {
        require_once "models/".$class.".php";
    }
    else
    {
        require "core/".$class.".php";
    }
});
$core = new Core();
$core->run();



    

