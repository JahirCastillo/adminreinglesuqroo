<?php

class Referencia_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->database();
    }
    
    function get_elimina($id) {
        $this->db->trans_begin();
        $this->db->where('ref_id', $id);
        $this->db->limit(1);
        $this->db->delete('adm_referencia');
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }
    
    function getDatoRow($id){
        $this->db->select('ref_titulo as titulo, ref_editorial as editorial,ref_autores as autores,ref_descripcion as descripcion, ref_tipo as tipo, ref_anio as year, ref_paginas as paginas,ref_fecha as fecha, ref_url as url, ref_nombre_sitio as nombresitio,ref_ciudad as ciudad,ref_nombre_revista as nombrerevista,ref_titulo_periodico as tituloperiodico');
        $this->db->from('adm_referencia');
        $this->db->where('ref_id', $id);
        $this->db->limit(1);
        $resul = $this->db->get();
        return $resul;
    }
    function searchRow($data){  
        $this->db->select('ref_titulo as titulo, ref_editorial as editorial,ref_autores as autores,ref_descripcion as descripcion, ref_tipo as tipo, ref_anio as year, ref_paginas as paginas,ref_fecha as fecha, ref_url as url, ref_nombre_sitio as nombresitio,ref_ciudad as ciudad,ref_nombre_revista as nombrerevista,ref_titulo_periodico as tituloperiodico');
        $this->db->from('adm_referencia');
        $this->db->where('ref_titulo',$data['ref_titulo']);
        $this->db->limit(1);
        $resul = $this->db->get();
        if ($resul->num_rows() == 0) {
            return true;
        }else{
        return false;
        }   
    }
    
    function datosDataTableLibro($columnas, $tabla, $where, $order, $limit) {
        $sql = "SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $columnas)) . "
		 FROM   $tabla 
		 $where
		 $order
		 $limit";

        $result = $this->db->query($sql);
        return $result;
    }

     function agregaReferencia($datos) {
        $this->db->trans_begin();
        $this->db->insert('adm_referencia', $datos);
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
    
    function get_update($datos) {
        $this->db->trans_begin();
        $this->db->where('ref_id', $datos['ref_id']);
        $this->db->update('adm_referencia', $datos);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }
}

?>