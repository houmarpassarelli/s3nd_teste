<?php

/**
 * Configurações do banco de dados
 */

define('USER','houmar');
define('PWD','houmar');
define('HOST','localhost');
define('DB','s3nd');

/**
 * Constantes de PATH
 */
define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../app'));
define('BASE_PATH', realpath(dirname('./')) . '/');
define('DEFAULT_BASE_PATH', 'cliente');
define('DEFAULT_API_PATH', 'api');

/**
 * Constantes de HOST
 */
define('BASE_HOST', 'http://' . $_SERVER['HTTP_HOST'] . '/');

/**
 * Helpers
 */

 foreach(glob(APPLICATION_PATH . '/helpers/*.php') as $helper){
    require_once $helper;
 }


/**
 * Função para carregar as classes
 *
 * _autoload() foi depreciado, então é registrado a função com autoload no spl_autoload_register
 *functio
 * Na função não foi utilizado o PSR de chars per line porque seria muito confuso de entender
 *
 * @param string $class Parametro de definição das classes
 * @return void
 */

spl_autoload_register(function ($class){

    $class = BASE_PATH . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . ".class.php";

    if(!file_exists($class)):
        throw new Exception("Arquivo '{$class}' não encontrado!");
    endif;

    include_once ($class);
});