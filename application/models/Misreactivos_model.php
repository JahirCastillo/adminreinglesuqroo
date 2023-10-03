<?php

if (!defined('BASEPATH'))
    exit('Acceso denegado');

class Misreactivos_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function get_misreactivos($usuario) {
        $sql = "select `rea_id` as id, `rea_clave` as clave, substring(rea_contenido,1,300) as contenido,`rea_estado` as estado,`pla_nombre` as plan,
            rea_fechaalta as fechaalta,rea_fechamodif as fechamodifica, rea_usuariovalido as usuvalido,rea_fechavalidacion as fechavalido, rea_comentariovalidacion as comentario 
            from adm_reactivo 
left join adm_plan on pla_id=rea_plan
where `rea_usuarioalta`=" . $usuario;
        return $this->db->query($sql)->result_array();
    }

}

?>