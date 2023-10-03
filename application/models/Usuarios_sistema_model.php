<?php

class Usuarios_sistema_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function get_datos($columnas, $tabla, $where, $order, $limit) {
        $sql = "SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $columnas)) . "
		 FROM   $tabla
                                    LEFT JOIN acl_rol on usu_rol=rol_id
		 $where
		 $order
		 $limit";

        $result = $this->db->query($sql);
        return $result;
    }

    function getusuario_sis($id) {
        $this->db->select('USU_LOGIN AS usu_login,USU_ROL AS usu_rol,USU_NOMBRE AS usu_nombre,USU_PERMISOS AS usu_permisos,USU_CORREO AS usu_correo,USU_ESTADOCUENTA AS usu_estadocuenta', FALSE);
        return $this->db->get_where('ADM_USUARIO', array('USU_ID' => $id));
    }

    function getElimina($id) {
        $result = FALSE;
        if ($id * 1 != 0) {
            $this->db->trans_begin();
            $this->db->where('apu_usuario', $id);
            $this->db->delete('acl_permisos_usuario');

            $this->db->where('usu_id', $id);
            $this->db->limit(1);
            $this->db->delete('acl_usuario');
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                $result = TRUE;
            }
        }
        return $result;
    }

    function getAgrega($datos, $permisos = FALSE) {
        $this->db->trans_begin();
        $rol = $datos['usu_rol'];
        $this->db->insert('acl_usuario', $datos);
        $insert_id = $this->db->insert_id();
        if (($rol * 1) != 0) {
            $this->db->select('apr_permiso as permiso', FALSE);
            $this->db->from('acl_permisos_rol');
            $this->db->where('apr_rol', $rol);
            $permisos = $this->db->get()->result_array();
            foreach ($permisos as $p) {
                $this->db->insert('acl_permisos_usuario', array('apu_permiso' => $p['permiso'], 'apu_usuario' => $insert_id));
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }

    function getModifica($id, $datos, $permisos = FALSE) {
        $this->db->trans_begin();
        $this->db->where('usu_id', $id);
        $this->db->update('acl_usuario', $datos);
        $rol = $datos['usu_rol'];
        if (($rol * 1) != 0) {
            if (($id * 1) != 0) {
                $this->db->where('apu_usuario', $id);
                $this->db->delete('acl_permisos_usuario');
                $this->db->select('apr_permiso as permiso', FALSE);
                $this->db->from('acl_permisos_rol');
                $this->db->where('apr_rol', $rol);
                $permisos = $this->db->get()->result_array();
                foreach ($permisos as $p) {
                    $this->db->insert('acl_permisos_usuario', array('apu_permiso' => $p['permiso'], 'apu_usuario' => $id));
                }
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }

    function get_datos_modifica($id) {
        $this->db->select('usu_id AS id, usu_imagen as foto, usu_login AS login,usu_rol AS rol,usu_nombre AS nombre,usu_apaterno as apaterno,usu_amaterno as amaterno,usu_correo AS email,`usu_estatuscuenta` AS estado, `usu_lada`as lada, `usu_telefono` AS telefono,`usu_celular` as celular, usu_usuarioagrego as usuarioagrego, usu_fechaagrego as fechaagrego, usu_usuariomodifico as usuariomodifico, usu_fechamodifico as fechamodifico', FALSE);
        $this->db->from('acl_usuario');
        $this->db->where('usu_id', $id);
        $resul = $this->db->get();
        return $resul->row();
    }

    function get_usuario_name($id) {
        $this->db->select('CONCAT(COALESCE(usu_nombre,"")," ",COALESCE(usu_apaterno,"")," ",COALESCE(usu_amaterno,"")) as nombre', FALSE);
        $this->db->from('acl_usuario');
        $this->db->where('usu_id', $id);
        $resul = $this->db->get();
        if ($resul->num_rows() > 0) {
            return $resul->row()->nombre;
        } else {
            return '';
        }
    }

    function get_modulos() {
        $this->db->select('per_id as permisoid, mod_clave as modclave, per_clave as permisoclave, `per_nombre` as permiso, `per_descripcion` as permisodesc, mod_id as moduloid, mod_nombre as modulo', FALSE);
        $this->db->select("if(mod_icon!='',
    concat('<i class=\"fa ',mod_icon,'\" ></i>'),
    if(mod_imagen!='',
        concat('<img src=\"./images/modulos/',mod_imagen,'\">'),
        '<i class=\"fa fa-square\"></i>'
    )
)as moduloicono", FALSE);
        $this->db->from('acl_permisos');
        $this->db->join('acl_modulo', 'mod_id=per_modulo');
        $this->db->order_by('mod_orden,`per_id`');
        return $this->db->get()->result_array();
    }

    function get_permisos_modulo_usuario($idusuario) {
        $this->db->select('per_id as permisoid, mod_id as moduloid', FALSE);
        $this->db->from('acl_permisos_usuario');
        $this->db->join('acl_permisos', 'apu_permiso=per_id');
        $this->db->join('acl_modulo', 'mod_id=per_modulo');
        $this->db->where('apu_usuario', $idusuario);
        $this->db->order_by('mod_orden,`per_id`');
        return $this->db->get()->result_array();
    }

    function get_detalles_usuario($idusuario) {
        $this->db->select('CONCAT(COALESCE(usu_nombre,"")," ",COALESCE(usu_apaterno,"")," ",COALESCE(usu_amaterno,"")) as nombre, COALESCE(`usu_imagen`,"") as foto, usu_usuarioagrego as usuarioagrego, usu_fechaagrego as fechaagrego, usu_usuariomodifico as usuariomodifico, usu_fechamodifico as fechamodifico', FALSE);
        $this->db->from('acl_usuario');
        $this->db->where('usu_id', $idusuario);
        return $this->db->get()->row_array();
    }

    function get_roles() {
        $this->db->select('rol_id as rolid, rol_nombre as rol,rol_descripcion as des,
mod_id as moduloid, 
per_id as permisoid, 
rol_clave as rolclave, mod_clave as moduloclave, per_clave as permisoclave', FALSE);
        $this->db->from('acl_permisos_rol');
        $this->db->join('acl_rol', 'rol_id=apr_rol');
        $this->db->join('acl_permisos', 'apr_permiso=per_id');
        $this->db->join('acl_modulo', 'mod_id=per_modulo');
        $this->db->order_by('rol_id, mod_id, per_id');
        return $this->db->get()->result_array();
    }

}

?>
