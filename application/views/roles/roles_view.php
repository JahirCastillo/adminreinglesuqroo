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
            </div>
        </div>
    </div>
<?php } ?>
<div class="col-md-12" >
    <div class="row">
        <div class="col-md-12">
            <div class=" button_bar row" >
                <div class="col-md-2 col-sm-6 col-xs-12"><button id="btn_actualiza" class="btn btn-primary form-control"><i class="fa fa-undo"></i>&nbsp;Actualizar</button></div>
                <?php if (isset($permisos_modulo) && in_array('add', $permisos_modulo)) { ?><div class="col-md-2 col-sm-6 col-xs-12"><button id="btn_agregar"  class="btn btn-primary form-control"><i class="fa fa-plus"></i>&nbsp;Agregar</button></div><?php } ?>
            </div>
            <div class="row">
                <div id="conten_table_data" class="responsive-table col-md-12">
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-responsive" id="dtdatos">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Clave</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5" class="dataTables_empty">Cargando...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" charset="utf-8">
    var dt_datos;

<?php if (isset($permisos_modulo) && in_array('per', $permisos_modulo)) { ?>
        function ver_permisos(id) {
            $('.conten_modulo').hide();
            var datos = "id=" + id,
                    urll = "roles/permisos",
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
                mensaje_center('Consultar permisos de rol', 'Error', 'Error al consultar permisos', 'error');
            }
        }
    <?php
}
if (isset($permisos_modulo) && in_array('upd', $permisos_modulo)) {
    ?>
        function modifica(id) {
            redirect_to('roles/update/' + id);
        }
<?php } if (isset($permisos_modulo) && in_array('del', $permisos_modulo)) { ?>
        function elimina(id) {
            BootstrapDialog.show({
                title: 'Borrar rol',
                message: 'Se borrará el rol seleccionado.<br> Los usuarios que tengan este rol, quedaran sin privilegios otorgados por dicho rol. <br>¿Deseas continuar?',
                buttons: [{
                        cssClass: 'btn-primary',
                        label: 'Si, Borrar rol',
                        action: function (dialog) {
                            try {
                                var datos = "id=" + id,
                                        urll = "roles/elimina",
                                        respuesta = get_object(urll, datos);
                                if (respuesta.resp == 'ok') {
                                    dt_datos.fnDraw();//recargar los datos del datatable
                                    notify_block('Eliminar rol', 'El rol de eliminó satisfactoriamente', '', 'success');
                                } else {
                                    mensaje_center('Eliminar rol', 'Error', 'Error al eliminar el rol. Intente más tarde.', 'error');
                                }
                            } catch (e) {
                                mensaje_center('Eliminar rol', 'Error', 'Error al eliminar el rol. Intente más tarde.', 'error');
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
<?php
if (isset($clave_modulo)) {
    echo '$("#menu_lt_' . $clave_modulo . '").addClass("active");';
}
?>
        dt_datos = $('#dtdatos').dataTable({
            "oLanguage": {
                "sProcessing": "<div class=\"ui-widget-header boxshadowround\"><strong>Procesando...</strong></div>",
                "sLengthMenu": "Mostrar _MENU_ roles",
                "sZeroRecords": "No se encontraron resultados",
                "sInfo": "Mostrando desde _START_ hasta _END_ de _TOTAL_ roles",
                "sInfoEmpty": "Mostrando desde 0 hasta 0 de 0 roles",
                "sInfoFiltered": "(filtrado de _MAX_ roles)",
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
                null,
                null,
                {"bSortable": false}
            ],
            "aaSorting": [[1, 'asc']],
            "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
            "sPaginationType": "full_numbers",
            "bProcessing": true,
            "bServerSide": true,
            "sServerMethod": "POST",
            "sAjaxSource": "index.php/roles/datos",
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
                $('td:eq(0)', nRow).attr('data-title', 'ID');
                $('td:eq(1)', nRow).attr('data-title', 'Clave');
                $('td:eq(2)', nRow).attr('data-title', 'Nombre');
                $('td:eq(3)', nRow).attr('data-title', 'Descripción');
                return nRow;
            }
        });

        //Asigna accion al boton para actualizar datatables
        $("#btn_actualiza").click(function () {
            dt_datos.fnDraw();
        });

        $("#btn_agregar").click(function () {
            redirect_to('roles/update');
        });
    });//fin marco jquery
</script>