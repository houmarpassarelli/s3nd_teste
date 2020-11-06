<?php
header("Content-type: text/html; charset=utf-8");

require_once('./config/config.php');
$routes = require_once('./config/routes.php');

$route = new \Core\Router($routes);

$pagina = $route->paginaURI();

if($pagina[0] == DEFAULT_API_PATH){
    $route->runAPI($pagina[1]);
}else{
    if($route->hasRoute()){
        include APPLICATION_PATH . "/views/layouts/header.phtml";
        $route->run($pagina[0]);
        include APPLICATION_PATH . "/views/layouts/footer.phtml";
    }else{
        exit('Página solicitada não existe!');
    }
}

