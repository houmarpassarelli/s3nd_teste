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
        return json_encode($_POST);
    }

    public function put()
    {
        return json_encode($_POST);
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

    private function convertTime($time)
    {
        if(is_null($time)){
            return '00:00';
        }
    }
}