<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Seguimiento extends CI_Controller {

    private $clave_modulo = 'SEG';
    private $clv_sess = '';
    private $user_id;

    function __construct() {
        parent::__construct();
        $this->clv_sess = $this->config->item('clv_sess');
        $this->user_id = $this->session->userdata('user_id' . $this->clv_sess);
        if (!$this->user_id) {
            redirect('inicio');
        }
        $this->load->model("seguimiento_model");
        $this->load->model("acceso_model");
        $this->load->model("generico_model");
    }

    public function index() {
        $user_id = $this->session->userdata('user_id' . $this->clv_sess);
        $this->acceso_model->get_permisosUsuario($user_id, $this->clave_modulo);
        $permisos = $this->ci_acl_framew->get_parse_array_permisos($this->acceso_model->get_permisosUsuario($user_id, $this->clave_modulo));
        //datos vista
        if (array_key_exists($this->clave_modulo, $permisos)) {
            $datos_vista['permisos_modulo'] = $permisos[$this->clave_modulo];
        } else {
            redirect('inicio');
        }
        //datos modulo
        $data_modulo = $this->acceso_model->get_iconModulo($this->clave_modulo);
        //datos plantilla
        $datos_plantilla['title_mod'] = $data_modulo['icon'] . ' ' . $data_modulo['nombre'];
        $datos_plantilla['navigate_mod'] = '<li><a onclick="redirect_to(\'inicio\')"><i class="fa fa-th"></i> Menú</a></li> <li><a class="active"> ' . $data_modulo['icon'] . ' ' . $data_modulo['nombre'] . '</a></li>';
        $datos_plantilla['content'] = $this->load->view('seguimiento/seguimiento_index_view', $datos_vista, true);
        $this->load->view('template', $datos_plantilla);
    }

    public function datos() {
        $permisos_db = $this->acceso_model->get_permisosUsuario($this->session->userdata('user_id' . $this->clv_sess), $this->clave_modulo);
        $permisos = $this->ci_acl_framew->get_parse_array_permisos($permisos_db);
        if (array_key_exists($this->clave_modulo, $permisos)) {
            $permisos_modulo = $permisos[$this->clave_modulo];
        } else {
            redirect('acceso/acceso_denegado');
        }
        $sIndexColumn = "seg_id";
        $aColumns = array($sIndexColumn, 'seg_nombre_rama', 'seg_responsable_elaboracion', 'seg_fecha_entrega_elaboracion', 'responsable_captura', 'seg_fecha_entrega_captura', 'seg_rama_enrevision', 'seg_total_reactivos_a_capturar', 'reactivos_capturados', "seg_responsable_captura");
        $sTable = "view_seguimiento";

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
            $row['DT_RowClass'] = 'class';
            $sOutput .= "[";
            $fechaEntregaRea = '';
            $reaCapturados = $totalReaACapturar = 0;
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "") {
                    $sOutput .= '"' . str_replace('"', '\"', $aRow[$aColumns[$i]]) . '",';
                } else if ($aColumns[$i] != ' ') {
                    $sOutput .= '"' . str_replace('"', '\"', $aRow[$aColumns[$i]]) . '",';
                }
            }
            $fechaEntregaRea = $aRow['seg_fecha_entrega_captura'];
            $reaCapturados = $aRow['reactivos_capturados'];
            $totalReaACapturar = $aRow['seg_total_reactivos_a_capturar'];
            if ($reaCapturados < $totalReaACapturar && date('Y-m-d') > $fechaEntregaRea) {
                $this->enviaCorreo($aRow["seg_responsable_captura"], "Entrega de reactivos.", "aexiuv19@gmail.com");
            }
            $upd = $del = $per = '';
            if (isset($permisos_modulo) && in_array('olr', $permisos_modulo)) {
                $per = "<button style='display:none;' class='btn btn-info opcdt' id='btn_envia_correo_" . $aRow[$sIndexColumn] . "' title='Ver detalles' onclick='enviarCorreo(" . $aRow[$sIndexColumn] . ")'><i class=' fa fa-lock'></i></button>";
            }
            if (isset($permisos_modulo) && in_array('upd', $permisos_modulo)) {
                $upd = "<button class='btn btn-warning opcdt' title='Modificar rol' onclick='modifica(" . $aRow[$sIndexColumn] . ")'><i class=' fa fa-edit'></i></button>";
            }
            if (isset($permisos_modulo) && in_array('del', $permisos_modulo)) {
                $del = "<button class='btn btn-danger opcdt' title='Eliminar rol' onclick='elimina(" . $aRow[$sIndexColumn] . ")'><i class=' fa fa-remove'></i></button>";
            }
            $sOutput .= '"' . str_replace('"', '\"', $upd . $del . $per) . '",';
            $sOutput = substr_replace($sOutput, "", -1);
            $sOutput .= "],";
        }//forn for
        $sOutput = substr_replace($sOutput, "", -1);
        $sOutput .= '] }';

        echo $sOutput;
    }

    private function enviaCorreo($id_user, $subject, $email_from = 'aexiuv19@gmail.com') {
        $datosUsuario = $this->seguimiento_model->getUsuario($id_user);
        if ((date('Y-m-d') > $datosUsuario[0]['fecha_correo_enviado']) || $datosUsuario[0]['fecha_correo_enviado'] == '') {
            $config = Array(
                'mailtype' => 'html',
                'charset' => 'utf-8'
            );
            $this->load->library('email', $config);
            $this->email->from($email_from, "Admin Re, recordatorio");
            $this->email->to($datosUsuario[0]['usu_correo']);
            $this->email->subject($subject);
            $body = $this->load->view('email-templates/recordatorio_reactivos_view', $datosUsuario[0], TRUE);
            $this->email->message($body);
            if ($this->email->send()) {
                $this->seguimiento_model->registraCorreoEnviado($this->user_id, $id_user);
                $this->email->from($email_from, "Admin RE.");
                $this->email->to("ggalvez.joyce@gmail.com");
                $this->email->subject($subject);
                $body = $this->load->view('email-templates/recordatorio_reactivos_admin_view', $datosUsuario[0], TRUE);
                $this->email->message($body);
                $this->email->send();
            }
        }
    }

    public function update($id = 0) {
        //$this->output->enable_profiler(true);
        $user_id = $this->session->userdata('user_id' . $this->clv_sess);
        $permisos = $this->ci_acl_framew->get_parse_array_permisos($this->acceso_model->get_permisosUsuario($user_id, $this->clave_modulo));
        $accion = '';
        if ($id != 0) {
            if (!in_array('upd', $permisos[$this->clave_modulo])) {
                redirect('seguimiento');
            }
            $datos_vista['datos_modifica'] = $this->seguimiento_model->get_datos_modifica($id);
            $datos_vista['ruta_rama'] = $this->seguimiento_model->get_ruta_rama($datos_vista['datos_modifica'][0]['seg_id_rama']);

            $accion = 'Modificar seguimiento';
            $datos_plantilla_modulo['sub_titulo'] = '<i class="fa fa-edit"></i> ' . $accion;
        } else {
            if (!in_array('add', $permisos[$this->clave_modulo])) {
                redirect('seguimiento');
            }
            $datos_vista['datos_modifica'] = false;
            $accion = 'Agregar seguimiento';
            $datos_plantilla_modulo['sub_titulo'] = '<i class="fa fa-plus"></i> ' . $accion;
        }
        //datos vista
        $datos_vista['modulos'] = $this->parser_modulos();
        $datos_vista['usuarios'] = $this->seguimiento_model->getUsuarios();
        $datos_vista['clave_modulo'] = $this->clave_modulo;
        //datos modulo
        $data_modulo = $this->acceso_model->get_iconModulo($this->clave_modulo);
        //datos plantilla
        $datos_plantilla['title_mod'] = $data_modulo['icon'] . ' ' . $data_modulo['nombre'] . ' <small>' . $accion . '</small>';
        $datos_plantilla['navigate_mod'] = '<li><a onclick="redirect_to(\'inicio\')"><i class="fa fa-th"></i> Menú</a></li> <li><a onclick="redirect_to(\'seguimiento\')"> ' . $data_modulo['icon'] . ' ' . $data_modulo['nombre'] . '</a></li>
            <li class="active">' . $accion . '</li>';
        $datos_plantilla['content'] = $this->load->view('seguimiento/seguimiento_update_view', $datos_vista, true);
        $this->load->view('template', $datos_plantilla);
    }

    private function parser_permisos_modulo_rol($idrol) {
        $out = array();
        $data_modulos = $this->roles_model->get_permisos_modulo_rol($idrol);
        foreach ($data_modulos as $mod) {
            if (!array_key_exists($mod['moduloid'], $out)) {
                $out[$mod['moduloid']] = array();
                $out[$mod['moduloid']]['permisos'] = array();
                array_push($out[$mod['moduloid']]['permisos'], $mod['permisoid']);
            } else {
                array_push($out[$mod['moduloid']]['permisos'], $mod['permisoid']);
            }
        }
        return $out;
    }

    function getupdate($id = 0) {
        $jsonresp = array();
        $userid = $this->session->userdata('user_id' . $this->clv_sess);
        $ramas = $this->input->post('ramas');
        $data_update['seg_elaboracion_rea'] = $this->input->post('elaboracion_rea');
        $data_update['seg_responsable_elaboracion'] = $this->input->post('responsable_elaboracion_rea');
        $data_update['seg_fecha_entrega_elaboracion'] = $this->input->post('fecha_entrega_rea');
        $data_update['seg_captura_adminre'] = $this->input->post('captura_rea');
        $data_update['seg_responsable_captura'] = $this->input->post('responsable_captura_rea');
        $data_update['seg_fecha_entrega_captura'] = $this->input->post('fecha_entrega_captura_rea');
        $data_update['seg_rama_enrevision'] = $this->input->post('rama_en_revision');
        $data_update['seg_responsable_revision'] = $this->input->post('responsable_revision_rea');
        $data_update['seg_fecha_termino_revision'] = $this->input->post('fecha_termino_revision');
        $data_update['seg_total_reactivos_a_capturar'] = $this->input->post('total_reactivos_capturar');
        $data_update['seg_id_rama'] = $this->input->post('idRama');
        $data_update['seg_nombre_rama'] = $this->input->post('nombreRama');


        $permisos = $this->ci_acl_framew->get_parse_array_permisos($this->acceso_model->get_permisosUsuario($userid, $this->clave_modulo));

        if ($id != 0) {
            if (!in_array('upd', $permisos[$this->clave_modulo])) {
                redirect('seguimiento');
            }
            $data_update['seg_usu_id'] = $userid;
            $data_update['seg_fecha_modifico'] = date('Y-m-d');
            $sepudo = $this->seguimiento_model->getModifica($id, $data_update);
            if ($sepudo) {
                $jsonresp['resultado'] = 'ok';
                $jsonresp['mensaje'] = 'Se modificó satisfactoriamente el registro';
            } else {
                $jsonresp['resultado'] = 'no';
                $jsonresp['mensaje'] = 'Error al modificar el rol ' . $data_update['rol_nombre'];
            }
        } else {
            if (!in_array('add', $permisos[$this->clave_modulo])) {
                redirect('seguimiento');
            }
            $data_update['seg_usu_id'] = $userid;
            $data_update['seg_fecha_agrego'] = date('Y-m-d');
            $sepudo = $this->seguimiento_model->getAgrega($data_update);
            if ($sepudo) {
                $jsonresp['resultado'] = 'ok';
                $jsonresp['mensaje'] = 'Se agregó satisfactoriamente el registro';
            } else {
                $jsonresp['resultado'] = 'no';
                $jsonresp['mensaje'] = 'Error al agregar el registro';
            }
        }

        echo json_encode($jsonresp);
    }

    private function parser_modulos() {
        $out = array();
        $this->load->model("usuarios_sistema_model");
        $data_modulos = $this->usuarios_sistema_model->get_modulos();
        foreach ($data_modulos as $mod) {
            if (!array_key_exists($mod['moduloid'], $out)) {
                $out[$mod['moduloid']] = array();
                $out[$mod['moduloid']]['nom'] = $mod['modulo'];
                $out[$mod['moduloid']]['clv'] = $mod['modclave'];
                $out[$mod['moduloid']]['ico'] = $mod['moduloicono'];
                $out[$mod['moduloid']]['permisos'] = array();
                array_push($out[$mod['moduloid']]['permisos'], array('pid' => $mod['permisoid'], 'permisoclave' => $mod['permisoclave'], 'pnom' => $mod['permiso'], 'pdes' => $mod['permisodesc']));
            } else {
                array_push($out[$mod['moduloid']]['permisos'], array('pid' => $mod['permisoid'], 'permisoclave' => $mod['permisoclave'], 'pnom' => $mod['permiso'], 'pdes' => $mod['permisodesc']));
            }
        }
        return $out;
    }

    function elimina() {
        if ($this->input->is_ajax_request()) {
            $user_id = $this->session->userdata('user_id' . $this->clv_sess);
            $permisos = $this->ci_acl_framew->get_parse_array_permisos($this->acceso_model->get_permisosUsuario($user_id, $this->clave_modulo));
            if (!in_array('del', $permisos[$this->clave_modulo])) {
                redirect('seguimiento');
            }
            $id = $this->input->post("id");
            $sepudo = $this->seguimiento_model->getElimina($id);
            if ($sepudo) {
                $array_out['resp'] = 'ok';
            } else {
                $array_out['resp'] = 'no';
                $array_out['msg'] = "Se produjo un error al eliminar el registro.";
            }
            echo json_encode($array_out);
        } else {
            echo 'no ajax';
        }
    }

    function test() {
        $this->load->view('email-templates/recordatorio_reactivos_view', false);
    }

}
