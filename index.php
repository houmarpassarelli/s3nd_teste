<?php
header("Content-type: text/html; charset=utf-8");

require_once('./config/config.php');
$routes = require_once('./config/routes.php');

$route = new \Core\Router($routes);

if($route->hasRoute()){
    $route->run();
}else{
    exit('Página solicitada não existe!');
}



