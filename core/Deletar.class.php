<?php

namespace Core;

use PDO;
use PDOStatement;
use PDOException;

/**
 * Class deletar
 *
 * Classe genérica para procedimento de DELETE no banco de dados
 */
class Deletar extends Conexao
{
    private $tabela;
    private $termos;
    private $condicoes;
    private $resultado;

    /**
     * Propriedade deve ser do tipo PDOStatement
     *
     * @var PDOStatement
     */
    private $deletar;

    /**
     * Propriedade deve ser do tipo PDO
     *
     * @var PDO
     */
    private $conexao;

    /**
     * Deletar constructor.
     *
     * Direciona os dados para montar a query e chama
     * o método que irá montar e iniciar a query
     *
     * @param string $tabela Esse parâmetro entra no nome da tabela
     * @param string $condicoes Esse parâmetro recebe as condições da query
     * @param null|string $parseString Dados em BIND vem em formato string por esse parâmetro
     * depois são transformadas com um parse_str em um array para serem manipuladas depois
     */
    public function __construct(string $tabela, string $condicoes, string $parseString = null)
    {

        $this->condicoes = (string)$condicoes;
        $this->tabela = (string)$tabela;

        if (!empty($parseString)):
            parse_str($parseString, $this->termos);
        endif;

        $this->getSyntax();
    }

    /**
     * Método usado para retornar resultados providos da execução da query,
     * nesse caso a linha afetada
     */
    public function resultado()
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
     * Em seguida é feita a preparação dos dados para incluir na query
     *
     * A query então é passada para declaração do PDO e executada
     *
     * Se sucesso, é retornado a última linha deletada no banco de dados
     *
     */
    private function getSyntax(): void
    {

        $this->connection();

        try {
            $this->deletar = $this->conexao->prepare("DELETE FROM {$this->tabela} {$this->condicoes}");

            $this->deletar->execute($this->termos);
            $this->resultado = $this->deletar->rowCount();

        } catch (PDOException $erro) {
            $this->resultado = null;
        }

    }
}