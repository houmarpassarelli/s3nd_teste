<?php

namespace Core;

class Router{

    private $routes;
    

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function hasRoute():string
    {
        return array_key_exists($this->paginaURI()[0], $this->routes);
    }

    public function run($pagina):void
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
                http_response_code(404);
                exit('Roteamento inválido!');
            }
        }else{
            http_response_code(404);
            exit('Roteamento inválido!');
        }
    }

    public function runAPI($pagina)
    {        
        $method = strtolower($_SERVER['REQUEST_METHOD']);

        $class = "App\\Controllers\\" . $this->routes[$pagina];

        if(class_exists($class)){
            
            $controller = new $class;

            if(method_exists($controller, $method)){
                header('Content-Type: application/json');
                print $controller->$method();
            }else{
                http_response_code(404);
                print json_encode(['error' => 'Roteamento inválido!']);
                exit;
            }
        }else{
            http_response_code(404);
            print json_encode(['error' => 'Roteamento inválido!']);
            exit;
        }
    }

    public function paginaURI()
    {
        $pagina = $_SERVER['REQUEST_URI'];

        $pagina = str_replace("/", "&", $pagina);
        $pagina = str_replace("?", "&", $pagina);

        $pagina = explode("&", $pagina);
        $pagina = array_values(array_filter($pagina));

        if(empty($pagina[0])){
            $pagina[0] = DEFAULT_BASE_PATH;
        }

        return $pagina;
    }
}