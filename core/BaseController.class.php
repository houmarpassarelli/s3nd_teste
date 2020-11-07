<?php

namespace Core;

class BaseController{

    /**
     * Renderiza o conteúdo inicial view
     * 
     * @param string $arquivo
     * @return void
     */
    protected function render($arquivo):void
    {   
        $path = APPLICATION_PATH . "/views/{$arquivo}.phtml";

        if(file_exists($path)){
            require_once $path;
        }else{
            exit('View solicitado não existe!');
        }
    }

    /**
     * Inclui uma partial no código
     * 
     * @param string $arquivo
     * @return void
     */
    protected function partial($arquivo):void
    {
        $path = APPLICATION_PATH . "/views/{$arquivo}.phtml";

        if(file_exists($path)){
            include_once $path;
        }else{
            echo 'Partial não existe!';
        }
    }
}