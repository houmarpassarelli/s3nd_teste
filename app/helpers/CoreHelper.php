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

function distinctdateweek(array $data)
{
        $pos = null;
        $dia_semana = [];
        $result = [];

        foreach($data as $key => $value){

            if(in_array($value['dia_semana'], $dia_semana)){
                
                $pos = array_search($value['dia_semana'] , array_column($result, 'dia_semana'));

                if(strtotime($value['hora_inicial']) < strtotime($result[$pos]['hora_inicial'])){                    
                    $result[$pos]['hora_inicial'] = $value['hora_inicial'];
                }else{                    
                    $value['hora_inicial'] = $result[$pos]['hora_inicial'];
                }

                if(strtotime($value['hora_final']) > strtotime($result[$pos]['hora_final'])){
                    $result[$pos]['hora_final'] = $value['hora_final'];
                }else{
                    $value['hora_final'] = $result[$pos]['hora_final'];
                }

            }

            $dia_semana[] = $value['dia_semana'];
            $result[$key] =  $value;
        }

        $result = array_map("unserialize", array_unique(array_map("serialize", $result)));
        $result = array_values($result);

        return $result;
}

function transmuteweekday($day)
{
    $valid = [
        'domingo' => 'Domingo',
        'segunda' => 'Segunda-feira',
        'terca' => 'Terça-feira',
        'quarta' => 'Quarta-feira',
        'quinta' => 'Quinta-feira',
        'sexta' => 'Sexta-feira',
        'sabado' => 'Sábado'
    ];

    return $valid[$day];
}
