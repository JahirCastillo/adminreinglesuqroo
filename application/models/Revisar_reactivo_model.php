<?php

class Revisar_reactivo_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->database();
    }

    function get_cambiaEstadoReactivo($clave, $datos) {
        $out = FALSE;
        $this->db->trans_begin();
        if ($clave != '' && (($clave * 1) != 0)) {
            $this->db->where('rea_id', $clave);
            $this->db->limit(1);
            $this->db->update('adm_reactivo', $datos);
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $out = FALSE;
        } else {
            $this->db->trans_commit();
            $out = TRUE;
        }
        return $out;
    }

}
