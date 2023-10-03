<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Configuracion extends CI_Controller {

    private $clave_modulo = 'CFG';
    private $clv_sess = '';

    function __construct() {
        parent::__construct();
        $this->clv_sess = $this->config->item('clv_sess');
        $user_id = $this->session->userdata('user_id' . $this->clv_sess);
        if (!$user_id) {
            redirect('inicio');
        }
        $this->load->model("configuraciones_model");
    }

    public function index() {
        $this->load->model('acceso_model');
        $user_id = $this->session->userdata('user_id' . $this->clv_sess);
        $permisos = $this->ci_acl_framew->get_parse_array_permisos($this->acceso_model->get_permisosUsuario($user_id, $this->clave_modulo));
        //$datos_vista['modulos'] = $this->parser_modulos();
        if (array_key_exists($this->clave_modulo, $permisos)) {
            $datos_vista['permisos_modulo'] = $permisos[$this->clave_modulo];
        }
        $datos_vista['datos'] = $this->parseCFGs($this->configuraciones_model->getDatos());
        //datos modulo
        $data_modulo = $this->acceso_model->get_iconModulo($this->clave_modulo);
        $datos_plantilla['title_mod'] = $data_modulo['icon'] . ' ' . $data_modulo['nombre'];
        $datos_plantilla['modulos'] = $this->acceso_model->get_modulos();
        $datos_plantilla['permisos'] = $permisos;
        $datos_plantilla['navigate_mod'] = '<li><a onclick="redirect_to(\'inicio\')"><i class="fa fa-th"></i> Menú</a></li> <li><a class="active"> ' . $data_modulo['icon'] . ' ' . $data_modulo['nombre'] . '</a></li>';
        $datos_plantilla['content'] = $this->load->view('configuracion/cfg_view', $datos_vista, true);
        $this->load->view('template', $datos_plantilla);
    }

    private function parseCFGs($data) {
        $arr_out = array();
        foreach ($data as $v) {
            if (!array_key_exists($v['modulo'], $arr_out)) {
                $arr_out[$v['modulo']] = array();
            }
            array_push($arr_out[$v['modulo']], $v);
        }
        return $arr_out;
    }

    function g() {
        echo substr('cfg_3a', 4);
    }
    function guarda() {
        $json_out = array();
        $json_out['resp'] = 'no';
        $sepudo = $this->configuraciones_model->getGuarda($this->input->post());
        if ($sepudo) {
            $json_out['resp'] = 'ok';
        }
        echo json_encode($json_out);
    }

}
