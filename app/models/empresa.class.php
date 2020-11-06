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

    public static function deleteEmployee($id)
    {
        return (new Deletar("colaborador", "WHERE id = :id", "id={$id}"))->resultado();
    }

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
}