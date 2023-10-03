<script type="text/javascript" language="javascript" src="./js/DataTables/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="./js/DataTables/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="./js/DataTables/css/dataTables.bootstrap.min.css" type="text/css" media="screen, projection">
<link rel="stylesheet" href="./css/responsive-table.css" type="text/css" media="screen, projection">
<script type="text/javascript" src="./js/modal_bootstrap_extend/js/bootstrap-dialog.js"></script>
<style>
    .button_bar .btn {margin-left: 12px; }
    .bg_adm {width: 70%;display: block;font-size: 85%;padding-top: 4px; float: left; margin-right: 2px; padding: 6px 1px !important;}
    #dinamic ul.dropdown-menu {min-width: 177px;font-size: 10px;padding: 4px;min-height: 173px;overflow-x: auto;}
    .dropdown-menu .form-control{overflow: auto;font-size: 10px;height: auto;}
    
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
                    <button id="btn_agregar"  class="btn btn-primary col-md-2 col-sm-6 col-xs-12"><i class="fa fa-plus"></i>&nbsp;Agregar</button>
                </div>
            </div><br><br><br>
            <div class="row">
                <div id="dinamic" class="responsive-table">
                    <table cellpadding="0" cellspacing="0" border="0" class="display col-md-12" id="dtdatos">
                        <thead>
                            <tr>
                                <th width="40px">id</th>
                                <th width="100">Clave</th>
                                <th>Contenido</th>
                                <th>Plan</th>
                                <th width="100">Fecha de alta</th>
                                <th width="100">Fecha modificación</th>
                                <th width="150">Estado</th>
                                <th width="100"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($reactivos)) {
                                foreach ($reactivos as $r) {
                                    ?>
                                    <tr>
                                        <td><?php echo $r['id']; ?></td>
                                        <td><?php echo $r['clave']; ?></td>
                                        <td><?php echo strip_tags($r['contenido']); ?></td>
                                        <td><?php echo $r['plan']; ?></td>
                                        <td><?php echo $r['fechaalta']; ?></td>
                                        <td><?php echo $r['fechamodifica']; ?></td>
                                        <td>
                                            <?php
                                            $estado = $r['estado'];
                                            if ($estado == 'C') {
                                                $class = 'danger';
                                                $edo = 'En captura';
                                            } else if ($estado == 'R') {
                                                $class = 'warning';
                                                $edo = 'En revisión';
                                            } else if ($estado == 'A') {
                                                $class = 'success';
                                                $edo = 'Revisado';
                                            }
                                            ?>
                                            <div class="bg_adm label label-<?php echo $class; ?>"><?php echo $edo; ?></div>
                                            <?php
                                            $comentario = $r['comentario'];
                                            if ($comentario != '') {
                                                ?>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa fa-comment"></i>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <div><b>Fecha:</b> <?php echo $r['fechavalido']; ?></div>
                                                        <br><b>Comentario:</b><br>
                                                        <div class="form-control"> <?php echo $comentario; ?></div>
                                                    </ul>
                                                </div>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-warning" onclick="modifica(<?php echo $r['id']; ?>)"><i class="fa fa-edit"></i></button>
                                            <button class="btn btn-danger" onclick="elimina(<?php echo $r['id']; ?>)"><i class="fa fa-remove"></i></button>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
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

    function modifica(id) {
        redirect_to('reactivo/update/' + id);
    }

    function elimina(id) {
        BootstrapDialog.show({
            title: 'Borrar caso',
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
            "aaSorting": [[1, 'asc']],
            "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
            "sPaginationType": "full_numbers"
        });

        $("#btn_agregar").click(function () {
            redirect_to('reactivo/update');
        });

    });//fin marco jquery
</script>