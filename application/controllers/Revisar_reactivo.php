<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Revisar_reactivo extends CI_Controller {

    function __construct() {
        parent::__construct();
        $clv_sess = $this->config->item('clv_sess');
        $user_id = $this->session->userdata('user_id' . $clv_sess);
        if (!$user_id) {
            redirect('acceso');
        }
        $this->load->model('revisar_reactivo_model');
    }

    public function index() {
        $data['contenido'] = $this->load->view('revisar_reactivo_view', '', true);
        $this->load->view('plantilla', $data);
    }

    public function datos() {
        $clv_sess = $this->config->item('clv_sess');
        $login = $this->session->userdata('login' . $clv_sess);
        $rol = $this->session->userdata('rol' . $clv_sess);
        if (!$login) {
            redirect('acceso/acceso_denegado');
        }
        $this->load->model('generico_model');
        $roles = $this->config->item('roles');
        $sIndexColumn = "id";
        $aColumns = array($sIndexColumn, 'clave', 'contenido', 'estado', 'plan', 'autor', 'caso', 'fechaalta');
        $sTable = "view_revisar_reactivos";

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
                if ($aColumns[$i] == "contenido") {
                    try {
                        $contenSinTags = substr(str_replace("\r\n", " ", strip_tags($aRow[$aColumns[$i]])),0,150);
                        if ($contenSinTags==''){
                            $contenSinTags='No hay vista previa';
                        }
                        $sOutput .= '"' . str_replace('"', '\"', $contenSinTags) . '",';
                    } catch (Exception $e) {
                        $sOutput .= '"' . str_replace('"', '\"', $aRow[$aColumns[$i]]) . '",';
                    }
                } else if ($aColumns[$i] != ' ') {
                    $sOutput .= '"' . str_replace('"', '\"', $aRow[$aColumns[$i]]) . '",';
                }
            }
            $edo = $aRow['estado'];
            $class = 'default';
            if ($edo == 'Revisado') {
                $class = 'success';
            } else if ($edo == 'En revisión') {
                $class = 'warning';
            } else if ($edo == 'En captura') {
                $class = 'danger';
            }
            $html_st = "";
            $html_st = "<div id='bg_" . $aRow[$sIndexColumn] . "' class='btn-group'>
              <button type='button' class='btn btn-$class dropdown-toggle' data-toggle='dropdown' aria-expanded='false'>
              " . $edo . " <span class='caret'></span>
              </button>
              <ul class='dropdown-menu' role='menu'>
              <li><a class='btn-danger ddw_it' onclick='set_st(" . $aRow[$sIndexColumn] . ",\"C\")'>En captura</a></li>
              <li><a class='btn-warning ddw_it' onclick='set_st(" . $aRow[$sIndexColumn] . ",\"R\")'>En revisión</a></li>
              <li><a class='btn-success ddw_it' onclick='set_st(" . $aRow[$sIndexColumn] . ",\"A\")'>Revisado</a></li>
              </ul>
              </div>"; 
            $sOutput .= '"' . str_replace('"', '\"', preg_replace("/[\r\n]*/", "", $html_st)) . '",';
            $sOutput = substr_replace($sOutput, "", -1);
            $sOutput .= "],";
        }//forn for
        $sOutput = substr_replace($sOutput, "", -1);
        $sOutput .= '] }';

        echo $sOutput;
    }

    function cambiaEstadoReactivo() {
        $jsonresp = array();
        $id = $this->input->post('id');
        $st = $this->input->post('st');
        $comentario = $this->input->post('com');
        $clv_sess = $this->config->item('clv_sess');
        $user_id = $this->session->userdata('user_id' . $clv_sess);
        $datos['rea_usuariovalido'] = $user_id;
        $datos['rea_estado'] = $st;
        $datos['rea_comentariovalidacion'] = $comentario;
        $datos['rea_fechavalidacion'] = date('Y-m-d');
        $sepudo = $this->revisar_reactivo_model->get_cambiaEstadoReactivo($id, $datos);
        if ($sepudo) {
            $jsonresp['resp'] = 'ok';
            $jsonresp['msg'] = 'Se modificó satisfactoriamente el estado del reactivo.';
        } else {
            $jsonresp['resp'] = 'no';
            $jsonresp['msg'] = 'Error al modificar el reactivo. Intenta más tarde.';
        }
        echo json_encode($jsonresp);
    }

}

?>