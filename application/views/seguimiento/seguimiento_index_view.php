<style>
    div.containerForm {
        border-style: groove;
        border-width: 2px;
    }
</style>

<link rel="stylesheet" href="./js/DataTables/css/dataTables.bootstrap.min.css" type="text/css" media="screen, projection">
<link rel="stylesheet" href="./css/responsive-table.css" type="text/css" media="screen, projection">
<script type="text/javascript" language="javascript" src="./js/DataTables/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="./js/DataTables/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="./js/modal_bootstrap_extend/js/bootstrap-dialog.js"></script>

<div class="col-md-12" >
    <div class="row">
        <div class="col-md-12">
            <div class=" button_bar row" >
                <div class="col-md-2 col-sm-6 col-xs-12"><button id="btn_actualiza" class="btn btn-primary form-control"><i class="fa fa-undo"></i>&nbsp;Actualizar</button></div>
                <?php if (isset($permisos_modulo) && in_array('add', $permisos_modulo)) { ?><div class="col-md-2 col-sm-6 col-xs-12"><button id="btn_agregar"  class="btn btn-primary form-control" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i>&nbsp;Agregar</button></div><?php } ?>
            </div>
            <div class="row">
                <div id="conten_table_data" class="responsive-table col-md-12">
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-responsive" id="dtdatos">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Rama</th>
                                <th>Responsable Elaboración</th>
                                <th>Fecha Entrega Elaboración</th>
                                <th>Responsable Captura</th>
                                <th>Fecha Entrega Captura</th>
                                <th>Rama en Revisión</th>
                                <th># Reactivos a capturar</th>
                                <th>Reactivos capturados</th>
                                <th>Resp id</th>
                                <th></th>
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
<?php if (isset($permisos_modulo) && in_array('upd', $permisos_modulo)) { ?>
        function modifica(id) {
            redirect_to('seguimiento/update/' + id);
        }
<?php } if (isset($permisos_modulo) && in_array('del', $permisos_modulo)) { ?>
        function elimina(id) {
            BootstrapDialog.show({
                title: 'Eliminar registro',
                message: 'Se borrará el registro seleccionado.',
                buttons: [{
                        cssClass: 'btn-primary',
                        label: 'Si, Eliminar',
                        action: function (dialog) {
                            try {
                                var datos = "id=" + id,
                                        urll = "seguimiento/elimina",
                                        respuesta = get_object(urll, datos);
                                if (respuesta.resp == 'ok') {
                                    dt_datos.fnDraw();//recargar los datos del datatable
                                    notify_block('Eliminar registro', 'El registro se eliminó satisfactoriamente', '', 'success');
                                } else {
                                    mensaje_center('Eliminar registro', 'Error', 'Error al eliminar el registro. Intente más tarde.', 'error');
                                }
                            } catch (e) {
                                mensaje_center('Eliminar rol', 'Error', 'Error al eliminar el registro. Intente más tarde.', 'error');
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
        dt_datos = $('#dtdatos').dataTable({
            "oLanguage": {
                "sProcessing": "<div class=\"ui-widget-header boxshadowround\"><strong>Procesando...</strong></div>",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron registros",
                "sInfo": "Mostrando desde _START_ hasta _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando desde 0 hasta 0 de 0 registros",
                "sInfoFiltered": "(filtrado de _MAX_ registros)",
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
                null,
                null,
                null,
                null,
                null,
                {"bVisible": false},
                {"bSortable": false}
            ],
            "aaSorting": [[1, 'asc']],
            "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
            "sPaginationType": "full_numbers",
            "bProcessing": true,
            "bServerSide": true,
            "sServerMethod": "POST",
            "sAjaxSource": "index.php/seguimiento/datos",
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
                $('td:eq(0)', nRow).attr('data-title', 'Rama');
                $('td:eq(1)', nRow).attr('data-title', 'Responsable elaboración');
                $('td:eq(2)', nRow).attr('data-title', 'Fecha entrega elaboración');
                $('td:eq(3)', nRow).attr('data-title', 'Responsable captura');
                $('td:eq(4)', nRow).attr('data-title', 'Fecha entrega captura');
                $('td:eq(5)', nRow).attr('data-title', 'Rama en revisión');
                $('td:eq(6)', nRow).attr('data-title', 'Reactivos a capturar');
                $('td:eq(7)', nRow).attr('data-title', 'Reactivos capturados');
                $('td:eq(8)', nRow).attr('data-title', 'Opciones');
                console.log(aData[5]);
                console.log(fechaActual());
                if (aData[8] < aData[7] && (esFechaMayor(fechaActual(),aData[5])))
                {
                    $('td', nRow).css('background-color', '#f2dede');
                }

                return nRow;
            }
        });

        //Asigna accion al boton para actualizar datatables
        $("#btn_actualiza").click(function () {
            dt_datos.fnDraw();
        });
        $("#btn_agregar").click(function () {
            redirect_to('seguimiento/update');
        });

    });//fin marco jquery

    function esFechaMayor(fechaUno, fechaDos) {
        var arrayFechaUno = fechaUno.split("-");
        var arrayFechaDos = fechaDos.split("-");
        var mesFechaUno = arrayFechaUno[1];
        var diaFechaUno = arrayFechaUno[2];
        var anioFechaUno = arrayFechaUno[0];
        var mesFechaDos = arrayFechaDos[1];
        var diaFechaDos = arrayFechaDos[2];
        var anioFechaDos = arrayFechaUno[0];
        if (anioFechaUno > anioFechaDos) {
            return true;
        } else {
            if (anioFechaUno === anioFechaDos) {
                if (mesFechaUno > mesFechaDos) {
                    return true;
                } else {
                    if (mesFechaUno === mesFechaDos) {
                        if (diaFechaUno > diaFechaDos)
                            return true;
                        else
                            return false;
                    } else
                        return false;
                }
            } else
                return false;
        }
    }

    function fechaActual() {
        var hoy = new Date();
        var dd = hoy.getDate();
        var mm = hoy.getMonth() + 1;
        var yyyy = hoy.getFullYear();

        dd = addZero(dd);
        mm = addZero(mm);

        return yyyy + '-' + mm + '-' + dd;
    }

    function addZero(i) {
        if (i < 10) {
            i = '0' + i;
        }
        return i;
    }
</script>