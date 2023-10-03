<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Referencias extends CI_Controller {

    private $clave_modulo = 'REF';
    private $clv_sess = '';

    function __construct() {
        parent::__construct();
        $clv_sess = $this->config->item('clv_sess');
        $user_id = $this->session->userdata('user_id' . $clv_sess);
        if (!$user_id) {
            redirect('acceso');
        }
        $this->load->model('reactivo_model');
        $this->load->model('referencia_model');
    }

    public function index() {

        $this->load->model('acceso_model');
        $user_id = $this->session->userdata('user_id' . $this->clv_sess);
        $permisos = $this->ci_acl_framew->get_parse_array_permisos($this->acceso_model->get_permisosUsuario($user_id, $this->clave_modulo));
        $datos_vista = array();
        if (array_key_exists($this->clave_modulo, $permisos)) {
            $datos_vista['permisos_modulo'] = $permisos[$this->clave_modulo];
        }
        $datos_vista['form_referencias'] = $this->minified_output($this->load->view('referencias/referencia_form_view', false, true));
        //datos modulo
        $data_modulo = $this->acceso_model->get_iconModulo($this->clave_modulo);
        $datos_plantilla['title_mod'] = $data_modulo['icon'] . ' ' . $data_modulo['nombre'];
        $datos_plantilla['modulos'] = $this->acceso_model->get_modulos();
        $datos_plantilla['permisos'] = $permisos;
        $datos_plantilla['navigate_mod'] = '<li><a onclick="redirect_to(\'inicio\')"><i class="fa fa-th"></i> Menú</a></li> <li><a class="active"> ' . $data_modulo['icon'] . ' ' . $data_modulo['nombre'] . '</a></li>';
        $datos_plantilla['content'] = $this->load->view('referencias/referencias_view', $datos_vista, true);
        $this->load->view('template', $datos_plantilla);
    }

    private function minified_output($buffer = '') {
        $search = array('/\>[^\S ]+/s', // strip whitespaces after tags, except space
            '/[^\S ]+\</s', // strip whitespaces before tags, except space
            '/(\s)+/s'       // shorten multiple whitespace sequences
        );
        $replace = array('>', '<', '\\1');
        $out = preg_replace($search, $replace, $buffer);
        return $out;
    }

    /**
     * @brief Funcion que verifica si un usuario se encuentra registrado y si su contraseña es correcta, con repecto al algoritmo de 
     * criptografia asimetrica de clave publica y privada implementada en la clase 'encrypt' de codeigniter @see http://codeigniter.com/user_guide/libraries/encryption.html
     * @access public
     * @param $this->input->post('nick');   String login del usuario
     * @param $this->input->post('clave')  String  Contraseña
     * @return JSON String con los resultados de la validacion
     */
    function acceso_sistema() {
        $this->load->library('encrypt');
        $this->load->model("acceso_model");
        $login = $this->input->post('usuario');
        $pass = $this->input->post('pass');
        $query = $this->acceso_model->datoslogin($login, $pass);
        $clv_sess = $this->config->item('clv_sess');
        $data = array();
        if ($query->num_rows() == 0) {
            $data['sientra'] = 'no';
            $data['mensaje'] = 'El usuario no se encuentra registrado o los datos son incorrectos, por favor intenta de nuevo. ';
        } else {
            $row = $query->row();
            //si la cadena $row->usu_password (decodificada) del set de datos no es igual a $clave resultado=error si es igual resultado=ok 
            if ($this->encrypt->decode($row->USU_PASSWORD) != $pass) {
                $data['sientra'] = 'no';
                $data['mensaje'] = 'La contraseña no correspone con el usuario';
            } else {
                $data['sientra'] = 'ok';
                $this->session->set_userdata('user_id' . $clv_sess, $row->USU_ID);
                $this->session->set_userdata('login' . $clv_sess, $row->USU_LOGIN);
                $this->session->set_userdata('nombre' . $clv_sess, $row->USU_NOMBRE);
                $this->session->set_userdata('rol' . $clv_sess, $row->USU_ROL);
            }
        }
        echo json_encode($data);
    }

    function logout() {
        $this->session->sess_destroy();
        redirect('acceso');
    }

    /**
     * @brief Funcion que muestra una página con el mensaje de acesso denegado, 
     * redirecciona al la pagina de ingresoo base URL
     * @example redirect('acceso/acceso_denegado');
     * @return Página con advertencia de acceso denagado
     * @note Esta página es independiente de cualquier plantilla, si se cambia de proyecto se debe verificar que se cumpla con los archivos 
     * js y css requeridos o en su defecto su adaptación
     * @see  acceso_home() 
     */
    function acceso_denegado() {
        $this->load->view('acceso/acceso_denegado_view');
    }

    /**
     * @brief Funcion que muestra una página con el mensaje de sitio en construcción, util cuando se esta dando matenimiento al sistema 
     * @example redirect('acceso/en_construccion');
     * @return Página con advertencia de sitio en construccion
     * @note Esta página es independiente de cualquier plantilla, si se cambia de proyecto se debe verificar que se cumpla con los archivos 
     * js y css requeridos o en su defecto su adaptación
     * @see  acceso_home() 
     */
    function en_construccion() {
        $this->load->view('acceso/construccion_view');
    }

    /**
     * @brief Funcion que muestra una página con el mensaje de acesso denegado, a diferencia de la función @link acceso_denegado(), esta no 
     * redirecciona al la pagina de ingreso, sino que se especifica una pagina a la cual se redireccionará en caso de no tener suficietes privilegios
     * @param $pag_redirect String 
     * @example redirect('acceso/acceso_home/inicio');
     * @return Página con advertencia de acceso denagado
     * @note Esta página es independiente de cualquier plantilla, si se cambia de proyecto se debe verificar que se cumpla con los archivos 
     * js y css requeridos o en su defecto su adaptación
     * @see  acceso_denegado() 
     */
    function acceso_home($pag_redirect) {
        $data['url'] = $pag_redirect;
        $this->load->view('acceso/home_acces_view', $data);
    }

    function encode($i) {
        $this->load->library('encrypt');
        echo $this->encrypt->encode($i);
    }

    public function get_datos_sitio() {
        $clv_sess = $this->config->item('clv_sess');
        $login = $this->session->userdata('login' . $clv_sess);
        $rol = $this->session->userdata('rol' . $clv_sess);
        if (!$login) {
            redirect('acceso/acceso_denegado');
        }
        $this->load->model('generico_model');
        $roles = $this->config->item('roles');
        $sIndexColumn = "ref_id";
        $aColumns = array($sIndexColumn, 'ref_tipo', 'ref_nombre_sitio', 'ref_autores', 'ref_fecha', 'ref_url', 'ref_descripcion', 'ref_fecha_add', 'ref_fecha_update');
        $sTable = "adm_referencia";

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

            $sOutput .= '"' . str_replace('"', '\"', "<button class='btn btn-warning' onclick='modifica(" . $aRow[$sIndexColumn] . ")'><i class=' fa fa-edit'></i></button><button class='btn btn-danger' onclick='elimina(" . $aRow[$sIndexColumn] . ")'><i class=' fa fa-remove'></i></button>") . '",';
            $sOutput = substr_replace($sOutput, "", -1);
            $sOutput .= "],";
        }//forn for

        $sOutput = substr_replace($sOutput, "", -1);
        $sOutput .= '] }';

        echo $sOutput;
    }

    public function get_datos_periodico() {
        $clv_sess = $this->config->item('clv_sess');
        $login = $this->session->userdata('login' . $clv_sess);
        $rol = $this->session->userdata('rol' . $clv_sess);
        if (!$login) {
            redirect('acceso/acceso_denegado');
        }
        $this->load->model('generico_model');
        $roles = $this->config->item('roles');
        $sIndexColumn = "ref_id";
        $aColumns = array($sIndexColumn, 'ref_tipo', 'ref_titulo', 'ref_autores', 'ref_titulo_periodico', 'ref_fecha', 'ref_paginas', 'ref_descripcion', 'ref_fecha_add', 'ref_fecha_update');
        $sTable = "adm_referencia";

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

            $sOutput .= '"' . str_replace('"', '\"', "<button class='btn btn-warning' onclick='modifica(" . $aRow[$sIndexColumn] . ")'><i class=' fa fa-edit'></i></button><button class='btn btn-danger' onclick='elimina(" . $aRow[$sIndexColumn] . ")'><i class=' fa fa-remove'></i></button>") . '",';
            $sOutput = substr_replace($sOutput, "", -1);
            $sOutput .= "],";
        }//forn for

        $sOutput = substr_replace($sOutput, "", -1);
        $sOutput .= '] }';

        echo $sOutput;
    }

    public function get_datos_revista() {
        $clv_sess = $this->config->item('clv_sess');
        $login = $this->session->userdata('login' . $clv_sess);
        $rol = $this->session->userdata('rol' . $clv_sess);
        if (!$login) {
            redirect('acceso/acceso_denegado');
        }
        $this->load->model('generico_model');
        $roles = $this->config->item('roles');
        $sIndexColumn = "ref_id";
        $aColumns = array($sIndexColumn, 'ref_tipo', 'ref_titulo', 'ref_autores', 'ref_nombre_revista', 'ref_paginas', 'ref_anio', 'ref_editorial', 'ref_descripcion', 'ref_fecha_add', 'ref_fecha_update');
        $sTable = "adm_referencia";

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

            $sOutput .= '"' . str_replace('"', '\"', "<button class='btn btn-warning' onclick='modifica(" . $aRow[$sIndexColumn] . ")'><i class=' fa fa-edit'></i></button><button class='btn btn-danger' onclick='elimina(" . $aRow[$sIndexColumn] . ")'><i class=' fa fa-remove'></i></button>") . '",';
            $sOutput = substr_replace($sOutput, "", -1);
            $sOutput .= "],";
        }//forn for

        $sOutput = substr_replace($sOutput, "", -1);
        $sOutput .= '] }';

        echo $sOutput;
    }

    public function get_datos() {
        $clv_sess = $this->config->item('clv_sess');
        $login = $this->session->userdata('login' . $clv_sess);
        $rol = $this->session->userdata('rol' . $clv_sess);
        if (!$login) {
            redirect('acceso/acceso_denegado');
        }
        $this->load->model('generico_model');
        $roles = $this->config->item('roles');
        $sIndexColumn = "ref_id";
        $aColumns = array($sIndexColumn, 'ref_tipo', 'ref_titulo', 'ref_autores', 'ref_anio', 'ref_ciudad', 'ref_editorial', 'ref_descripcion', 'ref_fecha_add', 'ref_fecha_update');
        $sTable = "adm_referencia";

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

        $rResult = $this->referencia_model->datosDataTableLibro($aColumns, $sTable, $sWhere, $sOrder, $sLimit);
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

            $sOutput .= '"' . str_replace('"', '\"', "<button class='btn btn-warning' onclick='modifica(" . $aRow[$sIndexColumn] . ")'><i class=' fa fa-edit'></i></button><button class='btn btn-danger' onclick='elimina(" . $aRow[$sIndexColumn] . ")'><i class=' fa fa-remove'></i></button>") . '",';
            $sOutput = substr_replace($sOutput, "", -1);
            $sOutput .= "],";
        }//forn for

        $sOutput = substr_replace($sOutput, "", -1);
        $sOutput .= '] }';

        echo $sOutput;
    }

    //-----Agregar referencia--------
    function agregaReferencia() {
        $out = array();
        $datos['ref_fecha_add'] = date('Y-m-d');
        $datos['ref_descripcion'] = str_replace("\n", "<br>", $this->input->post('des'));
        $datos['ref_autores'] = $this->input->post('aut');
        if (strcmp($this->input->post('tipo'), "L") == 0) {
            $datos['ref_titulo'] = $this->input->post('tit');
            $datos['ref_anio'] = $this->input->post('year');
            $datos['ref_ciudad'] = $this->input->post('ciudad');
            $datos['ref_editorial'] = $this->input->post('edi');
            $datos['ref_tipo'] = 'Libro';
        } else if (strcmp($this->input->post('tipo'), "AR") == 0) {
            $datos['ref_titulo'] = $this->input->post('tit');
            $datos['ref_nombre_revista'] = $this->input->post('nameRevista');
            $datos['ref_paginas'] = $this->input->post('pages');
            $datos['ref_anio'] = $this->input->post('year');
            $datos['ref_editorial'] = $this->input->post('edi');
            $datos['ref_tipo'] = 'Articulo de revista';
        } else if (strcmp($this->input->post('tipo'), "AP") == 0) {
            $datos['ref_titulo'] = $this->input->post('tit');
            $datos['ref_titulo_periodico'] = $this->input->post('namePeriodico');
            $datos['ref_fecha'] = $this->input->post('date');
            $datos['ref_paginas'] = $this->input->post('pages');
            $datos['ref_tipo'] = 'Articulo de periodico';
        } else if (strcmp($this->input->post('tipo'), "SW") == 0) {
            $datos['ref_nombre_sitio'] = $this->input->post('name');
            $datos['ref_fecha'] = $this->input->post('date');
            $datos['ref_url'] = $this->input->post('url');
            $datos['ref_tipo'] = 'Sitio web';
        }
        $id_insert = (!empty($datos)) ? $this->referencia_model->agregaReferencia($datos) : FALSE;

        if ($id_insert != false) {
            $out['resp'] = 'ok';
            $out['id'] = $id_insert;
        } else {
            $out['resp'] = 'no';
            $out['msg'] = 'Error al agregar la referencia ' . $datos['ref_titulo'];
        }

        echo json_encode($out);
    }

    function elimina() {
        $id = $this->input->post("id");
        $sepudo = $this->referencia_model->get_elimina($id);
        if ($sepudo) {
            $array_out['resp'] = 'ok';
        } else {
            $array_out['resp'] = 'no';
            $array_out['msg'] = "Se produjo un error al eliminar la referencia.";
        }
        echo json_encode($array_out);
    }

    function getReferencData() {
        $id = $this->input->post("id");
        $data = $this->referencia_model->getDatoRow($id)->row();
        echo json_encode($data);
    }

    function updateReferencias() {
        $out = array();
        $datos['ref_id'] = $this->input->post('id');
        $datos['ref_fecha_update'] = date('Y-m-d');
        $datos['ref_descripcion'] = str_replace("\n", "<br>", $this->input->post('des'));
        if (strcmp($this->input->post('tipo'), "L") == 0) {
            $datos['ref_titulo'] = $this->input->post('tit');
            $datos['ref_autores'] = $this->input->post('aut');
            $datos['ref_anio'] = $this->input->post('year');
            $datos['ref_ciudad'] = $this->input->post('ciudad');
            $datos['ref_editorial'] = $this->input->post('edi');
            $datos['ref_tipo'] = 'Libro';
        } else if (strcmp($this->input->post('tipo'), "AR") == 0) {
            $datos['ref_titulo'] = $this->input->post('tit');
            $datos['ref_autores'] = $this->input->post('aut');
            $datos['ref_nombre_revista'] = $this->input->post('nameRevista');
            $datos['ref_paginas'] = $this->input->post('pages');
            $datos['ref_anio'] = $this->input->post('year');
            $datos['ref_editorial'] = $this->input->post('edi');
            $datos['ref_tipo'] = 'Articulo de revista';
        } else if (strcmp($this->input->post('tipo'), "AP") == 0) {
            $datos['ref_titulo'] = $this->input->post('tit');
            $datos['ref_autores'] = $this->input->post('aut');
            $datos['ref_titulo_periodico'] = $this->input->post('namePeriodico');
            $datos['ref_fecha'] = $this->input->post('date');
            $datos['ref_paginas'] = $this->input->post('pages');
            $datos['ref_tipo'] = 'Articulo de periodico';
        } else if (strcmp($this->input->post('tipo'), "SW") == 0) {
            $datos['ref_nombre_sitio'] = $this->input->post('name');
            $datos['ref_autores'] = $this->input->post('aut');
            $datos['ref_fecha'] = $this->input->post('date');
            $datos['ref_url'] = $this->input->post('url');
            $datos['ref_tipo'] = 'Sitio web';
        }

        $update_ok = (!empty($datos)) ? $this->referencia_model->get_update($datos) : FALSE;
        if ($update_ok != false) {
            $out['resp'] = 'ok';
            $out['id'] = $update_ok;
        } else {
            $out['resp'] = 'no';
            $out['msg'] = 'Error al agregar la referencia ' . $datos['REF_TITULO'];
        }
        echo json_encode($out);
    }

    function searchPer() {
        $datos['ref_titulo'] = $_POST['titulo_articulo_periodico_referencia_modifica'];
        $id_insert = $this->referencia_model->searchRow($datos);

        if ($id_insert != false) {
            echo "true";
        } else {
            echo "false";
        }
    }

    function searchRev() {
        $datos['ref_titulo'] = $_POST['titulo_artticulo_revista_referencia_modifica'];
        $id_insert = $this->referencia_model->searchRow($datos);

        if ($id_insert != false) {
            echo "true";
        } else {
            echo "false";
        }
    }

    function searchReg() {
        $datos['ref_titulo'] = $_POST['titulo_libro_referencia_modifica'];

        $id_insert = $this->referencia_model->searchRow($datos);

        if ($id_insert != false) {
            echo "true";
        } else {
            echo "false";
        }
    }

}

?>