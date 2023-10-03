<script type="text/javascript" language="javascript" src="./js/datatables/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="./js/datatables/css/table_jui.css" type="text/css" media="screen, projection">
<link rel="stylesheet" href="./css/responsive-table.css" type="text/css" media="screen, projection">
<script type="text/javascript" src="./js/modal_bootstrap_extend/js/bootstrap-dialog.js"></script>
<style>
    .button_bar .btn {margin-left: 12px; }
</style>
<div class="span-24" >
    <div class="row" style="margin: 20px;">
        <div class="col-md-12">
            <div class=" button_bar col-md-12" >
                <div class="button_bar_content">
                    <button id="btn_actualiza" class="btn btn-primary col-md-2 col-sm-6 col-xs-12"><i class="fa fa-undo"></i>&nbsp;Actualizar</button>
                    <button id="btn_agregar"  class="btn btn-primary col-md-2 col-sm-6 col-xs-12"><i class="fa fa-plus"></i>&nbsp;Agregar</button>
                    <button id="btn_modificar"  class="btn btn-primary col-md-2 col-sm-6 col-xs-12"><i class="fa fa-pencil"></i>&nbsp;Modificar</button>
                    <button id="btn_eliminar"  class="btn btn-danger col-md-2 col-sm-6 col-xs-12"><i class="fa fa-minus"></i>&nbsp;Eliminar</button>
                </div>
            </div><br><br><br>
            <div class="row">
                <div id="dinamic" class="responsive-table">
                    <table cellpadding="0" cellspacing="0" border="0" class="display col-md-12" id="dtusuariossistema">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th width="60">Login</th>
                                <th>Password</th>
                                <th>Rol</th>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Estatus</th>
                                <th>Fecha</th>
                                <th>Región</th>
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
    </div><br>
</div><br>
<script type="text/javascript" charset="utf-8">
    var dt_usuariossistema, data_row_select;
    var row_select = 0, row_select_catedra = 0;

    /* obtener la fila seleccionada */
    function fnGetSelected(oTableLocal) {
        return $('tr.row_selected');
    }

    function elimina_usuario_sis(id) {
        BootstrapDialog.show({
            title: 'Borrar usuario',
            message: 'Se borrará el usuario seleccionado.<br>* Los reactivos creados por este usuario tambien serán borrados<br> ¿Deseas continuar?',
            buttons: [{
                    cssClass: 'btn-primary',
                    label: 'Si, Borrar usuario',
                    action: function(dialog) {
                        var datos = "id=" + id,
                                urll = "usuarios_sistema/eliminaUsuarioSistema",
                                respuesta = get_object(urll, datos);
                        if (respuesta.resp == 'ok') {
                            dt_usuariossistema.fnDraw();//recargar los datos del datatable
                            notify_block('Eliminar usuario', 'El usuario de eliminó satisfactoriamente', '', 'success');
                        } else {
                            mensaje_center('Eliminar usuario', 'Error', 'Error al eliminar el usuario. Intente más tarde.', 'error');
                        }
                        dialog.close();
                    }
                }, {
                    label: 'No, Cancelar',
                    action: function(dialog) {
                        dialog.close();
                    }
                }]
        });
    }

    function modifica_usuario_sis(id) {
        redirect_to('usuarios_sistema/updateUsuarioSistema/' + id);
    }

    $(document).ready(function() {
        dt_usuariossistema = $('#dtusuariossistema').dataTable({
            "bJQueryUI": true,
            "oLanguage": {
                "sProcessing": "<div class=\"ui-widget-header boxshadowround\"><strong>Procesando...</strong></div>",
                "sLengthMenu": "Mostrar _MENU_ usuarios",
                "sZeroRecords": "No se encontraron resultados",
                "sInfo": "Mostrando desde _START_ hasta _END_ de _TOTAL_ usuarios",
                "sInfoEmpty": "Mostrando desde 0 hasta 0 de 0 usuarios",
                "sInfoFiltered": "(filtrado de _MAX_ usuarios)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sPrevious": "Anterior",
                    "sNext": "Siguiente",
                    "sLast": "Último"
                }
            },
            "aoColumns": [
                /*0 id*/{"bVisible": false},
                /*0 login*/null,
                /*1 pass*/{"bSortable": false, "bVisible": false},
                /*2 Nombre completo*/null,
                /*3 rol*/null,
                /*4 correo*/null,
                /*4 area*/null,
                /*4 carrera*/null,
                /*5 permisos*/{"bSortable": false, "bVisible": false}],
            "aaSorting": [[1, 'asc']],
            "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
            "sPaginationType": "full_numbers",
            "bProcessing": true,
            "bServerSide": true,
            "sServerMethod": "POST",
            "sAjaxSource": "index.php/usuarios_sistema/datosUsuariosSistema",
            "fnServerData": function(sUrl, aoData, fnCallback) {
                $.ajax({
                    "type": 'POST',
                    "dataType": 'json',
                    "url": sUrl,
                    "data": aoData,
                    "success": fnCallback,
                    "cache": false
                });
            }
        });

        /* selecciona una fila del datatable no aplica para server_aside proccessing*/
        $('#dtusuariossistema tbody tr').live('click', function(e) {
            if ($(this).hasClass('row_selected')) {
                $(this).removeClass('row_selected');
                row_select = 0;
                $("#act_select").text('No se ha seleccionado un usuario');
            } else {
                $('tr.row_selected').removeClass('row_selected');
                $(this).addClass('row_selected');
                var anSelected = fnGetSelected(dt_usuariossistema);
                var datos = dt_usuariossistema.fnGetData(anSelected[0]);
                data_row_select = datos;
                row_select = datos[0];
            }
        });

        //Asigna accion al boton para actualizar datatables
        $("#btn_actualiza").click(function() {
            dt_usuariossistema.fnDraw();
        });

        $("#btn_volver").click(function() {
            redirect_to('gestion');
        });

        $("#btn_agregar").click(function() {
            redirect_to('usuarios_sistema/updateUsuarioSistema');
        });

        $('#btn_modificar').click(function() {
            //se intenta obtener valores de la fila seleccionada en el datatables almacenados en la variable global row_select
            if (row_select != 0) {
                modifica_usuario_sis(row_select);
            } else {
                mensaje_center('Selecciona un usuario', 'No se ha seleccionado ning&uacute;n usuario', '<b>Selecciona un usuario a modificar.</b>', 'info');
            }
        });

        $('#btnvolver').click(function() {
            redirect_to('gestion');
        });

        $('#btn_eliminar').click(function() {
            //se intenta obtener valores de la fila seleccionada en el datatables almacenados en la variable global row_select
            if (row_select != 0) {
                elimina_usuario_sis(row_select);
            } else {
                mensaje_center('Selecciona un usuario', 'No se ha seleccionado ning&uacute;n usuario', '<b>Selecciona un usuario a eliminar.</b>', 'info');
            }
        });

    });//fin marco jquery
</script>