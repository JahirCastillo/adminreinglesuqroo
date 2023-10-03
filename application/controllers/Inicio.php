<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Inicio extends CI_Controller {

    function __construct() {
        parent::__construct();
        $clv_sess = $this->config->item('clv_sess');
        $user_id = $this->session->userdata('user_id' . $clv_sess);
        if (!$user_id) {
            redirect('acceso');
        }
        $this->load->model('inicio_model');
    }

    function index() {
        $clv_sess = $this->config->item('clv_sess');
        $user_id = $this->session->userdata('user_id' . $clv_sess);
        $this->load->model('acceso_model');
        $datos_plantilla['title_mod'] = '<i class="fa fa-th"></i> INICIO - MENÚ';
        $datos_vista['modulos_inicio'] = $this->inicio_model->get_modulos($user_id);
        $permisos_db = $this->acceso_model->get_permisosUsuario($user_id);
        $datos_vista['permisos_inicio'] = $this->ci_acl_framew->get_parse_array_permisos($permisos_db);
        $datos_plantilla['content'] = $this->load->view('acl_views/inicio_view', $datos_vista, TRUE);
        $this->load->view('template', $datos_plantilla);
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
        //$this->load->library('encrypt');
        //$this->load->model("acceso_model");
        $login = $this->input->post('usuario');
        $pass = $this->input->post('pass');
        $clv_sess = $this->config->item('clv_sess');
        $usuarios = $this->config->item('usuarios');
        $data = array();
        if (!array_key_exists($login, $usuarios)) {
            $data['sientra'] = 'no';
            $data['mensaje'] = 'El usuario no se encuentra registrado o los datos son incorrectos, por favor intenta de nuevo. ';
        } else {
            //si la cadena $row->usu_password (decodificada) del set de datos no es igual a $clave resultado=error si es igual resultado=ok 
            if ($usuarios[$login]['password'] != $pass) {
                $data['sientra'] = 'no';
                $data['mensaje'] = 'La contraseña no correspone con el usuario';
            } else {
                $data['sientra'] = 'ok';
                $this->session->set_userdata('user_id' . $clv_sess, $usuarios[$login]['id']);
                $this->session->set_userdata('login' . $clv_sess, $login);
                $this->session->set_userdata('nombre' . $clv_sess, $usuarios[$login]['nombre']);
                $this->session->set_userdata('rol' . $clv_sess, 'A');
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

    //metodo que genera un password encriptado /encode/textoacodificar
    function encode($i) {
        $this->load->library('encrypt');
        echo $this->encrypt->encode($i);
    }

}

?>