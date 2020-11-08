<?php

namespace App\Controllers\Loja;

use Core\BaseController;
use App\Models\Loja;

class LojaController extends BaseController
{
    /**
     * Método que renderiza a tela 
     * principal da loja
     */
    public function index()
    {
        $this->render('loja/index');
    }

    /**
     * Gera e retorna o código de acesso
     * do usuário
     * 
     * @return string
     */
    public function code()
    {
        return md5(base64_encode(str_replace('.', $_GET['ip'])));
    }

    /**
     * Manipulador do status do usuário no
     * sistema
     * 
     * @return void
     */
    public function ping()
    {
        $dados = [
            'codigo' => $_GET['code']
        ];

        if(Loja::getUserStatus($_GET['code'])){

            $dados['ultimo_registro'] = date('Y-m-d H:i:s');            
            Loja::updateUserStatus($dados);
        }else{
            Loja::setUserStatus($dados);
        }
    }
}