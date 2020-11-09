<?php

namespace App\Models;

use Core\Exibir;

class Cliente
{
    public static function getall()
    {
        return (new Exibir(NULL, "usuario"))->resultado();
    }
}