<script type="text/javascript" language="javascript" src="./js/DataTables/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="./js/DataTables/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="./js/DataTables/css/dataTables.bootstrap.min.css" type="text/css" media="screen, projection">
<link rel="stylesheet" href="./css/responsive-table.css" type="text/css" media="screen, projection">
<script type="text/javascript" src="./js/modal_bootstrap_extend/js/bootstrap-dialog.js"></script>
<?php if (isset($permisos_modulo) && in_array('per', $permisos_modulo)) { ?>
    <div id="permisos_modal" class="modal fade">
        <div class="modal-dialog w90">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Permisos de la cuenta</h4>
                </div>
                <div id="div_permisos" class="modal-body">
                    <?php
                    $numod = $count_mod = 0;
                    $cuantos_modulos = count($modulos);
                    foreach ($modulos as $idmodulo => $mod) {
                        if ($count_mod == 0) {
                            echo '<div class="row_conpadding">';
                        }
                        ?>
                        <div id="modulo_<?php echo $idmodulo; ?>" class="col-md-2 conten_modulo border_color2">
                            <div class="head_modulo color2">
                                <div class="ico"><?php echo $mod['ico']; ?></div> <div class="nom"><?php echo $mod['nom']; ?></div>
                            </div>
                            <?php foreach ($mod['permisos'] as $per) { ?>
                                <div class="permiso_modulo" title="<?php echo $per['pdes']; ?>">
                                    <input type="checkbox" id="prm_<?php echo $idmodulo . '_' . $per['pid']; ?>" name="mod_<?php echo $per['pid']; ?>[]" value="<?php echo $per['pid']; ?>" disabled>
                                    <label for="<?php echo $idmodulo . '_' . $per['pid']; ?>"><?php echo $per['pnom']; ?></label>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                        $count_mod++;
                        $numod++;
                        if ($count_mod == 5) {
                            echo '</div>';
                            $count_mod = 0;
                        }
                        if ($cuantos_modulos == $numod) {
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
<?php } if (isset($permisos_modulo) && in_array('det', $permisos_modulo)) { ?>
    <div id="detalles_modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Detalles del usuario</h4>
                </div>
                <div id="body_det" class="modal-body">
                    <div id="nom_fot_det" class="col-md-3">
                        <div class="foto_div data_det"></div>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-12 nombre data_det"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 log_data"><b>Usuario agregó: </b><br><span class="usuagrego data_det"></span></div>
                            <div class="col-md-6 log_data"><b>Fecha alta: </b><br><span class="fechaagrego data_det"></span></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 log_data"><b>Usuario modificó: </b><br><span class="usumodifico data_det"></span></div>
                            <div class="col-md-6 log_data"><b>Última modificación: </b><br><span class="fechamodifico data_det"></span></div>  
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<div id="cont_div" class="col-md-12" >
    <div class="row">
        <div class="col-md-12">
            <div class=" button_bar row" >
                <div class="col-md-2 col-md-6 col-xs-6 column"><button id="btn_actualiza" class="btn btn-primary form-control"><i class="fa fa-undo"></i>&nbsp;Actualizar</button></div>
                <?php if (isset($permisos_modulo) && in_array('add', $permisos_modulo)) { ?><div class="col-md-2 col-md-6 col-xs-6 column end"><button id="btn_agregar"  class="btn btn-primary form-control"><i class="fa fa-plus"></i>&nbsp;Agregar</button></div><?php } ?>
            </div>
            <div class="row">
                <div id="conten_table_data" class="responsive-table col-md-12 container">
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-responsive" id="dtusuariossistema">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th width="60">Login</th>
                                <th>Nombre</th>
                                <th>Rol</th>
                                <th>Correo</th>
                                <th>Teléfono</th>
                                <th>Celular</th>
                                <th>Est Cnta</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5" class="dataTables_empty">Cargando...</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>id</th>
                                <th width="60">Login</th>
                                <th>Nombre</th>
                                <th>Rol</th>
                                <th>Correo</th>
                                <th>Teléfono</th>
                                <th>Celular</th>
                                <th>Est Cnta</th>
                                <th>&nbsp;</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" charset="utf-8">
    var dt_usuariossistema;

<?php if (isset($permisos_modulo) && in_array('per', $permisos_modulo)) { ?>
        function ver_permisos(id) {
            $('.conten_modulo').hide();
            var datos = "id=" + id,
                    urll = "usuarios_sistema/permisos",
                    respuesta = get_object(urll, datos);
            if (respuesta.resp == 'ok') {
                $('#div_permisos input:checkbox').attr("checked", false);
                $.each(respuesta.permisos_usu, function (idmodulo, permisos) {
                    $('#modulo_' + idmodulo).show();
                    $.each(permisos.permisos, function (index, idpermiso) {
                        $('#prm_' + idmodulo + '_' + idpermiso).attr("checked", true);
                    });
                });
                $('#permisos_modal').modal('show');
            } else {
                mensaje_center('Consultar permisos de usuario', 'Error', 'Error al consultar permisos', 'error');
            }
        }

<?php } if (isset($permisos_modulo) && in_array('det', $permisos_modulo)) { ?>
        function detalles(id) {
            var datos = "id=" + id,
                    urll = "usuarios_sistema/detalles",
                    respuesta = get_object(urll, datos);
            $('.data_det').html('');
            if (respuesta.resp == 'ok') {
                $.each(respuesta.detalles, function (index, value) {
                    $('.' + index).html(value);
                });
                var foto_html = '';
                if (respuesta.detalles.foto != '') {
                    foto_html = '<img class="foto" src="./images/perfil_usuarios_sistema/">';
                } else {
                    foto_html = '<i class="fa fa-user"></i>';
                }
                $('.foto_div').html(foto_html);
                $('#detalles_modal').modal('show');
            } else {
                mensaje_center('Consultar permisos de usuario', 'Error', 'Error al consultar permisos', 'error');
            }
        }
<?php } if (isset($permisos_modulo) && in_array('upd', $permisos_modulo)) { ?>
        function modifica(id) {
            redirect_to('usuarios_sistema/update/' + id);
        }
<?php } if (isset($permisos_modulo) && in_array('del', $permisos_modulo)) { ?>
        function elimina(id) {
            BootstrapDialog.show({
                title: 'Borrar usuario',
                message: 'Se borrará el usuario seleccionado.<br>* Las actividades desarrolladas por este usuario serán eliminadas. Si no esta seguro de la acción cancele la petición.<br> ¿Deseas continuar?',
                buttons: [{
                        cssClass: 'btn-primary',
                        label: 'Si, Borrar usuario',
                        action: function (dialog) {
                            try {
                                var datos = "id=" + id,
                                        urll = "usuarios_sistema/elimina",
                                        respuesta = get_object(urll, datos);
                                if (respuesta.resp == 'ok') {
                                    dt_usuariossistema.fnDraw();//recargar los datos del datatable
                                    notify_block('Eliminar usuario', 'El usuario de eliminó satisfactoriamente', '', 'success');
                                } else {
                                    mensaje_center('Eliminar usuario', 'Error', 'Error al eliminar el usuario. Intente más tarde.', 'error');
                                }
                            } catch (e) {
                                dt_usuariossistema.fnDraw();
                                mensaje_center('Eliminar usuario', 'Error', 'Error al eliminar el usuario. Intente más tarde.', 'error');
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
        }
<?php } ?>
    $(document).ready(function () {
        dt_usuariossistema = $('#dtusuariossistema').dataTable({
            "oLanguage": {
                "sProcessing": "<div class=\"ui-widget-header boxshadowround\"><strong>Procesando...</strong></div>",
                "sLengthMenu": "Mostrar _MENU_ usuarios",
                "sZeroRecords": "No se encontraron resultados",
                "sInfo": "Mostrando desde _START_ hasta _END_ de _TOTAL_ usuarios",
                "sInfoEmpty": "Mostrando desde 0 hasta 0 de 0 usuarios",
                "sInfoFiltered": "(filtrado de _MAX_ usuarios)",
                "sInfoPostFix": "",
                "sSearch": "Buscar: ",
                "sUrl": "",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sPrevious": "Anterior",
                    "sNext": "Siguiente",
                    "sLast": "Último"
                }
            },
            "aoColumns": [
                {"bVisible": false},
                null,
                {"bSortable": true, "bVisible": true},
                null,
                null,
                null,
                null,
                null,
                {"bSortable": false, "bVisible": true}
            ],
            "aaSorting": [[1, 'asc']],
            "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
            /* "sPaginationType": "full_numbers",*/
            "bProcessing": true,
            "bServerSide": true,
            "sServerMethod": "POST",
            "sAjaxSource": "index.php/usuarios_sistema/datos",
            "fnServerData": function (sUrl, aoData, fnCallback) {
                $.ajax({
                    "type": 'POST',
                    "dataType": 'json',
                    "url": sUrl,
                    "data": aoData,
                    "success": fnCallback,
                    "cache": false
                });
            },
            "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $('td:eq(0)', nRow).attr('data-title', 'Login');
                $('td:eq(1)', nRow).attr('data-title', 'Nombre');
                $('td:eq(2)', nRow).attr('data-title', 'Rol');
                $('td:eq(3)', nRow).attr('data-title', 'Correo');
                $('td:eq(4)', nRow).attr('data-title', 'Teléfono');
                $('td:eq(5)', nRow).attr('data-title', 'Celular');
                $('td:eq(6)', nRow).attr('data-title', 'Estatus');
                return nRow;
            }
        });

        //Asigna accion al boton para actualizar datatables
        $("#btn_actualiza").click(function () {
            dt_usuariossistema.fnDraw();
        });

        $("#btn_agregar").click(function () {
            redirect_to('usuarios_sistema/update');
        });
    });//fin marco jquery
</script>