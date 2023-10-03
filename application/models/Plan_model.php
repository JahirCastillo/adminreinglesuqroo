<?php

class Plan_model extends CI_Model {

    private $arrayIds = array();
    private $arrayNames = array();

    function __construct() {
        parent::__construct();
        $this->load->library('tools');
        $this->load->database();
    }

    //agregar plan
    function get_addNodo($pid, $nom, $usu, $rol) {
        $this->db->trans_begin();
        $data = array(
            'pla_padre' => $pid,
            'pla_nombre' => $nom,
            'pla_fechaalta' => date('Y-m-d'),
            'pla_usuario' => $usu
        );
        $this->db->insert('adm_plan', $data);
        $insert_id = $this->db->insert_id();
        if ($usu == 1) {
            $this->db->insert('roles_planes', array('rp_plan_id' => $insert_id, 'rp_rol_id' => $rol));
        } else {
            $this->db->insert('roles_planes', array('rp_plan_id' => $insert_id, 'rp_rol_id' => $rol));
            $this->db->insert('roles_planes', array('rp_plan_id' => $insert_id, 'rp_rol_id' => 1));
        }

        $r = FALSE;
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $r = false;
        } else {
            $this->db->trans_commit();
            $r = $insert_id;
        }
        return $r;
    }

    function get_moveNodo($nodeId, $targetNode, $typeNode, $typeNodeTarget, $usu_id) {
        $this->db->trans_begin();
        if (($typeNode == 'R' && $typeNodeTarget == 'R') || ($nodeId === 0 || $targetNode === 0)) {
            return false;
        }
        //mover carpeta entre otras carpetas
        if ($typeNode == 'P' && $typeNodeTarget == 'P') {
            $this->db->where('pla_id', $nodeId);
            $this->db->update('adm_plan', array('pla_padre' => $targetNode, 'pla_usuario_modifico' => $usu_id));
        } else if ($typeNode == 'R' && $typeNodeTarget == 'P') {
            $this->db->where('rea_id', $nodeId);
            $this->db->update('adm_reactivo', array('rea_plan' => $targetNode));
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $r = false;
        } else {
            $this->db->trans_commit();
            $r = true;
        }
        return $r;
    }

    //agregar plan
    function get_editNodoName($id, $nom) {
        $this->db->trans_begin();
        $data = array(
            'pla_nombre' => $nom,
            'pla_fechamodif' => date('Y-m-d')
        );
        $this->db->where('pla_id', $id);
        $this->db->limit(1);
        $this->db->update('adm_plan', $data);
        $r = FALSE;
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $r = false;
        } else {
            $this->db->trans_commit();
            $r = true;
        }
        return $r;
    }

    //editar padre plan
    function get_editNodoPadre($id, $pid) {
        $this->db->trans_begin();
        $data = array(
            'pla_padre' => $pid,
            'pla_fechamodif' => date('Y-m-d')
        );
        $this->db->where('pla_id', $id);
        $this->db->limit(1);
        $this->db->update('adm_plan', $data);
        $r = FALSE;
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $r = false;
        } else {
            $this->db->trans_commit();
            $r = true;
        }
        return $r;
    }

    //borrar plan
    function get_deleteNodo($id) {
        $this->db->trans_begin();
        $this->db->limit(1);
        $this->db->where('pla_id', $id);
        $this->db->delete('adm_plan');

        $r = FALSE;
        $this->db->where('rp_plan_id', $id);
        $this->db->delete('roles_planes');

        $data=$this->tools->getDatosLog("Eliminó un plan con el id ".$id);
        $this->db->insert('adm_logs', $data);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $r = false;
        } else {
            $this->db->trans_commit();
            $r = true;
        }
        return $r;
    }

    //-------------REGISTROS DE PAGINA A MOSTRAR PARA EL BUSCADOR--------------
    function searchDatosPlan($palabra) {
        $sql = "select plan1.`pla_id` as c, plan1.`pla_nombre` as n, plan1.`pla_descripcion` as d, (

select  `pla_nombre` 
from  `adm_plan` 
where plan1.`pla_padre` =  `pla_id`
) as p, (

select count(  `pla_padre` ) 
from  `adm_plan` 
where  `pla_padre` = plan1.`pla_id`
) as h
from  `adm_plan` as plan1
where  `pla_nombre` like  '%$palabra%'
limit 0 , 31";
        $query = $this->db->query($sql);
        $num = $query->num_rows();
        $datos['num'] = $num;
        $datos['array'] = $query;
        return $datos;
    }

    function plan_padre() {
        $sql = "select pla_clave, pla_nombre from adm_plan where pla_padre=''";
        return $this->db->query($sql);
    }

    function plan_hijos($clave) {
        $sql = "select pla_clave, pla_nombre from adm_plan where pla_padre='$clave' order by pla_nombre asc";
        $query = $this->db->query($sql);
        $num = $query->num_rows();
        $datos['num'] = $num;
        $datos['array'] = $query;
        return $datos;
    }

    function baseTree($rol, $user_id) {
        $where = "";
       // $where = ($rol != 1) ? " and pla_usuario=$user_id" : "";
        $sql = "select pla_id as id, pla_nombre as nom, (select count(pla_id) from adm_plan where pla_padre=id) as hij from adm_plan where pla_padre=0 $where";
        return $this->db->query($sql);
    }

    function obtenerHijos($clave) {
        $sql = "select pla_id as id, pla_nombre as nom, (select count(pla_id) from adm_plan where pla_padre=id) as hij from adm_plan where pla_padre=$clave";
        return $this->db->query($sql);
    }

    function get_dataNodos($id = 0, $id_rol) {
        //$this->output->enable_profiler((true));
        $id = $id * 1;
        $this->db->distinct();
        $this->db->select("`pla_id` as id,`pla_nombre` as name,'true' as isParent ", false);
        $this->db->from('adm_plan');
        $this->db->join('roles_planes', 'pla_id=rp_plan_id', 'left');
        $this->db->where('pla_padre', $id);
        $this->db->where('rp_rol_id', $id_rol);


        $ramas = $this->db->get()->result_array();
        //obtener hojas (Reactivos)
        $this->db->select("rea_id as id, if(`rea_clave`='',concat('~REA-',rea_id),concat('~',`rea_clave`)) as name, 'false' as isParent ", false);
        $this->db->from('adm_reactivo');
        $this->db->where('rea_plan', $id);
        $this->db->order_by("replace(name,'~REA-','')+0,replace(name,'~','')+0,replace(name,'~aib_','')+0,replace(name,'~aexiweb_','')+0"); //Para ordenar los reactivos por la clave
        $hojas = $this->db->get()->result_array();
        return array_merge($ramas, $hojas);
    }

    function get_dataNodosViewRoles($id = 0, $idRol) {
        //$this->output->enable_profiler((true));
        $id = $id * 1;
        $this->db->distinct();
        ($idRol != 0) ? $this->db->select("`pla_id` as id,`pla_nombre` as name,'true' as isParent,if((select count(*) from roles_planes where rp_plan_id=pla_id and rp_rol_id=$idRol)!=0,'true','false') as checked ", false) : $this->db->select("`pla_id` as id,`pla_nombre` as name,'true' as isParent,'false' as checked ", false);
        $this->db->from('adm_plan');
        $this->db->where('pla_padre', $id);
        ($idRol != 0) ? $this->db->join('roles_planes', 'pla_id=rp_plan_id', 'left') : '';
        $ramas = $this->db->get()->result_array();
        return $ramas;
    }

    function get_dataNodosViewSeguimiento($id = 0, $idSeguimiento) {
        //$this->output->enable_profiler((true));
        $id = $id * 1;
        $this->db->distinct();
        ($idSeguimiento != 0) ? $this->db->select("`pla_id` as id,`pla_nombre` as name,'true' as isParent,if((select count(*) from adm_seguimiento where seg_id_rama=pla_id and seg_id=$idSeguimiento)!=0,'true','false') as checked ", false) : $this->db->select("`pla_id` as id,`pla_nombre` as name,'true' as isParent,'false' as checked ", false);
        $this->db->from('adm_plan');
        $this->db->where('pla_padre', $id);
        $ramas = $this->db->get()->result_array();
        return $ramas;
    }

    function getIdsPadres($idPlan) {
        if ($idPlan != 0) {
            $this->db->select('pla_id,pla_padre');
            $this->db->from('adm_plan');
            $this->db->where('pla_padre', $idPlan);
            $resul = $this->db->get()->result_array();
            foreach ($resul as $value) {
                array_push($this->arrayIds, $value['pla_id']);
                $this->getIdsPadres($value['pla_id']);
            }
        }
        return $this->arrayIds;
    }

    function countNodos($id = 0) {
        $out = array();
        $this->db->select("COUNT(pla_id) as total");
        $this->db->from('adm_plan');
        $this->db->where('pla_padre', $id);
        $totalRamas = $this->db->get()->row();
        $out['totalRamas'] = $totalRamas->total;
        $idRamas = $this->getIdsPadres($id);
        array_push($idRamas, $id);
        $this->db->select("COUNT(rea_id) as totalReactivos");
        $this->db->from('adm_reactivo');
        $this->db->where_in('rea_plan', $idRamas);
        $totalReactivos = $this->db->get()->row();
        $out['totalReactivos'] = $totalReactivos->totalReactivos;
        $this->db->select("exa_id,exa_nombre,plan_nombre");
        $this->db->from('adm_examen_plan');
        $this->db->join('adm_examenes', 'exa_id=id_examen', 'left');
        $this->db->where('id_tbl_adm_plan', $id);
        $out['ramasEnExamen'] = $this->db->get()->result_array();
        return $out;
    }

    function obtenerPadres($clave) {
        $sql = "select pla_padre as padre from adm_plan where pla_id=$clave";
        $padre = $this->db->query($sql)->row();
        if ($padre->padre != 0) {
            $sqlDos = "select pla_id as id,pla_nombre as nombrePlan from adm_plan where pla_id=$padre->padre";
            $datosPlan = $this->db->query($sqlDos)->row();
            return $datosPlan;
        } else {
            return false;
        }
    }

    function copyReactivosToRama($idNodoOrigen, $idNodoDestino, $user_id, $rol, $nodoContenedor) {
        $out = FALSE;
        $this->db->trans_begin();
        $this->db->select("rea_id, rea_clave, rea_contenido, rea_modocalif, rea_estado, rea_tiporeactivo, rea_libro, rea_autor, rea_caso, rea_usuariovalido, rea_fechavalidacion, rea_comentariovalidacion, rea_respuestacorrecta", false);
        $this->db->from('adm_reactivo');
        $this->db->where('rea_plan', $idNodoOrigen);
        $query = $this->db->get();

        $data = array(
            'pla_padre' => $idNodoDestino,
            'pla_nombre' => $nodoContenedor,
            'pla_fechaalta' => date('Y-m-d'),
            'pla_usuario' => $user_id
        );
        $this->db->insert('adm_plan', $data);
        $idPlanNuevo = $this->db->insert_id();

        foreach ($query->result() as $row) {
            $sql = "insert into adm_reactivo (rea_clave, rea_contenido, rea_modocalif, rea_fechaalta, rea_fechamodif,rea_estado, rea_tiporeactivo, rea_plan, rea_libro, rea_autor, rea_caso,rea_usuariovalido,rea_fechavalidacion,rea_comentariovalidacion,rea_usuarioalta,rea_usuariomodif,rea_respuestacorrecta) values ('" . $row->rea_clave . "','" . $row->rea_contenido . "','" . $row->rea_modocalif . "','" . date('Y-m-d') . "','0000-00-00','" . $row->rea_estado . "','" . $row->rea_tiporeactivo . "','" . $idPlanNuevo . "','" . $row->rea_libro . "','" . $row->rea_autor . "','" . $row->rea_caso . "','" . $row->rea_usuariovalido . "','" . $row->rea_fechavalidacion . "','" . $row->rea_comentariovalidacion . "','" . $user_id . "','-','" . $row->rea_respuestacorrecta . "');";
            $this->db->query($sql);
            $insert_id_copia = $this->db->insert_id();

            $sqlOpciones = "SELECT opc_clave as clave, opc_contenido as contenido, opc_correcta as correcta, opc_imagen as imagen, opc_audio as audio, opc_video as video, opc_escorrecta as escorrecta, opc_tipo as tipo FROM `adm_opcion` where opc_reactivo=" . $row->rea_id;
            $opciones = $this->db->query($sqlOpciones);
            foreach ($opciones->result() as $rowRespuesta) {
                $this->db->query("insert into adm_opcion (opc_clave, opc_contenido, opc_correcta, opc_imagen, opc_audio, opc_video, opc_reactivo, opc_escorrecta, opc_tipo) values ('" . $rowRespuesta->clave . "','" . $rowRespuesta->contenido . "','" . $rowRespuesta->correcta . "','" . $rowRespuesta->imagen . "','" . $rowRespuesta->audio . "','" . $rowRespuesta->video . "','" . $insert_id_copia . "','" . $rowRespuesta->escorrecta . "','" . $rowRespuesta->tipo . "');");
            }
        }

        if ($user_id == 1) {
            $this->db->insert('roles_planes', array('rp_plan_id' => $idPlanNuevo, 'rp_rol_id' => $rol));
        } else {
            $this->db->insert('roles_planes', array('rp_plan_id' => $idPlanNuevo, 'rp_rol_id' => $rol));
            $this->db->insert('roles_planes', array('rp_plan_id' => $idPlanNuevo, 'rp_rol_id' => 1));
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

    function getNamePlanes($id) {
        $names = $this->getNamePadres($id);
        return $names;
    }

    function getNamePadres($idPlan) {
        if ($idPlan != 0) {
            $this->db->select('pla_id,pla_padre,pla_nombre');
            $this->db->from('adm_plan');
            $this->db->where('pla_padre', $idPlan);
            $resul = $this->db->get()->result_array();
            foreach ($resul as $value) {
                array_push($this->arrayNames, $value['pla_nombre']);
                $this->getNamePadres($value['pla_id']);
            }
        }
        return $this->arrayNames;
    }

    function check_reactOccupied($idRea) {
        $isOccupied = false;
        $this->db->select('id_reactivo');
        $this->db->from('adm_examen_reactivos');
        $this->db->where('id_reactivo', $idRea);
        $idsReaOccupied = $this->db->get();
        if ($idsReaOccupied->num_rows() > 0) {
            $isOccupied = true;
        }
        return $isOccupied;
    }

}

?>