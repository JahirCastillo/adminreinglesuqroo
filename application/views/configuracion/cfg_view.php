<style>
    .bg-label-default {
        min-height: 68px;
        width: 100%;
        border: 1px solid #3C8DBC;
        float: left;
        margin-bottom: 10px;
        padding-bottom: 10px;
        font-size: 12px;
        margin-top: 2px;
    }
    .label_cfg{    
        font-size: 14px;
        border-bottom-left-radius: 0px;
        border-bottom-right-radius: 0px;
        padding: 4px 6px;
    }
</style>
<div id="cfgs_div" class="row">
    <?php
    if (isset($datos)) {
        echo '<form id="frm_conf">';
        foreach ($datos as $modulo => $cfgs) {
            if ($modulo == '') {
                $modulo = 'Configuraciones del sistema';
            }
            ?>
            <div class="col-md-6">

                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $modulo; ?></h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body" style="display: block;">
                        <?php
                        $control = '';
                        foreach ($cfgs as $c) {
                            switch ($c['cfg_tipocontrol']) {
                                case 'txt':
                                    $control = '<input type="text" class="form-control" id="cfg_' . $c['cfg_id'] . '" name="cfg_' . $c['cfg_id'] . '" value="' . $c['cfg_valor'] . '">';
                                    break;
                                case 'sel':
                                    $pos_vals = explode('|', $c['cfg_posiblesvalores']);
                                    $control = '<select class="form-control" id="cfg_' . $c['cfg_id'] . '" name="cfg_' . $c['cfg_id'] . '">';
                                    foreach ($pos_vals as $value) {
                                        $data_val = explode('->', $value);
                                        $selected = '';
                                        if ($data_val[0] == $c['cfg_valor']) {
                                            $selected = 'selected="selected"';
                                        }
                                        $control.='<option value="' . $data_val[0] . '" ' . $selected . '>' . $data_val[1] . '</option>';
                                    }
                                    $control.='</select>';
                                    break;
                                case 'spn':
                                    $control = '<input type="text" class="form-control spinner" id="cfg_' . $c['cfg_id'] . '" name="cfg_' . $c['cfg_id'] . '" value="' . $c['cfg_valor'] . '">';
                                    break;
                                case 'txa':
                                    $control = '<textarea class="form-control spinner" id="cfg_' . $c['cfg_id'] . '" name="cfg_' . $c['cfg_id'] . '">' . $c['cfg_valor'] . '</textarea>';
                                    break;

                                default:
                                    break;
                            }
                            ?>
                            <div class="content_cfg">
                                <span class="nombre label label-primary label_cfg"><?php echo $c['cfg_nombre']; ?></span>
                                <div class="bg-label-default">
                                    <div class="col-md-12">
                                        <b>Clave: </b><?php echo $c['cfg_clave']; ?><br>
                                        <div class="desc"><?php echo $c['cfg_descripcion']; ?></div>
                                        <?php echo $control; ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>               
            </div>
            <?php
        }
        echo '</form>';
    }
    ?>
    <?php ?>
</div>
<div class="row">
    <div class="col-md-4 pull-right">
        <button id="btn_guardar" onclick="guardar()" class="btn btn-primary btn-flat col-md-12">Guardar configuraciones</button>
    </div>
</div>
<script type="text/javascript" src="./js/modal_bootstrap_extend/js/bootstrap-dialog.js"></script>
<script>
            function guardar() {
                BootstrapDialog.show({
                    title: 'Guardar configuraciones',
                    message: 'Se guardaran los cambios realizados en la configuración.<br> ¿Deseas continuar?',
                    buttons: [{
                            cssClass: 'btn-primary',
                            label: 'Si, guardar',
                            action: function (dialog) {
                                try {
                                    var datos = $('#frm_conf').serialize(),
                                            urll = "configuracion/guarda",
                                            respuesta = get_object(urll, datos);
                                    if (respuesta.resp == 'ok') {
                                        notify_block('Guardar configuraciones', 'Las configuraciones fueron guardadas satisfactoriamente', '', 'success');
                                    } else {
                                        mensaje_center('Guardar configuraciones', 'Error', 'Error al guardar las configuraciones. Intente más tarde.', 'error');
                                        alert('error');
                                        redirect_to('configuracion');
                                    }
                                } catch (e) {
                                    mensaje_center('Guardar configuraciones', 'Error', 'Error al guardar las configuraciones. Intente más tarde.', 'error');
                                    alert('error');
                                    redirect_to('configuracion');
                                }
                                dialog.close();
                            }
                        }, {
                            label: 'No, Cancelar',
                            action: function (dialog) {
                                dialog.close();
                                redirect_to('configuracion');
                            }
                        }]
                });
            }
            $(document).ready(function () {

            });
</script>
<script>
    $(document).ready(function () {

    });
</script>