<?php

class Acceso_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function get_modulos() {
        $this->db->select('mod_id as id,mod_clave as clave, mod_nombre as nombre, mod_url as url,mod_imagen as imagen, mod_imagenhover, mod_icon as icon, mod_categoria as categoria', false);
        $this->db->from('acl_modulo');
        $this->db->order_by('mod_orden');
        return $this->db->get()->result_array();
    }

    function datoslogin($login) {
        $this->db->select('usu_id as id,usu_login as login,usu_password as password,concat(COALESCE(usu_nombre,"")," ",COALESCE(usu_apaterno,"")) as nombre, rol_id as id_rol,rol_clave as claverol,rol_nombre as rol,usu_estatuscuenta as estatus, usu_imagen as imagen', false);
        $this->db->from('acl_usuario');
        $this->db->join('acl_rol', 'usu_rol=rol_id', 'left');
        $this->db->where('usu_login', $login);
        $this->db->limit(1);
        return $this->db->get();
    }

    function get_permisosUsuario($idusuario = 0, $clvmodulo = '') {
        $this->db->select('mod_clave as modulo,per_clave as permiso', false);
        $this->db->from('acl_permisos_usuario');
        $this->db->join('acl_permisos', 'apu_permiso=per_id');
        $this->db->join('acl_modulo', 'per_modulo=mod_id');
        $this->db->where('apu_usuario', $idusuario * 1);
        if ($clvmodulo != '') {
            $this->db->where('mod_clave', $clvmodulo);
        }
        return $this->db->get()->result_array();
    }

    function get_permisosRol($rol) {
        $this->db->select('mod_clave AS modulo, per_clave AS permiso', false);
        $this->db->from('acl_permisos_rol');
        $this->db->join('acl_permisos', 'apr_permiso = per_id');
        $this->db->join('acl_modulo', 'per_modulo=mod_id');
        $this->db->join('acl_rol', 'rol_id = apr_rol');
        $this->db->where('rol_clave', $rol);
        $this->db->order_by('mod_clave');
        return $this->db->get()->result_array();
    }

    function get_iconModulo($clave_modulo) {
        $icon = "<i class=\"fa fa-square\"></i>";
        $id = $nombre = $url = '';
        $sql = "SELECT if(mod_icon!='',
    concat('<i class=\"fa ',mod_icon,'\" ></i>'),
    if(mod_imagen!='',
        concat('<img src=\"./images/modulos/',mod_imagen,'\">'),
        '<i class=\"fa fa-square\"></i>'
    )
) as icon,mod_id as id, mod_nombre as nombre, mod_url as url
FROM acl_modulo where mod_clave='$clave_modulo'";
        $result = $this->db->query($sql);
        if ($result->num_rows() > 0) {
            $icon = $result->row()->icon;
            $nombre = $result->row()->nombre;
            $url = $result->row()->url;
            $id = $result->row()->id;
        }
        return array('id' => $id, 'icon' => $icon, 'nombre' => $nombre, 'url' => $url);
    }

}

?>