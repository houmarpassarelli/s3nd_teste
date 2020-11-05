<?php

namespace Core;

class Router{

    private $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function hasRoute()
    {
        return array_key_exists($this->paginaURI(), $this->routes);
    }

    public function run($pagina)
    {
        if(isset($_GET['acao']) && !empty($_GET['acao'])){
            $acao = $_GET['acao'];
        }else{
            $acao = 'index';
        }

        $class = "App\\Controllers\\" . $this->routes[$pagina];

        if(class_exists($class)){
            
            $controller = new $class;

            if(method_exists($controller, $acao)){
                $controller->$acao();
            }else{
                exit('Roteamento inválido!');
            }
        }else{
            exit('Roteamento inválido!');
        }
    }

    private function paginaURI()
    {
        $pagina = $_SERVER['REQUEST_URI'];

        $pagina = str_replace("/", "&", $pagina);
        $pagina = str_replace("?", "&", $pagina);

        $pagina = explode("&", $pagina);
        $pagina = array_values(array_filter($pagina));

        return $pagina[0];
    }
}