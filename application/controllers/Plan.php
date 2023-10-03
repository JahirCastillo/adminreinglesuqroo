<?php

/**
 * 
 * @package    AdminreWeb
 * @subpackage Comun
 * @author     Jose Adrian Ruiz <sakcret@gmail.com >
 */
class Plan extends CI_Controller {

    private $clave_modulo = 'PLA';
    private $clv_sess = '';

    function __construct() {
        parent::__construct();
        $this->clv_sess = $this->config->item('clv_sess');
        $user_id = $this->session->userdata('user_id' . $this->clv_sess);
        if (!$user_id) {
            redirect('inicio');
        }
        $this->load->model('plan_model');
    }

    function index() {
        //$this->output->enable_profiler(true);
        $this->load->model('acceso_model');
        $user_id = $this->session->userdata('user_id' . $this->clv_sess);
        $permisos = $this->ci_acl_framew->get_parse_array_permisos($this->acceso_model->get_permisosUsuario($user_id, $this->clave_modulo));
        $datos_vista = array();
        if (array_key_exists($this->clave_modulo, $permisos)) {
            $datos_vista['permisos_modulo'] = $permisos[$this->clave_modulo];
        }
         //print_r($datos_vista['permisos_modulo']);
        //datos modulo
        $data_modulo = $this->acceso_model->get_iconModulo($this->clave_modulo);
        $datos_plantilla['title_mod'] = $data_modulo['icon'] . ' ' . $data_modulo['nombre'];
        $datos_plantilla['modulos'] = $this->acceso_model->get_modulos();
        $datos_plantilla['permisos'] = $permisos;
        $datos_plantilla['navigate_mod'] = '<li><a onclick="redirect_to(\'inicio\')"><i class="fa fa-th"></i> Menú</a></li> <li><a class="active"> ' . $data_modulo['icon'] . ' ' . $data_modulo['nombre'] . '</a></li>';
        $datos_plantilla['content'] = $this->load->view('plan/plan_view', $datos_vista, true);
        $this->load->view('template', $datos_plantilla);
    }

    function addNodo() {
        $pid = $this->db->escape_str($this->input->post('pid'));
        $nom = $this->db->escape_str($this->input->post('nom'));
        $usu = $this->session->userdata('user_id' . $this->clv_sess);
        $rol = $this->session->userdata('id_rol' . $this->clv_sess);
        $array_out = array();
        $result = $this->plan_model->get_addNodo($pid, $nom, $usu, $rol);
        if ($result != FALSE) {
            $array_out['res'] = 'ok';
            $array_out['insert_id'] = $result;
        } else {
            $array_out['res'] = 'no';
            $array_out['msg'] = 'Error al agregar el plan. Intenta de nuevo.';
        }
        echo json_encode($array_out);
    }

    function editNodoName() {
        $id = $this->db->escape_str($this->input->post('id'));
        $nom = $this->db->escape_str($this->input->post('nom'));
        $array_out = array();
        $result = $this->plan_model->get_editNodoName($id, $nom);
        if ($result != FALSE) {
            $array_out['res'] = 'ok';
            $array_out['insert_id'] = $result;
        } else {
            $array_out['res'] = 'no';
            $array_out['msg'] = 'Error al agregar el plan. Intenta de nuevo.';
        }
        echo json_encode($array_out);
    }

    /* function getNodosPlan($id=0) {
      $this->db->query("SELECT pla_id as plaid
      FROM `adm_plan`
      where pla_padre=$id")->result();
      } */

    function moveNodo() {
        $nodeId = $this->input->post('id') * 1;
        $targetNode = $this->input->post('pid') * 1;
        $typeNode = $this->db->escape_str($this->input->post('type'));
        $typeNodeTarget = $this->db->escape_str($this->input->post('typeTar'));
        $usu = $this->session->userdata('user_id' . $this->clv_sess);
        $array_out = array();
        //$reactOccupied = $this->plan_model->check_reactOccupied($nodeId);
        $reactOccupied = false;
        if (!$reactOccupied) {
            $result = $this->plan_model->get_moveNodo($nodeId, $targetNode, $typeNode, $typeNodeTarget, $usu);
            if ($result != FALSE) {
                $array_out['res'] = 'ok';
                $array_out['insert_id'] = $result;
            } else {
                $array_out['res'] = 'no';
                $array_out['msg'] = 'Error al agregar el plan. Intenta de nuevo.';
            }
        } else {
            $array_out['res'] = 'no';
            $array_out['msg'] = 'Este reactivo se ha utilizado en un examen. No lo puedes mover.';
        }
        echo json_encode($array_out);
    }

    function editNodoPadre() {
        $id = $this->db->escape_str($this->input->post('id'));
        $pid = $this->db->escape_str($this->input->post('pid'));
        $array_out = array();
        $result = $this->plan_model->get_editNodoPadre($id, $pid);
        if ($result != FALSE) {
            $array_out['res'] = 'ok';
            $array_out['insert_id'] = $result;
        } else {
            $array_out['res'] = 'no';
            $array_out['msg'] = 'Error al mover el plan. Intenta de nuevo.';
        }
        echo json_encode($array_out);
    }

    function deleteNodo() {
        $id = $this->input->post('id') * 1;
        $array_out = array();
        $result = $this->plan_model->get_deleteNodo($id);
        if ($result != FALSE) {
            $array_out['res'] = 'ok';
        } else {
            $array_out['res'] = 'no';
            $array_out['msg'] = 'Error al borrar el plan. Intenta de nuevo.';
        }
        echo json_encode($array_out);
    }

    function getArbol($origen = '', $idRol) {
        $rol = $this->session->userdata('id_rol' . $this->clv_sess);
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
        $jsonOut = '[';
        if ($origen == 'roles') {
            $arbol = $this->plan_model->get_dataNodosViewRoles($pId, $idRol);
            foreach ($arbol as $n) {
                $jsonOut .= "{ id:'" . $n['id'] . "',	name:'" . $n['name'] . "',isParent:" . $n['isParent'] . ",checked:" . $n['checked'] . "},";
            }
        } else if ($origen == 'seguimiento') {
            $arbol = $this->plan_model->get_dataNodosViewSeguimiento($pId, $idRol);
            foreach ($arbol as $n) {
                $jsonOut .= "{ id:'" . $n['id'] . "',	name:'" . $n['name'] . "',isParent:" . $n['isParent'] . ",checked:" . $n['checked'] . "},";
            }
        } else {
            $arbol = $this->plan_model->get_dataNodos($pId, $rol);
            foreach ($arbol as $n) {
                $jsonOut .= "{ id:'" . $n['id'] . "',	name:'" . $n['name'] . "',isParent:" . $n['isParent'] . "},";
            }
        }


        $jsonOut = trim($jsonOut, ',');
        $jsonOut .= ']';
        echo $jsonOut;
    }

    function getArbol2() {
        $id = $this->input->post('id') * 1;
        $arbol = $this->plan_model->get_dataNodos($id);
        echo json_encode($arbol);
    }

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
            'pla_clave' => $this->db->escape_str($this->input->post('clave')),
            'pla_clv' => $this->db->escape_str($this->input->post('clv')),
            'pla_nombre' => $this->db->escape_str($this->input->post('nombre')),
            'pla_padre' => $this->db->escape_str($this->input->post('pclave')),
            'pla_descripcion' => $this->db->escape_str($this->input->post('descripcion'))
        );
        $this->load->model('plan_model');
        $plan = $this->plan_model->getAgregaPlan($datosPlan);
        echo $plan;
    }

    function contar() {
        $id = $this->input->post('id') * 1;
        $totalNodos = $this->plan_model->countNodos($id);
        echo json_encode($totalNodos);
    }

    function copyRama() {
        $idNodoOrigen = $this->input->post('nodoOrigen') * 1;
        $idNodoDestino = $this->input->post('nodoDestino') * 1;
        $nodoContenedor = $this->db->escape_str($this->input->post('nodoContenedor'));
        if ($idNodoOrigen != 0 && $idNodoDestino != 0) {
            $clv_sess = $this->config->item('clv_sess');
            $user_id = $this->session->userdata('user_id' . $clv_sess);
            $rol = $this->session->userdata('id_rol' . $this->clv_sess);
            $insert = $this->plan_model->copyReactivosToRama($idNodoOrigen, $idNodoDestino, $user_id, $rol, $nodoContenedor);
            if ($insert != FALSE) {
                $array_out['res'] = 'ok';
            } else {
                $array_out['res'] = 'no';
                $array_out['msg'] = 'Error al copiar los reactivos. Intenta de nuevo.';
            }
            echo json_encode($array_out);
        }
    }

    function getRamasContenidas() {
        $idPlan = $this->input->post('idPlan') * 1;
        if ($idPlan != 0) {
            $planes = $this->plan_model->getNamePlanes($idPlan);
            echo json_encode($planes);
        }
    }

}

?>