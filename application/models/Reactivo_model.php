<?php

class Reactivo_model extends CI_Model
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->library('session');
        $this->load->library('tools');
        $this->load->database();
    }

    //-----OBTINE TODOS LOS TIPOS DE REACTIVOS EXISTENTE EN LA TABLA TIPO REACTIVO----
    function tiposReactivo()
    {
        $sql = "select trea_clave as clave, trea_nombre as nombre from adm_tiporeactivo  where trea_activo=1 order by trea_clave asc";
        $query = $this->db->query($sql);
        return $query;
    }

    function getPlanRea($idrea)
    {
        $sqlPlan = "SELECT rea_plan  FROM adm_reactivo WHERE rea_id=" . $idrea;
        return $this->db->query($sqlPlan);
    }

    function getCondPlan($idreaActual)
    {
        $condPlan = '';
        if ($idreaActual != 0) {
            $resPlan = $this->getPlanRea($idreaActual);
            $condPlan = " and rea_plan=" . $resPlan->row()->rea_plan;
        }
        return $condPlan;
    }

    function getSiguiente($idreaActual)
    {
        $planCond = $this->getCondPlan($idreaActual);
        $sql = "SELECT rea_id as idrea FROM `adm_reactivo` where rea_id>" . $idreaActual . $planCond . " limit 1";
        $result = $this->db->query($sql);
        $siguiente = 0;
        if ($result->num_rows() == 1) {
            $siguiente = $result->row()->idrea;
        }
        return $siguiente;
    }

    function getAnterior($idreaActual)
    {

        $planCond = $this->getCondPlan($idreaActual);
        $sql = "SELECT rea_id as idrea FROM `adm_reactivo` where rea_id<" . $idreaActual . $planCond . " ORDER BY idrea DESC limit 1";
        $result = $this->db->query($sql);
        $anterior = 0;
        if ($result->num_rows() == 1) {
            $anterior = $result->row()->idrea;
        }
        return $anterior;
    }

    //----OBTIENE LOS REGISTROS DE UNA CONSULTA OBTENIDO POR LA SIMILITUD DE UN CARACTER O PALABRA------
    function searchDatosReactivo($edo, $tiprea, $fecha1, $fecha2, $usu, $txt)
    {
        $this->db->select("rea_id as id,rea_clave as clv, rea_estado as est, rea_tiporeactivo as tip, rea_modocalif as cal, rea_fechaalta as fec, 
rea_caso as cas, pla_nombre as pla, rea_contenido as con, count(opc_clave) as opc", false);
        $this->db->from('adm_reactivo');
        $this->db->join('adm_plan', ' rea_plan=pla_id', 'left');
        $this->db->join('adm_opcion', 'opc_reactivo=rea_id', 'left');
        $this->db->join('adm_usuario', 'usu_id=rea_usuarioalta', 'left');
        $this->db->group_by('rea_id');
        if ($edo != '') {
            $this->db->where('rea_estado', $edo);
        }
        if ($tiprea != '') {
            $this->db->where('rea_tiporeactivo', $tiprea);
        }
        if (($fecha1 != '') && ($fecha2 != '')) {
            $this->db->where('rea_fechaalta between "' . $fecha1 . '" and "' . $fecha2 . '"');
        }
        if ($usu != '') {
            $this->db->like('usu_nombre', $usu);
        }
        if ($txt != '') {
            $this->db->like('rea_contenido', $txt);
        }
        return $this->db->get();
    }

    function getDatosReactivo($id)
    {
        $sql = "select rea_id id, rea_clave as cla, rea_contenido as con, rea_estado as est, rea_plan as pid, pla_nombre as pnom, rea_tiporeactivo as tip, rea_modocalif as mcl, rea_caso as cid, rea_libro as lid, rea_autor as aid, rea_comentariovalidacion as com, rea_fechavalidacion as fva, usu_nombre as uva, rea_habilidad,rea_txthabilidad,rea_idpadrehab,rea_txtpadrehab,rea_id_materia,rea_texto_materia,rea_id_competencia,rea_texto_competencia,rea_id_bloque,rea_texto_bloque, rea_puntos puntos
from adm_reactivo
left join  `adm_plan` on  `pla_id` =  `rea_plan` 
left join adm_usuario on usu_id =  `rea_usuariovalido` 
where adm_reactivo.rea_id =$id";
        return $this->db->query($sql);
    }

    //-----------VERIFICA SI EXISTE REACTIVO---------------
    /**
     * verifica si existe reactivo
     * @param int $clave, identificador del reactivo
     * @return int $existe, numero de registros existente en la tabla.
     */
    function existeReactivo($clave)
    {
        return $this->db->query("select rea_clave from adm_reactivo where rea_id=$clave")->num_rows();
    }

    //-----------NUMERO DE OPCIONES DEL REACTIVO---------------
    /**
     * regresa numero de opciones
     * @param int $clave, identificador del reactivo
     * @return int $num, numero de registros de opciones que tiene el reactivo.
     */
    function opcionesReactivo($clave)
    {
        return $this->db->query("select opc_clave from adm_opcion where opc_reactivo=$clave")->num_rows();
    }

    //-----------NUMERO DE OPCIONES 1 DEL REACTIVO---------------
    /**
     * regresa numero de opciones
     * @param int $clave, identificador del reactivo
     * @return int $num, numero de registros de opciones que tiene el reactivo.
     */
    function opciones1Reactivo($clave)
    {
        return $this->db->query("select opc1_clave from adm_opcion1 left join adm_opcion onadm_opcion1.opc1_clave=adm_opcion.opc_clave where adm_opcion.opc_reactivo=$clave")->num_rows();
    }

    //-----------INSERTAR REGISTRO EN LA TABLA REACTIVO---------------
    /**
     * inserta nuevo reagistro en tabla de reactivo
     * @param array $datos, datos del reactivo..
     * @return $idinsert o False, $idinsert: insertado exitoso, False: error al insertar.
     */
    function insertarReactivo($datos, $datos_notas)
    {
        $this->db->trans_begin();
        $this->db->insert('adm_reactivo', $datos);
        $insertid = $this->db->insert_id();
        if ($datos_notas["nota_descripcion"] != '') {
            $datos_notas["nota_id_reactivo"] = $insertid;
            $this->db->insert('adm_notas_ractivo', $datos_notas);
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $out = FALSE;
        } else {
            $this->db->trans_commit();
            $out = $insertid;
        }
        return $out;
    }

    //-----------IMODIFICA REGISTRO EN LA TABLA REACTIVO---------------
    /**
     * actializa reactivo en tabla reactivo
     * @param int $clave, identificador del reactivo.
     * @param array $datos, datos del reactivo.
     * @return True: modificado exitoso, false: error al modificar.
     */
    function modificaReactivo($clave, $datos, $datos_notas)
    {
        $out = FALSE;
        $this->db->trans_begin();
        if ($clave != '' && (($clave * 1) != 0)) {
            $this->db->where('rea_id', $clave);
            $this->db->limit(1);
            $this->db->update('adm_reactivo', $datos);

            if ($datos_notas["nota_descripcion"] != '') {
                $query = "INSERT INTO adm_notas_ractivo (nota_id_reactivo, nota_descripcion) VALUES (" . $clave . ",'" . $datos_notas["nota_descripcion"] . "' )
     ON DUPLICATE KEY UPDATE nota_descripcion = '" . $datos_notas["nota_descripcion"] . "'";
                $this->db->query($query);
            }
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

    //-----------ELIMINA REGISTRO DE LA TABLA REACTIVO
    /**
     * elimina un reactivo
     * @param int $id, identificador del reactivo.
     * @return 1 o 2, 1: insertado exitoso, 2: error al insertar.
     */
    function deleteDatosReactivo($id)
    {
        $this->db->delete('reactivos', array('rea_clave' => $id));
        return 1;
    }

    function get_datosAutor($id)
    {
        $this->db->select("`usu_id` as id, `usu_login` as log, `usu_nombre` as nom, `usu_telefono` as tel, `usu_correo` as ema", false);
        $this->db->where('usu_id', $id);
        return $this->db->get("adm_usuario");
    }

    function searchDatosAutor($texto)
    {
        $sql = "select  `usu_id` as id,  `usu_login` as log, usu_nombre as nom,  `usu_telefono` as tel,  `usu_correo` as ema
from  `adm_usuario` 
where usu_nombre like  '%$texto%'
or usu_correo like  '%$texto%'
or usu_login like  '%$texto%'";
        return $this->db->query($sql);
    }

    function get_datosReferencia($id)
    {
        $this->db->select("`ref_id` as id,  `ref_titulo` as tit,  `ref_editorial` as edi,  `ref_autores` as aut,  `ref_descripcion` as des", false);
        $this->db->where('ref_id', $id);
        return $this->db->get("adm_referencia");
    }

    function searchDatosReferencia($texto)
    {
        $sql = "select  `ref_id` as id,  `ref_titulo` as tit,  `ref_editorial` as edi,  `ref_autores` as aut,  `ref_descripcion` as des
from  `adm_referencia` 
where ref_titulo like  '%$texto%'
or ref_editorial like  '%$texto%'
or ref_autores like  '%$texto%'";
        return $this->db->query($sql);
    }

    function get_agregaReferencia($datos)
    {
        $this->db->trans_begin();
        $this->db->insert('adm_referencia', $datos);
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

    ////////////////// Funciones para borrar reactivo /////////////////////
    private function getRutas($html)
    {
        $outArray = array();
        $dom = new DOMDocument;
        $dom->loadHTML($html);
        $imgs = $dom->getElementsByTagName('img');
        foreach ($imgs as $imgTag) {
            $src = $imgTag->attributes->getNamedItem("src")->value;
            if ($src != '') {
                array_push($outArray, $src);
            }
        }
        return $outArray;
    }

    /**
     * @brief Obtiene un arreglio con las urls de las imagenes de las opciones de respuesta
     * @param int $id Identificador del reactivo
     * @return array Arreglo con las urls de las imagenes
     * @note Esto solo funciona para imagenes
     * @example $arrayUris = $this->get_urlOpcRespuestas($someId);
     * * */
    private function get_urlOpcRespuestas($id)
    {
        $mediaOpcionesArray = array();
        $this->db->select('opc_id as id_opcion, opc_contenido as contenido_opcion');
        $this->db->from('adm_opcion');
        $this->db->where('opc_reactivo', $id);
        $resulOpciones = $this->db->get()->result_array();
        foreach ($resulOpciones as $row) {
            $content_opc = $row['contenido_opcion'];
            $rutasOpcion = $this->getRutas($content_opc);
            foreach ($rutasOpcion as $urlOpc) {
                if (is_file($urlOpc) && !$this->urlReaInOtherPlace($urlOpc, $id)) {
                    array_push($mediaOpcionesArray, $urlOpc);
                }
            }
        }
        return $mediaOpcionesArray;
    }

    private function urlReaInOtherPlace($url, $reaToDeleteId)
    {
        $coincidences = $this->db->query("select * from(
	(SELECT count(*) as countCoincidencesOptions FROM `adm_reactivo` WHERE rea_contenido like '%" . $url . "%' and rea_id!=" . $reaToDeleteId . ") as countCoincidencesOptions,
    (SELECT count(*) as countCoincidencesRea FROM `adm_opcion` WHERE opc_contenido like '%" . $url . "%' and opc_reactivo!=" . $reaToDeleteId . ") as countCoincidencesRea
    )")->row();
        if (($coincidences->countCoincidencesOptions * 1 === 0) && ($coincidences->countCoincidencesRea * 1 === 0)) {
            return FALSE;
        }
        return TRUE;
    }

    private function get_urlDataReactivo($id)
    {
        $mediaReactivosArray = array();
        $this->db->select('rea_id as id_reactivo, rea_contenido as contenido_reactivo');
        $this->db->from('adm_reactivo');
        $this->db->where('rea_id', $id);
        $resulReactivo = $this->db->get()->result_array();

        foreach ($resulReactivo as $row) {
            $content_reactivo = $row['contenido_reactivo'];
            $rutasReactivos = $this->getRutas($content_reactivo);
            foreach ($rutasReactivos as $urlRea) {
                if (is_file($urlRea) && !$this->urlReaInOtherPlace($urlRea, $id)) {
                    array_push($mediaReactivosArray, $urlRea);
                }
            }
        }
        return $mediaReactivosArray;
    }

    function deleteReactivos($idReact)
    {
        $delete_ok = FALSE;
        //$this->output->enable_profiler(true);
        if (($idReact * 1) != 0) {
            $madiaOpciones = $this->get_urlOpcRespuestas($idReact);
            $mediaReactivos = $this->get_urlDataReactivo($idReact);
      
            $this->db->trans_begin();
            $this->db->where('opc_reactivo', $idReact);
            $this->db->delete('adm_opcion');

            $this->db->where('rea_id', $idReact);
            $this->db->limit(1);
            $this->db->delete('adm_reactivo');

            
           $data=$this->tools->getDatosLog("Eliminó un reactivo con el id ".$idReact);

            $this->db->insert('adm_logs', $data);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                $this->deleteMediaFiles($mediaReactivos);
                $this->deleteMediaFiles($madiaOpciones);
                $delete_ok = TRUE;
            }
        }
        return $delete_ok;
    }

    private function deleteMediaFiles($dataMediaArray)
    {
        if (is_array($dataMediaArray)) {
            foreach ($dataMediaArray as $url) {
                unlink($url);
            }
        }
    }

    function copyReactivo($idNodoACopiar, $idPlan, $user_id)
    {
        $this->db->trans_begin();
        $this->db->select("rea_clave, rea_contenido, rea_modocalif, rea_estado, rea_tiporeactivo, rea_libro, rea_autor, rea_caso, rea_usuariovalido, rea_fechavalidacion, rea_comentariovalidacion, rea_respuestacorrecta", false);
        $this->db->from('adm_reactivo');
        $this->db->where('rea_id', $idNodoACopiar);
        $this->db->limit(1);
        $query = $this->db->get()->row();
        $sql = "insert into adm_reactivo (rea_clave, rea_contenido, rea_modocalif, rea_fechaalta, rea_fechamodif,rea_estado, rea_tiporeactivo, rea_plan, rea_libro, rea_autor, rea_caso,rea_usuariovalido,rea_fechavalidacion,rea_comentariovalidacion,rea_usuarioalta,rea_usuariomodif,rea_respuestacorrecta) values ('" . $query->rea_clave . "','" . $query->rea_contenido . "','" . $query->rea_modocalif . "','" . date('Y-m-d') . "','0000-00-00','" . $query->rea_estado . "','" . $query->rea_tiporeactivo . "','" . $idPlan . "','" . $query->rea_libro . "','" . $query->rea_autor . "','" . $query->rea_caso . "','" . $query->rea_usuariovalido . "','" . $query->rea_fechavalidacion . "','" . $query->rea_comentariovalidacion . "','" . $user_id . "','-','" . $query->rea_respuestacorrecta . "');";
        $this->db->query($sql);
        $insert_id_copia = $this->db->insert_id();

        $sqlOpciones = "SELECT opc_clave as clave, opc_contenido as contenido, opc_correcta as correcta, opc_imagen as imagen, opc_audio as audio, opc_video as video, opc_escorrecta as escorrecta, opc_tipo as tipo FROM `adm_opcion` where opc_reactivo=" . $idNodoACopiar;
        $opciones = $this->db->query($sqlOpciones);
        foreach ($opciones->result() as $row) {
            $this->db->query("insert into adm_opcion (opc_clave, opc_contenido, opc_correcta, opc_imagen, opc_audio, opc_video, opc_reactivo, opc_escorrecta, opc_tipo) values ('" . $row->clave . "','" . $row->contenido . "','" . $row->correcta . "','" . $row->imagen . "','" . $row->audio . "','" . $row->video . "','" . $insert_id_copia . "','" . $row->escorrecta . "','" . $row->tipo . "');");
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

    ////////////////// Fin Funciones para borrar reactivo /////////////////////
    /*     * *** Funcion para obtener en que examen se ocupo el reactivo* **** */
    function getExamenReactivo($idReactivo = 0)
    {
        if ($idReactivo != 0) {
            $this->db->select('exa_nombre');
            $this->db->from('adm_examenes');
            $this->db->join('adm_examen_reactivos', 'exa_id=id_examen');
            $this->db->where('id_reactivo', $idReactivo);
            return $this->db->get()->result_array();
        }
    }

    function getNotasReactivo($id = 0)
    {
        if ($id != 0) {
            $this->db->select('nota_id_reactivo,COALESCE(nota_descripcion,"") as notas');
            $this->db->from('adm_notas_ractivo');
            $this->db->where('nota_id_reactivo', $id);
            return $this->db->get();
        }
    }
    private $arrayArbol = array();
    function getHabilidades($id = 0, $hab_padre = 0)
    {
        if ($id != 0) {
            $this->db->where('rea_id', $id);
            $this->db->limit(1);
            $habilidadRea = $this->db->get('adm_reactivo')->result_array();
        }

        $nodos = $this->db->get('adm_habilidades')->result_array();
        $json = '';
        $ban_selected = "false";
        $parent = "";
        foreach ($nodos as $value_nodo) {
            if ($id != 0) {
                $ban_selected = ($habilidadRea[0]['rea_idpadrehab'] == $value_nodo['hab_id']) ? "true" : 'false';
            }

            if ($value_nodo['hab_espadre'] == 1 && $value_nodo['hab_padre'] == 0) {
                $parent = '"parent":"#","state":{"disabled":true,"opened": ' . $ban_selected . '}';
            } else {
                $disabled = ($value_nodo['hab_espadre'] == 1) ? 'true' : 'false';
                ($id != 0) ? $ban_selected = ($habilidadRea[0]['rea_habilidad'] == $value_nodo['hab_id']) ? "true" : 'false' : '';
                $parent = '"parent":"' . $value_nodo['hab_padre'] . '","state":{"disabled":' . $disabled . ',"opened":' . $ban_selected . ',"selected":' . $ban_selected . '}';
            }
            $json .= '{ "id" :"' . $value_nodo['hab_id'] . '" , ' . $parent  . ', "text" : "' . $value_nodo['hab_nombre'] . '" },';
        }
        unset($ban_selected);
        $json = trim($json, ',');
        return $json;
    }

    function get_hijos($idPadre, $es_padre = 0)
    {
        if ($es_padre != 0) {
            $this->db->select('hab_id,hb_subhab,hab_nombre');
            $this->db->from('adm_habilidades');
            $this->db->where('hab_padre', $idPadre);
            array_push($this->arrayArbol, $this->db->get()->result_array());
        }
        return $this->arrayArbol;
    }

    function getMaterias()
    {

        return $this->db->get('materias_semestres')->result_array();
    }
    function getCompetenciasMateria($idMateria = 0)
    {
        if ($idMateria != 0) {
            $this->db->where('comp_mat_id_materia', $idMateria);
        }
        return $this->db->get('competencias_materias')->result_array();
    }
    function getBloquesCompetencia($idCompetencia = 0)
    {
        if ($idCompetencia != 0) {
            $this->db->where('bloque_comp_id_competencia', $idCompetencia);
        }
        $this->db->group_by(array("bloque_comp_nombre", "bloque_comp_proposito", "bloque_comp_id_competencia", "bloque_comp_nombre_competencia", "bloque_comp_id_materia"));
        $this->db->order_by('bloque_comp_id');
        return $this->db->get('bloques_competencias')->result_array();
    }
    function getConocimientoBloque($idBloque = 0)
    {
        if ($idBloque != 0) {

            $this->db->where('con_hab_id_bloque_comp', $idBloque);
        }
        $this->db->limit(1);
        return $this->db->get('conocimientos_hab_bloques_comp')->result_array();
    }
    function permisoVerReactivo($idRol, $idPlan)
    {
        $tienePermiso = false;
        $this->db->where('rp_rol_id', $idRol);
        $this->db->where('rp_plan_id', $idPlan);
        $rows = $this->db->get('roles_planes');
        if ($rows->num_rows() >= 1) {
            $tienePermiso = true;
        }
        return $tienePermiso;
    }
}
