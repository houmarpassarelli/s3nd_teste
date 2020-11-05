<?php

namespace App\Controllers\Cliente;

use Core\BaseController;

class ClienteController extends BaseController
{
    /**
     * Método que renderiza a index
     * de clientes
     */
    public function index(): void
    {
        $this->render('cliente/index');
    }
}