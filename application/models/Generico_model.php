<?php

class Generico_model extends CI_Model {

    function __construct()     {
        parent::__construct();
        $this->load->database();
    }

    function datosDataTable($columnas, $tabla, $where, $order, $limit,$rol,$id_usuario,$campo="") {
        if($rol!=1){
            if($where==""){
                 $where= "where $campo=$id_usuario";
            }else{
                $where.= " and ($campo=$id_usuario)";
            }
           
        }

        $sql = "SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $columnas)) . "
		 FROM   $tabla 
		 $where
		 $order
		 $limit";

        $result = $this->db->query($sql);
        return $result;
    }

    function numFilasSQL() {
        $sql = "SELECT FOUND_ROWS() AS filas";
        $result = $this->db->query($sql);
        return $result;
    }

    function countResults($indice_clave, $tabla) {
        $sql = "SELECT COUNT(" . $indice_clave . ") as numreg FROM $tabla";
        $result = $this->db->query($sql);
        return $result;
    }
    
}
