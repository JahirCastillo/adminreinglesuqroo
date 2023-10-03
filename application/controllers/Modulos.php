<?php

class Modulos extends CI_Controller {

    private $clave_modulo = 'MDP';
    private $clv_sess = '';

    function __construct() {
        parent::__construct();
        $this->clv_sess = $this->config->item('clv_sess');
        $user_id = $this->session->userdata('user_id' . $this->clv_sess);
        if (!$user_id) {
            redirect('acceso');
        }
        $this->load->model('modulos_model');
    }

    function index() {
        $this->load->model('acceso_model');
        $user_id = $this->session->userdata('user_id' . $this->clv_sess);
        $permisos = $this->ci_acl_framew->get_parse_array_permisos($this->acceso_model->get_permisosUsuario($user_id, $this->clave_modulo));
        //datos vista
        $datos_vista['modulos_inicio'] = $this->modulos_model->get_modulos();
        if (array_key_exists($this->clave_modulo, $permisos)) {
            $datos_vista['permisos_modulo'] = $permisos[$this->clave_modulo];
        }
        //datos modulo
        $data_modulo = $this->acceso_model->get_iconModulo($this->clave_modulo);
        //datos plantilla
        $datos_plantilla['title_mod'] = $data_modulo['icon'] . ' ' . $data_modulo['nombre'];
        $datos_plantilla['navigate_mod'] = '<li><a onclick="redirect_to(\'inicio\')"><i class="fa fa-th"></i> Menú</a></li> <li><a class="active"> ' . $data_modulo['icon'] . ' ' . $data_modulo['nombre'] . '</a></li>';
        $datos_plantilla['content'] = $this->load->view('modulos/modulos_view', $datos_vista, true);
        $this->load->view('template', $datos_plantilla);
    }

    function update() {
        $json_out['resp'] = 'no';
        $json_out['msg'] = 'Ha ocurrido un problema. Intenta más tarde.';
        if ($this->input->is_ajax_request()) {
            $datos['mod_clave'] = strtoupper($this->input->post('clave'));
            $datos['mod_nombre'] = $this->input->post('nombre');
            $datos['mod_url'] = $this->input->post('url');
            $datos['mod_icon'] = $this->input->post('icono');
            $datos['mod_activo'] = $this->input->post('activo');
            $datos['mod_orden'] = $this->input->post('orden');
            $id = ($this->input->post('id') * 1);
            if ($id != 0) {
                $id_insert = $this->modulos_model->getModifica($datos, $id);
                if ($id_insert != FALSE) {
                    $json_out['resp'] = 'ok';
                } else {
                    $json_out['resp'] = 'no';
                    $json_out['msg'] = 'Ha ocurrido un problema con el servidor de datos.';
                }
            } else {
                $id_insert = $this->modulos_model->getAgrega($datos);
                $json_out = array();
                if ($id_insert != FALSE) {
                    $json_out['resp'] = 'ok';
                    $json_out['id_insert'] = $id_insert;
                } else {
                    $json_out['resp'] = 'no';
                    $json_out['msg'] = 'Ha ocurrido un problema con el servidor de datos.';
                }
            }
        }
        echo json_encode($json_out);
    }

    function elimina() {
        $json_out['resp'] = 'no';
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id');
            $id_insert = $this->modulos_model->getElimina($id);
            if ($id_insert != FALSE) {
                $json_out['resp'] = 'ok';
                $json_out['id_insert'] = $id_insert;
            }
        }
        echo json_encode($json_out);
    }

    function permisos() {
        $json_out = array();
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id');
            $json_out = $this->modulos_model->getPermisosModulo($id);
        }
        echo json_encode($json_out);
    }

    function update_permiso() {
        $json_out['resp'] = 'no';
        $json_out['msg'] = 'Ha ocurrido un problema. Intenta más tarde.';
        if ($this->input->is_ajax_request()) {
            $datos['per_clave'] = $this->input->post('clave_per');
            $datos['per_nombre'] = $this->input->post('nombre_per');
            $datos['per_descripcion'] = $this->input->post('descripcion_per');
            $idmodulo = $this->input->post('idmodulo');
            $id = ($this->input->post('id') * 1);
            if ($id != 0) {
                $id_insert = $this->modulos_model->getModificaPermiso($datos, $id);
                if ($id_insert != FALSE) {
                    $json_out['resp'] = 'ok';
                } else {
                    $json_out['resp'] = 'no';
                    $json_out['msg'] = 'Ha ocurrido un problema con el servidor de datos.';
                }
            } else {
                 $datos['per_modulo'] =$idmodulo;
                $id_insert = $this->modulos_model->getAgregaPermiso($datos);
                $json_out = array();
                if ($id_insert != FALSE) {
                    $json_out['resp'] = 'ok';
                    $json_out['id_insert'] = $id_insert;
                } else {
                    $json_out['resp'] = 'no';
                    $json_out['msg'] = 'Ha ocurrido un problema con el servidor de datos.';
                }
            }
        }
        echo json_encode($json_out);
    }

    function elimina_permiso() {
        $json_out['resp'] = 'no';
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id');
            $id_insert = $this->modulos_model->getEliminaPermiso($id);
            if ($id_insert != FALSE) {
                $json_out['resp'] = 'ok';
                $json_out['id_insert'] = $id_insert;
            }
        }
        echo json_encode($json_out);
    }

}
