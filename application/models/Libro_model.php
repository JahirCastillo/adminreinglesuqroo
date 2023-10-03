<?php
class Libro_model extends CI_Model{
	function __construct(){
        parent::__construct();
		$this->load->library('session');
		$this->load->database();
    }
	
	//------------------- NUEVA CLAVE "LIBRO"------------------------------
	/**
	* obtine la ultima clave continua para la inserccion de un registro de la tabla libro
	* 
	* @return int $clave, clave continua disponible.  
	*/
	function claveLibro(){
	  $sql="select max(lib_clave) as lib_clave from adm_libro";
	  $id=$this->db->query($sql);
	  $id=$id->result_array();
	  foreach($id as $id){ 
	    $clave=$id['lib_clave']+1; 
	  }
	  return $clave; 
	}
	
	//---------------INSERTAR O ACTUALIZA DATOS DE LIBRO---------------
	/**
	* inserta o actualiza datos en la tabla libro
	* 
	* @param array $datos, datos del registro.
	* @return int 1, si inserto nuevo registro. 
	* @return int 2, si actualizo algun registro existente. 
	*/
	function insertDatosLibro($datos){
	  $clave=$datos['lib_clave'];
	  $sql="select lib_clave from adm_libro where lib_clave=$clave";
	  $existe=$this->db->query($sql);
	  if($existe->num_rows() > 0){
		$this->db->where('lib_clave', $clave);
        $this->db->update('adm_libro', $datos); 
        return 2;  
	  }
	  else{
		$this->db->insert('adm_libro', $datos);
        return 1;  
	  }
   }
   
	//----------TOTAL DE REGISTRO DE BUSQUEDA DE LIBRO------------  
	/**
	* obtiene el total de registros encontrados que considieron en la busqueda.
	* 
	* @param char $this->input->post('palabra'), cadena a buscar en los registros tabla libro.
	* @return int $num, total de registros encontrados. 
	*/
   function searchTotalLibro(){
	  $palabra=$this->input->post('palabra');
	  $sql="select * from `adm_libro` where `lib_titulo` like '%$palabra%'";
	  $query=$this->db->query($sql);
	  $num= $query->num_rows();
	  return $num; 
	}

	//---------REGISTROS BUSQUEDA LIBRO------------------	
	/**
	* registros encontrados dentro de la busqueda.
	* 
	* @param char $this->input->post('palabra'), cadena a buscar en los registros tabla libro.
	* @return objetc $query, total de registros encontrados. 
	*/
	function searchDatosLibro($palabra){
	  $sql="select lib_clave as cla, lib_titulo as tit, lib_descripcion as des from `adm_libro` where `lib_titulo` like '%$palabra%' limit 0,30";
	  $query=$this->db->query($sql);
	  return $query; 
	}

	//---------DATOS DE UN REGISTRO DE LA TABLA LIBRO-----------------
	/**
	* obtiene datos de un registro espacifico de la tabla libro.
	* 
	* @param $clave, identificador de registro.
	* @return object $query, datos del registro. 
	*/
	function obtenerDatosLibro($clave){
		$sql="select `lib_clave`,`lib_titulo`,`lib_editorial`,`lib_autores`, `lib_descripcion` from `adm_libro` where `lib_clave`=$clave";
	  $query=$this->db->query($sql);
	  return $query; 
	}
   
}