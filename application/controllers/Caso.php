<?php

/**
 * Este Archivo controller reactivo contiene funciones utilizadas dentro de la página de "Reactivo"
 *
 * @package    Adminre
 * @subpackage Comun
 * @author     Jose Adrian Ruiz <sakcret@gmail.com >
 */
class Caso extends CI_Controller {

    private $clave_modulo = 'CAS';
    private $clv_sess = '';

    function __construct() {
        parent::__construct();
        $clv_sess = $this->config->item('clv_sess');
        $user_id = $this->session->userdata('user_id' . $clv_sess);
        if (!$user_id) {
            redirect('acceso');
        }
        $this->load->model("caso_model");
    }

    public function index() {
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
        $datos_plantilla['content'] = $this->load->view('caso/caso_view', $datos_vista, true);
        $this->load->view('template', $datos_plantilla);
    }

    protected function get_full_url() {
        $https = !empty($_SERVER['HTTPS']) && strcasecmp($_SERVER['HTTPS'], 'on') === 0;
        return
                ($https ? 'https://' : 'http://') .
                (!empty($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER'] . '@' : '') .
                (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ($_SERVER['SERVER_NAME'] .
                ($https && $_SERVER['SERVER_PORT'] === 443 ||
                $_SERVER['SERVER_PORT'] === 80 ? '' : ':' . $_SERVER['SERVER_PORT']))) .
                substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/'));
    }

    protected function get_server_var($id) {
        return isset($_SERVER[$id]) ? $_SERVER[$id] : '';
    }

    function up() {
        $caso_actual = $this->getActualCaso();
        $url = "/media_casos/caso" . $caso_actual . '/';
        error_reporting(E_ALL | E_STRICT);
        $options = array(
            'script_url' => $this->get_full_url() . '/',
            'upload_dir' => dirname($this->get_server_var('SCRIPT_FILENAME')) . $url,
            'upload_url' => $this->get_full_url() . $url,
            'user_dirs' => false,
            'mkdir_mode' => 0755,
            'param_name' => 'files');
        $this->load->library('UploadHandler', $options);
    }

    //-----guardar Caso-----------------------------------------
    /**
     * guarda un nuevo caso o actuatiza uno existente.
     * @param int cclave, identificador del registro.
     * @param char titulo, titulo de caso.
     * @param int cvreproduccion, numero de veces a reproducir el video.
     * @param int cvauto, 1 si se reproducira automaticamente del video.
     * @param int cvpauseo, 1 si se permitirá el pauseo del video.
     * @param int careproduccion, numero de veces que se repetirá el audio.
     * @param int caauto, 1 si se reproducirá automaticamente el audio.
     * @param int capauseo, 1 si se permitira el pauseo del audio.
     * @param int user_clave, identificador del usuario de la session abierta. 
     * @param cvideo, link del video.
     * @param caudio, link del audio.
     * @return char $cas, mensaje de registro guardado.
     */
    function guardarCaso() {
        $json_out = array();
        $datosCaso = array(//arreglo datos del caso
            'cas_titulo' => str_replace(array("\r\n", "\r", "\n", "\\n"), "", $this->input->post('cas_titulo')),
            'cas_contenido' => str_replace(array("\r\n", "\r", "\n", "\\n"), "", $this->input->post('cas_contenido')),
            'cas_instruccion' => str_replace(array("\r\n", "\r", "\n", "\\n"), "", $this->input->post('cas_instruccion')),
            'cas_imagen' => $this->input->post('img'),
            'cas_audio' => $this->input->post('aud'),
            'cas_video' => $this->input->post('vid'),
        );
        $clv_sess = $this->config->item('clv_sess');
        $user_id = $this->session->userdata('user_id' . $clv_sess);

        $datosCaso['cas_usuario'] = $user_id;
        $datosCaso['cas_fechaalta'] = date('Y-m-d');
        $this->load->model('caso_model');
        $idinsert = $this->caso_model->insertDatosCaso($datosCaso);
        if ($idinsert != FALSE) {
            $json_out['resp'] = 'ok';
            $json_out['act'] = 'add';
            $json_out['idins'] = $idinsert;
            $this->setActualCaso($idinsert);
        } else {
            $json_out['resp'] = 'no';
            $json_out['msg'] = 'Error al guardar el caso.';
        }
        echo json_encode($json_out);
    }

    function casoactual() {
        $clv_sess = $this->config->item('clv_sess');
        echo $this->session->userdata('id_caso_tmp' . $clv_sess);
    }

    private function getActualCaso() {
        $clv_sess = $this->config->item('clv_sess');
        return $this->session->userdata('id_caso_tmp' . $clv_sess);
    }

    function setActualCaso($id) {
        $clv_sess = $this->config->item('clv_sess');
        $this->session->set_userdata('id_caso_tmp' . $clv_sess, $id);
    }

    public function datos() {
        $clv_sess = $this->config->item('clv_sess');
        $login = $this->session->userdata('login' . $clv_sess);
        $rol = $this->session->userdata('id_rol' . $clv_sess);

        $user_id = $this->session->userdata('user_id' . $clv_sess);
        if (!$login) {
            redirect('acceso/acceso_denegado');
        }
        $this->load->model('generico_model');
        $roles = $this->config->item('roles');
        $sIndexColumn = "cas_id";
        $aColumns = array($sIndexColumn, 'cas_titulo', 'cas_instruccion', 'substring(cas_contenido,1,300)', 'cas_imagen', 'cas_audio', 'cas_video', 'cas_usuario', 'cas_fechaalta');
        $sTable = "adm_caso";

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

        $rResult = $this->generico_model->datosDataTable($aColumns, $sTable, $sWhere, $sOrder, $sLimit,$rol,$user_id,"cas_usuario");
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
                for ($i = 0; $i < count($aColumns); $i++) {
                    if ($aColumns[$i] == "substring(cas_contenido,1,300)") {
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
            }
            $sOutput .= '"' . str_replace('"', '\"', "<button class='btn btn-warning' onclick='modifica(" . $aRow[$sIndexColumn] . ")'><i class=' fa fa-edit'></i></button><button class='btn btn-danger' onclick='elimina(" . $aRow[$sIndexColumn] . ")'><i class=' fa fa-remove'></i></button>") . '",';
            $sOutput = substr_replace($sOutput, "", -1);
            $sOutput .= "],";
        }//forn for
        $sOutput = substr_replace($sOutput, "", -1);
        $sOutput .= '] }';

        echo $sOutput;
    }

    public function update($id = 0) {
        $datos = array();
        $id = $id * 1;
        $this->load->model('plan_model');
        $this->load->model('acceso_model');
        $user_id = $this->session->userdata('user_id' . $this->clv_sess);
        $permisos = $this->ci_acl_framew->get_parse_array_permisos($this->acceso_model->get_permisosUsuario($user_id, $this->clave_modulo));

        $datos_vista['titulo'] = "Casos";
        $datos_vista['encabezado'] = "CASOS";
        if (array_key_exists($this->clave_modulo, $permisos)) {
            $datos_vista['permisos_modulo'] = $permisos[$this->clave_modulo];
        }
        $actionTxt = 'Agregar';
        if ($id != 0) {
            $actionTxt = 'Modificar';
            $datos_vista['datos_modifica'] = $this->caso_model->get_datos_modifica($id);
        } else {
            $datos_vista['datos_modifica'] = false;
        }
        //datos modulo
        $data_modulo = $this->acceso_model->get_iconModulo($this->clave_modulo);
        $datos_plantilla['title_mod'] = $data_modulo['icon'] . ' ' . $data_modulo['nombre'] . ' <small>' . $actionTxt . '</small>';
        $datos_plantilla['modulos'] = $this->acceso_model->get_modulos();
        $datos_plantilla['permisos'] = $permisos;
        $datos_plantilla['navigate_mod'] = '<li><a onclick="redirect_to(\'inicio\')"><i class="fa fa-th"></i> Menú</a></li> <li><a onclick="redirect_to(\'caso\')"> ' . $data_modulo['icon'] . ' ' . $data_modulo['nombre'] . '</a></li>
            <li class="active">' . $actionTxt . '</li>';
        $datos_plantilla['content'] = $this->load->view('caso/update', $datos_vista, true);
        $this->load->view('template', $datos_plantilla);
    }

    function getCaso() {
        $id = $this->input->post('id');
        $data = $this->caso_model->get_datos_modifica($id);
        echo json_encode($data);
    }

    function elimina() {
        $id = $this->input->post("id");
        $sepudo = $this->caso_model->get_elimina($id);
        if ($sepudo) {
            $array_out['resp'] = 'ok';
        } else {
            $array_out['resp'] = 'no';
            $array_out['msg'] = "Se produjo un error al eliminar el usuario.";
        }
        echo json_encode($array_out);
    }

    public function getupdate($id = 0) {
        $json_out = array();
        $data_insert = array(//arreglo datos del caso
            'cas_titulo' => $this->input->post('cas_titulo'),
            'cas_contenido' => $this->input->post('cas_contenido'),
            'cas_instruccion' => $this->input->post('cas_instruccion'),
            'cas_imagen' => $this->input->post('img'),
            'cas_audio' => $this->input->post('aud'),
            'cas_video' => $this->input->post('vid'),
        );
        $clv_sess = $this->config->item('clv_sess');
        $userid = $this->session->userdata('user_id' . $clv_sess);

        if ($id != 0) {
            $data_insert['cas_usuariomodifico'] = $userid;
            $data_insert['cas_fechamodifico'] = date('Y-m-d');
            $sepudo = $this->caso_model->get_modifica($id, $data_insert);
            if ($sepudo) {
                $jsonresp['resultado'] = 'ok';
                $jsonresp['act'] = 'upd';
                $jsonresp['mensaje'] = 'Se modificó satisfactoriamente el caso ' . $data_insert['cas_titulo'];
            } else {
                $jsonresp['resultado'] = 'no';
                $jsonresp['mensaje'] = 'Error al modificar el caso ' . $data_insert['cas_titulo'];
            }
        } else {
            $data_insert['cas_usuario'] = $userid;
            $data_insert['cas_fechaalta'] = date('Y-m-d');
            $sepudo = $this->caso_model->get_agrega($data_insert);
            if ($sepudo != FALSE) {
                $jsonresp['resultado'] = 'ok';
                $jsonresp['act'] = 'add';
                $jsonresp['id_insert'] = $sepudo;
                $jsonresp['mensaje'] = 'Se agregó satisfactoriamente el caso ' . $data_insert['cas_titulo'];
            } else {
                $jsonresp['resultado'] = 'no';
                $jsonresp['mensaje'] = 'Error al agregar el caso ' . $data_insert['cas_titulo'];
            }
        }
        echo json_encode($jsonresp);
    }

}
