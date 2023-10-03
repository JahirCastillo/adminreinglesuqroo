<?php

class Ayuda extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $clv_sess = $this->config->item('clv_sess');
        $user_id = $this->session->userdata('user_id' . $clv_sess);
        $this->load->model('acceso_model');
        $modulos = $this->acceso_model->get_iconModulo('HLP');
        $datos_plantilla['title_mod'] = $modulos['icon'] . ' ' . $modulos['nombre'];
        if ($user_id !== FALSE) {
            $datos_plantilla['navigate_mod'] = '<li><a onclick="redirect_to(\'inicio\')"><i class="fa fa-th"></i> Menú</a></li>
            <li class="active">Ayuda</li>';
        }
        $datos_vista = '';
        $datos_plantilla['content'] = $this->load->view('acl_views/ayuda_view', $datos_vista, TRUE);
        $this->load->view('template', $datos_plantilla);
    }

    function test() {
        $this->load->helper('process');
        processStart();
        setProgressTitle('Importando ...');
        for ($i = 1; $i <= 50; $i++) {
            //sleep(1);
            setProgressOf($i, 50);
            setProgressText('Procesando: ' . $i);
        }
        setProgress(100);
        endProcess();
    }

}
