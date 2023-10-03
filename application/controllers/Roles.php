<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Roles extends CI_Controller {

    private $clave_modulo = 'ROL';
    private $clv_sess = '';
    private $rol_id;
    private $user_id;

    function __construct() {
        parent::__construct();
        $this->clv_sess = $this->config->item('clv_sess');
        $this->user_id = $this->session->userdata('user_id' . $this->clv_sess);
        $this->rol_id = $this->session->userdata('id_rol' . $this->clv_sess);
        if (!$this->user_id) {
            redirect('inicio');
        }
        $this->load->model("roles_model");
    }

    public function index() {
        $this->load->model('acceso_model');
        $this->acceso_model->get_permisosUsuario($this->user_id, $this->clave_modulo);
        $permisos = $this->ci_acl_framew->get_parse_array_permisos($this->acceso_model->get_permisosUsuario($this->user_id, $this->clave_modulo));
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
        $datos_plantilla['content'] = $this->load->view('roles/roles_view', $datos_vista, true);
        $this->load->view('template', $datos_plantilla);
    }

    public function datos() {
        // verificar permisos del modulo
        $this->load->model('acceso_model');
        $permisos_db = $this->acceso_model->get_permisosUsuario($this->session->userdata('user_id' . $this->clv_sess), $this->clave_modulo);
        $permisos = $this->ci_acl_framew->get_parse_array_permisos($permisos_db);
        if (array_key_exists($this->clave_modulo, $permisos)) {
            $permisos_modulo = $permisos[$this->clave_modulo];
        } else {
            redirect('acceso/acceso_denegado');
        }
        $this->load->model('generico_model');
        $sIndexColumn = "rol_id";
        $aColumns = array($sIndexColumn, 'rol_clave', 'rol_nombre', 'rol_descripcion');
        $sTable = "acl_rol";

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
        $rResult = $this->generico_model->datosDataTable($aColumns, $sTable, $sWhere, $sOrder, $sLimit,$this->rol_id,$this->user_id);
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
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "") {
                    $sOutput .= '"' . str_replace('"', '\"', $aRow[$aColumns[$i]]) . '",';
                } else if ($aColumns[$i] != ' ') {
                    $sOutput .= '"' . str_replace('"', '\"', $aRow[$aColumns[$i]]) . '",';
                }
            }
            $upd = $del = $per = '';
            if (isset($permisos_modulo) && in_array('per', $permisos_modulo)) {
                $per = "<button class='btn btn-info opcdt' title='Ver permisos' onclick='ver_permisos(" . $aRow[$sIndexColumn] . ")'><i class=' fa fa-lock'></i></button>";
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

    public function update($id = 0) {
        $datos = array();
        $this->load->model('generico_model');
        $this->load->model('acceso_model');
        $user_id = $this->session->userdata('user_id' . $this->clv_sess);
        $permisos = $this->ci_acl_framew->get_parse_array_permisos($this->acceso_model->get_permisosUsuario($user_id, $this->clave_modulo));
        $permisos_db_rol = $this->ci_acl_framew->get_parse_array_permisos($this->roles_model->get_permisosRol($id));
        $log = true;
        $accion = '';

        if ($id != 0) {
            if (!in_array('upd', $permisos[$this->clave_modulo])) {
                redirect('roles');
            }
            $datos_vista['datos_modifica'] = $this->roles_model->get_datos_modifica($id);
            $accion = 'Modificar Rol';
            $datos_plantilla_modulo['sub_titulo'] = '<i class="fa fa-edit"></i> ' . $accion;
        } else {
            if (!in_array('add', $permisos[$this->clave_modulo])) {
                redirect('roles');
            }
            $datos_vista['datos_modifica'] = false;
            $accion = 'Agregar Rol';
            $datos_plantilla_modulo['sub_titulo'] = '<i class="fa fa-plus"></i> ' . $accion;
        }
        //datos vista
        $datos_vista['modulos'] = $this->parser_modulos();
        $datos_vista['permisos_modulo'] = $permisos_db_rol;
        //datos vista
        $datos_vista['clave_modulo'] = $this->clave_modulo;
        //datos modulo
        $data_modulo = $this->acceso_model->get_iconModulo($this->clave_modulo);
        //datos plantilla
        $datos_plantilla['title_mod'] = $data_modulo['icon'] . ' ' . $data_modulo['nombre'] . ' <small>' . $accion . '</small>';
        $datos_plantilla['navigate_mod'] = '<li><a onclick="redirect_to(\'inicio\')"><i class="fa fa-th"></i> Menú</a></li> <li><a onclick="redirect_to(\'roles\')"> ' . $data_modulo['icon'] . ' ' . $data_modulo['nombre'] . '</a></li>
            <li class="active">' . $accion . '</li>';
        $datos_plantilla['content'] = $this->load->view('roles/roles_update', $datos_vista, true);
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
        $this->load->model('acceso_model');
        $jsonresp = array();
        $userid = $this->session->userdata('user_id' . $this->clv_sess);
        $data_update['rol_clave'] = $this->input->post('clave');
        $data_update['rol_nombre'] = $this->input->post('nombre');
        $data_update['rol_descripcion'] = $this->input->post('descripcion');
        $chk_restaura_permisos = $this->input->post('chk_restaura_permisos');
        $planRol = explode(",", $this->input->post('ramasRol'));
        $permisos_post = $this->input->post('permisos');
        $permisos = $this->ci_acl_framew->get_parse_array_permisos($this->acceso_model->get_permisosUsuario($userid, $this->clave_modulo));
        if ($id != 0) {
            if (!in_array('upd', $permisos[$this->clave_modulo])) {
                redirect('roles');
            }
            $data_update['rol_usuariomodifico'] = $userid;
            $data_update['rol_fechamodifico'] = date('Y-m-d');
            $sepudo = $this->roles_model->getModifica($id, $data_update, $permisos_post, $chk_restaura_permisos, $planRol);
            if ($sepudo) {
                $jsonresp['resultado'] = 'ok';
                $jsonresp['mensaje'] = 'Se modificó satisfactoriamente al rol ' . $data_update['rol_nombre'];
            } else {
                $jsonresp['resultado'] = 'no';
                $jsonresp['mensaje'] = 'Error al modificar el rol ' . $data_update['rol_nombre'];
            }
        } else {
            if (!in_array('add', $permisos[$this->clave_modulo])) {
                redirect('roles');
            }
            $data_update['rol_usuarioagrego'] = $userid;
            $data_update['rol_fechaagrego'] = date('Y-m-d');
            $sepudo = $this->roles_model->getAgrega($data_update, $permisos_post, $planRol);
            if ($sepudo) {
                $jsonresp['resultado'] = 'ok';
                $jsonresp['mensaje'] = 'Se agregó satisfactoriamente el rol ' . $data_update['rol_nombre'];
            } else {
                $jsonresp['resultado'] = 'no';
                $jsonresp['mensaje'] = 'Error al agregar el rol ' . $data_update['rol_nombre'];
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
            $this->load->model('acceso_model');
            $user_id = $this->session->userdata('user_id' . $this->clv_sess);
            $permisos = $this->ci_acl_framew->get_parse_array_permisos($this->acceso_model->get_permisosUsuario($user_id, $this->clave_modulo));
            if (!in_array('upd', $permisos[$this->clave_modulo])) {
                redirect('roles');
            }
            $id = $this->input->Post("id");
            $sepudo = $this->roles_model->getElimina($id);
            if ($sepudo) {
                $array_out['resp'] = 'ok';
            } else {
                $array_out['resp'] = 'no';
                $array_out['msg'] = "Se produjo un error al eliminar el rol.";
            }
            echo json_encode($array_out);
        } else {
            echo 'no ajax';
        }
    }

    function test($rol) {
        //$rol_id = $this->session->userdata('user_id' . $this->clv_sess);
        $rol_id = $rol;
        $this->roles_model->llenaPlanesRoles($rol_id);
    }

}
