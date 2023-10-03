
<script type="text/javascript" language="javascript" src="./js/DataTables/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="./js/DataTables/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="./js/modal_bootstrap_extend/js/bootstrap-dialog.js"></script>
<link rel="stylesheet" href="./js/DataTables/css/dataTables.bootstrap.min.css" type="text/css" media="screen, projection">
<link rel="stylesheet" href="./css/responsive-table.css" type="text/css" media="screen, projection">
<style>
    .button_bar .btn {margin-left: 12px; }
    div.dataTables_wrapper div.dataTables_length select {
        color: #4e4e4e;
        border: 1px solid #fafafa;
        border-radius: 4px;
    }
    div.dataTables_wrapper div.dataTables_filter input {
        color: #4e4e4e;
        border: 1px solid #fafafa;
        border-radius: 4px;
        padding: 0px 4px;
    }
</style>
<div class="span-24" >
    <div class="row" style="margin: 20px;">
        <div class="col-md-12">
            <div class=" button_bar col-md-12" >
                <div class="button_bar_content">
                    <button id="btn_actualiza" class="btn btn-primary col-md-2 col-sm-6 col-xs-12"><i class="fa fa-undo"></i>&nbsp;Actualizar</button>
                    <button id="btn_agregar"  class="btn btn-primary col-md-2 col-sm-6 col-xs-12"><i class="fa fa-plus"></i>&nbsp;Agregar</button>
                </div>
            </div><br><br><br>
            <div class="row">
                <div id="dinamic" class="responsive-table">
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered display" id="dtdatos">
                        <thead>
                            <tr>
                                <th width="40px">id</th>
                                <th width="15%">Título</th>
                                <th>Instrucción</th>
                                <th>Contenido</th>
                                <th>imagen</th>
                                <th>Audio</th>
                                <th>Video</th>
                                <th>Usuario agrego</th>
                                <th>Fecha Alta</th>
                                <th width="100"></th>
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
    var dt_data, data_row_select;
    var row_select = 0, row_select_catedra = 0;

    /* obtener la fila seleccionada */
    function fnGetSelected(oTableLocal) {
        return $('tr.row_selected');
    }

    function elimina(id) {
        BootstrapDialog.show({
            title: 'Borrar caso',
            message: 'Se borrará el caso seleccionado.<br>* Los reactivos los asociados al caso se actualizarán quedando sin caso<br> ¿Deseas continuar?',
            buttons: [{
                    cssClass: 'btn-primary',
                    label: 'Si, Borrar Caso',
                    action: function (dialog) {
                        var datos = "id=" + id,
                                urll = "caso/elimina",
                                respuesta = get_object(urll, datos);
                        if (respuesta.resp == 'ok') {
                            dt_data.fnDraw();//recargar los datos del datatable
                            notify_block('Eliminar caso', 'El caso de eliminó satisfactoriamente', '', 'success');
                        } else {
                            mensaje_center('Eliminar caso', 'Error', 'Error al eliminar el caso. Intente más tarde.', 'error');
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

    function modifica(id) {
        redirect_to('caso/update/' + id);
    }

    $(document).ready(function () {
        dt_data = $('#dtdatos').dataTable({
            "bJQueryUI": true,
            "oLanguage": {
                "sProcessing": "<div class=\"ui-widget-header boxshadowround\"><strong>Procesando...</strong></div>",
                "sLengthMenu": "Mostrar _MENU_ casos",
                "sZeroRecords": "No se encontraron resultados",
                "sInfo": "Mostrando desde _START_ hasta _END_ de _TOTAL_ casos",
                "sInfoEmpty": "Mostrando desde 0 hasta 0 de 0 casos",
                "sInfoFiltered": "(filtrado de _MAX_ casos)",
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
                {"bVisible": true},
                null,
                {"bSortable": false, "bVisible": true},
                null,
                null,
                null,
                null,
                {"bSortable": false, "bVisible": false},
                {"bSortable": false, "bVisible": true},
                {"bSortable": false, "bVisible": true}
            ],
            "aaSorting": [[1, 'asc']],
            "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
            "sPaginationType": "full_numbers",
            "bProcessing": true,
            "bServerSide": true,
            "sServerMethod": "POST",
            "sAjaxSource": "index.php/caso/datos",
            "fnServerData": function (sUrl, aoData, fnCallback) {
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

        //Asigna accion al boton para actualizar datatables
        $("#btn_actualiza").click(function () {
            dt_data.fnDraw();
        });

        $("#btn_volver").click(function () {
            redirect_to('gestion');
        });

        $("#btn_agregar").click(function () {
            redirect_to('caso/update');
        });

        $('#btn_modificar').click(function () {
            //se intenta obtener valores de la fila seleccionada en el datatables almacenados en la variable global row_select
            if (row_select != 0) {
                modifica(row_select);
            } else {
                mensaje_center('Selecciona un caso', 'No se ha seleccionado ning&uacute;n caso', '<b>Selecciona un caso a modificar.</b>', 'info');
            }
        });

        $('#btnvolver').click(function () {
            redirect_to('gestion');
        });

        $('#btn_eliminar').click(function () {
            //se intenta obtener valores de la fila seleccionada en el datatables almacenados en la variable global row_select
            if (row_select != 0) {
                elimina(row_select);
            } else {
                mensaje_center('Selecciona un caso', 'No se ha seleccionado ning&uacute;n caso', '<b>Selecciona un caso a eliminar.</b>', 'info');
            }
        });

    });//fin marco jquery
</script>