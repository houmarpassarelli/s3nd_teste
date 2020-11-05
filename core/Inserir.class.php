<?php

namespace Core;

use PDO;
use PDOStatement;
use PDOException;

/**
 * Class Inserir
 *
 * Classe genérica para procedimento de INSERT no banco de dados
 */
class Inserir extends Conexao
{

    private $tabela;
    private $dados;
    private $resultado;

    /**
     * Propriedade deve ser do tipo PDOStatement
     *
     * @var PDOStatement
     */
    private $inserir;

    /**
     * Propriedade deve ser do tipo PDO
     *
     * @var PDO
     */
    private $conexao;

    /**
     * Método que direciona os dados para montar a query e chama
     * o método que irá montar e iniciar a query
     *
     * @param string $tabela Informar a tabela que recebera os dados
     * @param array $dados Dados a serem inseridos
     */
    public function __construct(string $tabela, array $dados)
    {
        $this->tabela = (string)$tabela;
        $this->dados = $dados;

        $this->getSyntax();
    }

    /**
     * Método usado para retornar resultados providos da execução da query
     * exemplo: quantidade de linhas afetadas
     *
     * @return int
     */
    public function resultado(): int
    {
        return $this->resultado;
    }

    /*
     * Metódo que instancia conexão com o banco de dados
     */
    private function connection(): void
    {
        $this->conexao = parent::getCon();
    }

    /*
     * Metódo que monta e inicia a query
     *
     * Primeiramente é chamada o método que inicia a conexão
     *
     * As variáveis $fields e $places resgata e manipula os dados passados como
     * parâmetros para montar a query
     *
     * A query então é passada para declaração do PDO e executada
     *
     * Se sucesso, é retornado o último ID inserido no banco como resultado
     *
     */
    private function getSyntax(): void
    {

        $this->connection();

        $fields = implode(', ', array_keys($this->dados));
        $places = ':' . implode(', :', array_keys($this->dados));

        try {
            $this->inserir = $this->conexao->prepare("INSERT INTO {$this->tabela} ({$fields}) VALUES ({$places})");
            $this->inserir->execute($this->dados);
            $this->resultado = $this->conexao->lastInsertId();
        } catch (PDOException $erro) {
            $this->resultado = null;
        }
    }

}