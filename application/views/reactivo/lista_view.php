<script type="text/javascript" language="javascript" src="./js/DataTables/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="./js/DataTables/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="./js/DataTables/css/dataTables.bootstrap.min.css" type="text/css" media="screen, projection">
<link rel="stylesheet" href="./css/responsive-table.css" type="text/css" media="screen, projection">
<script type="text/javascript" src="./js/modal_bootstrap_extend/js/bootstrap-dialog.js"></script>
<style>
    .button_bar .btn {margin-left: 12px; }
    .ddw_it{ color:white !important;margin-bottom: 2px; cursor: pointer}
    .btn-danger.ddw_it{background-color: #D78885;border-color: #d43f3a;}
    .btn-warning.ddw_it{background-color: #BEA786;border-color: #A07D4D;}
    .btn-success.ddw_it{background-color: #93A89A;border-color: #3E684B;}
    .btn-danger.ddw_it:hover{background-color: #d9534f;border-color: #d43f3a;}
    .btn-warning.ddw_it:hover{background-color: #BE9356;border-color: #A07D4D;}
    .btn-success.ddw_it:hover{background-color: #4F8360;border-color: #3E684B;}

    .dataTable button {
        font-size: 16px !important;
        padding: 2px;
        padding-left: 8px;
        padding-right: 8px;
        margin-right: 3px;
    }
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
<div class="row" >
    <div class="col-md-12" >
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Búsqueda Avanzada
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">
                        <div class="form-control">
                            <label for="estado">Estado:</label>
                            <select id="estado">
                                <option value="">Todos</option>
                                <option value="C">Captura</option>
                                <option value="P">Pendiente</option>
                                <option value="R">Revisado</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row" >
    <div class="col-md-12" >
        <button class="btn btn-success" onclick="redirect_to('reactivo/update')"><i class="fa fa-plus"></i> Agregar Reactivo</button>
    </div>
</div>
<div class="span-24" >
    <div class="row" style="margin: 20px;">
        <div class="col-md-12">
            <div class="row">
                <div id="dinamic" class="responsive-table">
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-responsive" id="dtdatos">
                        <thead>
                            <tr>
                                <th width="40px">id</th>
                                <th width="80">clave</th>
                                <th width="200px">Contenido</th>
                                <th>Edo</th>
                                <th width="200px">plan</th>
                                <th>autor</th>
                                <th>caso</th>
                                <th>fechaalta</th>
                                <th width="100"></th>
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
                                <th>clave</th>
                                <th>Contenido</th>
                                <th>Edo</th>
                                <th>plan</th>
                                <th>autor</th>
                                <th>caso</th>
                                <th>fechaalta</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div><br>
</div><br>
<script type="text/javascript" charset="utf-8">
    function deleteRea(id) {
        BootstrapDialog.show({
            title: 'Eliminar reactivo',
            message: 'Se borrará el reactivo seleccionado.<br> ¿Deseas continuar?',
            buttons: [{
                    cssClass: 'btn-primary',
                    label: 'Si, Borrar reactivo',
                    action: function (dialog) {
                        var datos = "id=" + id,
                                urll = "reactivo/delete",
                                respuesta = get_object(urll, datos);
                        if (respuesta.res == 'ok') {
                            dt_data.fnDraw();//recargar los datos del datatable
                            notify_block('Eliminar reactivo', 'El reactivo de eliminó satisfactoriamente', '', 'success');
                        } else {
                            mensaje_center('Eliminar reactivo', 'Error', 'Error al eliminar el reactivo. Intente más tarde.', 'error');
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
    var dt_data, data_row_select;
    var row_select = 0, row_select_catedra = 0;

    function fnFilterColumnValue(i, val) {
        try {
            dt_data.columns(i).search(val).draw();
        } catch (e) {
            alert(e);
        }
    }
    /* obtener la fila seleccionada */
    function fnGetSelected(oTableLocal) {
        return $('tr.row_selected');
    }

    function set_st(id, st) {
        var classe = '', edo_desc = '';
        if (st == 'C') {
            edo_desc = 'En captura';
            classe = 'danger';
        } else if (st == 'R') {
            edo_desc = 'En revisión';
            classe = 'warning';
        } else if (st == 'A') {
            edo_desc = 'Revisado';
            classe = 'success';
        }

        BootstrapDialog.show({
            title: 'Cambiar estado de reactivo',
            message: 'Se cambiará el estado del reactivo seleccionado a  <span class="label label-' + classe + '">' + edo_desc + '</span> .<br>* Agregue un comentario de ser pertinente:<br> <textarea id="comment" class="form-control"></textarea> <br> ¿Deseas continuar?',
            buttons: [{
                    cssClass: 'btn-primary',
                    label: 'Si, cambiar estado a ' + edo_desc,
                    action: function (dialog) {
                        var datos = "id=" + id + '&st=' + st + '&com=' + $('#comment').val(),
                                urll = "revizar_reactivo/cambiaEstadoReactivo",
                                respuesta = get_object(urll, datos);
                        if (respuesta.resp == 'ok') {
                            dt_data.fnDraw();//recargar los datos del datatable
                            notify_block('Cambiar estado de reactivo', respuesta.msg, '', 'success');

                        } else {
                            mensaje_center('Cambiar estado de reactivo', 'Error', respuesta.msg, 'error');
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

    $(document).ready(function () {
        dt_data = $('#dtdatos').dataTable({
            "bJQueryUI": true,
            "oLanguage": {
                "sProcessing": "<div class=\"ui-widget-header boxshadowround\"><strong>Procesando...</strong></div>",
                "sLengthMenu": "Mostrar _MENU_ reactivos",
                "sZeroRecords": "No se encontraron resultados",
                "sInfo": "Mostrando desde _START_ hasta _END_ de _TOTAL_ reactivos",
                "sInfoEmpty": "Mostrando desde 0 hasta 0 de 0 reactivos",
                "sInfoFiltered": "(filtrado de _MAX_ reactivos)",
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
                {"bSortable": true, "bVisible": false},
                null,
                null,
                {"bSortable": false, "bVisible": true},
                null,
                null,
                null,
                null,
               {"bSortable": false, "bVisible": true}
            ],
            "aaSorting": [[0, 'asc']],
            "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
            "sPaginationType": "full_numbers",
            "bProcessing": true,
            "bServerSide": true,
            "sServerMethod": "POST",
            "sAjaxSource": "reactivo/lista",
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
    });//fin marco jquery

</script>