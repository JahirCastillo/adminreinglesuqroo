<?php

class Seguimiento_model extends CI_Model {

    private $rutaRama = array();

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function getUsuarios() {
        return $this->db->get('acl_usuario')->result_array();
    }

    function getUsuario($idUsuario) {
        return $this->db->query("SELECT usu_nombre,usu_apaterno,usu_correo,(select correo_fecha from adm_correos_enviados where usu_id_captura_rea=usu_id and correo_fecha=(select max(correo_fecha) from adm_correos_enviados where usu_id_captura_rea=usu_id)) as fecha_correo_enviado FROM `acl_usuario` where usu_id=$idUsuario")->result_array();
    }

    function getAgrega($datos) {
        $result = FALSE;
        $this->db->trans_begin();
        $this->db->insert('adm_seguimiento', $datos);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }

    function get_datos_modifica($idSeguimiento) {
        $this->db->where("seg_id", $idSeguimiento);
        $this->db->limit(1);
        return $this->db->get('adm_seguimiento')->result_array();
    }

    function get_ruta_rama($idRama) {
        if ($idRama != 0) {
            $this->db->select('pla_padre,pla_nombre');
            $this->db->from('adm_plan');
            $this->db->where('pla_id', $idRama);
            $resul = $this->db->get()->row_array();
            array_push($this->rutaRama, $resul['pla_nombre']);
            $this->get_ruta_rama($resul['pla_padre']);
        }
        return array_reverse($this->rutaRama);
    }

    function getModifica($idSeg, $datos) {
        $resultado = false;
        $this->db->where('seg_id', $idSeg);
        $this->db->limit(1);
        $this->db->update('adm_seguimiento', $datos);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            $resultado = true;
        }
        return $resultado;
    }

    function getElimina($idSeguimiento) {
        $result = FALSE;
        $this->db->where('seg_id', $idSeguimiento);
        $this->db->delete('adm_seguimiento');
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }

    function registraCorreoEnviado($idUsuarioAdministracion, $idUsuarioCaptura) {
        $data = array(
            'usu_id_sis' => $idUsuarioAdministracion,
            'usu_id_captura_rea' => $idUsuarioCaptura,
            'correo_fecha' => date('Y-m-d'),
            'correo_status' => 1
        );
        $this->db->insert('adm_correos_enviados', $data);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    function correos_enviados() {
        $sql = "SELECT count(*) as numeroCorreosEnviados FROM `aex_correos_enviados` where correo_fecha='" . date('Y-m-d') . "'";
        return $this->db->query($sql)->row_array();
    }

}
