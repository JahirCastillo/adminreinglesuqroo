<?php

/**
 * Este Archivo controller reactivo contiene funciones utilizadas dentro de la página de "Reactivo"
 *
 * @package    AdminreWeb
 * @subpackage Comun
 * @author     Jose Adrian Ruiz <sakcret@gmail.com >
 */
class Reactivotmp extends CI_Controller {

    private $clave_modulo = 'REA';
    private $clv_sess = '';

    function __construct() {
        parent::__construct();
        $this->clv_sess = $this->config->item('clv_sess');
        $user_id = $this->session->userdata('user_id' . $this->clv_sess);
        if (!$user_id) {
            redirect('inicio');
        }
        $this->load->model('reactivo_model');
    }

    function index() {
        $this->load->model('acceso_model');
        $user_id = $this->session->userdata('user_id' . $this->clv_sess);
        $permisos = $this->ci_acl_framew->get_parse_array_permisos($this->acceso_model->get_permisosUsuario($user_id, $this->clave_modulo));
        if (array_key_exists($this->clave_modulo, $permisos)) {
            $datos_vista['permisos_modulo'] = $permisos[$this->clave_modulo];
        }
        //datos modulo
        $data_modulo = $this->acceso_model->get_iconModulo($this->clave_modulo);
        $datos_plantilla['title_mod'] = $data_modulo['icon'] . ' ' . $data_modulo['nombre'];
        $datos_plantilla['modulos'] = $this->acceso_model->get_modulos();
        $datos_plantilla['permisos'] = $permisos;
        $datos_plantilla['navigate_mod'] = '<li><a onclick="redirect_to(\'inicio\')"><i class="fa fa-th"></i> Menú</a></li> <li><a class="active"> ' . $data_modulo['icon'] . ' ' . $data_modulo['nombre'] . '</a></li>';
        $datos_plantilla['content'] = $this->load->view('reactivo/lista_view', $datos_vista, true);
        $this->load->view('template', $datos_plantilla);
    }

    public function lista() {
        $clv_sess = $this->config->item('clv_sess');
        $login = $this->session->userdata('login' . $clv_sess);
        $rol = $this->session->userdata('rol' . $clv_sess);
        if (!$login) {
            redirect('acceso/acceso_denegado');
        }
        $this->load->model('generico_model');
        $roles = $this->config->item('roles');
        $sIndexColumn = "id";
        $aColumns = array($sIndexColumn, 'clave', 'contenido', 'clvestado', 'plan', 'autor', 'caso', 'fechaalta');
        $sTable = "view_reactivos";

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
            $html_st = "<button class='btn btn-warning' onclick='open_in_new(\"reactivo/update/" . $aRow[$sIndexColumn] . "\")'><i class='fa fa-pencil'></i></button><button class='btn btn-danger' onclick='delete(\"" . $aRow[$sIndexColumn] . "\")'><i class='fa fa-trash'></i></button>";
            $sOutput .= '"' . str_replace('"', '\"', preg_replace("/[\r\n]*/", "", $html_st)) . '",';
            $sOutput = substr_replace($sOutput, "", -1);
            $sOutput .= "],";
        }//forn for
        $sOutput = substr_replace($sOutput, "", -1);
        $sOutput .= '] }';

        echo $sOutput;
    }

    public function update($id = 0) {
        $id = $id * 1;
        $this->load->model('plan_model');
        $this->load->model('acceso_model');
        $user_id = $this->session->userdata('user_id' . $this->clv_sess);
        $permisos = $this->ci_acl_framew->get_parse_array_permisos($this->acceso_model->get_permisosUsuario($user_id, $this->clave_modulo));
        $tipos = $this->reactivo_model->tiposReactivo();   //tipos de reactivos
        $datos_vista['tipos'] = $tipos->result_array();
        $datos_vista['lista'] = $this->plan_model->baseTree()->result_array();
        $datos_vista['plan'] = $this->getActualPlan();
        $datos_vista['siguiente'] = $this->reactivo_model->getSiguiente($id);
        $datos_vista['referencias_html'] = $this->load->view('reactivo/referencias', FALSE, true);
        $datos_vista['casos_html'] = $this->load->view('reactivo/casos', FALSE, true);
        $datos_vista['loadreactivo'] = $id;
        $datos_vista['titulo'] = "Reactivo";
        $datos_vista['encabezado'] = "REACTIVO";
        if (array_key_exists($this->clave_modulo, $permisos)) {
            $datos_vista['permisos_modulo'] = $permisos[$this->clave_modulo];
        }
        $actionTxt = 'Agregar';
        if ($id != 0) {
            $actionTxt = 'Modificar';
        }
        //datos modulo
        $data_modulo = $this->acceso_model->get_iconModulo($this->clave_modulo);
        $datos_plantilla['title_mod'] = $data_modulo['icon'] . ' ' . $data_modulo['nombre'] . ' <small>' . $actionTxt . '</small>';
        $datos_plantilla['modulos'] = $this->acceso_model->get_modulos();
        $datos_plantilla['permisos'] = $permisos;
        $datos_plantilla['navigate_mod'] = '<li><a onclick="redirect_to(\'inicio\')"><i class="fa fa-th"></i> Menú</a></li> <li><a onclick="redirect_to(\'reactivo\')"> ' . $data_modulo['icon'] . ' ' . $data_modulo['nombre'] . '</a></li>
            <li class="active">' . $actionTxt . '</li>';
        $datos_plantilla['content'] = $this->load->view('reactivo/reactivo_view_tmp', $datos_vista, true);
        $this->load->view('template', $datos_plantilla);
    }

//-----------------------DESPLEGAR HIJOS-------------------
    /**
     * muestra una sublista de plan de estudios que dependen (hijos) de otro elemento de plan de estudios (padre).
     * @param int clave, identificador del elemento padre.
     * @return char $list, cadena html que contiene enlistado los elementos hijos que dependen de padre.
     */
    function desplegarHijos() {
        $idrea = $this->input->post('idrea');
        $this->load->model('plan_model');
        $lista = $this->plan_model->obtenerHijos($idrea)->result_array();
        $list = '<ul class="nav">';
        foreach ($lista as $l) {
            if ($l['hij'] > 0) {
                $list = $list . '<li><div class="container-fluid"><div onclick="desplegarHijos(' . $l['id'] . ')" class="row-fluid padre"><i id="i' . $l['id'] . '" class="icon-chevron-up"></i><label class="span11">' . $l['nom'] . '</label></div><div class="row-fluid" id="' . $l['id'] . '"></div></div></li>';
            } else {
                $nombre = "'" . $l['nom'] . "'";
                $list = $list . '<li><div class="container-fluid"><div id="pla_' . $l['id'] . '_lista" onClick="llenarPlan(' . $l['id'] . ',' . $nombre . ');" class="row-fluid hijo"><label>' . $l['nom'] . '</label></div></div><div class="container-fluid" id="' . $l['id'] . '"></div></li>';
            }
        }
        $list = $list . '</ul>';
        echo $list;
    }

//--------------PLAN----------------------------------------------------------------------------------------------
//----------------BUSCADOR DE PLAN------------------------
    /**
     * funcion que obtiene los registros de plan de estudio buscando con la concidencia de una cadena.
     * 
     * @param 1 char $this->input->post('palabra'), cadena a buscar en registros.
     * @return array $aData, registros a mostrar dentro de la busqueda.
     */
    function buscarPlan() {
        $palabra = $this->input->post('palabra');
        $this->load->model('plan_model');
        $datos = $this->plan_model->searchDatosPlan($palabra);
        $array = $datos['array']->result_array();
        $aDatos = array();
        foreach ($array as $a) {
            $aDatos[] = array(
                'cla' => $a['c'],
                'nom' => $a['n'],
                'des' => $a['d'],
                'hij' => $a['h'],
            );
        }
        echo json_encode($aDatos);
    }

//------------obtiene nombre de la clave de plan----------
    /**
     * obtiene el nombre del plan por su clave de registros.
     * @param int clave, identificador del registro de plan.
     * @return char $n, nombre del plan. 
     */
    function nombrePlan() {
        $clave = $this->input->post('clave');
        $this->load->model('plan_model');
        $nombre = $this->plan_model->nombrePlan($clave);
        if ($nombre != 'N') {
            $nombre = $nombre->result_array();
            foreach ($nombre as $nom) {
                $n = $nom['pla_nombre'];
            }
        } else
            $n = 'N';
        echo $n;
    }

//-----------clave nuevo de plan-------------
    /** obtiene la clave continua de plan para un nuevo ingreso. */
    function ClavePlan() {
        $this->load->model('plan_model');
        $pla_id = $this->plan_model->clavePlan();  //nueva clave para plan
        echo $pla_id;
    }

//---------------guardar plan--------------
    /**
     * guarda un nuevo ingreso o actalizacion de plan. 
     * @param int clave, identificador del registro.
     * @param char nombre, nombre de plan.
     * @param int pclave, identificador de plan de estudios que dependerá.
     * @param char descripcion, cadena que describe el plan de estudios.
     * @return array $plan, datos guardados.
     */
    function guardarPlan() {
        $datosPlan = array(//arreglo datos de una opción
            'pla_clave' => $this->input->post('clave'),
            'pla_nombre' => $this->input->post('nombre'),
            'pla_padre' => $this->input->post('pclave'),
            'pla_descripcion' => $this->input->post('descripcion')
        );
        $this->load->model('plan_model');
        $plan = $this->plan_model->insertDatosPlan($datosPlan);
        echo $plan;
    }

//---------------datos plan--------------
    /**
     * obtiene datos de un registro espeficico de la tabla plan
     * @param int clave, identificador del registro plan.
     * @return array $datosPlan, datos del registro obtenido.
     */
    function datosPlan() {
        $clave = $this->input->post('clave');
        $this->load->model('plan_model');
        $plan = $this->plan_model->datosPlan($clave);
        if ($plan->num_rows > 0) {
            $plan = $plan->result_array();
            foreach ($plan as $plan) {
                $datosPlan = array(
                    'cla' => $plan['cla'],
                    'nom' => $plan['nom'],
                    'pad' => $plan['pad'],
                    'pnom' => $plan['pnom'],
                    'des' => $plan['des'],
                    'rea' => $plan['rea'],
                    'hij' => $plan['hijos'],
                );
            }
        } else {
            $datosPlan = 'NULL';
        }
        echo json_encode($datosPlan);
    }

//---------------elimianr plan--------------
    /**
     * eliminación de un plan de estudio especifico.
     * @param int clave, identificador del registro plan.
     * @return int $plan, 1 si la eliminación fue exitosa y 0 si hubo algún error.
     */
    function eliminarPlan() {
        $clave = $this->input->post('clave');
        $this->load->model('plan_model');
        $plan = $this->plan_model->eliminarPlan($clave);
        echo $plan;
    }

    function delete() {
        $array_out=array();
        $clave = $this->input->post('id') * 1;
        if ($clave !== 0) {
            $delete = $this->reactivo_model->deleteReactivos($clave);
            if ($delete != FALSE) {
                $array_out['res'] = 'ok';
            } else {
                $array_out['res'] = 'no';
                $array_out['msg'] = 'Error al borrar el reactivo. Intenta de nuevo.';
            }
        }
        echo json_encode($array_out);
    }

//--------------------AUTOR-------------------------------------------------------------------------------------------------------	
//---------------BUSCADOR DE AUTOR--------------------------
    function buscarAutor() {
        $palabra = $this->input->post('texto');
        echo json_encode($this->reactivo_model->searchDatosAutor($palabra)->result_array());
    }

//-----OBTENER DATOS DE UN AUTOR--------------------------
    function datosAutor() {
        $id = $this->input->post('id');
        echo json_encode($this->reactivo_model->get_datosAutor($id)->row());
    }

//--------------------REFERENCIAS-------------------------------------------------------------------------------------------------------	
//---------------BUSCADOR DE REFERENCIA--------------------------
    function buscarReferencia() {
        $palabra = $this->input->post('texto');
        echo json_encode($this->reactivo_model->searchDatosReferencia($palabra)->result_array());
    }

//-----OBTENER DATOS DE UN REFERENCIA--------------------------
    function datosReferencia() {
        $id = $this->input->post('id');
        echo json_encode($this->reactivo_model->get_datosReferencia($id)->row());
    }

//-----Agregar referencia--------------------------
    function agregaReferencia() {
        $datos['ref_titulo'] = $this->input->post('tit');
        $datos['ref_editorial'] = $this->input->post('edi');
        $datos['ref_autores'] = $this->input->post('aut');
        $datos['ref_descripcion'] = $this->input->post('des');
        $out = array();
        $id_insert = $this->reactivo_model->get_agregaReferencia($datos);
        if ($id_insert != false) {
            $out['resp'] = 'ok';
            $out['id'] = $id_insert;
        } else {
            $out['resp'] = 'no';
            $out['msg'] = 'Error al agregar la referencia ' . $datos['REF_TITULO'];
        }
        echo json_encode($out);
    }

//-------------ACTUALIZA O INGRESA LIBRO--------------------------------
    /**
     * función para actualizar o ingresar un registro de la tabla libro
     * @param 1 int $clave, identificador del registro.
     * @param 2 char $titulo
     * @param 3 char $editorial
     * @param 4 char $autores
     * @param 5 char $descripcion
     * @return array $lista, datos del registro actualizado o ingresado.  
     */
    function guardarLibro() {
        $clave = $this->input->post('clave');
        $titulo = $this->input->post('titulo');
        $editorial = $this->input->post('editorial');
        $autores = $this->input->post('autores');
        $descripcion = $this->input->post('descripcion');
        $datoslibro = array(
            'lib_clave' => $clave,
            'lib_titulo' => $titulo,
            'lib_editorial' => $editorial,
            'lib_autores' => $autores,
            'lib_descripcion' => $descripcion
        );
        $this->load->model('libro_model');
        $insert = $this->libro_model->insertDatosLibro($datoslibro);
        $lista = array();
        $lista['clave'] = $clave;
        $lista['titulo'] = $titulo;
        $lista['editorial'] = $editorial;
        $lista['autores'] = $autores;
        $lista['descripcion'] = $descripcion;
        if ($insert == 1) {
            $lista['mensaje'] = 'El Libro ha INGRESADO correctamente.';
            echo json_encode($lista);
        } elseif ($insert == 2) {
            $lista['mensaje'] = 'El Libro se ha ACTUALIZADO correctamente.';
            echo json_encode($lista);
        }
    }

//-----------------------CASO------------------------------------------------------------------------------------------------------
//----------------BUSCADOR DE CASO------------------------
    /**
     * funcion que obtiene los registros de casos de estudio buscando con la concidencia de una cadena.
     * @param 1 char $this->input->post('palabra'), cadena a buscar en registros.
     * @return array $aData, registros a mostrar dentro de la busqueda.
     */
    function buscarCaso() {
        $palabra = $this->input->post('palabra');
        $this->load->model('caso_model');
        $datos = $this->caso_model->searchDatosCaso($palabra)->result_array();
        echo json_encode($datos);
    }

//-----OBTENER DATOS DE UN CASO--------------------------
    /**
     * función que obtiene los datos de un registro libro especifico. 
     * @param int $this->input->post('clave'), identificador del registro.
     * @return array $lista, datos del registro solicitado.  
     */
    function datosCaso() {
        $id = $this->input->post('id');
        $jsonOut = array();
        if ($id != '') {
            $this->load->model('caso_model');
            $jsonOut = $this->caso_model->obtenerDatosCaso($id)->row_array();
        }
        echo json_encode($jsonOut);
    }

//------------------GUARDAR REACTIVO O CASO----------------------------
    /**
     * función que inserta o actualiza los datos de reactivo junto a su vez Caso.
     *
     * @param int $this->input->post('clave'), identificador del registro.
     * @param char $this->input->post('contenido'), contenido del editor de texto del reactivo.
     * @param char $this->input->post('modocalif'), el modo de calificar del reactivo.
     * @param char $this->input->post('estado'), estado del reactivo C/I/A.
     * @param int $this->input->post('tiporeactivo'), depende del numero identificador es el tipo de reactivo.
     * @param int $this->input->post('pclave'), identificador del registro de plan de estudios del reactivo.
     * @param int $this->input->post('cclave'), identificador del registro caso del reactivo.
     * @param int $this->input->post('lclave'), identificador del registro libro fuente del reactivo.
     * @param int $this->input->post('aclave'), identificador del registro autor creador del reactivo.
     * @return msg si fue insertado o modificado el reactivo.  
     */
    function guardarReactivo() {
        $json_out = array();
        $this->load->helper('file');
        $this->load->helper('date');
        $fecha = mdate('%Y-%m-%d'); //fecha actual
        $clave = $this->input->post('clave');
        $contenido = $this->input->post('contenido');
        $clv_sess = $this->config->item('clv_sess');
        $user_id = $this->session->userdata('user_id' . $clv_sess);
        $datos = array(//arreglo datos del reactivo
            'rea_clave' => $this->input->post('rclv'),
            'rea_contenido' => $contenido,
            'rea_modocalif' => $this->input->post('modocalif'),
            'rea_estado' => $this->input->post('estado'),
            'rea_tiporeactivo' => $this->input->post('tiporeactivo'),
            'rea_plan' => $this->input->post('pclave'),
            'rea_caso' => $this->input->post('cclave'),
            'rea_libro' => $this->input->post('lclave'),
            'rea_autor' => $this->input->post('aclave')
        );
        $existe = 0;
        if (($clave * 1) != 0) {
            $existe = $this->reactivo_model->existeReactivo($clave);
        }
        if ($existe > 0) { // modifica reactivo
            $datos['rea_fechamodif'] = $fecha;
            $datos['rea_usuariomodif'] = $user_id;
            $sepudo = $this->reactivo_model->modificaReactivo($clave, $datos);
            if ($sepudo !== FALSE) {
                $json_out['resp'] = 'ok';
                $json_out['act'] = 'upd';
                $this->setActualReactivo($clave);
            } else {
                $json_out['resp'] = 'no';
                $json_out['msg'] = 'Error al modificar el reactivo.';
            }
        } else { // inserta nuevo reactivo
            $datos['rea_fechaalta'] = $fecha;
            $datos['rea_usuarioalta'] = $user_id;
            $idinsert = $this->reactivo_model->insertarReactivo($datos);
            if ($idinsert != FALSE) {
                $json_out['resp'] = 'ok';
                $json_out['act'] = 'add';
                $json_out['idins'] = $idinsert;
                $this->setActualReactivo($idinsert);
            } else {
                $json_out['resp'] = 'no';
                $json_out['msg'] = 'Error al guardar el reactivo.';
            }
        }
        echo json_encode($json_out);
    }

//----------------BUSCADOR DE REACTIVO------------------------
    /**
     * funcion que obtiene los registros de reactivo buscando con la concidencia de una cadena.
     * @param varchar sql, consulta generada por el usuario por datos de reactivo a buscar.
     * @return array $aData, registros a mostrar dentro de la busqueda.
     */
    function buscarReactivo() {
        $edo = $this->input->post('edo');
        $tiprea = $this->input->post('tiprea');
        $fecha1 = $this->input->post('fecha1');
        $fecha2 = $this->input->post('fecha2');
        $usu = $this->input->post('usu');
        $txt = $this->input->post('txt');
        $array = $this->reactivo_model->searchDatosReactivo($edo, $tiprea, $fecha1, $fecha2, $usu, $txt)->result_array();
        $aDatos = array();
        foreach ($array as $a) {
            if ($a['tip'] == 0)
                $tip = 'Ninguno';
            elseif ($a['tip'] == 1)
                $tip = 'Múltiple';
            elseif ($a['tip'] == 2)
                $tip = 'Radio';
            elseif ($a['tip'] == 3)
                $tip = 'Relacionar';
            elseif ($a['tip'] == 4)
                $tip = 'Corta';
            elseif ($a['tip'] == 5)
                $tip = 'Ordenar';
            elseif ($a['tip'] == 6)
                $tip = 'Clasificar';
            elseif ($a['tip'] == 7)
                $tip = 'Numérico';

            $cad = strip_tags($a['con']);
            if (strlen($cad) > 200) {
                $cad = substr($cad, 0, 197) . '...';
            }
            $aDatos[] = array(
                'sel' => '<button class="btn btn-primary" onClick="llenarReactivo(' . $a['id'] . ');">Seleccionar</button>',
                'clv' => $a['clv'],
                'con' => $cad,
                'est' => $a['est'],
                'tip' => $tip,
                'opc' => $a['opc'],
                'cal' => $a['cal'],
                'fec' => $a['fec'],
                'cas' => $a['cas'],
                'pla' => $a['pla']
            );
        }
        echo json_encode($aDatos);
    }

//-----OBTENER DATOS DE UN REACTIVO-------------------------
    /**
     * función que obtiene los datos de un registro de reactivo especifico.
     * @param int $this->input->post('clave'), identificador del registro.
     * @return array $lista, datos del registro solicitado.  
     */
    function llenarReactivo() {
        $idrea = $this->input->post('idrea');
        $this->load->model('opcion_model');
        $datos = $this->reactivo_model->getDatosReactivo($idrea)->row_array();
        $datos['opcres'] = $this->opcion_model->get_data_opciones_reactivo($idrea)->result_array();
        echo json_encode($datos);
    }

    function rmComment() {
        $idrea = $this->input->post('id');
        $this->load->model('opcion_model');
        $json_out = array();
        $datos['rea_comentariovalidacion'] = '';
        $sepudo = $this->reactivo_model->modificaReactivo($idrea, $datos);
        if ($sepudo !== FALSE) {
            $json_out['resp'] = 'ok';
        } else {
            $json_out['resp'] = 'no';
            $json_out['msg'] = 'Error al eliminar el comentario el reactivo.';
        }
        echo json_encode($json_out);
    }

    function dataReaVP() {
        $idrea = $this->input->post('idrea');
        $this->load->model('opcion_model');
        $this->load->model('caso_model');
        $datos = $this->reactivo_model->getDatosReactivo($idrea)->row_array();
        $datos['opcres'] = $this->opcion_model->get_data_opciones_reactivo($idrea)->result_array();
        $datos['caso'] = $this->caso_model->get_data_caso_reactivo($datos['cid']);
        echo json_encode($datos);
    }

//----------------------OPCION----------------------------------------------------------------------------------------------------
//------------------GUARDAR OPCION----------------------------
    /**
     * guarda opciones de respuesta de un reactivo
     *
     * @param char $this->input->post('opcion'), contenido en html de la opcion.
     * @param int $this->input->post('radio'), 0 respuesta incorrecta 1 respuesta correcta.
     * @param int $this->input->post('reactivo'), identificador del registro del reactivo.
     */
    function guardarOpciones() {
        $idrea = $this->input->post('idrea');
        $tipo_medio = $this->input->post('tipo_medio');
        $opciones = $this->input->post('opc');
        $tipo_rea = $this->input->post('tipo_rea');
        $json_out = array();
        if (($idrea * 1 != 0)) {
            $this->load->model('opcion_model');
            $ids = $this->opcion_model->insertDatosOpcion($idrea, $tipo_medio, $tipo_rea, $opciones);
            if ($ids != FALSE) {
                if ($tipo_medio == 'txt') {
                    try {
                        $this->delDir('./media/reactivo' . $idrea);
                    } catch (Exception $e) {
                        
                    }
                }
                $json_out['sepudo'] = 'ok';
                $json_out['ids'] = $ids;
            }
        } else {
            $json_out['sepudo'] = 'no';
            $json_out['msg'] = 'No se ha seleccionado un reactivo. <br>Intenta de nuevo.';
        }
        echo json_encode($json_out);
    }

    function checkDir() {
        $idrea = $this->input->post('idrea');
        $json_out = array();
        try {
            if ($this->delDir('./media/reactivo' . $idrea)) {
                $json_out['sepudo'] = 'ok';
            } else {
                $json_out['sepudo'] = 'no';
            }
        } catch (Exception $e) {
            $json_out['sepudo'] = 'no';
        }
        echo json_encode($json_out);
    }

    private function delDir($filename) {
        if (file_exists($filename)) {
            $this->load->helper('file');
            delete_files($filename, TRUE);
            if (rmdir($filename)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

//-----------OBTENER OPCIONES DE RESPUESTA DE UN REACTIVO------------
    /**
     * obtener las opciones de respuestas del reactivo seleccionado de la tabla ADM_OPCION.
     * @param int clave, identificador del reactivo.
     * @return array $opciones, arreglo de todas las opciones de respuesta.
     */
    function datosOpciones() {
        $this->load->helper('file');
        $clave = $this->input->post('clave');
        $this->load->model('opcion_model');
        $opc = $this->opcion_model->datosOpciones($clave);
        $opc = $opc->result_array();
        $opciones = array();
        $i = 0;
        $num = 96;
        foreach ($opc as $opc) {
            $i++;
            $num++;
            $letra = chr($num);
            $opciones[] = array(
                'car' => $letra,
                'cla' => $opc['opc_clave'],
                'con' => $opc['opc_contenido'],
                'cor' => $opc['opc_correcta'],
            );
        }
        echo json_encode($opciones);
    }

//-----------OBTENER OPCIONES DE RESPUESTA DE UN REACTIVO DE DOS COLUMNAS------------
    /**
     * obtener las opciones de respuestas del reactivo seleccionado de la tabla ADM_OPCION1.
     * @param int clave, identificador del reactivo.
     * @return array $opciones, arreglo de todas las opciones de respuesta.
     */
    function datosOpciones1() {
        $this->load->helper('file');
        $clave = $this->input->post('clave');
        $this->load->model('opcion_model');
        $opc = $this->opcion_model->datosOpciones1($clave);
        $opc = $opc->result_array();
        $opciones = array();
        foreach ($opc as $opc) {
            $opciones[] = array(
                'cla' => $opc['opc1_clave'],
                'con' => $opc['opc1_contenido'],
                'opc' => $opc['opc1_opcion']
            );
        }
        echo json_encode($opciones);
    }

//------------------VISTA PRELIMINAR-----------
//----------reactivo existente-------
    /**
     * verificar si la clave del reactivo existe en la tabla ADM_REACTIVO.
     * @param int clave, identificador del reactivo.
     * @return 1 si existe, 0 si no existe.
     */
    function existeReactivo() {
        $clave = $this->input->post('clave');
        $num = $this->reactivo_model->existeReactivo($clave);
        if ($num > 0)
            echo 1;
        else
            echo 0;
    }

    function up() {
        $id_reactivo = $this->getActualReactivo();
        $url = "/media/reactivo" . $id_reactivo . '/';
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

    function getActualReactivo() {
        $clv_sess = $this->config->item('clv_sess');
        return $this->session->userdata('id_reativo_tmp' . $clv_sess);
    }

    function setActualReactivo($id) {
        $clv_sess = $this->config->item('clv_sess');
        $this->session->set_userdata('id_reativo_tmp' . $clv_sess, $id * 1);
    }

    function getActualPlan() {
        $clv_sess = $this->config->item('clv_sess');
        return $this->session->userdata('id_plan_tmp' . $clv_sess);
    }

    function getDataActualPlan() {
        $out = array();
        $data = explode('@_@', $this->getActualPlan());
        $out['id'] = $data[0];
        $out['nom'] = $data[1];
        echo json_encode($out);
    }

    function setActualPlan() {
        $id = $this->input->post('id');
        $nom = $this->input->post('nom');
        $clv_sess = $this->config->item('clv_sess');
        $this->session->set_userdata('id_plan_tmp' . $clv_sess, $id . '@_@' . $nom);
    }

    //subir formula matemática
    function upFMath() {
        $id_reactivo = $this->getActualReactivo();
        $url = "/media/reactivo" . $id_reactivo . '/';
        /* if ($_POST["save"]) {
          $type = $_POST["type"];
          if ($_POST["name"] and ( $type == "JPG" or $type == "PNG")) {
          $img = base64_decode($_POST["image"]);

          $myFile = "" . $_POST["name"] . "." . $type;
          $fh = fopen($myFile, 'w');
          fwrite($fh, $img);
          fclose($fh);
          echo  "/media/reactivo" . $id_reactivo . '/'. $_POST["name"] . "." . $type;
          }
          } else {
          header('Content-Type: image/jpeg');
          echo base64_decode($_POST["image"]);
          } */
        $url = "media/reactivo" . $id_reactivo . '/';
        if ($_POST["save"]) {
            $type = $_POST["type"];
            if ($_POST["name"] and ( $type == "JPG" or $type == "PNG")) {
                $img = base64_decode($_POST["image"]);
                $myFile = $url . $_POST["name"] . "." . $type;
                $fh = fopen($myFile, 'w');
                fwrite($fh, $img);
                fclose($fh);
                echo $this->get_full_url() . '/' . $url . $_POST["name"] . "." . $type;
            }
        } else {
            header('Content-Type: image/jpeg');
            echo base64_decode($_POST["image"]);
        }
    }

    function test() {
        header('Content-Type: text/html; charset=utf-8');
        echo urldecode('sta%20es%20el%20%E1rea@_@N@_@opc_0');
    }

}

?>