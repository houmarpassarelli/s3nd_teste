<?php

namespace App\Models;

use Core\Exibir;
use Core\Inserir;
use Core\Alterar;
use Core\Deletar;

class Empresa
{
    /**
     * Faz retorno da lista de colaborados
     * 
     * @return array
     */
    public static function getEmployees()
    {
        return (new Exibir(NULL, "colaborador"))->resultado();
    }

    /**
     * Faz retorno da lista de expediente dos
     * colaboradores conforme id
     * 
     * @param int $id
     * @return array
     */
    public static function getEmployeesTime($id)
    {
        $id = intval($id);

        return (new Exibir("SELECT * FROM horarios WHERE id_colaborador = '{$id}'"))->resultado();
    }

    /**
     * Cria informação na tabela colaborador
     * 
     * @param array $dados
     * @return int
     */
    public static function createEmployee(array $dados)
    {
        return (new Inserir("colaborador", $dados))->resultado();
    }

    /**
     * Cria horários para um colaborador
     * específico
     * 
     * @param array $data
     * @return void
     */
    public static function createEmployeeTime(array $data)
    {
        foreach($data['data'] as $key => $value){
            (new Inserir("horarios", array_merge(['id_colaborador' => $data['id']], ['dia_semana' => $key], $value)));
        }
    }

    /**
     * Altera informações do colaborador
     * conforme id
     * 
     * @param array $data
     * @return void
     */
    public static function updateEmployee(array $data)
    {
        return (new Alterar(NULL, "colaborador", $data['data'], "WHERE id = :id","id={$data['id']}"));
    }

    /**
     * Apaga informação da tabela colaborador
     * conforme id
     * 
     * @param int $id
     * @return boolean
     */
    public static function deleteEmployee($id)
    {
        return (new Deletar("colaborador", "WHERE id = :id", "id={$id}"))->resultado();
    }

    /**
     * Apaga horário de colaborador conforme
     * id
     * 
     * @param int $id
     * @param string $where
     * @return boolean
     */
    public static function deleteEmployeeTime($id, $where = null)
    {
        $where_query = "WHERE id = :id";

        if($where){
            if($where == "colaborador"){
                $where_query = "WHERE id_colaborador = :id";
            }
        }

        return (new Deletar("horarios", "{$where_query}", "id={$id}"))->resultado();
    }

    /**
     * Traz grupo de expediente conforme
     * todos os horários dos colaboradores
     * pré estabelecidos
     * 
     * @return array
     */
    public function getOfficeExpedient()
    {
        $sql = "SELECT dia_semana, hora_inicial, hora_final FROM horarios
                GROUP BY dia_semana, hora_inicial, hora_final
                HAVING MAX(hora_inicial) AND MAX(hora_final)
                ORDER BY (CASE 
                            WHEN dia_semana = 'domingo' THEN 1 
                            WHEN dia_semana = 'segunda' THEN 2
                            WHEN dia_semana = 'terca' THEN 3
                            WHEN dia_semana = 'quarta' THEN 4
                            WHEN dia_semana = 'quinta' THEN 5
                            WHEN dia_semana = 'sexta' THEN 6
                            WHEN dia_semana = 'sabado' THEN 7
                        END) ASC";
        
        return (new Exibir($sql))->resultado();
    }
}