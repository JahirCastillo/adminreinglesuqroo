<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Usuarios_sistema extends CI_Controller {

    private $clave_modulo = 'USU';
    private $clv_sess = '';

    function __construct() {
        parent::__construct();
        $this->clv_sess = $this->config->item('clv_sess');
        $user_id = $this->session->userdata('user_id' . $this->clv_sess);
        if (!$user_id) {
            redirect('inicio');
        }
        $this->load->model("usuarios_sistema_model");
    }

    public function index() {
        $this->load->model('acceso_model');
        $user_id = $this->session->userdata('user_id' . $this->clv_sess);
        $permisos = $this->ci_acl_framew->get_parse_array_permisos($this->acceso_model->get_permisosUsuario($user_id, $this->clave_modulo));
        $datos_vista['modulos'] = $this->parser_modulos();
        if (array_key_exists($this->clave_modulo, $permisos)) {
            $datos_vista['permisos_modulo'] = $permisos[$this->clave_modulo];
        }
        //datos modulo
        $data_modulo = $this->acceso_model->get_iconModulo($this->clave_modulo);
        $datos_plantilla['title_mod'] = $data_modulo['icon'] . ' ' . $data_modulo['nombre'];
        $datos_plantilla['modulos'] = $this->acceso_model->get_modulos();
        $datos_plantilla['permisos'] = $permisos;
        $datos_plantilla['navigate_mod'] = '<li><a onclick="redirect_to(\'inicio\')"><i class="fa fa-th"></i> Menú</a></li> <li><a class="active"> ' . $data_modulo['icon'] . ' ' . $data_modulo['nombre'] . '</a></li>';
        $datos_plantilla['content'] = $this->load->view('usuarios_sistema/usuarios_sistema_view', $datos_vista, true);
        $this->load->view('template', $datos_plantilla);
    }

    public function datos() {
        // verificar permisos del modulo
        $this->load->model('acceso_model');
        $user_id = $this->session->userdata('user_id' . $this->clv_sess);
        $permisos = $this->ci_acl_framew->get_parse_array_permisos($this->acceso_model->get_permisosUsuario($user_id, $this->clave_modulo));
        if (array_key_exists($this->clave_modulo, $permisos)) {
            $permisos_modulo = $permisos[$this->clave_modulo];
        } else {
            redirect('acceso/acceso_denegado');
        }
        $this->load->model('generico_model');
        $sIndexColumn = "usu_id";
        $aColumns = array($sIndexColumn, 'usu_login', 'CONCAT(COALESCE(usu_nombre,"")," ",COALESCE(usu_apaterno,"")," ",COALESCE(usu_amaterno,""))', 'rol_nombre', 'usu_correo', 'if(usu_telefono is not null and usu_telefono!="",concat("(",usu_lada,") ",usu_telefono),"" )', 'usu_celular', 'case usu_estatuscuenta when 1 then \'Activa\' when 0 then \'Inactiva\' end');
        $sTable = "acl_usuario";

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
        $rResult = $this->usuarios_sistema_model->get_datos($aColumns, $sTable, $sWhere, $sOrder, $sLimit);
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
                if ($aColumns[$i] == "usu_rol") {
                    $sOutput .= '"' . str_replace('"', '\"', $aRow[$aColumns[$i]]) . '",';
                } else if ($aColumns[$i] != ' ') {
                    $sOutput .= '"' . str_replace('"', '\"', $aRow[$aColumns[$i]]) . '",';
                }
            }
            $det = $upd = $del = $per = '';
            if (isset($permisos_modulo) && in_array('det', $permisos_modulo)) {
                $det = "<button class='btn btn-success opcdt' title='Ver detalles' onclick='detalles(" . $aRow[$sIndexColumn] . ")'><i class=' fa fa-list-alt'></i></button>";
            }
            if (isset($permisos_modulo) && in_array('upd', $permisos_modulo)) {
                $upd = "<button class='btn btn-warning opcdt' title='Modificar usuario' onclick='modifica(" . $aRow[$sIndexColumn] . ")'><i class=' fa fa-edit'></i></button>";
            }
            if (isset($permisos_modulo) && in_array('del', $permisos_modulo)) {
                $del = "<button class='btn btn-danger opcdt' title='Eliminar usuario' onclick='elimina(" . $aRow[$sIndexColumn] . ")'><i class=' fa fa-remove'></i></button>";
            }
            if (isset($permisos_modulo) && in_array('per', $permisos_modulo)) {
                $per = "<button class='btn btn-info opcdt' title='Ver permisos' onclick='ver_permisos(" . $aRow[$sIndexColumn] . ")'><i class=' fa fa-lock'></i></button>";
            }
            $sOutput .= '"' . str_replace('"', '\"', $upd . $del . $det . $per) . '",';
            $sOutput = substr_replace($sOutput, "", -1);
            $sOutput .= "],";
        }//forn for
        $sOutput = substr_replace($sOutput, "", -1);
        $sOutput .= '] }';

        echo $sOutput;
    }

    public function update($id = 0) {
        $datos_vista = array();
        $log = true;
        $accion = '';
        if ($id != 0) {
            $datos_vista['datos_modifica'] = $this->usuarios_sistema_model->get_datos_modifica($id);
            if ($log) {
                $datos_vista['datos_modifica']->usuagrego = $this->usuarios_sistema_model->get_usuario_name($datos_vista['datos_modifica']->usuarioagrego);
                $datos_vista['datos_modifica']->usumodifico = $this->usuarios_sistema_model->get_usuario_name($datos_vista['datos_modifica']->usuariomodifico);
            }
            $accion = 'Modificar Usuario';
        } else {
            $datos_vista['datos_modifica'] = false;
            $accion = 'Agregar Usuario';
        }
        $this->load->model('acceso_model');
        $user_id = $this->session->userdata('user_id' . $this->clv_sess);
        $permisos = $this->ci_acl_framew->get_parse_array_permisos($this->acceso_model->get_permisosUsuario($user_id, $this->clave_modulo));
        //datos vista
        $datos_vista['clave_modulo'] = $this->clave_modulo;
        $datos_vista['roles'] = $this->parser_roles();
        $datos_vista['modulos'] = $this->parser_modulos();
        //datos modulo
        $data_modulo = $this->acceso_model->get_iconModulo($this->clave_modulo);
        //datos plantilla
        $datos_plantilla['title_mod'] = $data_modulo['icon'] . ' ' . $data_modulo['nombre'] . ' <small>' . $accion . '</small>';
        $datos_plantilla['navigate_mod'] = '<li><a onclick="redirect_to(\'inicio\')"><i class="fa fa-th"></i> Menú</a></li> <li><a onclick="redirect_to(\'usuarios_sistema\')"> ' . $data_modulo['icon'] . ' ' . $data_modulo['nombre'] . '</a></li>
            <li class="active">' . $accion . '</li>';
        $datos_plantilla['content'] = $this->load->view('usuarios_sistema/usuarios_sistema_update', $datos_vista, true);
        $this->load->view('template', $datos_plantilla);
    }

    /*
     * {
      1: {modulos: {1: ["1","2","3"],2: ["6"]},nom: "Super Administrador"},
      2: {modulos: {1: ["1","2","3","4","5"],2: ["6"]},nom: "Administrador"}}
     */

    private function parser_roles() {
        $out = array();
        $data_roles = $this->usuarios_sistema_model->get_roles();
        foreach ($data_roles as $rol) {
            if (!array_key_exists($rol['rolid'], $out)) {
                $out[$rol['rolid']] = array();
                $out[$rol['rolid']]['modulos'] = array();
                $out[$rol['rolid']]['nom'] = $rol['rol'];
                $out[$rol['rolid']]['des'] = $rol['des'];
                if (!array_key_exists($rol['moduloid'], $out[$rol['rolid']]['modulos'])) {
                    $out[$rol['rolid']]['modulos'][$rol['moduloid']] = array();
                    array_push($out[$rol['rolid']]['modulos'][$rol['moduloid']], $rol['permisoid']);
                } else {
                    array_push($out[$rol['rolid']]['modulos'][$rol['moduloid']], $rol['permisoid']);
                }
            } else {
                $out[$rol['rolid']]['nom'] = $rol['rol'];
                if (!array_key_exists($rol['moduloid'], $out[$rol['rolid']]['modulos'])) {
                    $out[$rol['rolid']]['modulos'][$rol['moduloid']] = array();
                    array_push($out[$rol['rolid']]['modulos'][$rol['moduloid']], $rol['permisoid']);
                } else {
                    array_push($out[$rol['rolid']]['modulos'][$rol['moduloid']], $rol['permisoid']);
                }
            }
        }
        return $out;
    }

    /*
     * {"4":{"nom":"Reportes","ico":"<\/i>","permisos":[{"pid":"8","pnom":"Solo consulta","pdes":"Solo se puede consultar"},{"pid":"10","pnom":"Impresi\u00f3n","pdes":"Impresion"},{"pid":"11","pnom":"PDF","pdes":null},{"pid":"12","pnom":"Hoja de calculo","pdes":null}]},"1":{"nom":"Usuarios del sistema","ico":"<\/i>","permisos":[{"pid":"1","pnom":"Agregar","pdes":"Agregar datos de usuarios"},{"pid":"2","pnom":"Actualizar","pdes":"Actualizar datos de usuarios"},{"pid":"3","pnom":"Eliminar","pdes":"Eliminar usuarios"},{"pid":"4","pnom":"Deshabilitar cuenta","pdes":"Deshabilitar cuenta de usuario"},{"pid":"5","pnom":"Solo consulta","pdes":"Solo se puede consultar"},{"pid":"9","pnom":"Reportes","pdes":"Reporte de usuarios"}]},"2":{"nom":"Acerca de","ico":"<\/i>","permisos":[{"pid":"6","pnom":"Solo consulta","pdes":"Solo se puede consultar"}]},"3":{"nom":"Ayuda","ico":"<\/i>","permisos":[{"pid":"7","pnom":"Solo consulta","pdes":"Solo se puede consultar"}]}}
     */

    private function parser_modulos() {
        $out = array();
        $data_modulos = $this->usuarios_sistema_model->get_modulos();
        foreach ($data_modulos as $mod) {
            if (!array_key_exists($mod['moduloid'], $out)) {
                $out[$mod['moduloid']] = array();
                $out[$mod['moduloid']]['nom'] = $mod['modulo'];
                $out[$mod['moduloid']]['ico'] = $mod['moduloicono'];
                $out[$mod['moduloid']]['permisos'] = array();
                array_push($out[$mod['moduloid']]['permisos'], array('pid' => $mod['permisoid'], 'pnom' => $mod['permiso'], 'pdes' => $mod['permisodesc']));
            } else {
                array_push($out[$mod['moduloid']]['permisos'], array('pid' => $mod['permisoid'], 'pnom' => $mod['permiso'], 'pdes' => $mod['permisodesc']));
            }
        }
        return $out;
    }

    private function parser_permisos_modulo_usuario($idusuario) {
        $out = array();
        $data_modulos = $this->usuarios_sistema_model->get_permisos_modulo_usuario($idusuario);
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

    function permisos() {
        $id = $this->input->post('id');
        echo json_encode(array('resp' => 'ok', 'permisos_usu' => $this->parser_permisos_modulo_usuario($id)));
    }

    function detalles() {
        $id = $this->input->post('id');
        $datos = $this->usuarios_sistema_model->get_detalles_usuario($id);
        $datos['usuagrego'] = $this->usuarios_sistema_model->get_usuario_name($datos['usuarioagrego']);
        $datos['usumodifico'] = $this->usuarios_sistema_model->get_usuario_name($datos['usuariomodifico']);
        echo json_encode(array('resp' => 'ok', 'detalles' => $datos));
    }

    function elimina() {
        $id = $this->input->Post("id");
        $sepudo = $this->usuarios_sistema_model->getElimina($id);
        if ($sepudo) {
            $array_out['resp'] = 'ok';
        } else {
            $array_out['resp'] = 'no';
            $array_out['msg'] = "Se produjo un error al eliminar el usuario.";
        }
        echo json_encode($array_out);
    }

    public function getupdate($id = 0) {
        $jsonresp = array();
        $this->load->library('encryption');
        $userid = $this->session->userdata('user_id' . $this->clv_sess);
        $data_insert['usu_login'] = $this->input->post('login');
        $data_insert['usu_nombre'] = $this->input->post('nombre');
        $data_insert['usu_apaterno'] = $this->input->post('apaterno');
        $data_insert['usu_amaterno'] = $this->input->post('amaterno');
        $pass = $this->input->post('password');
        $data_insert['usu_correo'] = $this->input->post('email');
        $data_insert['usu_estatuscuenta'] = $this->input->post('estado');
        $data_insert['usu_lada'] = $this->input->post('lada');
        $data_insert['usu_telefono'] = $this->input->post('telefono');
        $data_insert['usu_celular'] = $this->input->post('celular');
        $data_insert['usu_rol'] = $this->input->post('rol');
        $data_insert['usu_imagen'] = $this->input->post('foto');
        $it = $this->input->post('it');
        $permisos = FALSE;
        if ($permisos != FALSE) {
            
        }
        if ($id != 0) {
            if ($pass != '') {
                $data_insert['usu_password'] = $this->encryption->encrypt($pass);
            }
            $data_insert['usu_usuariomodifico'] = $userid;
            $data_insert['usu_fechamodifico'] = date('Y-m-d');
            $sepudo = $this->usuarios_sistema_model->getModifica($id, $data_insert, $permisos);
            if ($sepudo) {
                $jsonresp['resultado'] = 'ok';
                $jsonresp['mensaje'] = 'Se modificó satisfactoriamente al usuario ' . $data_insert['usu_nombre'];
            } else {
                $jsonresp['resultado'] = 'no';
                $jsonresp['mensaje'] = 'Error al modificar el usuario ' . $data_insert['usu_nombre'];
            }
        } else {
            $data_insert['usu_password'] = $this->encryption->encrypt($pass);
            $data_insert['usu_usuarioagrego'] = $userid;
            $data_insert['usu_fechaagrego'] = date('Y-m-d');
            $sepudo = $this->usuarios_sistema_model->getAgrega($data_insert, $permisos);
            if ($sepudo) {
                $jsonresp['resultado'] = 'ok';
                $jsonresp['mensaje'] = 'Se agregó satisfactoriamente al usuario ' . $data_insert['usu_nombre'];
            } else {
                $jsonresp['resultado'] = 'no';
                $jsonresp['mensaje'] = 'Error al agregar el usuario ' . $data_insert['usu_nombre'];
            }
        }
        echo json_encode($jsonresp);
    }

    function upfoto() {
        $url = "/images/perfil_usuarios_sistema";
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

}

?>
