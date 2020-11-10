<?php

namespace App\Controllers\Cliente;

use Core\BaseController;
use App\Models\Cliente;
use App\Models\Empresa;

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

    /**
     * Método que pega os acessos dos
     * usuários registrados, calcula
     * tempo fora e dentro do horário 
     * de atendimento e retorna
     * 
     * @return object
     */
    public function get()
    {   
        $pos = null;
        $cliente_parsed = [];

        $usuarios = Cliente::getall();
        $expediente = distinctdateweek(Empresa::getOfficeExpedient());
        
        foreach($usuarios as $value){

            // $value['entrada'] = '2020-11-09 06:30';
            // $value['ultimo_acesso'] = '2020-11-09 20:30';

            $pos_de = array_search($this->convertweekday($value['entrada']), array_column($expediente, 'dia_semana'));
            $pos_ds = array_search($this->convertweekday($value['ultimo_registro']), array_column($expediente, 'dia_semana'));
            
            $data_entrada = date('Y-m-d', strtotime($value['entrada']));
            $data_saida = date('Y-m-d', strtotime($value['ultimo_registro']));
            $hora_entrada = date('H:i', strtotime($value['entrada']));
            $hora_saida = date('H:i', strtotime($value['ultimo_registro']));

            $hora_inicial_de = $expediente[$pos_de]['hora_inicial'];            
            $hora_final_de = $expediente[$pos_de]['hora_final'];
            $hora_inicial_ds = $expediente[$pos_ds]['hora_inicial'];            
            $hora_final_ds = $expediente[$pos_ds]['hora_final'];

            $usuario_entrada = new \DateTime($value['entrada']);
            $usuario_saida = new \DateTime($value['ultimo_registro']);

            $data_usuario_diff = $usuario_entrada->diff($usuario_saida);
            
            $data_inicial_de = new \DateTime("{$data_entrada} {$hora_inicial_de}");
            $data_final_de = new \DateTime("{$data_entrada} {$hora_final_de}");
            $data_inicial_ds = new \DateTime("{$data_saida} {$hora_inicial_ds}");
            $data_final_ds = new \DateTime("{$data_saida} {$hora_final_ds}");

            $tempo_usuario_in = 0;
            $tempo_usuario_out = 0;
            
            if($pos_de == $pos_ds){
                if(($value['entrada'] > "{$data_entrada} 00:00") &&
                    ($value['ultimo_registro'] < "{$data_entrada} {$hora_inicial_ds}")){                        
                        $usuario_pi_time_out = round((($data_usuario_diff->h * 60) + $data_usuario_diff->i));
                        $tempo_usuario_out += $usuario_pi_time_out;
                }else{

                    $tempo_atendimento_diff_ds = $data_inicial_ds->diff($data_final_ds);
                    $tempo_atendimento_ds = (($tempo_atendimento_diff_ds->h * 60) + $tempo_atendimento_diff_ds->i);

                    // $lacuna_pi_out_diff_ds = $usuario_entrada->diff($data_inicial_ds);                        
                    // $lacuna_pi_out_time_ds = round((($lacuna_pi_out_diff_ds->h * 60) + $lacuna_pi_out_diff_ds->i));                    
                    // $tempo_usuario_out += $lacuna_pi_out_time_ds;

                    if(($value['ultimo_registro'] >= "{$data_saida} {$hora_inicial_ds}") && 
                        ($value['ultimo_registro'] <= "{$data_saida} {$hora_final_ds}")){

                        if($value['ultimo_registro'] == date('Y-m-d H:i')){
                            $tempo_usuario_in = 'online';    
                        }else{
                            $usuario_in_diff_ds = $usuario_saida->diff($data_inicial_ds);                            
                            $usuario_in_time_ds = round((($usuario_in_diff_ds->h * 60) + $usuario_in_diff_ds->i));
                        
                            $tempo_usuario_in += $usuario_in_time_ds;                            
                        }
                    }

                    if(($value['ultimo_registro'] > "{$data_saida} {$hora_final_ds}") &&
                        ($value['ultimo_registro'] <= "{$data_saida} 23:59")){
                            
                            $usuario_diff_pf_out_ds = $usuario_saida->diff($data_final_ds);
                            $usuario_pf_time_ds = round((($usuario_diff_pf_out_ds->h * 60) + $usuario_diff_pf_out_ds->i));

                            $tempo_usuario_out += $usuario_pf_time_ds;
                            // $tempo_usuario_in += $tempo_atendimento_ds;
                    }
                }
                // exit(var_dump(['in' => $tempo_usuario_in, 'out' => $tempo_usuario_out]));                
            }else{
                
                $tempo_24h = (24 * 60);

                //Processo do dia/hora de entrada
                if($pos_de !== false){

                    $tempo_atendimento_diff_de = $data_inicial_de->diff($data_final_de);

                    $tempo_atendimento_de = (($tempo_atendimento_diff_de->h * 60) + $tempo_atendimento_diff_de->i);

                    if($value['entrada'] < "{$data_entrada} {$hora_inicial_de}"){

                        //Parte Inicial
                        $lacuna_pi_out_de = new \DateTime("{$data_entrada} 00:00");
                        $lacuna_pf_out_de = new \DateTime("{$data_entrada} {$hora_inicial_de}");
                        
                        $lacuna_pi_diff_de = $lacuna_pi_out_de->diff($lacuna_pf_out_de);

                        $tempo_24h_pi_diff_de = ((24 - intval(round((($lacuna_pi_diff_de->h * 60) + $lacuna_pi_diff_de->i) / 60))) / 2);                    
                        
                        $usuario_pi_diff_de = $usuario_entrada->diff($lacuna_pf_out_de);
                        
                        $usuario_pi_time_de = round((($usuario_pi_diff_de->h * 60) + $usuario_pi_diff_de->i));
                        
                        $tempo_usuario_out += $usuario_pi_time_de;

                        //Parte Final
                        $lacuna_si_out_de = new \DateTime("{$data_entrada} 23:59");
                        $lacuna_sf_out_de = new \DateTime("{$data_entrada} {$hora_final_de}");
                        
                        $lacuna_sf_diff_de = $lacuna_si_out_de->diff($lacuna_sf_out_de);
                        
                        $usuario_sf_time_de = round((($lacuna_sf_diff_de->h * 60) + $lacuna_sf_diff_de->i));
                        
                        $tempo_usuario_out += $usuario_sf_time_de;

                        $tempo_usuario_in += $tempo_atendimento_de;
                    }

                    if($value['entrada'] > "{$data_entrada} {$hora_final_de}"){
                        
                        $lacuna_si_out_de = new \DateTime("{$data_entrada} 23:59");
                        
                        $usuario_si_diff_de = $usuario_entrada->diff($lacuna_si_out_de);
                        
                        $usuario_si_time_de = round((($usuario_si_diff_de->h * 60) + $usuario_si_diff_de->i));
                        
                        $tempo_usuario_out += $usuario_si_time_de;
                    }

                }else{
                    $data_de_final = new \DateTime("{$data_entrada} 23:59");
                    $usuario_diff_sa = $usuario_entrada->diff($data_de_final);

                    if($usuario_diff_sa->d > 1){
                        $usuario_total_sa = round(((($usuario_diff_sa->d * 24) * 60) + $usuario_diff_sa->i));
                    }else{
                        $usuario_total_sa = round((($usuario_diff_sa->h * 60) + $usuario_diff_sa->i));
                    }

                    $tempo_usuario_out += $usuario_total_sa;
                }

                //Processo do dia/hora de saída
                if($pos_ds !== false){                    

                    $tempo_atendimento_diff_ds = $data_inicial_ds->diff($data_final_ds);

                    $tempo_atendimento_ds = (($tempo_atendimento_diff_ds->h * 60) + $tempo_atendimento_diff_ds->i);

                    if((("{$data_saida} 00:00" < $value['ultimo_registro']) && 
                        ($value['ultimo_registro'] < "{$data_saida} {$hora_inicial_ds}")) ||
                        (($value['ultimo_registro'] >= "{$data_saida} {$hora_inicial_ds}") &&
                        ($value['ultimo_registro'] <= "{$data_saida} 23:59"))){                  

                        if((($value['ultimo_registro'] > "{$data_saida} 00:00") && 
                            ($value['ultimo_registro'] < "{$data_saida} {$hora_inicial_ds}"))){

                                $lacuna_pi_out_ds = new \DateTime("{$data_saida} 00:00");
                                $usuario_diff_pi_out_ds = $lacuna_pi_out_ds->diff($usuario_saida);

                                $usuario_pi_time_ds = round((($usuario_diff_pi_out_ds->h * 60) + $usuario_diff_pi_out_ds->i));
                                
                                $tempo_usuario_out += $usuario_pi_time_ds;
                        }else{

                            $lacuna_pi_out_ds = new \DateTime("{$data_saida} 00:00");
                            $lacuna_pi_out_diff_ds = $lacuna_pi_out_ds->diff($data_inicial_ds);
                            
                            $lacuna_pi_out_time_ds = round((($lacuna_pi_out_diff_ds->h * 60) + $lacuna_pi_out_diff_ds->i));
                            
                            $tempo_usuario_out += $lacuna_pi_out_time_ds;


                            if(($value['ultimo_registro'] >= "{$data_saida} {$hora_inicial_ds}") && 
                                ($value['ultimo_registro'] <= "{$data_saida} {$hora_final_ds}")){

                                if($value['ultimo_registro'] == date('Y-m-d H:i')){
                                    $tempo_usuario_in = 'online';    
                                }else{
                                    $usuario_in_diff_ds = $usuario_saida->diff($data_inicial_ds);                            
                                    $usuario_in_time_ds = round((($usuario_in_diff_ds->h * 60) + $usuario_in_diff_ds->i));
                                
                                    $tempo_usuario_in += $usuario_in_time_ds;                            
                                }
                            }

                            if(($value['ultimo_registro'] > "{$data_saida} {$hora_final_ds}") &&
                                ($value['ultimo_registro'] <= "{$data_saida} 23:59")){
                                    
                                    $usuario_diff_pf_out_ds = $usuario_saida->diff($data_final_ds);
                                    $usuario_pf_time_ds = round((($usuario_diff_pf_out_ds->h * 60) + $usuario_diff_pf_out_ds->i));

                                    $tempo_usuario_out += $usuario_pf_time_ds;
                                    $tempo_usuario_in += $tempo_atendimento_ds;
                            }
                        }
                    }
                }else{
                    $usuario_diff_sa = $usuario_entrada->diff($usuario_saida);

                    if($usuario_diff_sa->d > 1){
                        $usuario_total_sa = round(((($usuario_diff_sa->d * 24) * 60) + $usuario_diff_sa->i));
                    }else{
                        $usuario_total_sa = round((($usuario_diff_sa->h * 60) + $usuario_diff_sa->i));
                    }

                    $tempo_usuario_out += $usuario_total_sa;                    
                }
            }

            $value['in'] = $tempo_usuario_in;
            $value['out'] = $tempo_usuario_out;
            $value['entrada'] = date('d/m/Y H:i', strtotime($value['entrada']));
            $value['ultimo_registro'] = date('d/m/Y H:i', strtotime($value['ultimo_registro']));

            $cliente_parsed[] = $value;
        }

        return json_encode($cliente_parsed);
    }

    /**
     * Método que converte o dia da semana
     * numeral e descrito
     */
    private function convertweekday($date)
    {
        $day = date('w', strtotime($date));

        /**
         * 0 - Domingo
         * ...
         * 6 - Sábado
         */

        $array = [
            'domingo',
            'segunda',
            'terca',
            'quarta',
            'quinta',
            'sexta',
            'sabado'
        ];

        return $array[$day];
    }
}