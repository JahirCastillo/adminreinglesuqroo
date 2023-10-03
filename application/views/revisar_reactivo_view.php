
<script type="text/javascript" language="javascript" src="./js/datatables/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="./js/datatables/css/table_jui.css" type="text/css" media="screen, projection">
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
</style>
<div class="span-24" >
    <div class="row" style="margin: 20px;">
        <div class="col-md-12">
            <div class="row">
                <div id="dinamic" class="responsive-table">
                    <table cellpadding="0" cellspacing="0" border="0" class="display col-md-12" id="dtdatos">
                        <thead>
                            <tr>
                                <th width="40px">id</th>
                                <th width="80">clave</th>
                                <th>Contenido</th>
                                <th>Estado</th>
                                <th>plan</th>
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

    function set_st(id, st) {
        var classe='',edo_desc='';
        if(st=='C'){
            edo_desc='En captura';
            classe='danger';
        }else if(st=='R'){
            edo_desc='En revisión';
            classe='warning';
        }else if(st=='A'){
            edo_desc='Revisado';
            classe='success';
        }
        
        BootstrapDialog.show({
            title: 'Cambiar estado de reactivo',
            message: 'Se cambiará el estado del reactivo seleccionado a  <span class="label label-'+classe+'">'+edo_desc+'</span> .<br>* Agregue un comentario de ser pertinente:<br> <textarea id="comment" class="form-control"></textarea> <br> ¿Deseas continuar?',
            buttons: [{
                    cssClass: 'btn-primary',
                    label: 'Si, cambiar estado a '+edo_desc,
                    action: function (dialog) {
                        var datos = "id=" + id+'&st='+st+'&com='+$('#comment').val(),
                                urll = "revizar_reactivo/cambiaEstadoReactivo",
                                respuesta = get_object(urll, datos);
                        if (respuesta.resp == 'ok') {
                            dt_data.fnDraw();//recargar los datos del datatable
                            notify_block('Cambiar estado de reactivo',respuesta.msg, '', 'success');
                            
                        } else {
                            mensaje_center('Cambiar estado de reactivo', 'Error',respuesta.msg, 'error');
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
                {"bSortable": false, "bVisible": false},
                null,
                null,
                {"bSortable": false, "bVisible": false},
                null,
                null,
                null,
                null,
                null
            ],
            "aaSorting": [[1, 'asc']],
            "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
            "sPaginationType": "full_numbers",
            "bProcessing": true,
            "bServerSide": true,
            "sServerMethod": "POST",
            "sAjaxSource": "index.php/revisar_reactivo/datos",
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

        /* selecciona una fila del datatable no aplica para server_aside proccessing*/
        $('#dtdatos tbody tr').live('click', function (e) {
            if ($(this).hasClass('row_selected')) {
                $(this).removeClass('row_selected');
                row_select = 0;
                $("#act_select").text('No se ha seleccionado un caso');
            } else {
                $('tr.row_selected').removeClass('row_selected');
                $(this).addClass('row_selected');
                var anSelected = fnGetSelected(dt_data);
                var datos = dt_data.fnGetData(anSelected[0]);
                data_row_select = datos;
                row_select = datos[0];
            }
        });

    });//fin marco jquery

</script>