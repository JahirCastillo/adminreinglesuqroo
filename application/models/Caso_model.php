<?php

class Caso_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->database();
    }

    //-----------------INSERTAR REGISTRO CASO--------------
    function insertDatosCaso($datos) {
        $this->db->trans_begin();
        $this->db->insert('adm_caso', $datos);
        $insertid = $this->db->insert_id();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $out = FALSE;
        } else {
            $this->db->trans_commit();
            $out = $insertid;
        }
        return $out;
    }

    //---------REGISTROS BUSQUEDA CASO------------------	
    /**
     * registros encontrados dentro de la busqueda.
     * 
     * @param char $this->input->post('palabra'), cadena a buscar en los registros tabla caso.
     * @return objetc $query, total de registros encontrados. 
     */
    function searchDatosCaso($palabra) {
        $sql = "select cas_titulo as tit,concat('<button class=\"btn btn-primary\" onclick=\"datosCaso(' ,cas_id, ');\">seleccionar</button>') as con from `adm_caso` where `cas_titulo` like '%$palabra%' or `cas_contenido` like '%$palabra%' limit 0,30";
        $query = $this->db->query($sql);
        return $query;
    }

    //---------DATOS DE UN REGISTRO DE LA TABLA CASO-----------------
    /**
     * obtiene datos de un registro espacifico de la tabla caso.
     * 
     * @param $clave, identificador de registro.
     * @return object $query, datos del registro. 
     */
    function obtenerDatosCaso($id) {
        $sql = "select  cas_id as clave,cas_titulo as titulo, cas_instruccion as instruccion,cas_contenido as contenido,`cas_imagen` as imagen,`cas_audio` as audio , cas_video as video from adm_caso where cas_id=$id";
        return $this->db->query($sql);
    }

    function get_data_caso_reactivo($idrea) {
        if (($idrea * 1) != 0) {
            $sql = "select `cas_id` as id,`cas_titulo` as tit,cas_instruccion as ins, `cas_contenido` as con, `cas_imagen` as img, `cas_audio` as aud, `cas_video` as vid
from `adm_caso` 
where `cas_id`=$idrea";
            return $this->db->query($sql)->row_array();
        } else {
            return FALSE;
        }
    }

    function get_elimina($id) {
        $this->db->trans_begin();
        $this->db->where('cas_id', $id);
        $this->db->limit(1);
        $this->db->delete('adm_caso');
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }

    function get_agrega($datos) {
        $this->db->trans_begin();
        $this->db->insert('adm_caso', $datos);
        $insertid = $this->db->insert_id();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $out = FALSE;
        } else {
            $this->db->trans_commit();
            $out = $insertid;
        }
        return $out;
    }

    function get_modifica($id, $datos) {
        $this->db->trans_begin();
        $this->db->where('cas_id', $id);
        $this->db->update('adm_caso', $datos);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }

    function get_datos_modifica($id) {
        $this->db->select(' `cas_id` as id,`cas_titulo` as tit,`cas_instruccion` as ins, `cas_contenido` as con, `cas_imagen` as img, `cas_audio` as aud, `cas_video` as vid', false);
        $this->db->from('adm_caso');
        $this->db->where('cas_id', $id);
        $this->db->limit(1);
        $resul = $this->db->get();
        if ($resul->num_rows() == 1) {
            $resul = $resul->row();
        } else {
            $resul = false;
        }
        return $resul;
    }

}

?>