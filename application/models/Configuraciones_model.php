<?php

class Configuraciones_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function getDatos() {
        $this->db->select('imc_configuraciones.*, mod_nombre as modulo ', false);
        $this->db->from('imc_configuraciones');
        $this->db->join('acl_modulo', 'mod_id= cfg_modulo', 'left');
        //$this->db->order_by('');
        return $this->db->get()->result_array();
    }

    function getGuarda($datos) {
        $this->db->trans_begin();
        $result = FALSE;
        foreach ($datos as $idtxt => $v) {
            $id = substr($idtxt, 4);
            if (($id * 1) != 0) {
                $this->db->where('cfg_id', $id);
                $this->db->limit(1);
                $this->db->update('imc_configuraciones', array('cfg_valor' => $v));
            }
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }

    function get_parserConfiguraciones($id_modulo = 0) {
        $this->db->select('cfg_clave,cfg_valor', FALSE);
        if ($id_modulo != 0) {
            $this->db->where('cfg_modulo=' . $id_modulo . ' or cfg_modulo is null');
        }
        $codeerror = $this->db->get('imc_configuraciones')->result_array();
        $ec_array = array();
        foreach ($codeerror as $code) {
            if (!array_key_exists($code['cfg_clave'], $ec_array)) {
                $ec_array[$code['cfg_clave']] = array();
            }
            $ec_array[$code['cfg_clave']] = $code['cfg_valor'];
        }
        return $ec_array;
    }

}

?>