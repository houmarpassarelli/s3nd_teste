<?php

/**
 * Configurações do banco de dados
 */

define('USER','');
define('PWD','');
define('HOST','');
define('DB','s3nd');

/**
 * Constantes de PATH
 */
define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../app'));
define('PUBLIC_PATH', realpath(dirname('./')) . '/');


/**
 * Função para carregar as classes
 *
 * _autoload() foi depreciado, então é registrado a função com autoload no spl_autoload_register
 *
 * Na função não foi utilizado o PSR de chars per line porque seria muito confuso de entender
 *
 * @param string $class Parametro de definição das classes
 * @return void
 */

spl_autoload_register(function ($class){

    $class = PUBLIC_PATH . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . ".class.php";

    if(!file_exists($class)):
        throw new Exception("Arquivo '{$class}' não encontrado!");
    endif;

    include_once ($class);
});