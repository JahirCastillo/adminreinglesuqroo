<style>
    .mn_icon i{font-size: 30px;}
    .mn_modulo_txt{ font-weight: bolder}
    #div_menu{margin: 20px 0px;}
    .btn_modulo { width: 100%; border-radius: 0px; margin-bottom: 5px; padding: 5px;}
    .btn_modulo table{ font-size: 10px; text-align: left; margin-bottom: 0px;}
    .btn_modulo table td{ padding: 4px;}
    .div_opc{    float: right;}
    .btn_modoption { font-size: 12px !important; padding: 6px; min-width: 40px;}
    #panel_mod .box-body{ height: 540px; overflow-y: auto;}
    .btn_modulo.modulo_seleccionado{ border: 2px solid #3C8DBC; background-color: #CEE7F5;}
    #modulo_seleccionado { float: right; margin-right: 60px;  font-size: 16px;  padding: 2px 10px; background-color: #3C8DBC; color: #fff;}
    #tit_permisos_modulo{ width: 100%}
    #btn_addModulo,#btn_addPermiso{ float: right;  margin-right: 34px; }
    .btn_modulo table td {padding: 2px !important; padding-left: 10px !important;}
    /* ventana add upd*/
    #icono_render { text-align: center;}
    #icono_render i { font-size: 60px;}
    .body_permiso{ font-size: 10px;}
    .panel_permiso h3{font-size: 13px !important;}
    .panel_permiso .box-header{padding: 5px 10px; }
    .panel_permiso .box-header>.box-tools {top: 0px;}
    .btn_del_per, .btn_upd_per { padding: 2px; min-width: 34px; border-radius: 0px; border: 0px;}
</style>
<script type="text/javascript" src="./js/modal_bootstrap_extend/js/bootstrap-dialog.js"></script>
<script type="text/javascript" language="javascript" src="./js/jquery-validation/jquery.validate.min.js"></script>
<script type="text/javascript" language="javascript" src="./js/jquery-validation/messages_es.js"></script>
<div id="dialog_moduloAddupd" class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 id="title_diagAddupdModulo" class="modal-title" >Modal title</h4>
            </div>
            <div class="modal-body">
                <form class="row" id="frm_modAddUpd">
                    <input type="hidden" id="modid" name="modid" value="">
                    <div class="col-md-4">
                        <div id="icono_render"><i class="fa fa-square"></i></div>
                        <div><div class="form-group"><label for="icono">Icono*</label><input type="text" class="form-control required" id="icono" name="icono"> </div></div>
                        <div><div class="form-group"><label for="url">Activo*</label><select id="activo" name="activo" class="form-control required"><option value="0">Inactivo</option><option value="1">Activo</option></select></div></div> 
                    </div>
                    <div class="col-md-8">
                        <div class="form-group"><label for="nombre">Nombre del módulo*</label><input type="text" class="form-control required" id="nombre" name="nombre"> </div>
                        <div class="form-group"><label for="url">URL*</label><input type="text" class="form-control required" id="url" name="url"> </div>
                        <div class="row">
                            <div class="col-md-6"><div class="form-group"><label for="clave">Clave*</label><input type="text" class="form-control required" id="clave" name="clave" maxlength="3" minlength="3"> </div></div>
                            <div class="col-md-6"><div class="form-group"><label for="url">Orden*</label><input type="text" class="form-control number required" id="orden" name="orden" value="0"> </div></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn_action" data-action="">Aceptar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<div id="dialog_permisosAddupd" class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 id="title_diagAddupdPermisos" class="modal-title" >Modal title</h4>
            </div>
            <div class="modal-body">
                <form class="row" id="frm_perAddUpd">
                    <input type="hidden" id="perid" name="perid" value="">
                    <input type="hidden" id="idmodulo" name="idmodulo" value="">
                    <div class="col-md-12">
                        <div class="form-group"><label for="nombre_per">Nombre del permiso*</label><input type="text" class="form-control required" id="nombre_per" name="nombre_per"> </div>
                        <div class="form-group"><label for="clave_per">Clave*</label><input type="text" class="form-control required" id="clave_per" name="clave_per" maxlength="3" minlength="3"> </div>
                        <div class="form-group"><label for="descripcion_per">Descripción</label><textarea id="descripcion_per" name="descripcion_per" class="form-control"></textarea></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn_action_per" data-action="">Aceptar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<div class="col-md-3 clases">       
    <div id="panel_mod" class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Módulos</h3>
            <i id="btn_addModulo" class="fa fa-plus btn_modoption btn btn-success" title="Agregar módulo"></i>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div><!-- /.box-tools -->
        </div><!-- /.box-header -->
        <div class="box-body" id="body_modulos">
            <?php
            if (isset($modulos_inicio)) {
                foreach ($modulos_inicio as $mod) {
                    $clickOn = "redirect_to('" . $mod['url'] . "');";
                    $icon_tmp = $mod['icon'];
                    $img_tmp = $mod['imagen'];
                    $icon = '<i class="fa fa-square"></i>';
                    if ($icon_tmp != '') {
                        $icon = '<i class="fa ' . $icon_tmp . '"></i>';
                    } else if ($img_tmp != '') {
                        $icon = '<img src="./images/' . $img_tmp . '"/>';
                    }
                    $clave_mod = $mod['clave'];
                    ?>
                    <button class="btn btn_modulo" id="mod_<?php echo $mod['id']; ?>" data-id="<?php echo $mod['id']; ?>">
                        <div class="mn_icon"><?php echo $icon; ?> <div class="div_opc" >
                                <?php if (isset($permisos_modulo) && in_array('upd', $permisos_modulo)) { ?>
                                    <i data-id="<?php echo $mod['id']; ?>" class="fa fa-edit btn_modoption btn btn-warning opc_modUpd"  title="Modificar módulo"></i> 
                                <?php } if (isset($permisos_modulo) && in_array('del', $permisos_modulo)) { ?>
                                    <i data-id="<?php echo $mod['id']; ?>" class="fa fa-trash btn_modoption btn btn-danger opc_modDel"  title="Eliminar módulo"></i>
                                <?php } ?>
                            </div> </div> 
                        <table class="table table-bordered table-condensed">
                            <tr><td>Nombre</td><td class="mod_nombre"><?php echo $mod['nombre']; ?></td>
                            <tr><td>Clave</td><td class="mod_clave"><?php echo $mod['clave']; ?></td></tr>
                            <tr><td>Icono</td><td  class="mod_icon"><?php echo $icon_tmp; ?></td>
                            <tr><td>URL</td><td  class="mod_url"><?php echo $mod['url']; ?></td>
                            <tr><td>Activo</td><td  class="mod_activo"><?php
                                    if ($mod['activo'] == 1) {
                                        echo 'Activo';
                                    } else
                                        echo 'Inactivo';
                                    ?></td>
                            <tr><td>Orden</td><td  class="mod_orden" data-id="<?php echo $mod['id']; ?>"><?php echo $mod['orden']; ?></td>
                            </tr>
                        </table>
                    </button>
                    <?php
                }
            }
            ?>
        </div><!-- /.box-body -->
    </div>
</div>
<div class="col-md-9 clases">       
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 id="tit_permisos_modulo" class="box-title"  data-idmod="0">Permisos <i id="btn_addPermiso" class="fa fa-plus btn_modoption btn btn-success" title="Agregar permiso"></i><div id="modulo_seleccionado">Selecciona un módulo</div>  </h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div id="body_permisos_panel" class="box-body">Selecciona un módulo para ver permisos.</div>
    </div>
</div>
<script>
    function getHtmlModulo(id, nombre, clave, icono, url, activo, orden) {
        return '<button class="btn btn_modulo" id="mod_' + id + '" data-id="' + id + '">' +
                '<div class="mn_icon"><i class="fa ' + icono + '"></i> <div class="div_opc">' +
                '<i data-id="' + id + '" class="fa fa-edit btn_modoption btn btn-warning opc_modUpd"  title="Editar módulo"></i>' +
                '<i data-id="' + id + '" class="fa fa-minus btn_modoption btn btn-danger opc_modDel"  title="Eliminar módulo"></i>' +
                '</div> </div>' +
                '<table class="table table-bordered table-condensed">' +
                '<tbody><tr><td>Nombre</td><td class="mod_nombre">' + nombre + '</td>' +
                '</tr><tr><td>Clave</td><td class="mod_clave">' + clave + '</td>' +
                '</tr><tr><td>Icono</td><td class="mod_icon">' + icono + '</td>' +
                '</tr><tr><td>URL</td><td class="mod_url">' + url + '</td>' +
                '</tr><tr><td>Activo</td><td class="mod_activo">' + activo + '</td>' +
                '</tr><tr><td>Orden</td><td class="mod_orden" data-id="' + id + '">' + orden + '</td>' +
                '</tr>' +
                '</tbody></table>' +
                '</button>';
    }

    function getHtmlPermiso(id, nombre, clave, descripcion, idmodulo) {
        return '<div id="content_per_' + id + '" class="col-md-4">' +
                '<div id="per_' + id + '" data-idmod="' + idmodulo + '" class="box box-default panel_permiso">' +
                '<div class="box-header with-border">' +
                '<h3 class="box-title per_nombre">' + nombre + '</h3>' +
                '<div class="box-tools pull-right"><button class="btn btn-danger btn_del_per" data-id="' + id + '" title="Eliminar permiso"><i class="fa fa-trash"></i></button><button class="btn btn-warning btn_upd_per" data-id="' + id + '" title="Editar permiso"><i class="fa fa-edit"></i></button><button class="btn btn-box-tool" data-widget="collapse" title="Eliminar permiso"><i class="fa fa-minus"></i></button> </div>' +
                '</div>' +
                '<div class="box-body body_permiso"><div><label>Clave: </label><span class="clv_per"> ' + $.trim(clave) + '</span></div><div><label>Descripcion: </label><div class="des_per">' + descripcion + '</div></div></div>' +
                '</div>' +
                '</div>';
    }

    function noSelectModule() {
        $('#body_permisos_panel').html('Selecciona un módulo para ver permisos.');
        $('#modulo_seleccionado').html('Selecciona un módulo');
        $('#tit_permisos_modulo').attr('data-idmod', 0);
    }

    function getIDafterInsertOrden(myOrden) {
        var orden_ant = 0, orden = 0, id_ant = 0, id = 0;
        id_after = 0;
        $('#panel_mod .mod_orden').each(function () {
            orden = $(this).text();
            id = $(this).attr('data-id');
            if ((myOrden * 1) == (orden * 1)) {
                id_after = id;
                return true;
            } else if ((myOrden * 1) < (orden * 1)) {
                id_after = id_ant;
                return true;
            }
            orden_ant = $(this).text();
            id_ant = $(this).attr('data-id');
        });
        return id_after;
    }

    $(document).ready(function () {
<?php if (isset($permisos_modulo) && in_array('add', $permisos_modulo)) { ?>
            function agregar() {
                var sepudo = false;
                if ($('#frm_modAddUpd').validate().form()) {
                    try {
                        var datos = $('#frm_modAddUpd').serialize(),
                                urll = "modulos/update",
                                respuesta = get_object(urll, datos);
                        if (respuesta.resp == 'ok') {
                            notify_block('Agregar módulo', 'El módulo se agregó satisfactoriamente', '', 'success');
                            var nombre = $('#frm_modAddUpd #nombre').val(),
                                    clave = $('#frm_modAddUpd #clave').val(),
                                    icono = $('#frm_modAddUpd #icono').val(),
                                    url = $('#frm_modAddUpd #url').val(),
                                    orden = $('#frm_modAddUpd #orden').val(),
                                    activo = $('#frm_modAddUpd #activo').val();
                            var html = getHtmlModulo(respuesta.id_insert, nombre, clave, icono, url, activo, orden);
                            var id_mod = getIDafterInsertOrden(orden) * 1;
                            if (id_mod == 0) {
                                $('#body_modulos').prepend(html);
                            } else {
                                $('#mod_' + id_mod).after(html);
                            }
                            sepudo = true;
                        } else {
                            mensaje_center('Agregar módulo', 'Error', respuesta.msg, 'error');
                        }
                    } catch (e) {
                        mensaje_center('Agregar módulo', 'Error', 'Error al agregar el módulo. Intente más tarde.', 'error');
                    }
                } else {
                    sepudo = -1;
                }
                return sepudo;
            }

            $('#btn_addModulo').click(function () {
                formClearModuloAddupd();
                $('#title_diagAddupdModulo').text('Agregar Módulo');
                $('#btn_action').attr('data-action', 'add');
                $('#btn_action').text('Agregar módulo');
                $('#dialog_moduloAddupd').modal('show');
            });
<?php } if (isset($permisos_modulo) && in_array('upd', $permisos_modulo)) { ?>
            $('#body_modulos').on('click', '.opc_modUpd', function () {
                formClearModuloAddupd();
                var data_id = $(this).attr('data-id');
                var id = 'mod_' + data_id;
                $('#title_diagAddupdModulo').text('Modificar Módulo');
                $('#btn_action').attr('data-action', 'upd');
                $('#btn_action').text('Modificar módulo');
                $('#dialog_moduloAddupd').modal('show');
                $('#dialog_moduloAddupd #modid').val($(this).attr('data-id'));
                $('#dialog_moduloAddupd #nombre').val($('#' + id + ' .mod_nombre').text());
                $('#dialog_moduloAddupd #url').val($('#' + id + ' .mod_url').text());
                $('#dialog_moduloAddupd #icono').val($('#' + id + ' .mod_icon').text());
                $('#dialog_moduloAddupd #clave').val($('#' + id + ' .mod_clave').text());
                $('#icono_render i').attr('class', 'fa ' + $('#' + id + ' .mod_icon').text());
                $('#dialog_moduloAddupd #orden').val($('#' + id + ' .mod_orden').text());
                var activo = 0;
                if ($('#' + id + ' .mod_activo').text() == 'Activo') {
                    activo = 1;
                }
                $('#dialog_moduloAddupd #activo').val(activo);
            });

            function modifica(id) {
                var sepudo = false;
                if ($('#frm_modAddUpd').validate().form()) {
                    try {
                        var datos = $('#frm_modAddUpd').serialize() + '&id=' + id,
                                urll = "modulos/update",
                                respuesta = get_object(urll, datos);
                        if (respuesta.resp == 'ok') {
                            $('#mod_' + id).remove();
                            notify_block('Modificar módulo', 'El módulo modificó satisfactoriamente', '', 'success');
                            var nombre = $('#frm_modAddUpd #nombre').val(),
                                    clave = $('#frm_modAddUpd #clave').val(),
                                    icono = $('#frm_modAddUpd #icono').val(),
                                    url = $('#frm_modAddUpd #url').val(),
                                    orden = $('#frm_modAddUpd #orden').val(),
                                    activo = $('#frm_modAddUpd #activo').val();
                            var html = getHtmlModulo(id, nombre, clave, icono, url, activo, orden);
                            var id_mod = getIDafterInsertOrden(orden) * 1;
                            if (id_mod == 0) {
                                $('#body_modulos').prepend(html);
                            } else {
                                $('#mod_' + id_mod).after(html);
                            }
                            noSelectModule();
                            sepudo = true;
                        } else {
                            mensaje_center('Modificar módulo', 'Error', respuesta.msg, 'error');
                        }
                    } catch (e) {
                        mensaje_center('Modificar módulo', 'Error', 'Error al modificar el módulo. Intente más tarde.', 'error');
                    }
                } else {
                    sepudo = -1;
                }
                return sepudo;
            }

<?php } if (isset($permisos_modulo) && in_array('del', $permisos_modulo)) { ?>
            function elimina(id) {
                var sepudo = false;
                BootstrapDialog.show({
                    title: 'Borrar módulo',
                    message: 'Se borrará el módulo seleccionado: se ejcutarán las siguientes acciones.<br>* Se eliminarán los permisos asociados al módulo. <br>* Se eliminarán los permisos de los usuarios que tengan acceso a este módulo.<br> ¿Deseas continuar?',
                    buttons: [{
                            cssClass: 'btn-primary',
                            label: 'Si, Borrar módulo',
                            action: function (dialog) {
                                try {
                                    var datos = "id=" + id,
                                            urll = "modulos/elimina",
                                            respuesta = get_object(urll, datos);
                                    if (respuesta.resp == 'ok') {
                                        notify_block('Eliminar módulo', 'El módulo se eliminó satisfactoriamente', '', 'success');
                                        $('#mod_' + id).remove();
                                        noSelectModule();
                                        dialog.close();
                                        sepudo = true;
                                    } else {
                                        mensaje_center('Eliminar módulo', 'Error', respuesta.msg, 'error');
                                    }
                                } catch (e) {
                                    mensaje_center('Eliminar módulo', 'Error', 'Error al eliminar el módulo. Intente más tarde.', 'error');
                                }
                                dialog.close();
                            }
                        }, {
                            label: 'No, Cancelar',
                            action: function (dialog) {
                                dialog.close();
                            }
                        }]
                });
                return sepudo;
            }
            
            $('#body_modulos').on('click', '.opc_modDel', function () {
                var id = $(this).attr('data-id');
                var sepudo = elimina(id);
                if (sepudo) {
                    $('#body_permisos_panel').html('No hay permisos para el módulo seleccionado.');
                }
            });
<?php } ?>
<?php if (isset($permisos_modulo) && in_array('add', $permisos_modulo)) { ?>
            function agregar_permiso() {
                var sepudo = false;
                $('#idmodulo').val($('#tit_permisos_modulo').attr('data-idmod'));
                if ($('#frm_perAddUpd').validate().form()) {
                    try {
                        var datos = $('#frm_perAddUpd').serialize(),
                                urll = "modulos/update_permiso",
                                respuesta = get_object(urll, datos);
                        if (respuesta.resp == 'ok') {
                            notify_block('Agregar permiso', 'El permiso se agregó satisfactoriamente', '', 'success');
                            var nombre = $('#frm_perAddUpd #nombre_per').val(),
                                    clave = $('#frm_perAddUpd #clave_per').val(),
                                    idmodulo = $('#tit_permisos_modulo').attr('data-idmod'),
                                    descripcion = $('#frm_perAddUpd #descripcion_per').val();
                            var html = getHtmlPermiso(respuesta.id_insert, nombre, clave, descripcion, idmodulo);
                            $('#body_permisos_panel').append(html);
                            sepudo = true;
                        } else {
                            mensaje_center('Agregar permiso', 'Error', respuesta.msg, 'error');
                        }
                    } catch (e) {
                        mensaje_center('Agregar permiso', 'Error', 'Error al agregar el permiso. Intente más tarde.', 'error');
                    }
                } else {
                    sepudo = -1;
                }
                return sepudo;
            }

            $('#btn_addPermiso').click(function () {
                formClearPermisosAddupd();
                $('#title_diagAddupdPermisos').text('Agregar Permiso');
                $('#btn_action_per').attr('data-action', 'add');
                $('#btn_action_per').text('Agregar permiso');
                if (($('#tit_permisos_modulo').attr('data-idmod') * 1) != 0) {
                    $('#dialog_permisosAddupd').modal('show');
                } else {
                    mensaje_center('Agregar permiso a módulo', 'Error', 'Selecciona un módulo.', 'error');
                }
            });
<?php } if (isset($permisos_modulo) && in_array('upd', $permisos_modulo)) { ?>
            $('#body_permisos_panel').on('click', '.btn_upd_per', function () {
                formClearPermisosAddupd();
                var id = 'per_' + $(this).attr('data-id');
                $('#title_diagAddupdPermisos').text('Modificar Permiso');
                $('#btn_action_per').attr('data-action', 'upd');
                $('#btn_action_per').text('Modificar Permiso');
                $('#dialog_permisosAddupd').modal('show');
                $('#dialog_permisosAddupd #perid').val($(this).attr('data-id'));
                $('#dialog_permisosAddupd #nombre_per').val($('#' + id + ' .per_nombre').text());
                $('#dialog_permisosAddupd #clave_per').val($('#' + id + ' .clv_per').text());
                $('#dialog_permisosAddupd #descripcion_per').val($('#' + id + ' .des_per').text());
            });

            function modifica_permiso(id) {
                var sepudo = false;
                if ($('#frm_perAddUpd').validate().form()) {
                    try {
                        var datos = $('#frm_perAddUpd').serialize() + '&id=' + id,
                                urll = "modulos/update_permiso",
                                respuesta = get_object(urll, datos);
                        if (respuesta.resp == 'ok') {
                            $('#content_per_' + id).remove();
                            notify_block('Modificar permiso', 'El permiso se modificó satisfactoriamente', '', 'success');
                            var nombre = $('#frm_perAddUpd #nombre_per').val(),
                                    clave = $('#frm_perAddUpd #clave_per').val(),
                                    idmodulo = $('#tit_permisos_modulo').attr('data-idmod'),
                                    descripcion = $('#frm_perAddUpd #descripcion_per').val();
                            var html = getHtmlPermiso(respuesta.id_insert, nombre, clave, descripcion, idmodulo);
                            $('#body_permisos_panel').append(html);
                            sepudo = true;
                        } else {
                            mensaje_center('Modificar permiso', 'Error', respuesta.msg, 'error');
                        }
                    } catch (e) {
                        mensaje_center('Modificar permiso', 'Error', 'Error al agregar el permiso. Intente más tarde.', 'error');
                    }
                } else {
                    sepudo = -1;
                }
                return sepudo;
            }
<?php } if (isset($permisos_modulo) && in_array('del', $permisos_modulo)) { ?>
            function elimina_permiso(id) {
                var sepudo = false;
                BootstrapDialog.show({
                    title: 'Borrar permiso',
                    message: 'Se borrará el permiso seleccionado: se ejcutarán las siguientes acciones.<br>* Se eliminará el permiso de los <b>usuarios</b> que lo tengan asignado.<br>* Se eliminará el permiso de los <b>roles</b> que lo tengan asignado.<br> ¿Deseas continuar?',
                    buttons: [{
                            cssClass: 'btn-primary',
                            label: 'Si, Borrar permiso',
                            action: function (dialog) {
                                try {
                                    var datos = "id=" + id,
                                            urll = "modulos/elimina_permiso",
                                            respuesta = get_object(urll, datos);
                                    if (respuesta.resp == 'ok') {
                                        notify_block('Eliminar permiso', 'El permiso se eliminó satisfactoriamente', '', 'success');
                                        $('#content_per_' + id).remove();
                                        dialog.close();
                                        sepudo = true;
                                    } else {
                                        mensaje_center('Eliminar permiso', 'Error', respuesta.msg, 'error');
                                    }
                                } catch (e) {
                                    mensaje_center('Eliminar módulo', 'Error', 'Error al eliminar el permiso. Intente más tarde.', 'error');
                                }
                                dialog.close();
                            }
                        }, {
                            label: 'No, Cancelar',
                            action: function (dialog) {
                                dialog.close();
                            }
                        }]
                });
                return sepudo;
            }

            $('#body_permisos_panel').on('click', '.btn_del_per', function () {
                var id = $(this).attr('data-id');
                elimina_permiso(id);
            });
<?php } ?>

        function formClearModuloAddupd() {
            $('#dialog_moduloAddupd input[type="text"]').val('');
            $('#dialog_moduloAddupd select').val('0');
            $('#dialog_moduloAddupd #orden').val('0');
            $('#dialog_moduloAddupd #icono').val('fa-square');
        }
        function formClearPermisosAddupd() {
            $('#dialog_permisosAddupd input[type="text"]').val('');
            $('#dialog_permisosAddupd textarea').val('');
        }

        function cargaPermisosModulo(id) {
            try {
                var html = '', permisos = get_object('modulos/permisos', {id: id});
                $.each(permisos, function (k, v) {
                    html += getHtmlPermiso(v.id, v.nom, v.clv, v.des, v.mid);
                });
                $('#body_permisos_panel').html(html);
            } catch (e) {
                $('#body_permisos_panel').html('No hay permisos para el módulo seleccionado.');
            }
        }

        //// Add, Update Permisos
        $('#btn_action_per').click(function () {
            if ($(this).attr('data-action') == 'upd') {
                var se_pudo = modifica_permiso($('#perid').val());
                if (se_pudo == true) {
                    $('#dialog_permisosAddupd').modal('hide');
                } else if (se_pudo == false) {
                    $('#dialog_permisosAddupd').modal('hide');
                    mensaje_center('Modificar permiso', 'Error', 'Error al modificar el permiso. Intente más tarde.', 'error');
                }
            } else {
                var se_pudo = agregar_permiso();
                if (se_pudo == true) {
                    $('#dialog_permisosAddupd').modal('hide');
                } else if (se_pudo == false) {
                    $('#dialog_permisosAddupd').modal('hide');
                    mensaje_center('Agregar módulo', 'Error', 'Error al agregar el módulo. Intente más tarde.', 'error');
                }
            }
        });

        //// Add, Update Módulos
        $('#btn_action').click(function () {
            if ($(this).attr('data-action') == 'upd') {
                var se_pudo = modifica($('#modid').val());
                if (se_pudo == true) {
                    $('#dialog_moduloAddupd').modal('hide');
                } else if (se_pudo == false) {
                    $('#dialog_moduloAddupd').modal('hide');
                    mensaje_center('Modificar módulo', 'Error', 'Error al modificar el módulo. Intente más tarde.', 'error');
                }
            } else {
                var se_pudo = agregar();
                if (se_pudo == true) {
                    $('#dialog_moduloAddupd').modal('hide');
                } else if (se_pudo == false) {
                    $('#dialog_moduloAddupd').modal('hide');
                    mensaje_center('Agregar módulo', 'Error', 'Error al agregar el módulo. Intente más tarde.', 'error');
                }
            }
        });

        $('#panel_mod').on('click', '.btn_modulo', function () {
            $('.btn_modulo').removeClass('modulo_seleccionado');
            $(this).addClass('modulo_seleccionado');
            var id_html = $(this).attr('id');
            var id = $(this).attr('data-id');
            $('#modulo_seleccionado').text($('#' + id_html + ' .mod_nombre').text());
            $('#tit_permisos_modulo').attr('data-idmod', id);
            cargaPermisosModulo(id);
        });
    });
</script>

