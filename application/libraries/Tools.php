<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tools {

    // return array(2) { ["cap"]=> string(3) "abm" ["sed"]=> string(3) "abm" }
    public function get_array_permisos($cad_permisos) {
        $permisosxmodulo = explode('|', $cad_permisos);
        $permisos_arr = array();
        foreach ($permisosxmodulo as $permisos) {
            try {
                $prm = explode('>', $permisos);
                if (array_key_exists('1', $prm)) {
                    $permisos_arr[$prm[0]] = $prm[1];
                }
            } catch (Exception $e) {
                
            }
        }
        return $permisos_arr;
    }

    //return false si no encuentra la una clave($modulo) en el arreglo arrojado por get_array_permisos($cad_permisos) si lo encuentra devuelve true
    public function tengo_permisos_modulo($cad_permisos, $modulo = '') {
        $array_prm = false;
        $tengopermiso = false;
        try {
            $array_prm = $this->get_array_permisos($cad_permisos);
            if ($modulo != '') {
                if (array_key_exists($modulo, $array_prm)) {
                    $tengopermiso = TRUE;
                } else {
                    $tengopermiso = FALSE;
                }
            }
        } catch (Exception $e) {
            
        }
        if ($array_prm == false) {
            $tengopermiso = FALSE;
        }
        return $tengopermiso;
    }

    public function get_permisos_modulo($cad_permisos, $str_modulo, $url_redirect) {
        $permisosxmodulo = explode('|', $cad_permisos);
        $permisos_arr = array();
        $permisos_modulo = '';
        if ($cad_permisos != '') {
            foreach ($permisosxmodulo as $permisos) {
                try {
                    $prm = explode('>', $permisos);
                    if (array_key_exists(0, $prm) && array_key_exists(1, $prm)) {
                        $permisos_arr[$prm[0]] = $prm[1];
                    } 
                } catch (Exception $e) {
                    
                }
            }
            if (array_key_exists($str_modulo, $permisos_arr)) {
                $permisos_modulo = $permisos_arr[$str_modulo];
            } else {
                if ($url_redirect != false)
                    redirect($url_redirect);
            }
        }
        return $permisos_modulo;
    }

    function get_permisos_rol($roles, $rol) {
        $permisos_rol = '';
        if ($rol != '') {
            if (array_key_exists($rol, $roles)) {
                $permisos_rol = $roles[$rol]['permisos'];
            }
        }
        return $permisos_rol;
    }

    function getDatosLog($texto){
        $ci = &get_instance();
        $clv_sess = $ci->config->item('clv_sess');
        $user_id = $ci->session->userdata('user_id' . $clv_sess);
        $data = array(
            'log_id_usuario' => $user_id,
            'log_movimiento' => $texto,
            'log_ip' => $ci->input->ip_address(),
            'log_fecha' => date('Y-m-d')
        );
        return $data;
    }

}

?>