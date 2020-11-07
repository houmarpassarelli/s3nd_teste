<?php

namespace App\Controllers\Empresa;

use Core\BaseController;
use App\Models\Empresa;

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
        $employees = Empresa::getEmployees();
        
        $response = [];

        foreach($employees as $item){

            $time = [];

            foreach(Empresa::getEmployeesTime($item['id']) as $day){
                
                $day['dia_semana'] = $this->convertWeekDay($day['dia_semana']);
                $day['hora_inicial'] = $this->convertTime($day['hora_inicial']);
                $day['intervalo_inicial'] = $this->convertTime($day['intervalo_inicial']);
                $day['intervalo_final'] = $this->convertTime($day['intervalo_final']);
                $day['hora_final'] = $this->convertTime($day['hora_final']);

                $time[] = $day;
            }

            $item['time'] = $time;
            
            $response[] = $item;
        }

        return json_encode($response);
    }

    public function post()
    {
        $referencia = explode('/', $_SERVER['REQUEST_URI']);
        $post = $_POST;
        
        $colaborador = [];
        $horarios = [];

        foreach($post as $key => $value){
            
            if($key == 'nome'){
                $colaborador['nome'] = $value;
            }

            if(is_array($value)){
                $horarios[$this->translateArrayKey($key)] = $value;
            }
        }

        if($post['request'] == "update"){

            if(Empresa::deleteEmployeeTime($referencia[3], "colaborador")){
                Empresa::updateEmployee(['id' => $referencia[3], 'data' => $colaborador]);
                Empresa::createEmployeeTime(['id' => $referencia[3], 'data' => $horarios]);
            }else{
                http_response_code(501);
            }
        }

        if($post['request'] == "insert"){

        }
    }

    public function delete()
    {
        $referencia = explode('/', $_SERVER['REQUEST_URI']);
        
        if(Empresa::deleteEmployee($referencia[3])){

            Empresa::deleteEmployeeTime($referencia[3], "colaborador");

            http_response_code(204);
        }else{
            http_response_code(501);
        }
    }

    private function convertWeekDay($day)
    {
        $valid = [
            'domingo' => 'Domingo',
            'segunda' => 'Segunda-feira',
            'terca' => 'Terça-feira',
            'quarta' => 'Quarta-feira',
            'quinta' => 'Quinta-feira',
            'sexta' => 'Sexta-feira',
            'sabado' => 'Sábado'
        ];

        return $valid[$day];
    }

    private function translateArrayKey($key)
    {
        $key = str_replace('á', 'a', $key);
        $key = str_replace('ç', 'c', $key);

        $key = explode('-', $key);
        $key = $key[0];
        
        return $key;        
    }

    private function convertTime($time)
    {
        if(is_null($time)){
            return '00:00';
        }else{
            return date('H:i', strtotime($time));
        }
    }
}