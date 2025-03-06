<?php
session_start();
use Core\Router;

error_reporting(E_ALL);
ini_set("display_errors", E_ALL);



require_once 'define.php';
require_once CORE_PATH . '/Autoloader.php';


$router = new Router();
$router->dispatch();







    