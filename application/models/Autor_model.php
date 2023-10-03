<?php
class Autor_model extends CI_Model{
	function __construct(){
        parent::__construct();
		$this->load->library('session');
		$this->load->database();
    }
	
	//------------------- NUEVA CLAVE "AUTOR"------------------------------
	function claveAutor(){
	  $sql="select max(aut_clave) as aut_clave from adm_autor";
	  $id=$this->db->query($sql);
	  $id=$id->result_array();
	  foreach($id as $id){ 
	    $clave=$id['aut_clave']+1; 
	  }
	  return $clave; 
	}
	
	//---------------INSERTAR DATOS DE AUTOR---------------
	function insertDatosAutor($datos){
	  $clave=$datos['aut_clave'];
	  $sql="select aut_clave from adm_autor where aut_clave=$clave";
	  $existe=$this->db->query($sql);
	  if($existe->num_rows() > 0){
		$this->db->where('aut_clave', $clave);
        $this->db->update('adm_autor', $datos); 
        return 2;  
	  }
	  else{
		$this->db->insert('adm_autor', $datos);
        return 1;  
	  }
   }
   
//----------TOTAL DE REGISTRO DE BUSQUEDA DE AUTOR------------   
   function searchTotalAutor($palabra){
	  $sql="select * from `adm_autor` where `aut_nombre` like '%$palabra%'";
	  $query=$this->db->query($sql);
	  $num= $query->num_rows();
	  return $num; 
	}

//---------REGISTROS POR PAGINA BUSQUEDA AUTOR------------------	
	function searchDatosAutor($palabra){
	  $sql="select aut_clave as cla, aut_nombre as nom, aut_cargo as car from `adm_autor` where `aut_nombre` like '%$palabra%' limit 0,30";
	  $query=$this->db->query($sql);
	  $num= $query->num_rows();
	  $datos['num']=$num;
	  $datos['array']=$query;
	  return $datos; 
	}
	
	function obtenerDatosAutor($clave){
		$sql="select `aut_clave`,`aut_nombre`,`aut_direccion`,`aut_telefono`, `aut_email`, `aut_cargo`, `aut_institucion` from `adm_autor` where `aut_clave`=$clave";
	  $query=$this->db->query($sql);
	  return $query; 
	}
   
}