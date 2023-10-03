<?php

/**
 * Description of Examenes_model
 *
 * @author yahir
 */
class Examenes_model extends CI_Model {

    private $arrayRamasFull = array();
    private $arrayIds = array();

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->database();
    }

    function getExamenes($columnas, $tabla, $where, $order, $limit, $rol, $id_usuario) {
        //$where = ($where == '') ? 'where exa_visible=1' : 'and exa_visible=1';
        if ($rol != 1) {
            if ($where == "") {
                $where = "where exa_usu_id=$id_usuario";
            } else {
                $where .= "and (exa_usu_id=$id_usuario)";
            }
        }

        $sql = "SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $columnas)) . "
		 FROM   $tabla 
		 $where
		 $order
		 $limit";
//die($sql);
        $result = $this->db->query($sql);
        return $result;
    }

    function get_moveNodo($nodeId, $targetNode, $typeNode, $typeNodeTarget, $idExamen = 0) {
        $idExamen = $idExamen * 1;
        //$this->output->enable_profiler(true);
        $this->db->trans_begin();
        if (($typeNode == 'R' && $typeNodeTarget == 'R') || ($nodeId === 0)) {
            return false;
        }
        //mover carpeta entre otras carpetas
        if ($typeNode == 'P' && $typeNodeTarget == 'P') {
            $this->db->where('id_plan', $nodeId);
            $this->db->update('adm_examen_plan', array('plan_padre' => $targetNode));
        } else if ($typeNode == 'R' && $typeNodeTarget == 'P') {
            $this->db->limit(1);
            $this->db->where('id_examen', $idExamen);
            $this->db->where('id_reactivo', $nodeId);
            $this->db->update('adm_examen_reactivos', array('id_plan' => $targetNode));
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

    //Obtiene ramas y hojas del banco de reactivos
    function get_dataNodos($id = 0, $rol, $id_usuario) {
        // $this->output->enable_profiler(true);
        // $arrayId=array(2568,2559,2563,2556,2690,2796,2825);
        $id = $id * 1;
        $this->db->select("`pla_id` as id,`pla_nombre` as name,'true' as isParent ", false);
        $this->db->from('adm_plan');
        $this->db->where('pla_padre', $id);
        ($rol != 1) ? $this->db->where('pla_usuario', $id_usuario) : '';

        // $this->db->where_not_in('pla_id', $arrayId);
        $ramas = $this->db->get()->result_array();
        //obtener hojas (Reactivos)
        //die($this->db->last_query());
        $this->db->select("rea_id as id, if(`rea_clave`='',concat('~REA-',rea_id),concat('~',`rea_clave`)) as name, 'false' as isParent ", false);
        $this->db->from('adm_reactivo');
        $this->db->where('rea_plan', $id);
        $this->db->order_by("replace(name,'~REA-','')+0,replace(name,'~','')+0");
        $hojas = $this->db->get()->result_array();
        return array_merge($ramas, $hojas);
    }

    //Obtiene ramas y hojas del examen
    function get_dataNodosExamen($id = 0, $idExamen = 0) {
        //die($this->db->last_query());  
        //$this->output->enable_profiler(true);
        $id = $id * 1;
        $idExamen = $idExamen * 1;
        //$this->db->distinct();
        $this->db->select("`id_plan` as id,`plan_nombre` as name,'true' as isParent ", false);
        $this->db->from('adm_examen_plan');
        //$this->db->join('adm_examen_reactivos', 'adm_examen_reactivos.id_reactivo = adm_reactivo.rea_id');
        $this->db->where('plan_padre', $id);
        $this->db->where('id_examen', $idExamen);
        $this->db->order_by('pla_orden asc');
        $ramas = $this->db->get()->result_array();

        //obtener hojas (Reactivos)
        $this->db->select("rea_id as id, if(`rea_clave`='',concat('~REA-',rea_id),concat('~',`rea_clave`)) as name, 'false' as isParent ", false);
        $this->db->from('adm_reactivo');
        $this->db->join('adm_examen_reactivos', 'adm_examen_reactivos.id_reactivo = adm_reactivo.rea_id');
        $this->db->where('id_plan', $id);
        $this->db->where('adm_examen_reactivos.id_examen', $idExamen);
        $this->db->order_by("replace(name,'~REA-','')+0,replace(name,'~','')+0");
        $hojas = $this->db->get()->result_array();
        return array_merge($ramas, $hojas);
    }

    //Obtiene los reactivos del examen para guardarlos en un csv
    function get_ReactivosExamen($id = 0) {
        $id = $id * 1;
        $this->db->distinct();
        $this->db->select("rea_id as id, if(`rea_clave`='',concat('REA-',rea_id),`rea_clave`) as clave, CONVERT(rea_contenido USING utf8) as contenido,rea_tiporeactivo,rea_respuestacorrecta as respuesta, IFNULL( rea_caso,'sin caso') as caso_reactivo, rea_plan as plan,rea_puntos");
        $this->db->from('adm_reactivo');
        $this->db->join('adm_examen_reactivos', 'adm_examen_reactivos.id_reactivo = adm_reactivo.rea_id');
        $this->db->join('adm_examen_plan', 'adm_examen_reactivos.id_plan = adm_examen_plan.id_plan','left');
        $this->db->where('adm_examen_reactivos.id_examen', $id);
        // $this->db->order_by("replace(clave,'REA-','')+0");
        $this->db->order_by("adm_examen_plan.pla_orden");
        $this->db->order_by("rea_id");
       
        $reactivos = $this->db->get()->result_array();
         //die($this->db->last_query());
        return $reactivos;
    }

    //Obtiene los planes de los reactivos del examen para guardarlos en un csv
    function getPlanesReactivos($id = 0) {
        $id = $id * 1;
        $this->db->distinct();
        $this->db->select("pla_id as id, CONVERT(adm_examen_plan.plan_nombre USING utf8) as nombre, if(`pla_tipo`='',concat('sin tipo'),`pla_tipo`) as tipo,adm_examen_plan.pla_orden as pla_orden");
        $this->db->from('adm_plan');
        $this->db->join('adm_reactivo', 'adm_plan.pla_id = adm_reactivo.rea_plan');
        $this->db->join('adm_examen_reactivos', 'adm_reactivo.rea_id = adm_examen_reactivos.id_reactivo');
        $this->db->join('adm_examen_plan', 'adm_examen_reactivos.id_plan = adm_examen_plan.id_plan');
        $this->db->where('adm_examen_reactivos.id_examen', $id);
        $this->db->order_by("adm_examen_plan.pla_orden");
        $reactivos = $this->db->get()->result_array();
        return $reactivos;
    }

    //Obtiene las opciones de respuesta del reactivo de la tabla adm_opcion 11636
    function getOpcionesReactivos($id = 0) {
        //$this->output->enable_profiler(true);
        $id = $id * 1;
        $this->db->select("opc_id as id, CONVERT(opc_contenido USING utf8) as contenido , opc_reactivo as opcion_reactivo, opc_escorrecta as es_correcta, opc_tipo as tipo");
        $this->db->from('adm_opcion');
        $this->db->join('adm_reactivo', 'adm_reactivo.rea_id = adm_opcion.opc_reactivo');
        $this->db->join('adm_examen_reactivos', 'adm_reactivo.rea_id = adm_examen_reactivos.id_reactivo');
        $this->db->where('adm_examen_reactivos.id_examen', $id);
        //$this->db->order_by('opc_reactivo', "ASC");
        
        $opciones = $this->db->get()->result_array();
        return $opciones;
    }

    function getCasosReactivos($id = 0) {
        $id = $id * 1;
        return $this->db->query("SELECT distinct cas_instruccion as instruccion, cas_id AS id_caso, CONVERT(cas_titulo USING utf8) as titulo_caso, CONVERT(cas_contenido USING utf8) AS contenido_caso  FROM `adm_caso` 
                  JOIN adm_reactivo ON adm_caso.cas_id=adm_reactivo.rea_caso
                   WHERE EXISTS( SELECT * FROM adm_examen_reactivos WHERE adm_reactivo.rea_id=adm_examen_reactivos.id_reactivo AND adm_examen_reactivos.id_examen=" . $id . ")")->result_array();
    }

    //Agrega datos de un nuevo examen
    function addExamen($datos) {
        $this->db->trans_begin();
        $this->db->insert('adm_examenes', $datos);
        $insertid = $this->db->insert_id();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $out = FALSE;
        } else {
            $this->db->trans_commit();
            $out = $insertid;
        }
        return $out;
    }

    //Busca datos del examen para mostrarlos en los input de la vista editar
    function getDatoRow($id = 0) {
        $id = $id * 1;
        $this->db->select('exa_clave as clave, exa_nombre as nombre, exa_num_reactivos as totalReactivos');
        $this->db->from('adm_examenes');
        $this->db->where('exa_id', $id);
        $this->db->limit(1);
        $resul = $this->db->get();
        return $resul;
    }

    /**
     * @brief Asocia un reactivo con un examen y actualiza el total de reactivos del examen
     * @param array $data_exam_react id del reactivo, id del plan y id del examen array $totalReact total de reactivos del examen
     * @return boolean Retorna verdadero o falso dependiendo si se realizo la transacción correctamente** */
    function addReactivosExam($data_exam_react, $totalReact) {
        $this->db->trans_begin();
        $out = FALSE;
        $this->db->insert('adm_examen_reactivos', $data_exam_react);

        $this->db->where('exa_id', $data_exam_react['id_examen']);
        $this->db->update('adm_examenes', $totalReact);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            $out = TRUE;
        }
        return $out;
    }

    //Actualiza los datos del examen clave y nombre
    function updateExam($data, $id = 0) {
        $id = $id * 1;
        $result = FALSE;
        $this->db->trans_begin();
        $this->db->where('exa_id', $id);
        $this->db->update('adm_examenes', $data);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }

    //Eliminar datos del examen clave, nombre... Reactivos asociados y planes
    function get_elimina($id = 0) {
        $id = $id * 1;
        $result = FALSE;
        $this->db->trans_begin();
        $this->db->where('id_examen', $id);
        $this->db->delete('adm_examen_plan');

        $this->db->where('id_examen', $id);
        $this->db->delete('adm_examen_reactivos');

        $this->db->where('exa_id', $id);
        $this->db->limit(1);
        $this->db->delete('adm_examenes');
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            $result = TRUE;
        }
        return $result;
    }

    //elimina reactivo asociado con un examen y actualiza el total de reactivos del examen
    function deleteReactivoExamen($data, $totalReact) {
        $out = FALSE;
        //$this->output->enable_profiler(true);
        $this->db->trans_begin();
        $this->db->limit(1);
        $this->db->where('id_reactivo', $data['id_reactivo']);
        $this->db->where('id_examen', $data['id_examen']);
        $this->db->delete('adm_examen_reactivos');


        $this->db->where('exa_id', $data['id_examen']);
        $this->db->update('adm_examenes', $totalReact);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            $out = TRUE;
        }
        return $out;
    }

    function getIdsPadresReactivos($idPlan) {
        if ($idPlan != 0) {
            $this->db->select('id_plan,plan_padre');
            $this->db->from('adm_examen_plan');
            $this->db->where('plan_padre', $idPlan);
            $resul = $this->db->get()->result_array();
            foreach ($resul as $value) {
                array_push($this->arrayIds, $value['id_plan']);
                $this->getIdsPadresReactivos($value['id_plan']);
            }
        }
        return $this->arrayIds;
    }

    //Elimina los reactivos que pertenecen a una sección cuando se elimina una seccion completa del examen
    function deleteAllReactivos($id = 0, $idExam = 0, $totReactivos) {
        //$this->output->enable_profiler(true);
        $id = $id * 1;
        $idExam = $idExam * 1;
        $this->arrayIds = array();
        $this->db->trans_begin();

        $idRamasPadresReactivos = $this->getIdsPadresReactivos($id);
        array_push($idRamasPadresReactivos, $id);

        $this->db->select("COUNT(id_plan) as count", false);
        $this->db->from('adm_examen_reactivos');
        $this->db->where_in('id_plan', $idRamasPadresReactivos);
        $eliminados = $this->db->get()->row()->count;

        $this->db->where_in('id_plan', $idRamasPadresReactivos);
        $this->db->where('id_examen', $idExam);
        $this->db->delete('adm_examen_reactivos');

        $this->db->where_in('id_plan', $idRamasPadresReactivos);
        $this->db->where('id_examen', $idExam);

        $this->db->delete('adm_examen_plan');

        $reactivos['exa_num_reactivos'] = $totReactivos - $eliminados;
        $this->db->where('exa_id', $idExam);
        $this->db->update('adm_examenes', $reactivos);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return $reactivos;
        }
    }

    //agregar sección raía (plan)
    function get_addNodo($pid, $nom, $usu, $idExam) {
        $r = FALSE;
        $this->db->trans_begin();
        $data = array(
            'plan_padre' => $pid,
            'plan_nombre' => $nom,
            'plan_fechaalta' => date('Y-m-d'),
            'plan_usuario' => $usu,
            'id_examen' => $idExam
        );
        $this->db->insert('adm_examen_plan', $data);
        $insert_id = $this->db->insert_id();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $r = false;
        } else {
            $this->db->trans_commit();
            $r = $insert_id;
        }
        return $r;
    }

    //Modifica el nombre del nodo padre (carpetita)
    function get_editNodoName($id = 0, $nom) {
        $id = $id * 1;
        $this->db->trans_begin();
        $data = array(
            'plan_nombre' => $nom,
            'pla_fechamodif' => date('Y-m-d')
        );
        $this->db->where('id_plan', $id);
        $this->db->limit(1);
        $this->db->update('adm_examen_plan', $data);
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

    function getRamasFull($idPlan) {
        if ($idPlan != 0) {
            $this->db->select('pla_id,pla_padre,pla_nombre');
            $this->db->from('adm_plan');
            $this->db->where('pla_padre', $idPlan);
            $resul = $this->db->get()->result_array();
            foreach ($resul as $value) {
                array_push($this->arrayRamasFull, $value['pla_id']);
                $this->getRamasFull($value['pla_id']);
            }
        }
        return $this->arrayRamasFull;
    }

    function addReactivosToNodo($data, $totReactivos) {
        $count = 0;
        $out = array();
        $this->db->trans_begin();
        //$idRamas = $this->getRamasFull($data['id_plan_origen']);

        $this->db->select("rea_id as id_reactivo, rea_clave as clave_id", false);
        $this->db->from('adm_reactivo');
        $this->db->where('rea_plan', $data['id_plan_origen']);
        $query = $this->db->get();

        $this->db->query("insert into adm_examen_plan (plan_padre, plan_nombre, plan_fechaalta, id_examen,id_tbl_adm_plan) values (" . $data['id_plan_destino'] . ",'" . $data['plan_nombre_origen'] . "'," . "'" . date('Y-m-d') . "'," . $data['id_examen'] . "," . $data['id_plan_origen'] . ");");
        $insert_id_plan = $this->db->insert_id();
        /* foreach ($idRamas as $valId) {
          $this->db->select("rea_id as id_reactivo, rea_clave as clave_id", false);
          $this->db->from('adm_reactivo');
          $this->db->where('rea_plan', $valId);
          $query = $this->db->get();

          foreach ($query->result() as $row) {
          $count++;
          $this->db->query("insert into adm_examen_reactivos (id_examen, id_reactivo, id_plan) values (" . $data['id_examen'] . "," . $row->id_reactivo . "," . $insert_id_plan . ");");
          }
          } */

        foreach ($query->result() as $row) {
            $count++;
            $this->db->query("insert into adm_examen_reactivos (id_examen, id_reactivo, id_plan) values (" . $data['id_examen'] . "," . $row->id_reactivo . "," . $insert_id_plan . ");");
        }

        $totReactivos += $count;
        $this->db->query("update adm_examenes set exa_num_reactivos=" . $totReactivos . " where exa_id=" . $data['id_examen'] . ";");
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $out['ok'] = "no";
        } else {
            $this->db->trans_commit();
            $out['ok'] = "ok";
            $out['totReact'] = $totReactivos;
        }
        return $out;
    }

    function getRamasOrdenar($id = 0) {
        $id = $id * 1;
        $this->db->distinct();
        $this->db->select("id_plan as id, plan_nombre as nombre");
        $this->db->from('adm_examen_plan');
        $this->db->where('id_examen', $id);
        $this->db->where('plan_padre!=', '0');
        $this->db->order_by("pla_orden");
        $reactivos = $this->db->get()->result_array();
        return $reactivos;
    }

    function saveOrdenRamas($arrayRamas, $idExamen) {
        foreach ($arrayRamas as $value) {
            $data['pla_orden'] = $value['orden'];
            $this->db->where('id_plan', $value['idRama']);
            $this->db->where('id_examen', $idExamen);
            $this->db->update('adm_examen_plan', $data);
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $out['ok'] = "no";
        } else {
            $this->db->trans_commit();
            $out['ok'] = "ok";
        }
        return $out;
    }

    function getIdPlan($plan) {
        return $this->db->query("select pla_id,pla_nombre from adm_plan where pla_nombre='$plan' and pla_id in(2760,2761,2762,2763,2764,2765,2766,2767,2768,2770,2771,2772,2773,2774,2775,2776,2777,2778,2779,2780,2781,2782,2783,2784,2785,2786,2787,2788,2789,2790,2791,2792,2793) limit 1;")->result_array();
    }

    function insertaReactivo($idPlan, $clave, $textoPregunta) {
        $arrayData = array();
        $arrayData['rea_clave'] = $clave;
        $arrayData['rea_contenido'] = $textoPregunta;
        $arrayData['rea_fechaalta'] = date('Y-m-d');
        $arrayData['rea_fechamodif'] = date('Y-m-d');
        $arrayData['rea_estado'] = "C";
        $arrayData['rea_tiporeactivo'] = 1;
        $arrayData['rea_plan'] = $idPlan;
        $arrayData['rea_usuarioalta'] = 1;
        $this->db->insert('adm_reactivo', $arrayData);
        $insert_id = $this->db->insert_id();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $r = false;
        } else {
            $this->db->trans_commit();
            $r = $insert_id;
        }
        return $r;
    }

    function guardaRespuestas($idRea, $textoOpc, $esCorrecta) {
        $data = array();
        $data['opc_contenido'] = $textoOpc;
        $data['opc_reactivo'] = $idRea;
        $data['opc_escorrecta'] = $esCorrecta;
        $data['opc_tipo'] = "txt";
        $this->db->insert('adm_opcion', $data);
        $insert_id = $this->db->insert_id();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $r = false;
        } else {
            $this->db->trans_commit();
            $r = true;
        }
        return $r;
    }

    /* function test($data, $totReactivos) {
      $this->output->enable_profiler(true);
      $count = 0;
      $out = array();
      $conitnuarBuscando=FALSE;
      $this->db->trans_begin();

      $this->db->select("pla_id as id_plan, pla_padre as plan_padre, pla_nombre as nombre_plan", false);
      $this->db->from('adm_plan');
      $this->db->where('pla_padre', $data['id_plan_origen']);
      $queryPlan = $this->db->get();

      $this->db->select("rea_id as id_reactivo, rea_clave as clave_id", false);
      $this->db->from('adm_reactivo');
      $this->db->where('rea_plan', $data['id_plan_origen']);
      $queryReactivos = $this->db->get();
      $data = $queryPlan->result_array();

      foreach ($data as $row) {
      $this->db->select("pla_id as id_plan, pla_padre as plan_padre, pla_nombre as nombre_plan", false);
      $this->db->from('adm_plan');
      $this->db->where('pla_padre', $row['id_plan']);
      $dataPlanHijos = $this->db->get()->result_array();
      if($dataPlanHijos){$conitnuarBuscando=TRUE;}
      print_r($dataPlanHijos);
      }

      // $this->db->query("insert into adm_examen_plan (plan_padre, plan_nombre, plan_fechaalta, id_examen) values (" . $data['id_plan_destino'] . ",'" . $data['plan_nombre_origen'] . "'," . "'" . date('Y-m-d') . "'," . $data['id_examen'] . ");");
      //$insert_id_plan = $this->db->insert_id();

      /* foreach ($query->result() as $row) {
      $count++;
      $this->db->query("insert into adm_examen_reactivos (id_examen, id_reactivo, id_plan) values (" . $data['id_examen'] . "," . $row->id_reactivo . "," . $insert_id_plan . ");");
      } */
    /* $totReactivos+=$count;
      $this->db->query("update adm_examenes set exa_num_reactivos=" . $totReactivos . " where exa_id=" . $data['id_examen'] . ";");
      if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $out['ok'] = "no";
      } else {
      $this->db->trans_commit();
      $out['ok'] = "ok";
      $out['totReact'] = $totReactivos;
      }
      return $out;
      } */
}
