<?php

namespace Core;

class BaseController{

    /**
     * Renderiza o conteúdo inicial view
     * 
     * @param $arquivo
     * @return void
     */
    protected function render($arquivo):void
    {   
        $path = APPLICATION_PATH . "/views/{$arquivo}.phtml";

        if(file_exists($path)){
            
            // ob_clean();
            require_once APPLICATION_PATH . "/views/layouts/header.phtml";
            require_once $path;
            require_once APPLICATION_PATH . "/views/layouts/footer.phtml";
            // exit;
        }else{
            exit('View solicitado não existe!');
        }
    }
}