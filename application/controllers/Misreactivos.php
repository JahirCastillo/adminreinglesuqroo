<?php

/**
 * Archivo controller inicio contiene funciones en la página de inicio.
 *
 * @package    AdminreWeb
 * @subpackage Inicio
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Misreactivos extends CI_Controller {

    private $clave_modulo = 'MRS';
    private $clv_sess = '';

    function __construct() {
        parent::__construct();
        $this->clv_sess = $this->config->item('clv_sess');
        $user_id = $this->session->userdata('user_id' . $this->clv_sess);
        if (!$user_id) {
            redirect('inicio');
        }
        $this->load->model('misreactivos_model');
    }

    function index() {
        $this->load->model('acceso_model');
        $user_id = $this->session->userdata('user_id' . $this->clv_sess);
        $permisos = $this->ci_acl_framew->get_parse_array_permisos($this->acceso_model->get_permisosUsuario($user_id, $this->clave_modulo));
        $datos_vista = array();
        $datos_vista['reactivos'] = $this->misreactivos_model->get_misreactivos($user_id);
        if (array_key_exists($this->clave_modulo, $permisos)) {
            $datos_vista['permisos_modulo'] = $permisos[$this->clave_modulo];
        }
        //datos modulo
        $data_modulo = $this->acceso_model->get_iconModulo($this->clave_modulo);
        $datos_plantilla['title_mod'] = $data_modulo['icon'] . ' ' . $data_modulo['nombre'];
        $datos_plantilla['modulos'] = $this->acceso_model->get_modulos();
        $datos_plantilla['permisos'] = $permisos;
        $datos_plantilla['navigate_mod'] = '<li><a onclick="redirect_to(\'inicio\')"><i class="fa fa-th"></i> Menú</a></li> <li><a class="active"> ' . $data_modulo['icon'] . ' ' . $data_modulo['nombre'] . '</a></li>';
        $datos_plantilla['content'] = $this->load->view('reactivo/misreactivos_view', $datos_vista, true);
        $this->load->view('template', $datos_plantilla);
    }

    /* public function datos() {
      $clv_sess = $this->config->item('clv_sess');
      $login = $this->session->userdata('login' . $clv_sess);
      $rol = $this->session->userdata('rol' . $clv_sess);
      if (!$login) {
      redirect('acceso/acceso_denegado');
      }
      $this->load->model('generico_model');
      $roles = $this->config->item('roles');
      $sIndexColumn = "REA_ID";
      $aColumns = array($sIndexColumn, 'REA_CLAVE', 'substring(REA_CONTENIDO,0,300)', 'REA_ESTADO', 'CAS_AUDIO', 'CAS_VIDEO', 'CAS_USUARIO');
      $sTable = "ADM_REACTIVO";

      /* Generar limits con paginacion /
      $sLimit = "";
      $iDisplayStart = $this->input->post('iDisplayStart');
      $iDisplayLength = $this->input->post('iDisplayLength');
      if (isset($iDisplayStart) && $iDisplayLength != '-1') {
      $sLimit = "LIMIT " . $this->input->post('iDisplayStart') . ", " .
      $this->input->post('iDisplayLength');
      }
      /* order /
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
      /* Generar limits con paginacion /
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

      $rResult = $this->generico_model->datosDataTable($aColumns, $sTable, $sWhere, $sOrder, $sLimit);
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
      if ($aColumns[$i] == "usu_rol") {
      try {
      if (array_key_exists($aRow[$aColumns[$i]], $roles)) {
      $sOutput .= '"' . str_replace('"', '\"', $roles[$aRow[$aColumns[$i]]]['rol']) . '",';
      } else {
      $sOutput .= '"' . 'Indefinido' . '",';
      }
      } catch (Exception $e) {
      $sOutput .= '"' . str_replace('"', '\"', $aRow[$aColumns[$i]]) . '",';
      }
      } else if ($aColumns[$i] != ' ') {
      $sOutput .= '"' . str_replace('"', '\"', $aRow[$aColumns[$i]]) . '",';
      }
      }
      $sOutput .= '"' . str_replace('"', '\"', "<button class='btn btn-warning' onclick='modifica(" . $aRow[$sIndexColumn] . ")'><i class=' fa fa-edit'></i></button><button class='btn btn-danger' onclick='elimina(" . $aRow[$sIndexColumn] . ")'><i class=' fa fa-remove'></i></button>") . '",';
      $sOutput = substr_replace($sOutput, "", -1);
      $sOutput .= "],";
      }//forn for
      $sOutput = substr_replace($sOutput, "", -1);
      $sOutput .= '] }';

      echo $sOutput;
      } */
}

?>
