<?php

namespace Core;

use PDO;
use PDOException;

/**
 * Class Conexao
 *
 * Classe abstrata que faz conexão com o banco de dados MYSQL
 * e transmite a conexão para as classes que fazem extensão
 *
 * As propriedades são vinculadas com a informações passadas
 * no arquivo de configuração config.inc.php
 */
class Conexao
{
    private $host = HOST;
    private $database = DB;
    private $user = USER;
    private $password = PWD;

    /**
     * Propriedade deve ser do tipo PDO
     *
     * @var PDO
     */
    private $instancia = null;

    /**
     * Método para iniciar a instancia de conexão com o banco de dados
     *
     * @return null|PDO
     */
    protected function getCon(): PDO
    {
        return $this->conecta();
    }

    /*
     * Método que cria a conexão com o banco de dados
     *
     * Responsável também por retornar os erros e encerrar conexões
     * não realizadas corretamente
     */
    private function conecta(): PDO
    {
        try {
            if ($this->instancia == null):
                $opcoes = [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'];
                $this->instancia = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->database, $this->user, $this->password, $opcoes);
            endif;
        } catch (PDOException $erro) {
            die;
        }
        $this->instancia->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $this->instancia;
    }
}