<?php

class Roles_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function get_permisos_modulo_rol($id) {
        $this->db->select('per_id as permisoid, mod_id as moduloid', FALSE);
        $this->db->from('acl_permisos_rol');
        $this->db->join('acl_permisos', 'apr_permiso=per_id');
        $this->db->join('acl_modulo', 'mod_id=per_modulo');
        $this->db->where('apr_rol', $id);
        $this->db->order_by('mod_orden,`per_id`');
        return $this->db->get()->result_array();
    }

    function get_datos_modifica($id) {
        $this->db->select('rol_id as id, rol_clave as clave, rol_nombre as nombre, rol_descripcion as descripcion', FALSE);
        $this->db->from('acl_rol');
        $this->db->where('rol_id', $id);
        $resul = $this->db->get();
        return $resul->row();
    }

    function getElimina($id) {
        $this->db->trans_begin();
        $result = FALSE;
        if (($id * 1) != 0) {
            //actualizar rol usuarios a 0
            $this->db->where('usu_rol', $id);
            $this->db->update('acl_usuario', array('usu_rol' => '0'));
            //eliminar permisos del rol
            $this->db->where('apr_rol', $id);
            $this->db->delete('acl_permisos_rol');
            //eliminar ramas del rol
            $this->db->where('rp_rol_id', $id);
            $this->db->delete('roles_planes');
            //eliminar rol
            $this->db->where('rol_id', $id);
            $this->db->limit(1);
            $this->db->delete('acl_rol');
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                $result = TRUE;
            }
        }
        return $result;
    }

    function getAgrega($datos, $permisos = FALSE, $planRol) {
        $this->db->trans_begin();
        $this->db->insert('acl_rol', $datos);
        $insert_id = $this->db->insert_id();
        if (is_array($permisos)) {
            foreach ($permisos as $p) {
                $this->db->insert('acl_permisos_rol', array('apr_permiso' => $p, 'apr_rol' => $insert_id));
            }
        }
        if (count($planRol) > 0) {
            foreach ($planRol as $plan) {
                $this->db->insert('roles_planes', array('rp_plan_id' => $plan, 'rp_rol_id' => $insert_id));
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

    function getModifica($id, $datos, $permisos = FALSE, $chk_restaura_permisos = '', $planRol) {
        $this->db->trans_begin();
        $this->db->where('rol_id', $id);
        $this->db->update('acl_rol', $datos);
        if (is_array($permisos) && (count($permisos) > 0)) {
            $this->db->where('apr_rol', $id);
            $this->db->delete('acl_permisos_rol');
            foreach ($permisos as $p) {
                $this->db->insert('acl_permisos_rol', array('apr_permiso' => $p, 'apr_rol' => $id));
            }
            if (count($planRol) > 0) {
                $this->db->where('rp_rol_id', $id);
                $this->db->delete('roles_planes');
                foreach ($planRol as $plan) {
                    $this->db->insert('roles_planes', array('rp_plan_id' => $plan, 'rp_rol_id' => $id));
                }
            }
            if ($chk_restaura_permisos == 'S') {
                $this->db->select('usu_id as id', FALSE);
                $this->db->where('usu_rol', $id);
                $usuarios_rol = $this->db->get('acl_usuario')->result();
                foreach ($usuarios_rol as $usu) {
                    $this->db->where('apu_usuario', $usu->id);
                    $this->db->delete('acl_permisos_usuario');
                    foreach ($permisos as $p) {
                        $this->db->insert('acl_permisos_usuario', array('apu_usuario' => $usu->id, 'apu_permiso' => $p));
                    }
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

    function get_permisosRol($id) {
        $sql = "SELECT mod_clave as modulo,per_clave as permiso FROM acl_permisos_rol join acl_permisos on apr_permiso=per_id join acl_modulo on per_modulo=mod_id where apr_rol=$id ";
        return $this->db->query($sql)->result_array();
    }

    function llenaPlanesRoles($rol_id) {
        $this->db->select("`pla_id` as id", false);
        $this->db->from('adm_plan');
        $ramas = $this->db->get()->result_array();
        $this->db->where('rp_rol_id', $rol_id);
        $this->db->delete('roles_planes');
        foreach ($ramas as $plan) {
            $this->db->insert('roles_planes', array('rp_plan_id' => $plan['id'], 'rp_rol_id' => $rol_id));
            echo "ok";
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result = FALSE;
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
    }

}
