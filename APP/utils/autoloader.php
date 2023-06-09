<?php

function my_autoloader($class)
{


    $directories = ['utils', 'model', 'controller'];
    foreach ($directories as $directory) {
        $filePath = DIRECTORY . DIRECTORY_SEPARATOR . $directory . DIRECTORY_SEPARATOR . $class . '.php';
     #   echo "\n".$filePath;
        if (file_exists($filePath)) {
            require_once $filePath;
            return;
        }
    }
}
spl_autoload_register('my_autoloader');
