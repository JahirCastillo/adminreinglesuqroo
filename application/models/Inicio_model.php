<?php

class Inicio_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function get_modulos($usuario) {
        $this->db->select('mod_id as id,mod_clave as clave, mod_nombre as nombre, mod_url as url,mod_imagen as imagen, mod_imagenhover, mod_icon as icon', FALSE);
        $this->db->from('acl_permisos_usuario');
        $this->db->join('acl_permisos', 'apu_permiso=per_id');
        $this->db->join('acl_modulo', 'per_modulo=mod_id');
        $this->db->where('apu_usuario', $usuario);
        $this->db->where('mod_activo', 1);
        $this->db->order_by('mod_orden');
        $this->db->group_by('mod_id');
        return $this->db->get()->result_array();
    }

}
