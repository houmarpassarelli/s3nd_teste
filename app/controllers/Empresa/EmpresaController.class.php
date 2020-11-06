<?php

namespace App\Controllers\Empresa;

use Core\BaseController;

class EmpresaController extends BaseController
{
    /**
     * Método que renderiza a index
     * de empresa
     */
    public function index()
    {
        $this->render('empresa/index');
    }

    public function get()
    {

    }

    public function post()
    {
        return json_encode([1 => 'teste']);
    }

    public function put()
    {

    }

    public function deletex()
    {

    }
}