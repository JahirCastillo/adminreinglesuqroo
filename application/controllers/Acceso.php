<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Acceso extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("acceso_model");
    }

    public function index() {
        $data_template['show_header'] = FALSE;
        $data_template['content'] = $this->load->view('acceso/acceso_view', FALSE, TRUE);
        // $data_template['content'] = $this->load->view('acceso/construccion_view', FALSE, TRUE);
        $this->load->view('template', $data_template);
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
        $this->load->library('encryption');
        $login = $this->db->escape_str($this->input->post('usuario'));
        $pass = $this->db->escape_str($this->input->post('pass'));
        $query = $this->acceso_model->datoslogin($login, $pass);
        $clv_sess = $this->config->item('clv_sess');
        $data = array();
        if ($query->num_rows() == 0) {
            $data['sientra'] = 'no';
            $data['msg_class'] = 'error';
            $data['mensaje'] = 'El usuario <b>' . $login . '</b> no se encuentra registrado o los datos son incorrectos, por favor intenta de nuevo. ';
        } else {
            $row = $query->row();
            //si la cadena $row->usu_password (decodificada) del set de datos no es igual a $clave resultado=error si es igual resultado=ok 
            if ($this->encryption->decrypt($row->password) != $pass) {
                $data['sientra'] = 'no';
                $data['msg_class'] = 'warning';
                $data['mensaje'] = 'La contraseña no correspone con el usuario <b>' . $login . '</b>.';
            } else {
                if ($row->estatus == 0) {
                    $data['sientra'] = 'no';
                    $data['msg_class'] = 'info';
                    $data['mensaje'] = 'La cuenta se encuentra deshabilitada. Consulta al administrador del sistema.';
                } else {
                    $data['sientra'] = 'ok';
                    $this->session->set_userdata('user_id' . $clv_sess, $row->id);
                    $this->session->set_userdata('login' . $clv_sess, $row->login);
                    $this->session->set_userdata('nombre' . $clv_sess, $row->nombre);
                    $this->session->set_userdata('rol' . $clv_sess, $row->rol);
                    $this->session->set_userdata('id_rol' . $clv_sess, $row->id_rol);
                    $this->session->set_userdata('foto' . $clv_sess, $row->imagen);
                }
            }
        }
        echo json_encode($data);
    }

    /**
     * @brief Funcion obtiene un arreglo con los permisos y modulos de un usuario
     * @access public
     * @param $this->input->post('nick');   String login del usuario
     * @param $this->input->post('clave')  String  Contraseña
     * @return JSON String con los resultados de la validacion
     */
    //{acd: ["rdo"],hlp: ["rdo"],rep: ["pdf","xsl"],usu: ["add","upd","del","des","rdo","rep"]}
    function get_permisos($usuario = '') {
        $permisos_arr = array();
        if ($usuario != '' && ($usuario * 1) != 0) {
            $permisos_db = $this->acceso_model->get_permisosUsuario($usuario);
            $permisos_arr = $this->ci_acl_framew->get_parse_array_permisos($permisos_db);
        }
        return $permisos_arr;
    }

    function logout() {
        $this->session->sess_destroy();
        $this->load->helper('url');
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
        $datos_plantilla['title_mod'] = 'Acceso denegado';
        $datos_plantilla['navigate_mod'] = '<li><a onclick="redirect_to(\'inicio\')"><i class="fa fa-th"></i> Menú</a></li> <li><a class="active">error !</a></li>';
        $datos_plantilla['content'] = $this->load->view('acceso/acceso_denegado_view', FALSE, true);
        $this->load->view('template', $datos_plantilla);
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
        $datos_plantilla['title_mod'] = 'Sitio en mantenimiento';
        $datos_plantilla['navigate_mod'] = '<li><a onclick="redirect_to(\'inicio\')"><i class="fa fa-th"></i> Menú</a></li> <li><a class="active">Sitio en construcción</a></li>';
        $datos_plantilla['content'] = $this->load->view('acceso/construccion_view', FALSE, true);
        $this->load->view('template', $datos_plantilla);
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
    /* function acceso_home($pag_redirect) {
      $data['url'] = $pag_redirect;
      $this->load->view('acceso/home_acces_view', $data);
      } */

    /* function encode() {
      $this->load->library('encryption');
      echo $this->encryption->decrypt("Chacha.53");
      }
     
    /*function decode() {
        $this->load->library('encryption');
        echo $this->encryption->decrypt('8fdc1a9f9b56f7fb46ba629f8b02657b0880c509c4ed3f8faaad4a59ea1172244aad644e8ced5e42bcb9eb42891215e70b967b19dfe5ec128f065fe28b4bb88cylTpQf22TCcLiEuQCPNWLf5CK+29pmqEJ0mgFan9kl0=');
    }*/

}
