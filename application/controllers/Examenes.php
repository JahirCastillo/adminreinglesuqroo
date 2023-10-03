<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Examenes
 *
 * @author yahir
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Examenes extends CI_Controller
{

    private $clave_modulo = 'EXA';
    private $clv_sess = '';

    function __construct()
    {
        parent::__construct();
        $this->clv_sess = $this->config->item('clv_sess');
        $user_id = $this->session->userdata('user_id' . $this->clv_sess);
        if (!$user_id) {
            redirect('acceso');
        }
        $this->load->model('examenes_model');
        $this->load->library('zip');
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $this->load->helper('process');
    }

    public function index()
    {
        //$this->output->enable_profiler(true);
        $this->load->model('acceso_model');
        $user_id = $this->session->userdata('user_id' . $this->clv_sess);
        $permisos = $this->ci_acl_framew->get_parse_array_permisos($this->acceso_model->get_permisosUsuario($user_id, $this->clave_modulo));
        $datos_vista = array();

        if (array_key_exists($this->clave_modulo, $permisos)) {
            $datos_vista['permisos_modulo'] = $permisos[$this->clave_modulo];
        }
        //datos modulo
        $data_modulo = $this->acceso_model->get_iconModulo($this->clave_modulo);
        $datos_plantilla['title_mod'] = $data_modulo['icon'] . ' ' . $data_modulo['nombre'];
        $datos_plantilla['modulos'] = $this->acceso_model->get_modulos();
        $datos_plantilla['permisos'] = $permisos;
        $datos_plantilla['navigate_mod'] = '<li><a onclick="redirect_to(\'inicio\')"><i class="fa fa-th"></i> Menú</a></li> <li><a class="active"> ' . $data_modulo['icon'] . ' ' . $data_modulo['nombre'] . '</a></li>';
        $datos_plantilla['content'] = $this->load->view('examenes/examenes_index_view', $datos_vista, true);
        $this->load->view('template', $datos_plantilla);
    }

    //Obtiene los datos del examen para mostrarlos en la tabla
    public function get_datos()
    {
        $this->load->model('acceso_model');
        $user_id = $this->session->userdata('user_id' . $this->clv_sess);
        $permisos = $this->ci_acl_framew->get_parse_array_permisos($this->acceso_model->get_permisosUsuario($user_id, $this->clave_modulo));
        if (array_key_exists($this->clave_modulo, $permisos)) {
            $permisos_modulo = $permisos[$this->clave_modulo];
        }

        $login = $this->session->userdata('login' . $this->clv_sess);
        $rol = $this->session->userdata('id_rol' . $this->clv_sess);
        /* if (!$login) {
          redirect('acceso/acceso_denegado');
          } */
        $this->load->model('generico_model');
        $roles = $this->config->item('roles');
        $sIndexColumn = "exa_id";
        $aColumns = array($sIndexColumn, 'exa_clave', 'exa_nombre', 'exa_num_reactivos', 'exa_fecha_add');
        $sTable = "adm_examenes";

        /* Generar limits con paginacion */
        $sLimit = "";
        $iDisplayStart = $this->input->post('iDisplayStart');
        $iDisplayLength = $this->input->post('iDisplayLength');
        if (isset($iDisplayStart) && $iDisplayLength != '-1') {
            $sLimit = "LIMIT " . $this->input->post('iDisplayStart') . ", " .
                $this->input->post('iDisplayLength');
        }
        /* order */
        $iSortCol_0 = $this->input->post('iSortCol_0');
        if (isset($iSortCol_0)) {
            $sOrder = "ORDER BY  ";
            for ($i = 0; $i < intval($this->input->post('iSortingCols')); $i++) {
                if ($this->input->post('bSortable_' . intval($this->input->post('iSortCol_' . $i))) == "true") {
                    $sOrder .= $aColumns[intval($this->input->post('iSortCol_' . $i))] . "
				 	" . $this->input->post('sSortDir_' . $i) . ", ";
                }
            }
            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = "";
            }
        }
        /* Generar limits con paginacion */
        $sWhere = "";
        if ($this->input->post('sSearch') != "") {
            $sWhere = "WHERE (";
            for ($i = 0; $i < count($aColumns); $i++) {
                $sWhere .= $aColumns[$i] . " LIKE '%" . $this->input->post('sSearch') . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }
        for ($i = 0; $i < count($aColumns); $i++) {
            if ($this->input->post('bSearchable_' . $i) == "true" && $this->input->post('sSearch_' . $i) != '') {
                if ($sWhere == "") {
                    $sWhere = "WHERE ";
                } else {
                    $sWhere .= " AND ";
                }
                $sWhere .= $aColumns[$i] . " LIKE '%" . $this->input->post('sSearch_' . $i) . "%' ";
            }
        }

        $rResult = $this->examenes_model->getExamenes($aColumns, $sTable, $sWhere, $sOrder, $sLimit, $rol, $user_id);
        $aResultFilterTotal = $this->generico_model->numFilasSQL()->row_array();
        $iFilteredTotal = $aResultFilterTotal['filas'];
        $aResultTotal = $this->generico_model->countResults($sIndexColumn, $sTable)->row_array();
        $iTotal = $aResultTotal['numreg'];

        $sOutput = '{';
        $sOutput .= '"sEcho": ' . intval($this->input->post('sEcho')) . ', ';
        $sOutput .= '"iTotalRecords": ' . $iTotal . ', ';
        $sOutput .= '"iTotalDisplayRecords": ' . $iFilteredTotal . ', ';
        $sOutput .= '"aaData": [ ';
        for ($x = 0; $x < $rResult->num_rows(); $x++) {

            $aRow = $rResult->row_array($x);
            $row = array();
            $row['DT_RowId'] = 'row_' . $aRow[$sIndexColumn];
            $row['DT_RowClass'] = 'gradeA';
            $sOutput .= "[";
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "contenido") {
                    try {
                        $contenSinTags = substr(str_replace("\r\n", " ", strip_tags($aRow[$aColumns[$i]])), 0, 150);
                        if ($contenSinTags == '' || $contenSinTags == ' ' || $contenSinTags == '&#65279; ') {
                            $contenSinTags = 'No hay vista previa';
                        }
                        //$contenSinTags='';
                        $sOutput .= '"' . str_replace('"', '\"', $contenSinTags) . '",';
                    } catch (Exception $e) {
                        $sOutput .= '"' . str_replace('"', '\"', $aRow[$aColumns[$i]]) . '",';
                    }
                    // } else if ($aColumns[$i] == "contenido") {   
                } else if ($aColumns[$i] != ' ') {
                    $sOutput .= '"' . str_replace('"', '\"', $aRow[$aColumns[$i]]) . '",';
                }
            }

            $upd = $del = $olr = '';
            if (isset($permisos_modulo) && in_array('olr', $permisos_modulo)) {
                $olr = "<button class='btn btn-info' title='Ver detalles del examen' onclick='contenidoExamen(" . $aRow[$sIndexColumn] . ")'><i class=' fa fa-list'></i></button>";
            }
            if (isset($permisos_modulo) && in_array('upd', $permisos_modulo)) {
                $upd = "<button class='btn btn-warning' title='Editar examen' onclick='modifica(" . $aRow[$sIndexColumn] . ")'><i class=' fa fa-edit'></i></button>";
            }
            if (isset($permisos_modulo) && in_array('del', $permisos_modulo)) {
                $del = "<button class='btn btn-danger' title='Eliminar examen' onclick='elimina(" . $aRow[$sIndexColumn] . ")'><i class=' fa fa-remove'></i></button>";
            }

            $sOutput .= '"' . str_replace('"', '\"', "<label class='switch'><input class='examenSeleccionado' type='checkbox'  name='' data-prefijo='" . $aRow['exa_clave'] . "' data-id='" . $aRow[$sIndexColumn] . "' value='" . $aRow[$sIndexColumn] . "'><span class='slider'></span></label>") . '",';
            $sOutput .= '"' . str_replace('"', '\"', $olr . $upd . $del) . '",';
            $sOutput = substr_replace($sOutput, "", -1);
            $sOutput .= "],";
        } //forn for
        $sOutput = substr_replace($sOutput, "", -1);
        $sOutput .= '] }';
        echo $sOutput;
    }

    //Asocia los reactivos con el examen cada vez que se suelta un reactivo en una seccion del contenedor de los reactivos y actualiza el numero de reactivos
    function newReactivosExam($idExamen = 0)
    {
        $idExamen = $idExamen * 1;
        //$this->output->enable_profiler(true);
        $data_exam['id_reactivo'] = $this->input->post('id_reactivo');
        $data_exam['id_examen'] = $idExamen;
        $data_exam['id_plan'] = $this->input->post('nodo');
        $totalReactivos['exa_num_reactivos'] = $this->input->post('numReact');
        $insert = $this->examenes_model->addReactivosExam($data_exam, $totalReactivos);
        if ($insert != FALSE) {
            $array_out['res'] = 'ok';
        } else {
            $array_out['res'] = 'no';
            $array_out['msg'] = 'Error al agregar el reactivo. Intenta de nuevo.';
        }
        echo json_encode($array_out);
    }

    //Agrega los datos clave, nombre del examen al agregarlo
    function crea_examen()
    {
        $out = array();
        $this->load->model('acceso_model');
        $user_id = $this->session->userdata('user_id' . $this->clv_sess);
        $permisos = $this->ci_acl_framew->get_parse_array_permisos($this->acceso_model->get_permisosUsuario($user_id, $this->clave_modulo));
        $datos_vista = array();
        if (array_key_exists($this->clave_modulo, $permisos)) {
            $datos_vista['permisos_modulo'] = $permisos[$this->clave_modulo];
        }
        $data['exa_clave'] = $this->input->post('clave');
        $data['exa_fecha_add'] = date('Y-m-d');
        $data['exa_nombre'] = $this->input->post('nombre');
        $data['exa_usu_id'] = $user_id;
        $id_insert = $this->examenes_model->addExamen($data);
        if ($id_insert != false) {
            $out['resp'] = 'ok';
            $out['id'] = $id_insert;
        } else {
            $out['resp'] = 'no';
            $out['msg'] = 'Error al agregar la referencia ';
        }
        echo json_encode($out);
    }

    //Completar examen redirecciona a la vista para arrastrar los reactivos
    function completar_examen($id = 0)
    {
        //$this->output->enable_profiler(true);
        $this->load->model('acceso_model');
        $user_id = $this->session->userdata('user_id' . $this->clv_sess);
        $permisos = $this->ci_acl_framew->get_parse_array_permisos($this->acceso_model->get_permisosUsuario($user_id, $this->clave_modulo));
        $datos_vista = array();
        if (array_key_exists($this->clave_modulo, $permisos)) {
            $datos_vista['permisos_modulo'] = $permisos[$this->clave_modulo];
        }
        $datos_vista['idExamen'] = $id;
        $data_modulo = $this->acceso_model->get_iconModulo($this->clave_modulo);
        $datos_plantilla['title_mod'] = $data_modulo['icon'] . ' ' . $data_modulo['nombre'] . ' <small> Agregar </small>';
        $datos_plantilla['modulos'] = $this->acceso_model->get_modulos();
        $datos_plantilla['permisos'] = $permisos;
        $datos_plantilla['navigate_mod'] = '<li><a onclick="redirect_to(\'inicio\')"><i class="fa fa-th"></i> Menú</a></li> <li><a onclick="redirect_to(\'examenes\')"> ' . $data_modulo['icon'] . ' ' . $data_modulo['nombre'] . '</a></li><li class="active"> Crear examen</li>';
        $datos_plantilla['content'] = $this->load->view('examenes/crear_examen_view', $datos_vista, true);
        $this->load->view('template', $datos_plantilla);
    }

    //Busca datos del examen clave. nombre, total de reactivos
    function getExamData()
    {
        $id = $this->input->post("id");
        $data = $this->examenes_model->getDatoRow($id)->row();
        echo json_encode($data);
    }

    function getArbolEdit($idExamen = 0)
    {
        //$this->output->enable_profiler(true);
        //die($this->db->last_query());
        $idExamen = $idExamen * 1;
        $pId = "0";
        $pName = "";
        $pLevel = "";
        $pCheck = "";
        if (array_key_exists('id', $_REQUEST)) {
            $pId = $_REQUEST['id'];
        }
        if (array_key_exists('lv', $_REQUEST)) {
            $pLevel = $_REQUEST['lv'];
        }
        if (array_key_exists('n', $_REQUEST)) {
            $pName = $_REQUEST['n'];
        }
        if (array_key_exists('chk', $_REQUEST)) {
            $pCheck = $_REQUEST['chk'];
        }
        if ($pId == null || $pId == "")
            $pId = "0";
        if ($pLevel == null || $pLevel == "")
            $pLevel = "0";
        if ($pName == null)
            $pName = "";
        else
            $pName = $pName . ".";

        $pId = htmlspecialchars($pId);
        $pName = htmlspecialchars($pName);
        $arbol = $this->examenes_model->get_dataNodosExamen($pId, $idExamen);

        $jsonOut = '[';

        foreach ($arbol as $n) {
            $jsonOut .= "{id:'" . $n['id'] . "',name:'" . $n['name'] . "',isParent:" . $n['isParent'] . "},";
        }
        $jsonOut = trim($jsonOut, ',');
        $jsonOut .= ']';
        echo $jsonOut;
    }

    function getArbol()
    {
        //$this->output->enable_profiler(true);    
        $pId = "0";
        $pName = "";
        $pLevel = "";
        $pCheck = "";
        $user_id = $this->session->userdata('user_id' . $this->clv_sess);
        $rol = $this->session->userdata('id_rol' . $this->clv_sess);
        if (array_key_exists('id', $_REQUEST)) {
            $pId = $_REQUEST['id'];
        }
        if (array_key_exists('lv', $_REQUEST)) {
            $pLevel = $_REQUEST['lv'];
        }
        if (array_key_exists('n', $_REQUEST)) {
            $pName = $_REQUEST['n'];
        }
        if (array_key_exists('chk', $_REQUEST)) {
            $pCheck = $_REQUEST['chk'];
        }
        if ($pId == null || $pId == "")
            $pId = "0";
        if ($pLevel == null || $pLevel == "")
            $pLevel = "0";
        if ($pName == null)
            $pName = "";
        else
            $pName = $pName . ".";

        $pId = htmlspecialchars($pId);
        $pName = htmlspecialchars($pName);
        $arbol = $this->examenes_model->get_dataNodos($pId, $rol, $user_id);
        $jsonOut = '[';
        foreach ($arbol as $n) {
            $jsonOut .= "{ id:'" . $n['id'] . "',	name:'" . $n['name'] . "',isParent:" . $n['isParent'] . "},";
        }
        $jsonOut = trim($jsonOut, ',');
        $jsonOut .= ']';

        echo $jsonOut;
    }

    function moveNodo($idExamen = 0)
    {
        $idExamen = $idExamen * 1;
        $array_out = array();
        $nodeId = $this->input->post('id') * 1;
        $targetNode = $this->input->post('pid') * 1;
        $typeNode = $this->input->post('type');
        $typeNodeTarget = $this->input->post('typeTar');
        $result = $this->examenes_model->get_moveNodo($nodeId, $targetNode, $typeNode, $typeNodeTarget, $idExamen);
        if ($result != FALSE) {
            $array_out['res'] = 'ok';
            $array_out['insert_id'] = $result;
        } else {
            $array_out['res'] = 'no';
            $array_out['msg'] = 'Error al mover el nodo. Intenta de nuevo.';
        }
        echo json_encode($array_out);
    }

    //Elimina el reactivo asociado con un examen      
    function deleteReactivoExam($id = 0)
    {
        $data['id_reactivo'] = $this->input->post("idReactivo");
        $data['id_examen'] = $id;
        $totalReact['exa_num_reactivos'] = $this->input->post('totalReactivos');
        if ($id != 0) {
            $sepudoDeleteReact = $this->examenes_model->deleteReactivoExamen($data, $totalReact);
            if ($sepudoDeleteReact) {
                $array_out['resp'] = 'ok';
            } else {
                $array_out['resp'] = 'no';
                $array_out['msg'] = "Se produjo un error al eliminar el reactivo del examen.";
            }
        } else {
            $array_out['resp'] = 'no';
            $array_out['msg'] = "Se produjo un error al eliminar el reactivo del examen.";
        }
        echo json_encode($array_out);
    }

    //Elimina el nodo con todos los reactivos que pertenecen a el
    function deleteNodo($idExamen = 0)
    {
        $array_out = array();
        if ($idExamen != 0) {
            $id = $this->input->post('id');
            $totalReactivos = $this->input->post('numReact');
            $deleteReactivos = $this->examenes_model->deleteAllReactivos($id, $idExamen, $totalReactivos);
            if ($deleteReactivos != FALSE) {
                $array_out['NumeroReactivos'] = $deleteReactivos['exa_num_reactivos'];
                $array_out['res'] = 'ok';
            } else {
                $array_out['res'] = 'no';
                $array_out['msg'] = 'Error al borrar la sección y/o los reactivos asociados a la sección. Intenta de nuevo.';
            }
        } else {
            $array_out['res'] = 'no';
            $array_out['msg'] = 'Error al borrar la sección y/o los reactivos asociados a la sección. Intenta de nuevo.';
        }
        echo json_encode($array_out);
    }

    //Cuando se elimina el examen
    function elimina()
    {
        $id = $this->input->post("id");
        $deleteOk = $this->examenes_model->get_elimina($id);
        if ($deleteOk != FALSE) {
            $array_out['resp'] = 'ok';
        } else {
            $array_out['resp'] = 'no';
            $array_out['msg'] = "Se produjo un error al eliminar el examen.";
        }
        echo json_encode($array_out);
    }

    //Actualiza datos de examen nombre y clave
    function update_examen()
    {
        $out = array();
        $user_id = $this->session->userdata('user_id' . $this->clv_sess);
        $idExamen = $this->input->post('id');
        $data['exa_nombre'] = $this->input->post('nombre');
        $data['exa_clave'] = $this->input->post('clave');
        $data['exa_fecha_modif'] = date('Y-m-d');
        $data['exa_usu_id_modifica'] = $user_id;

        $update_ok = $this->examenes_model->updateExam($data, $idExamen);
        if ($update_ok != false) {
            $out['resp'] = 'ok';
            $out['id'] = $update_ok;
        } else {
            $out['resp'] = 'no';
            $out['msg'] = 'Error al modificar el examen ' . $data['exa_nombre'];
        }
        echo json_encode($out);
    }

    //Si se actualizaron bien los datos del examen, se redirige a la vista de editar
    function editar_examen($id = 0)
    {
        $this->load->model('acceso_model');
        $user_id = $this->session->userdata('user_id' . $this->clv_sess);
        $permisos = $this->ci_acl_framew->get_parse_array_permisos($this->acceso_model->get_permisosUsuario($user_id, $this->clave_modulo));
        $datos_vista = array();
        if (array_key_exists($this->clave_modulo, $permisos)) {
            $datos_vista['permisos_modulo'] = $permisos[$this->clave_modulo];
        }
        $datos_vista['idExamen'] = $id;
        $data_modulo = $this->acceso_model->get_iconModulo($this->clave_modulo);
        $datos_plantilla['title_mod'] = $data_modulo['icon'] . ' ' . $data_modulo['nombre'] . ' <small> editar </small>';
        $datos_plantilla['modulos'] = $this->acceso_model->get_modulos();
        $datos_plantilla['permisos'] = $permisos;
        $datos_plantilla['navigate_mod'] = '<li><a onclick="redirect_to(\'inicio\')"><i class="fa fa-th"></i> Menú</a></li> <li><a onclick="redirect_to(\'examenes\')"> ' . $data_modulo['icon'] . ' ' . $data_modulo['nombre'] . '</a></li><li class="active"> Editar examen</li>';
        $datos_plantilla['content'] = $this->load->view('examenes/editar_examen_view', $datos_vista, true);
        $this->load->view('template', $datos_plantilla);
    }

    //Agrega un nodo raíz (sección)
    function addNodo()
    {
        $pid = $this->input->post('pid');
        $nom = $this->input->post('nom');
        $idExam = $this->input->post('idExam');
        $usu = $this->session->userdata('user_id' . $this->clv_sess);
        $array_out = array();
        $result = $this->examenes_model->get_addNodo($pid, $nom, $usu, $idExam);
        if ($result != FALSE) {
            $array_out['res'] = 'ok';
            $array_out['insert_id'] = $result;
        } else {
            $array_out['res'] = 'no';
            $array_out['msg'] = 'Error al agregar el plan. Intenta de nuevo.';
        }
        echo json_encode($array_out);
    }

    //Edita el nombre del nodo
    function editNodoName()
    {
        $id = $this->input->post('id');
        $nom = $this->input->post('nom');
        $array_out = array();
        $result = $this->examenes_model->get_editNodoName($id, $nom);
        if ($result != FALSE) {
            $array_out['res'] = 'ok';
            $array_out['insert_id'] = $result;
        } else {
            $array_out['res'] = 'no';
            $array_out['msg'] = 'Error al agregar el plan. Intenta de nuevo.';
        }
        echo json_encode($array_out);
    }

    private function getRutas($html)
    {
        $outArray = array();
        if ($html !== '') {
            $html = str_ireplace('<audio', '<p', $html);
            $html = str_ireplace('<video', '<p', $html);
            $html = str_ireplace('<source', '<img', $html);
            $html = str_ireplace('</audio>', '</p>', $html);
            $html = str_ireplace('</video>', '</p>', $html);
            $dom = new DOMDocument;
            $dom->loadHTML($html);
            $imgs = $dom->getElementsByTagName('img');
            foreach ($imgs as $imgTag) {
                $src = $imgTag->attributes->getNamedItem("src")->value;
                if ($src != '') {
                    array_push($outArray, $src);
                }
            }
        }
        return $outArray;
    }

    private function createDir($urlDir, $permisos)
    {
        if (!file_exists($urlDir)) {
            if (!mkdir($urlDir, $permisos, true)) {
                return FALSE;
            }
        }
        return TRUE;
    }

    private function copyMedia($media, $destino)
    {
        foreach ($media as $url) {
            $patInfo = pathinfo($url);
            $createDir = $this->createDir($destino . '/' . urldecode($patInfo['dirname']), 0777);
            if ($createDir) {
                $file = urldecode($patInfo['filename']) . '.' . explode('?', $patInfo['extension'])[0];
                if (is_file(urldecode($patInfo['dirname']) . '/' . $file)) {
                    if (!copy(urldecode($patInfo['dirname']) . '/' . $file, $destino . urldecode($patInfo['dirname']) . "/" . $file)) {
                        return FALSE;
                    }
                } else {
                    return FALSE;
                }
            }
        }
        return TRUE;
    }

    private function copyMediaEL($media, $destino, $prefijo)
    {
        foreach ($media as $url) {
            $patInfo = pathinfo($url);
            $createDir = $this->createDir($destino . '/' . $prefijo . '_' . urldecode($patInfo['dirname']), 0777);
            if ($createDir) {
                $file = urldecode($patInfo['filename']) . '.' . explode('?', $patInfo['extension'])[0];
                if (is_file(urldecode($patInfo['dirname']) . '/' . $file)) {
                    if (!copy(urldecode($patInfo['dirname']) . '/' . $file, $destino . $prefijo . '_' . urldecode($patInfo['dirname']) . "/" . $file)) {
                        //echo $file;
                        return FALSE;
                    }
                } else {

                    echo $patInfo['dirname'] . $file;
                }
            }
        }
        return TRUE;
    }

    private function exportDataReactivos($id_examen = 0, $path)
    {
        $success = FALSE;
        $mediaReactivosArray = array();
        $csvFileReact = $path . "reactivos_examen_" . $id_examen . ".csv";
        $fileReactivos = fopen($csvFileReact, 'w+');
        if ($fileReactivos) {
            fputcsv($fileReactivos, array('ID', 'CLAVE', 'CONTENIDO', 'RESPUESTA', 'CASO_REACTIVO', 'PLAN'));
            $reactivosExam = $this->examenes_model->get_ReactivosExamen($id_examen);
            foreach ($reactivosExam as $row) {
                $content = $row['contenido'];
                $rutasImagen = $this->getRutas($content);
                foreach ($rutasImagen as $urlRea) {
                    array_push($mediaReactivosArray, $urlRea);
                }
                /* if (substr($row['contenido'], 0, 3) == '<p>') {
                  $row['contenido'] = str_ireplace('<p>', '', $row['contenido']);
                  } */
                //fputcsv($fileReactivos, $row, ",");
                fputcsv($fileReactivos, array($row['id'], $row['clave'], iconv("UTF-8", "Windows-1252//IGNORE", $row['contenido']), $row['respuesta'], $row['caso_reactivo'], $row['plan']), ",");
            }
            fclose($fileReactivos);
            $success = TRUE;
            if (!empty($mediaReactivosArray)) {
                if (!$this->copyMedia($mediaReactivosArray, $path)) {
                    return FALSE;
                }
            }
        }
        return $success;
    }

    private function exportDataOpciones($id_examen = 0, $path)
    {
        //$this->output->enable_profiler(true);
        $success = FALSE;
        $mediaOpcionesArray = array();
        $csvFileOpciones = $path . "opciones_respuesta_examen_" . $id_examen . ".csv";
        $fileOpciones = fopen($csvFileOpciones, 'w+');
        if ($fileOpciones) {
            fputcsv($fileOpciones, array('ID_OPCION', 'CONTENIDO_OPCION', 'OPCION_REACTIVO', 'OPC_ESCORRECTA', 'OPCION_TIPO'));
            $opcionesRespuesta = $this->examenes_model->getOpcionesReactivos($id_examen);
            foreach ($opcionesRespuesta as $row) {
                $contentOpc = $row['contenido'];
                $rutasImagenOpc = $this->getRutas($contentOpc);
                foreach ($rutasImagenOpc as $urlOpc) {
                    array_push($mediaOpcionesArray, $urlOpc);
                }
                //fputcsv($fileOpciones, $row, ",");
                fputcsv($fileOpciones, array($row['id'], iconv("UTF-8", "Windows-1252//IGNORE", $row['contenido']), $row['opcion_reactivo'], $row['es_correcta'], $row['tipo']), ",");
            }
            fclose($fileOpciones);
            $success = TRUE;
            if (!empty($mediaOpcionesArray)) {
                if (!$this->copyMedia($mediaOpcionesArray, $path)) {
                    return FALSE;
                }
            }
        }
        return $success;
    }

    private function exportDataCasos($id_examen = 0, $path)
    {
        $success = FALSE;
        $mediaCasosArray = array();
        $csvFileCasos = $path . "casos_examen_" . $id_examen . ".csv";
        $fileCasos = fopen($csvFileCasos, 'w+');
        if ($fileCasos) {
            fputcsv($fileCasos, array('ID_CASO', 'TITULO_CASO', iconv("UTF-8", "Windows-1252//IGNORE", 'INSTRUCCIÓN DEL CASO'), 'CONTENIDO_CASO'));
            $casos = $this->examenes_model->getCasosReactivos($id_examen);
            foreach ($casos as $row) {
                $contentCaso = $row['contenido_caso'];
                $rutasImagenCaso = $this->getRutas($contentCaso);
                foreach ($rutasImagenCaso as $urlCasos) {
                    array_push($mediaCasosArray, $urlCasos);
                }
                //fputcsv($fileCasos, $row);
                fputcsv($fileCasos, array($row['id_caso'], iconv("UTF-8", "Windows-1252//IGNORE", $row['titulo_caso']), iconv("UTF-8", "Windows-1252//IGNORE", $row['instruccion']), iconv("UTF-8", "Windows-1252//IGNORE", $row['contenido_caso'])), ",");
            }
            fclose($fileCasos);
            $success = TRUE;
            if (!empty($mediaCasosArray)) {
                if (!$this->copyMedia($mediaCasosArray, $path)) {
                    return FALSE;
                }
            }
        }
        return $success;
    }

    private function exportDataPlanes($id_examen = 0, $path)
    {
        $csv_end = "\n";
        $csv_sep = ",";
        $success = FALSE;
        $csv = "";
        $csvFilePlanes = $path . "planes_examen_" . $id_examen . ".csv";
        $filePlanes = fopen($csvFilePlanes, 'w+');
        if ($filePlanes) {
            fputcsv($filePlanes, array('ID_PLAN', 'NOMBRE_PLAN', 'TIPO_PLAN'));
            $planes_reactivos = $this->examenes_model->getPlanesReactivos($id_examen);
            foreach ($planes_reactivos as $row) {
                $csv .= '"' . $row['id'] . '"' . $csv_sep . '"' . $row['nombre'] . '"' . $csv_sep . '"' . $row['tipo'] . '"' . $csv_end;
            }
            fwrite($filePlanes, utf8_decode($csv));
            fclose($filePlanes);
            $success = TRUE;
        }
        return $success;
    }

    function export()
    {
        $out = array();
        $id_examen = $this->input->post('idExamen') * 1;
        if ($id_examen === 0) {
            redirect('examenes');
        }
        $pathOut = "./media_examenes/" . hash('md5', 'exams_temp') . "/out/";
        $itCouldCreateExamenPath = $this->createDir($pathOut, 0740);
        if ($itCouldCreateExamenPath !== FALSE) {
            $outReactivos = $this->exportDataReactivos($id_examen, $pathOut);
            $outPlanes = $this->exportDataPlanes($id_examen, $pathOut);
            $outOpciones = $this->exportDataOpciones($id_examen, $pathOut);
            $outCasos = $this->exportDataCasos($id_examen, $pathOut);
            if ($outReactivos && $outPlanes && $outOpciones && $outCasos) {
                $this->createZip($id_examen, $pathOut);
                //$this->download_arch($id_examen);
                $out['res'] = 'ok';
            } else {
                if (is_dir('./media_examenes/')) {
                    delete_files('./media_examenes/', TRUE);
                }
                $out['res'] = 'no';
                $out['msg'] = 'Ocurrió un error al exportar los archivos.No se encontraron todas las imágenes. Intenta de nuevo.';
            }
        } else {
            if (is_dir('./media_examenes/')) {
                delete_files('./media_examenes/', TRUE);
            }
            $out['res'] = 'no';
            $out['msg'] = 'No se pudo exportar el examen.';
        }

        echo json_encode($out);
    }

    private function createZip($id_examen = 0, $path)
    {
        $url_arch = $path . 'archivos_examen' . $id_examen . '.zip';
        $csv_file_react = $path . "reactivos_examen_" . $id_examen . ".csv";
        $csv_file_planes = $path . "planes_examen_" . $id_examen . ".csv";
        $csv_file_opciones = $path . "opciones_respuesta_examen_" . $id_examen . ".csv";
        $csv_file_casos = $path . "casos_examen_" . $id_examen . ".csv";
        $this->zip->clear_data();
        $this->zip->read_file($csv_file_react);
        $this->zip->read_file($csv_file_planes);
        $this->zip->read_file($csv_file_opciones);
        $this->zip->read_file($csv_file_casos);
        $this->zip->read_dir($path . 'media/', FALSE);
        $this->zip->archive($url_arch);
    }

    function download_arch($idExamen = 0)
    {
        $idExamen = $idExamen * 1;
        $hashExams = hash('md5', 'exams_temp');
        $archivo = "./media_examenes/" . $hashExams . "/out/" . "archivos_examen" . $idExamen . ".zip";
        if ($idExamen != 0) {
            if (file_exists($archivo)) {
                $file_content = file_get_contents($archivo);
                if (is_dir('./media_examenes/')) {
                    delete_files('./media_examenes/', TRUE);
                }
                force_download("archivos_examen" . $idExamen . ".zip", $file_content);
            } else {
                redirect('examenes/editar_examen/' . $idExamen);
            }
        }
    }

    //Agrega todos los nodos de una rama cuando esta se arrastra
    function addHojasNodo($idExamen = 0)
    {
        $idExamen = $idExamen * 1;
        $out = array();
        $data['id_plan_origen'] = $this->input->post('idNodoOrigen');
        $data['id_plan_destino'] = $this->input->post('idNodoDestino');
        $data['id_examen'] = $idExamen;
        $data['plan_nombre_origen'] = $this->input->post('nodeNameOrigin');
        $totReactivos = $this->input->post('totalReactivos');
        if ($idExamen != 0) {
            $reactivos = $this->examenes_model->addReactivosToNodo($data, $totReactivos);
            if ($reactivos['ok'] == "ok") {
                $out['res'] = 'ok';
                $out['newTotalReact'] = $reactivos['totReact'];
            } else {
                $out['res'] = 'ok';
                $out['msg'] = 'Ocurrio un error al agregar los reactivos. Intenta de nuevo.';
            }
        } else {
            $out['res'] = 'ok';
            $out['msg'] = 'Ocurrio un error al agregar los reactivos. Intenta de nuevo.';
        }

        echo json_encode($out);
    }

    function notFound()
    {
        $datos_plantilla['title_mod'] = 'Examen no encontrado.';
        $datos_plantilla['navigate_mod'] = '<li><a onclick="redirect_to(\'inicio\')"><i class="fa fa-th"></i> Menú</a></li> <li><a class="active">error !</a></li>';
        $datos_plantilla['content'] = $this->load->view('acceso/acceso_denegado_view', FALSE, true);
        $this->load->view('template', $datos_plantilla);
    }

    function mantenimiento()
    {
        $datos_plantilla['title_mod'] = 'En mantenimiento.';
        $datos_plantilla['navigate_mod'] = '<li><a onclick="redirect_to(\'inicio\')"><i class="fa fa-th"></i> Menú</a></li> <li><a onclick="redirect_to(\'examenes\')"><i class="fa fa-th"></i> Examenes</a></li><li><a class="active">Mantenimiento</a></li>';
        $datos_plantilla['content'] = $this->load->view('acceso/construccion_view', FALSE, true);
        $this->load->view('template', $datos_plantilla);
    }

    private function validaP($contenido)
    {
        if (substr($contenido, 0, 1) == '?') {
            $contenido = str_replace("?", "", $contenido);
        }
        if (substr($contenido, 0, 3) == '<p>') {
            $contenido = preg_replace('~<p>(.*?)</p>~is', '$1', $contenido, /* limit */ 1);
        }
        return $contenido;
    }

    /*
      function exportWord() {
      //$this->output->enable_profiler(TRUE);
      $out = Array();
      $contenidoReact = '';
      $numReac = 0;
      $i = 0;
      $letras = array("A)", "B)", "C)", "D)", "E)");
      $id_examen = $this->input->post('idExamen') * 1;
      $mark = $this->input->post('ansMark');
      if ($id_examen === 0) {
      redirect('examenes');
      }
      if ($id_examen != 0) {
      $content = '<div style="font-size: 12px !important;font-family: Arial, serif !important;">';
      $reactivos = $this->examenes_model->get_ReactivosExamen($id_examen);
      $opciones = $this->examenes_model->getOpcionesReactivos($id_examen);
      $casos = $this->examenes_model->getCasosReactivos($id_examen);
      $content.='';
      foreach ($reactivos as $row) {
      $numReac++;
      $contenidoReact = $row['contenido'];
      if (substr($contenidoReact, 0, 3) == '<p>') {
      $contenidoReact = str_ireplace('<p>', '', $contenidoReact);
      }
      $content.='<br><br>' . '<div style="margin-bottom:0;">' . $row['clave'] . '-' . $contenidoReact . '</div>';
      foreach ($casos as $val) {
      if ($val['id_caso'] == $row['caso_reactivo']) {
      $content.='<div>' . $val['instruccion'] . '<br><br>';
      $content.=iconv("UTF-8", "Windows-1252//IGNORE", $val['contenido_caso']) . '</div>';
      }
      }
      foreach ($opciones as $value) {
      if ($value['opcion_reactivo'] == $row['id']) {
      $value['contenido'] = str_ireplace('<p>', '', $value['contenido']);
      $value['contenido'] = str_ireplace('</p>', '', $value['contenido']);
      if ($mark == 1) {
      if ($value['es_correcta'] == 'S') {
      $content.='<div style=" margin-top:0;"><u>' . $letras[$i] . ' ' . $value['contenido'] . '</u></div>';
      } else {
      $content.='<div style=" margin-top:0;">' . $letras[$i] . ' ' . $value['contenido'] . '</div>';
      }
      } else {
      $content.='<div style=" margin-top:0;">' . $letras[$i] . ' ' . $value['contenido'] . '</div>';
      }
      $i++;
      }
      }
      $i = 0;
      }
      $out['res'] = 'ok';
      $content.='</div>';
      $out['content'] = $content;
      }
      echo json_encode($out);
      } */

    function exportWord2($nombreWord = '', $id_examen = 0, $mark = 0)
    {

        $out = array();
        $duplicados = array();
        $letras = array("A) ", "B) ", "C) ", "D) ", "E) ", "F)", "G)", "H)", "I)", "J)", "K)", "L)", "M)", "N)", "Ñ)", "O)", "P)", "Q)", "R)", "S)", "T)", "U)", "V)", "W)", "X)", "Y)", "Z)");
        $contenidoReact = '';
        $content = "";
        $numReac = 0;
        $i = 0;
        if ($id_examen === 0) {
            redirect('examenes');
        }
        if ($id_examen != 0) {
            $planes = $this->examenes_model->getPlanesReactivos($id_examen);
            $reactivos = $this->examenes_model->get_ReactivosExamen($id_examen);
            $opciones = $this->examenes_model->getOpcionesReactivos($id_examen);
            $casos = $this->examenes_model->getCasosReactivos($id_examen);
            foreach ($planes as $valPlan) {
                $content .= "<br/><strong>" . $valPlan['nombre'] . "</strong>";

                foreach ($reactivos as $row) {
                    if ($row['plan'] == $valPlan['id']) {
                        $numReac++;
                        $contenidoReact = $row['contenido'];
                        if (substr($contenidoReact, 0, 3) == '<p>') {
                            $contenidoReact = str_replace('<p>', '', $contenidoReact);
                        }
                        $content .= '<br><br>' . '<div style="font-size: 12px !important; margin-bottom:0; font-family: Arial, serif !important;">' . "<strong>" . $numReac . "</strong>.-"  . $contenidoReact . '</div>';
                        foreach ($casos as $val) {
                            if ($val['id_caso'] == $row['caso_reactivo']) {
                                /* array_push($duplicados, $val['id_caso']);
                                  $numeroRepetidos = array_count_values($duplicados); */
                                //if ($numeroRepetidos[$val['id_caso']] == 1) {
                                // $content .= '<div style="font-size: 12px !important; font-family: Arial, serif !important;">' . $val['instruccion'] . '<br><br>';
                                //$content .= iconv("UTF-8", "Windows-1252//IGNORE",  $val['titulo_caso'].'<br><br>'.$val['contenido_caso']) . '</div>';
                                $content .= '<div style="text-align:center;">' . $val['titulo_caso'] . '<br></div>' . $val['contenido_caso'] . '</div>';
                                //} else {
                                //  $content .= '<div style="font-size: 12px !important; font-family: Arial, serif !important; "> <br> Con base en el caso ' . $val['titulo_caso'] . ' planteado anteriormente, responda las preguntas<br><br></div>';
                                // }
                            }
                        }
                        foreach ($opciones as $value) {
                            if ($value['opcion_reactivo'] == $row['id']) {
                                $value['contenido'] = str_ireplace('<p>', '', $value['contenido']);
                                $value['contenido'] = str_ireplace('</p>', '', $value['contenido']);
                                if ($mark == 1) {
                                    if ($value['es_correcta'] == 'S') {
                                        $content .= '<div style=" margin-top:0;"><u>' . $letras[$i] . ' ' . $value['contenido'] . '</u></div>';
                                    } else {
                                        $content .= '<div style=" margin-top:0;">' . $letras[$i] . ' ' . $value['contenido'] . '</div>';
                                    }
                                } else {
                                    $content .= '<div style=" margin-top:0;">' . $letras[$i] . ' ' . $value['contenido'] . '</div>';
                                }
                                $i++;
                            }
                        }
                        $i = 0;
                    }
                }
            }
            $numReac = 0;
            $this->load->model('acceso_model');
            $user_id = $this->session->userdata('user_id' . $this->clv_sess);
            $permisos = $this->ci_acl_framew->get_parse_array_permisos($this->acceso_model->get_permisosUsuario($user_id, $this->clave_modulo));
            $datos_vista = array();
            if (array_key_exists($this->clave_modulo, $permisos)) {
                $datos_vista['permisos_modulo'] = $permisos[$this->clave_modulo];
            }
            $datos_vista['examenContent'] = $content;
            $datos_vista['nombreWord'] = $nombreWord;
            $data_modulo = $this->acceso_model->get_iconModulo($this->clave_modulo);
            $datos_plantilla['title_mod'] = $data_modulo['icon'] . ' ' . $data_modulo['nombre'] . ' <small> exportar </small>';
            $datos_plantilla['modulos'] = $this->acceso_model->get_modulos();
            $datos_plantilla['permisos'] = $permisos;
            $datos_plantilla['navigate_mod'] = "<li><a onclick='redirect_to(\"inicio\")'><i class='fa fa-th'></i> Menú</a></li> <li><a onclick='redirect_to(\"examenes\")'> " . $data_modulo['icon'] . "" . $data_modulo['nombre'] . "</a></li><li class=''> <a onclick='redirect_to(\"examenes/editar_examen/" . $id_examen . "\")'>Editar examen</a></li><li class='active'> Exportar examen</li>";
            $datos_plantilla['content'] = $this->load->view('examenes/examen_word_view', $datos_vista, true);
            $this->load->view('template', $datos_plantilla);
        }
    }

    function exportEl()
    {
        $out = array();
        $id_examen = $this->input->post('idExamen') * 1;
        $prefijo = $this->input->post('prefijo');
        if ($id_examen === 0) {
            redirect('examenes');
        }
        $pathOut = "./media_examenes/" . hash('md5', 'exams_temp') . "/out/";
        $itCouldCreateExamenPath = $this->createDir($pathOut, 0740);
        if ($itCouldCreateExamenPath !== FALSE) {
            $respScript = $this->createScript($id_examen, $pathOut, $prefijo, false, 1);
            if ($respScript) {
                if ($this->createZipEL($id_examen, $pathOut, $prefijo, false)) {
                    $out['res'] = 'ok';
                    $out['msg_class'] = 'success';
                    $out['msg'] = 'Tu archivo zip se encuentra listo para descargar. Presiona Descargar sql.';
                } else {
                    $out['res'] = 'no';
                    $out['msg_class'] = 'error';
                    $out['msg'] = 'Ocurrió un error al crear el archivo zip.';
                }
            } else {
                if (is_dir('./media_examenes/')) {
                    delete_files('./media_examenes/', TRUE);
                }
                $out['res'] = 'no';
                $out['msg_class'] = 'error';
                $out['msg'] = 'Ocurrió un error al exportar los archivos.No se encontraron todas las imágenes. Intenta de nuevo.';
            }
        } else {
            if (is_dir('./media_examenes/')) {
                delete_files('./media_examenes/', TRUE);
            }
            $out['res'] = 'no';
            $out['msg'] = 'No se pudo exportar el examen.';
        }

        echo json_encode($out);
    }

    function exportBloqueEl()
    {
        $out = array();
        $examenes = $this->input->post('examenes');
        $totalExamenes = count($examenes);
        $prefijo = $this->input->post('prefijo');
        if (empty($examenes)) {
            redirect('examenes');
        }
        $pathOut = "./media_examenes/3x4m3n35BlOque/out/";
        $itCouldCreateExamenPath = $this->createDir($pathOut, 0740);
        if ($itCouldCreateExamenPath !== FALSE) {
            foreach ($examenes as $valExamen) {
                $respScript = $this->createScript($valExamen['idExamen'], $pathOut, $valExamen['prefijo'], true, $totalExamenes);
            }
            if ($respScript) {
                if ($this->createZipEL(0, $pathOut, $prefijo, true)) {
                    $out['res'] = 'ok';
                    $out['msg_class'] = 'success';
                    $out['msg'] = 'El archivo zip se encuentra listo. Presiona Descargar sql.';
                } else {
                    $out['res'] = 'no';
                    $out['msg_class'] = 'error';
                    $out['msg'] = 'Ocurrió un error al crear el archivo zip.';
                }
            } else {
                if (is_dir('./media_examenes/')) {
                    delete_files('./media_examenes/', TRUE);
                }
                $out['res'] = 'no';
                $out['msg_class'] = 'error';
                $out['msg'] = 'Ocurrió un error al exportar los archivos.No se encontraron todas las imágenes. Intenta de nuevo.';
            }
        } else {
            if (is_dir('./media_examenes/')) {
                delete_files('./media_examenes/', TRUE);
            }
            $out['res'] = 'no';
            $out['msg'] = 'No se pudo exportar el examen.';
        }

        echo json_encode($out);
    }

    function downloadSql($id_examen = 0, $prefix = '', $variosExamenes)
    {
        if ($variosExamenes == 1) {
            $archivo = "./media_examenes/3x4m3n35BlOque/out/archivosExamenes.zip";
            if (file_exists($archivo)) {
                $file_content = file_get_contents($archivo);
                if (is_dir('./media_examenes/')) {
                    delete_files('./media_examenes/', TRUE);
                }
                force_download("archivosExamenes.zip", $file_content);
            } else {
                redirect('examenes');
            }
        } else {
            if ($id_examen != 0 && $prefix != '') {
                $hashExams = hash('md5', 'exams_temp');
                $archivo = "./media_examenes/" . $hashExams . "/out/" . $prefix . $id_examen . ".zip";
                if (file_exists($archivo)) {
                    $file_content = file_get_contents($archivo);
                    if (is_dir('./media_examenes/')) {
                        delete_files('./media_examenes/', TRUE);
                    }
                    force_download($prefix . $id_examen . ".zip", $file_content);
                } else {
                    redirect('examenes/editar_examen/' . $id_examen);
                }
            } else {
                redirect('examenes/editar_examen/' . $id_examen);
            }
        }
    }

    private function createZipEL($id_examen = 0, $path, $prefijo, $variosExamenes = FALSE)
    {
        $url_arch = ($variosExamenes) ? $path . 'archivosExamenes.zip' : $path . $prefijo . $id_examen . '.zip';
        $fileScriptSql = ($variosExamenes) ? $this->fileSql : $path . $prefijo . "_script_" . $id_examen . ".sql";
        $this->zip->clear_data();
        $this->zip->read_file($fileScriptSql);
        $this->zip->read_dir($path . 'archivos/', FALSE);
        if ($this->zip->archive($url_arch)) {
            return true;
        } else {
            return false;
        }
    }

    private $content = '';
    private $fileSql = './media_examenes/3x4m3n35BlOque/out/script_examenes.sql';
    private $contadorExamenes = 0;

    private function createScript($id_examen, $path, $prefijo, $variosExamenes = FALSE, $numExamenes = 0)
    {
        $this->load->helper('file');
        $mediaReactivosArray = array();
        $mediaOpcionesArray = array();
        $mediaCasosArray = array();
        $this->contadorExamenes += 1;
        if (!$variosExamenes) {
            $this->fileSql = $path . $prefijo . "_script_" . $id_examen . ".sql";
        }

        $reactivosExam = $this->examenes_model->get_ReactivosExamen($id_examen);
        $this->content .= "DROP TABLE IF EXISTS `" . $prefijo . "_reactivos`; CREATE TABLE IF NOT EXISTS `" . $prefijo . "_reactivos` (
        `ID` int(11) unsigned NOT NULL,
        `TIPO_REACTIVO` int(11) unsigned NOT NULL,
        `CLAVE` varchar(11) NOT NULL DEFAULT '',
        `CONTENIDO` longtext NOT NULL,
        `PLAN` int(11) NOT NULL,
        `CASO_REACTIVO` int(11) DEFAULT NULL,
        `RESPUESTA` int(11) NOT NULL,
        `PUNTOS` double NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;\n";

        foreach ($reactivosExam as $row) {
            $rutasImagen = $this->getRutas($row['contenido']);
            foreach ($rutasImagen as $urlRea) {
                array_push($mediaReactivosArray, $urlRea);
            }
            $row['contenido'] = str_replace('src="media', 'src="' . $prefijo . '_media', $row['contenido']);
            $contenidoReactivo = str_replace("'", "\'", $row['contenido']);
            $this->content .= "INSERT INTO `" . $prefijo . "_reactivos` (`ID`, `TIPO_REACTIVO`, `CLAVE`, `CONTENIDO`, `PLAN`, `CASO_REACTIVO`, `RESPUESTA`, `PUNTOS`) VALUES ('" . $row['id'] . "','" . $row['rea_tiporeactivo'] . "','" . $row['clave'] . "','" .  $contenidoReactivo . "','" . $row['plan'] . "','" . $row['caso_reactivo'] . "','" . $row['respuesta'] . "','" . $row['rea_puntos'] . "');\n";
        }
        if (!empty($mediaReactivosArray)) {
            if (!$this->copyMediaEL($mediaReactivosArray, $path . "archivos/", $prefijo)) {
                return FALSE;
            }
        }
        $this->content .= "DROP TABLE IF EXISTS `" . $prefijo . "_opciones`; CREATE TABLE IF NOT EXISTS `" . $prefijo . "_opciones` (
        `ID_OPCION` int(11) NOT NULL,
        `CONTENIDO_OPCION` text NOT NULL,
        `OPCION_REACTIVO` int(11) NOT NULL,
        `OPC_ESCORRECTA` char(1) DEFAULT 'n',
        `OPCION_TIPO` char(3) DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;\n";
        $opcionesRespuesta = $this->examenes_model->getOpcionesReactivos($id_examen);
        foreach ($opcionesRespuesta as $rowResp) {
            $contentOpc = $rowResp['contenido'];
            $rutasImagenOpc = $this->getRutas($contentOpc);
            foreach ($rutasImagenOpc as $urlOpc) {
                array_push($mediaOpcionesArray, $urlOpc);
            }
            $rowResp['contenido'] = str_replace('src="media', 'src="' . $prefijo . '_media', $rowResp['contenido']);
            $rowResp['contenido'] = str_replace("'", "\'", $rowResp['contenido']);
            $this->content .= "INSERT INTO `" . $prefijo . "_opciones` (`ID_OPCION`, `CONTENIDO_OPCION`, `OPCION_REACTIVO`, `OPC_ESCORRECTA`, `OPCION_TIPO`) VALUES ('" . $rowResp['id'] . "','" . $rowResp['contenido'] . "','" . $rowResp['opcion_reactivo'] . "','" . $rowResp['es_correcta'] . "','" . $rowResp['tipo'] . "');\n";
        }
        if (!empty($mediaOpcionesArray)) {
            if (!$this->copyMediaEL($mediaOpcionesArray, $path . "archivos/", $prefijo)) {
                return FALSE;
            }
        }
        $this->content .= "DROP TABLE IF EXISTS `" . $prefijo . "_casos`; CREATE TABLE IF NOT EXISTS `" . $prefijo . "_casos` (
            `ID_CASO` int(11) NOT NULL,
            `TITULO_CASO` varchar(45) NOT NULL DEFAULT '',
            `INSTRUCCION_CASO` varchar(3000) DEFAULT NULL,
            `CONTENIDO_CASO` longtext
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;\n";

        $casos = $this->examenes_model->getCasosReactivos($id_examen);
        foreach ($casos as $rowCasos) {
            $rutasImagenCaso = $this->getRutas($rowCasos['contenido_caso']);
            foreach ($rutasImagenCaso as $urlCasos) {
                array_push($mediaCasosArray, $urlCasos);
            }

            $rowCasos['contenido_caso'] = str_replace('src="media', 'src="' . $prefijo . '_media', $rowCasos['contenido_caso']);
            $rowCasos['contenido_caso'] = str_replace("'", "\'", $rowCasos['contenido_caso']);
            $this->content .= "INSERT INTO `" . $prefijo . "_casos` (`ID_CASO`, `TITULO_CASO`, `INSTRUCCION_CASO`, `CONTENIDO_CASO`) VALUES ('" . $rowCasos['id_caso'] . "','" . $rowCasos['titulo_caso'] . "','" . $rowCasos['instruccion'] . "','" . $rowCasos['contenido_caso'] . "');\n";
        }

        if (!empty($mediaCasosArray)) {
            if (!$this->copyMediaEL($mediaCasosArray, $path . "archivos/", $prefijo)) {
                return FALSE;
            }
        }
        $this->content .= "DROP TABLE IF EXISTS `" . $prefijo . "_planes`; CREATE TABLE IF NOT EXISTS `" . $prefijo . "_planes` (
        `ID_PLAN` int(11) NOT NULL,
        `NOMBRE_PLAN` varchar(100) NOT NULL DEFAULT '',
        `TIPO_PLAN` varchar(200) CHARACTER SET latin1 NOT NULL,
        `PLAN_ORDEN` int(11) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;\n";
        $planes_reactivos = $this->examenes_model->getPlanesReactivos($id_examen);
        foreach ($planes_reactivos as $rowPlanes) {
            $this->content .= "INSERT INTO `" . $prefijo . "_planes` (`ID_PLAN`, `NOMBRE_PLAN`, `TIPO_PLAN`,`PLAN_ORDEN`) VALUES ('" . $rowPlanes['id'] . "','" . $rowPlanes['nombre'] . "','" . $rowPlanes['tipo'] . "'," . $rowPlanes['pla_orden'] . ");\n";
        }
        $this->content .= 'ALTER TABLE `' . $prefijo . '_casos`
 ADD PRIMARY KEY (`ID_CASO`),ADD INDEX (`ID_CASO`);';
        $this->content .= 'ALTER TABLE `' . $prefijo . '_planes`
 ADD PRIMARY KEY (`ID_PLAN`),ADD INDEX (`ID_PLAN`);';
        $this->content .= 'ALTER TABLE `' . $prefijo . '_opciones`
 ADD PRIMARY KEY (`ID_OPCION`),ADD INDEX (`ID_OPCION`),ADD INDEX (`OPCION_REACTIVO`);';
        $this->content .= 'ALTER TABLE `' . $prefijo . '_reactivos`
 ADD PRIMARY KEY (`ID`),ADD INDEX (`ID`),ADD INDEX (`PLAN`),ADD INDEX (`CASO_REACTIVO`);';
        if (!$variosExamenes) {
            write_file($this->fileSql, $this->content, "w+");
        } else {
            if ($this->contadorExamenes == $numExamenes) {
                write_file($this->fileSql, $this->content, "w+");
            }
        }

        return true;
    }

    function getRamas()
    {
        $id_examen = $this->input->post('idExamen');
        if ($id_examen != 0) {
            $result = $this->examenes_model->getRamasOrdenar($id_examen);
        }
        echo json_encode($result);
    }

    function saveOrden()
    {
        $arrayOrden = $this->input->post('arrayRamas');
        $idExamen = $this->input->post('idExamen');
        $resultado = $this->examenes_model->saveOrdenRamas($arrayOrden, $idExamen);
        echo json_encode($resultado);
    }

    /* function test() {
      $html = "<p>This <img src='1.png' >is mystring. </p> <p>Google.</p><p>khjhk</p>";
      echo $html;
      $dom = new DOMDocument;
      $dom->loadHTML($html);
      $nodes = $dom->getElementsByTagName("p");
      if ($nodes->item(0) && $nodes->item(0)->nodeName == 'p') {
      $node = $nodes->item(0);
      $node->parentNode->replaceChild(new DOMText($node->textContent), $node);
      }
      $var = $dom->saveHTML();
      echo $var;
      } */

    function muestraExamen()
    {
        $datosExamen = $this->examenes_model->saveOrdenRamas($arrayOrden, $idExamen);
    }

    function reactivosTec()
    {
        $this->output->enable_profiler(true);
        $xml = simplexml_load_file("./cuestionatiop1.xml");
        $datosPlan = array();
        $contador = 0;
        $arrayCategoria = array();
        foreach ($xml->question as $nodo) {
            $atributos = $nodo->attributes();
            $tipo = $atributos['type'];
            if ($tipo == 'category') {
                $arrayCategoria = explode('/', $nodo->category->text);
                $ultimaRama = $arrayCategoria[count($arrayCategoria) - 1];
                if ($ultimaRama != '') {
                    $datosPlan = $this->examenes_model->getIdPlan($ultimaRama);
                }
            }
            if ($tipo != 'category') {
                $contador++;
                //print_r($datosPlan);
                //echo "ID PLAN" . $datosPlan[0]['pla_id'] . "PLAN " . $datosPlan[0]['pla_nombre'] . "<br>";
                $idReactivo = $this->examenes_model->insertaReactivo($datosPlan[0]['pla_id'], $contador, $nodo->questiontext->text);
                if ($idReactivo != false) {
                    echo "Reactivo guardado";
                    foreach ($nodo->answer as $testoRes) {
                        $atributosOpc = $testoRes->attributes();
                        $esCorrecta = ($atributosOpc['fraction'] == "100") ? "S" : "N";
                        if ($this->examenes_model->guardaRespuestas($idReactivo, $testoRes->text, $esCorrecta)) {
                            echo "opcion guardada";
                        } else {
                            echo "opcion NO guardada";
                        }
                    }
                } else {
                    echo "Reactivo NO guardado";
                }
            }
            echo "<br>";
        }
    }
    function reactivosJuanito()
    {
        $this->load->model('reactivo_model');

        $xml = simplexml_load_file("./cuestionariop1.xml");
        $datosPlan = array();
        $contador = 0;
        $arrayCategoria = array();
        $datosReactivo = $datosOpciones = array();
        $i = 0;
        //echo count($xml->pregunta);
        foreach ($xml->pregunta as $nodo) {
            echo "<br>" . $nodo->planteamiento . "<br><br>" . $nodo->recursoplanteamiento . "<br>";
            $i++;
            //echo print_r($nodo->opciones);
            $datosReactivo['rea_clave'] = $i;
            $datosReactivo['rea_contenido'] = $nodo->planteamiento . "<br><br>" . $nodo->recursoplanteamiento;
            $datosReactivo['rea_fechaalta'] = date('Y-m-d');
            $datosReactivo['rea_estado'] = 'C';
            $datosReactivo['rea_plan'] = 3216;
            $datosReactivo['rea_usuarioalta'] = 1;
            $datosOpciones['rea_tiporeactivo'] = 1;
            $datosOpciones['rea_caso'] = 0;
            $resCorrecta = $nodo->respuestacorrecta;
            $datos_notas = array();
            $datos_notas["nota_descripcion"] = '';
            $idinsert =  $this->reactivo_model->insertarReactivo($datosReactivo, $datos_notas);

            foreach ($nodo->opciones as $nodoOpcion) {
                // print_r($nodoOpcion);
                foreach ($nodoOpcion as $opc) {
                    $atributos = $opc->attributes();
                    //echo "<strong>".$atributos['id']."</strong>---".$nodo->respuestacorrecta;
                    $datosOpciones['opc_contenido'] = $opc . "<br><br>" . $opc->recursoopcion;
                    $datosOpciones['opc_reactivo'] = $idinsert;
                    $datosOpciones['opc_escorrecta'] = 'N';
                    $datosOpciones['opc_tipo'] = 'txt';

                    if (strcmp($resCorrecta, $atributos['id']) === 0) {
                        $datosOpciones['opc_escorrecta'] = 'S';
                        echo "<strong>" . $opc . "</strong><br><br>" . $opc->recursoopcion;
                    } else {
                        echo $opc . "<br><br>" . $opc->recursoopcion;
                    }

                    $this->db->insert('adm_opcion', $datosOpciones);

                    //echo $atributos['id'];
                }
            }
            $datosReactivo = $datosOpciones = array();
        }
        echo "OK";
    }
}
