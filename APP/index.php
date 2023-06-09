<?php


declare(strict_types=1);
define('DIRECTORY', dirname(__FILE__));
require_once "./utils/ErrorHandler.php";
require_once "./utils/autoloader.php";

set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");
header("Content-type: application/json; charset=UTF-8");
$urlParts = explode("/", $_SERVER['REQUEST_URI']);
if (!empty($urlParts)) {
    $controller = ucfirst($urlParts[1]) . "Controller";
    $actions = $urlParts[2] ?? null;
    $controllerUnit = new $controller();
    //the action is whatever is after problems/ for example
    $controllerUnit->processRequest($_SERVER["REQUEST_METHOD"], $actions);

}
