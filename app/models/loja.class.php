<?php

namespace App\Models;

use Core\Exibir;
use Core\Inserir;
use Core\Alterar;

class Loja
{
    /**
     * Pega informação do usuário no banco
     * pelo código
     * 
     * @param string $codigo
     * @return array
     */
    public static function getUserStatus($codigo)
    {
        return (new Exibir(NULL, "usuario", "WHERE codigo = :codigo", "codigo={$codigo}"))->resultado();
    }

    /**
     * Inseri as primeiras informações de acesso
     * 
     * @param array $dados
     * @return void
     */
    public static function setUserStatus(array $dados)
    {
        (new Inserir("usuario", $dados));
    }

    /**
     * Atualiza a informção de acesso do usuário
     * 
     * @param array $dados
     */
    public static function updateUserStatus(array $dados)
    {
        (new Alterar(NULL, "usuario", $dados, "WHERE codigo = :codigo", "codigo={$dados['codigo']}"));
    }
}