<?php

namespace Core;

use PDO;
use PDOStatement;
use PDOException;

/**
 * Class Exibir
 *
 * Classe genérica para procedimento de SELECT no banco de dados
 */
class Exibir extends Conexao
{

    private $tabela;
    private $termos;
    private $condicoes;
    private $coluna;
    private $resultado;
    private $fetchObj = false;
    private $manual;

    /**
     * Propriedade deve ser do tipo PDOStatement
     *
     * @var PDOStatement
     */
    private $exibir;

    /**
     * Propriedade deve ser do tipo PDO
     *
     * @var PDO
     */
    private $conexao;

    /**
     * exeExibir.
     *
     * Direciona os dados para montar a query e chama
     * o método que irá montar e iniciar a query
     *
     * @param null|string $manual Parametro onde pode ser passada a query em forma de string manualmente
     * @param null|string $tabela Em caso de não manual, esse parâmetro entra o nome da tabela
     * @param null|string $condicoes Em caso de não manual, esse parâmetro recebe as condições da query
     * @param null|string $parseString Dados em BIND vem em formato string por esse parâmetro
     * depois são transformadas com um parse_str em um array para serem manipuladas depois
     * @param null|bool $fetchOBJ Decide se quer o retorno em ASSOC_OBJ ou ASSOC_ARRAY,
     * se true OBJ se false array. Padrão(false)
     * @param null|string $coluna Em casi de não manual, pode ser usado para passar uma coluna específica
     * que queira retornar
     */
    public function exeExibir(string $manual = null, string $tabela = null, string $condicoes = null, string $parseString = null, bool $fetchOBJ = null, string $coluna = null)
    {

        $this->manual = $manual ?? null;
        $this->tabela = $tabela ?? null;
        $this->condicoes = $condicoes ?? null;
        $this->coluna = $coluna ?? null;

        if (!empty($parseString)):
            parse_str($parseString, $this->termos);
        endif;

        if ($fetchOBJ):
            $this->fetchObj = false;
        endif;

        $this->getSyntax();
    }

    /**
     * Método usado para retornar resultados providos da execução da query
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
     * Método que monta os termos para query providos pelo $parseString
     */
    private function termo(): void
    {
        if ($this->termos):
            foreach ($this->termos as $key => $value):
                if ($key == 'limit' || $key == 'offset'):
                    $value = (int)$value;
                endif;
                $this->exibir->bindValue(":{$key}", $value, (is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR));
            endforeach;
        endif;
    }

    /*
     * Metódo que monta e inicia a query
     *
     * Primeiramente é chamada o método que inicia a conexão
     *
     * Em seguida é analisado o tipo query e as condições que serão processadas
     *
     * Em seguida é definido o tipo de retorno dos dados
     *
     * Em seguida é passado os termos para pelo procedimento do BIND e os
     * parâmetros de SQL, exemplo: limit e offset
     *
     * A query então é passada para declaração do PDO e executada
     *
     * Se sucesso, é retornado ou não dados existentes de acordo com
     * a query montada
     *
     */
    private function getSyntax(): void
    {

        $this->connection();

        try {
            if ($this->condicoes && $this->tabela):
                $this->exibir = $this->conexao->prepare("SELECT * FROM {$this->tabela} {$this->condicoes}");
            elseif ($this->coluna && $this->tabela):
                $this->exibir = $this->conexao->prepare("SELECT {$this->coluna} FROM {$this->tabela} {$this->condicoes}");
            elseif ((!empty($this->manual) && (empty($this->tabela) || is_null($this->tabela) || !$this->tabela == false))):
                $this->exibir = $this->conexao->prepare($this->manual);
            else:
                $this->exibir = $this->conexao->prepare("SELECT * FROM {$this->tabela}");
            endif;

            if ($this->fetchObj):
                $this->exibir->setFetchMode(PDO::FETCH_OBJ);
            else:
                $this->exibir->setFetchMode(PDO::FETCH_ASSOC);
            endif;

            $this->exibir->execute($this->termo());
            $this->resultado = $this->exibir->fetchAll();
        } catch (PDOException $erro) {
            $this->resultado = null;
        }
    }
}