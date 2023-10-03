<?php

class Opcion_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->database();
    }

    //---------------INSERTAR OPCION DE RESPUESTA-------------
    /**
     * insertar o actualizar opcion de respuesta
     * 
     * @param array $datos, datos de un registro de una opcion de respuesta.
     */
    function insertDatosOpcion($idrea, $tipo_medio, $tipo_rea, $opciones) {
        $sepudo = TRUE;
        $ids = array();
        $this->db->trans_begin();
        $this->db->delete('adm_opcion', array('opc_reactivo' => $idrea)); //borra si es que existen opciones de respuesta anteriores 
        $this->db->delete('adm_opcion1', array('opc1_reactivo' => $idrea)); //borra si es que existen opciones 2 de respuesta anteriores 
        //insertar opciones de respuesta
        foreach ($opciones as $opc) {
            try {
                $exp = explode('@_@', $opc);
                //si no es de relacionar o clasificar
                if (!in_array($tipo_rea, array(6, 3))) {
                    if ($tipo_medio=='') {
                        $tipo_medio='txt';
                    }
                    $insert_arr = array('opc_reactivo' => $idrea, 'opc_tipo' => $tipo_medio, 'opc_escorrecta' => $exp[1]);
                    if ($tipo_medio == 'txt') {
                        $insert_arr['opc_contenido'] = urldecode($exp[0]);
                    } elseif ($tipo_medio == 'img') {
                        $insert_arr['opc_imagen'] = $exp[0];
                    } elseif ($tipo_medio == 'aud') {
                        $insert_arr['opc_audio'] = $exp[0];
                    } elseif ($tipo_medio == 'vid') {
                        $insert_arr['opc_video'] = $exp[0];
                    }
                    $this->db->insert('adm_opcion', $insert_arr);
                    $idins = $this->db->insert_id();
                    $ids[$exp[2]] = $idins;
                }
            } catch (Exception $e) {
                
            }
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $sepudo = FALSE;
        } else {
            $this->db->trans_commit();
            $sepudo = TRUE;
        }

        if ($sepudo) {
            return $ids;
        } else {
            return FALSE;
        }
    }

    //---------------INSERTAR OPCION DE RESPUESTA 1-------------
    /**
     * insertar o actualizar opcion de respuesta
     * 
     * @param array $datos, datos de un registro de una opcion de respuesta.
     */
    function insertDatosOpcion1($datos) {
        $clave = $datos['opc1_clave'];
        $sql = "select opc1_clave,opc1_contenido from adm_opcion1 where opc1_clave='$clave'";
        $existe = $this->db->query($sql);
        if ($existe->num_rows() > 0) {
            $existe = $existe->result_array();
            foreach ($existe as $ext) {
                $cla = $ext['opc1_clave'];
            }
            $this->db->where('opc1_clave', $clave);
            $this->db->update('adm_opcion1', $datos);
            $res = $clave . ' actualizo BD:' . $cla;
            return $res;
        } else {
            $this->db->insert('adm_opcion1', $datos);
            $res = $clave . ' inserto';
            return $res;
        }
    }

    //-----ELIMINAR OPCION----------
    function eliminarOpcion($clave) {
        $this->db->where('opc_clave', $clave);
        if ($this->db->delete('adm_opcion'))
            return 1;
        else
            return 2;
    }

    //-----ELIMINAR OPCION  1----------
    function eliminarOpcion1($clave) {
        $this->db->where('opc1_clave', $clave);
        if ($this->db->delete('adm_opcion1'))
            return 1;
        else
            return 2;
    }

    //------------DATOS OPCIONES DE UN REACTIVO-----------------
    function datosOpciones($clave) {
        $sql = "select * from adm_opcion where opc_reactivo='$clave' order by rand()";
        $query = $this->db->query($sql);
        return $query;
    }

    //------------DATOS OPCIONES 1 DE UN REACTIVO-----------------
    function datosOpciones1($clave) {
        $sql = "select * from  `adm_opcion1` ,  `adm_opcion` where  `opc1_opcion` =  `opc_clave` and `opc_reactivo` ='$clave' order by rand()";
        $query = $this->db->query($sql);
        return $query;
    }

    //---------VERIFICAR OPCION SI ES CORECTA----------------
    function opc_correcta_multiple($clave) {
        $query = $this->db->query("select opc_correcta from adm_opcion where opc_clave='$clave'");
        return $query;
    }

    //--------- ----------------
    function get_data_opciones_reactivo($idrea) {
        $sql = "select opc_id as id, `opc_clave` as clv, `opc_contenido` as con, `opc_imagen` as img, `opc_audio` as aud, `opc_video` as vid,
`opc_escorrecta` as escorrecta, `opc_tipo` as tip
from `adm_opcion` 
where `opc_reactivo`=$idrea";
        return $this->db->query($sql);
    }

}

?>