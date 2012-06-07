<?php

// Prime the include path with the src directory.
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(__DIR__ . "/../src"),
    get_include_path(),
)));

// Quickfire autoloader.
spl_autoload_register(function($className){
    $filename = str_replace("\\","/", $className) . ".php";
    require_once $filename;
});

