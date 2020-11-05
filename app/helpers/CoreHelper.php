<?php

/**
 * Retorna asset conforme referência
 * 
 * @param array $referencia
 * @return void
 */
function requer(array $referencia):void
{
    $js = require_once(BASE_PATH . 'config/js.php');
    $css = require_once(BASE_PATH . 'config/css.php');

    $return = '';
    
    foreach($referencia as $item){
        
        if(array_key_exists($item, $js)){

            $js_file = BASE_PATH . "assets/js/{$js[$item]}.js";        
            
            if(file_exists($js_file)){
                $return .= '<script type="text/javascript" src="/assets/js/' . $js[$item] . '.js"></script>';
            }
        }

        if(array_key_exists($item, $css)){
        
            $css_file = BASE_PATH . "assets/css/{$css[$item]}.css";
    
            if(file_exists($css_file)){
                $return .= '<link rel="stylesheet" type="text/css" href="/assets/css/'. $css[$item] .'.css" />';
            }
        }
    }

    echo $return;
}
