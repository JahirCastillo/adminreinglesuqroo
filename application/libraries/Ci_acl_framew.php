<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ci_acl_framew {

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

    /**
     * @brief Funcion obtiene un arreglo con los permisos y modulos de un usuario
     * @access public
     * @param Array $permisos_db
     * @return Array String Arreglo con indices por modulo con sus respectivos permisos
     * @example get_parse_array_permisos($permisos); 
     * Resultado (Array) {acd: ["rdo"],hlp: ["rdo"],rep: ["pdf","xsl"],usu: ["add","upd","del","des","rdo","rep"]} 
     */
    function get_parse_array_permisos($permisos_db = NULL) {
        $permisos_arr = array();
        if ($permisos_db != NULL) {
            foreach ($permisos_db as $p) {
                $permisos_arr;
                if (array_key_exists($p['modulo'], $permisos_arr)) {
                    array_push($permisos_arr[$p['modulo']], $p['permiso']);
                } else {
                    $permisos_arr[$p['modulo']] = array();
                    array_push($permisos_arr[$p['modulo']], $p['permiso']);
                }
            }
        }
        return $permisos_arr;
    }

}

?>