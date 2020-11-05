<?php

namespace Core;

use PDO;
use PDOStatement;
use PDOException;

/**
 * Class alterar
 *
 * Classe genérica para procedimento de UPDATE no banco de dados
 */
class Alterar extends Conexao
{

    private $tabela;
    private $resultado;
    private $termos;
    private $condicoes;
    private $dados;
    private $manual;

    /**
     * Propriedade deve ser do tipo PDOStatement
     *
     * @var PDOStatement
     */
    private $alterar;

    /**
     * Propriedade deve ser do tipo PDO
     *
     * @var PDO
     */
    private $conexao;

    /**
     * Alterar constructor.
     *
     * Direciona os dados para montar a query e chama
     * o método que irá montar e iniciar a query
     *
     * @param null|string $manual Parametro onde pode ser passada a query em forma de string manualmente
     * @param null|string $tabela Em caso de não manual, esse parâmetro entra o nome da tabela
     * @param array $dados dados a serem alterados
     * @param null|string $condicoes Em caso de não manual, esse parâmetro recebe as condições da query
     * @param null|string $parseString dados em BIND vem em formato string por esse parâmetro
     * depois são transformadas com um parse_str em um array para serem manipuladas depois
     */
    public function __construct(string $manual = null, string $tabela = null, array $dados = null, string $condicoes = null, string $parseString = null)
    {

        $this->manual = $manual ?? null;
        $this->tabela = $tabela ?? null;
        $this->dados = $dados ?? null;
        $this->condicoes = $condicoes ?? null;

        if (!empty($parseString)):
            parse_str($parseString, $this->termos);
        endif;

        $this->getSyntax();
    }

    /**
     * Método usado para retornar resultados providos da execução da query
     * nesse caso a linha afetado
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
     * Em seguida é analisado o tipo query e as condições que serão processadas
     *
     * A query então é passada para declaração do PDO e executada
     *
     * Se sucesso, é retornado a última linha alterada no banco de dados
     *
     */
    private function getSyntax(): void
    {

        $this->connection();

        if (!$this->manual):
            foreach ($this->dados as $key => $value):
                $places[] = $key . ' =:' . $key;
            endforeach;

            $places = implode(', ', $places);
        endif;

        try {
            if ($this->manual && !empty($this->manual)):
                $this->alterar = $this->conexao->prepare($this->manual);
                $this->alterar->execute();
            else:
                $this->alterar = $this->conexao->prepare("UPDATE {$this->tabela} SET {$places} {$this->condicoes}");
                $this->alterar->execute(array_merge($this->dados, $this->termos));
            endif;

            $this->resultado = $this->alterar->rowCount();

        } catch (PDOException $erro) {
            $this->resultado = null;
        }
    }
}