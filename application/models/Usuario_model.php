<?php
class Usuario_model extends CI_Model{
	function __construct(){
        parent::__construct();
		$this->load->library('session');
		$this->load->database();
    }
	
	//--------CLAVE NUEVA USUARIO----
	function claveUsuario(){
		$sql="select max(usu_clave) as cla from adm_usuario";
	 	$id=$this->db->query($sql);
	  	$id=$id->result_array();
	  	foreach($id as $id){ 
	  	  $clave=$id['cla']+1; 
	  	}
	  	return $clave; 	
	}
	
	//------TIPOS DE USUARIO----
	function tiposUsuario(){
		$sql="select * from adm_tipousuario";
		$query=$this->db->query($sql);
		return $query;	
	}
	
	//------LOGIN USUARIO-----
	function loginUsuario($login){
	  $existe=$this->db->query("select usu_clave from adm_usuario where usu_login like '$login'");
	  $existe=$existe->num_rows();
	  return $existe;
	}
	
	//-----EXISTE USUARIO----
	function existeUsuario($clave){
	  $existe=$this->db->query("select usu_clave from adm_usuario where usu_clave=$clave");
	  $existe=$existe->num_rows();
	  return $existe;
	}
	
	//---------INGRESAR USAURIO------
	function ingresarUsuario($datos){
	  if($this->db->insert('adm_usuario', $datos)) return 1; 
	  else return 2;
	}
	
	//--------ACTUALIZAR USUARIO-------
	function actualizarUsuario($clave,$datos){
	  $this->db->where('usu_clave', $clave);
      if($this->db->update('adm_usuario', $datos)) return 1;
	  else return 2;
	} 
	
	//------BUSCAR USUARIO------
	function buscarUsuario($cadena){
		$sql="select usu_clave as cla, usu_login as log, usu_nombre as nom, (select tusu_nombre from adm_tipousuario where tusu_clave=usu_tipo) as tip, usu_estado as est, usu_fechaalta as fec from adm_usuario where usu_nombre like '%$cadena%' or usu_login like '%$cadena%'";
		 $query=$this->db->query($sql);
		 return $query;
	}
	
	//-----DATOS USUARIO------
	function datosUsuario($clave){
		$sql="select * from adm_usuario where usu_clave=$clave";
		$query=$this->db->query($sql);
		return $query;
	}
	
	//-------BORRA USUARIO------
	function borrarUsuario($clave){
		$this->db->where('usu_clave',$clave);
		if($this->db->delete('adm_usuario')) return 1;
		else return 0;
	}
    
}
?>