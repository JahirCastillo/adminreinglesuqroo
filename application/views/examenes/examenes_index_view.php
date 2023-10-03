<script type="text/javascript" language="javascript" src="./js/DataTables/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="./js/DataTables/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="./js/modal_bootstrap_extend/js/bootstrap-dialog.js"></script>
<script src="./js/tinymce/js/tinymce/jquery.tinymce.min.js" type="text/javascript"></script>
<script src="./js/tinymce/js/tinymce/tinymce.min.js" type="text/javascript"></script>

<script type="text/javascript" language="javascript" src="./js/jquery-validation/jquery.validate.min.js"></script>
<script type="text/javascript" language="javascript" src="./js/jquery-validation/messages_es.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/css/bootstrap-select.min.css">
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

    th {
        padding: 6px 4px;
        background-color: #f3f3f3;
    }
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }

    .switch input { 
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }
    input:checked + .slider {background-color: #21779B;}
    input:focus + .slider {box-shadow: 0 0 1px #2196F3;}
    input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }
    div#containerDataPrefix {
        width: 35%;
        margin: auto;
    }
    #downloadSql{display: none;color: #ffff;}
</style>

<div class="span-24" >
    <div class="row" style="margin: 20px;">
        <div class="col-md-12">
            <div class=" button_bar col-md-12" >
                <div id="container_buttons"class="button_bar_content">
                    <button id="btn_actualiza" class="btn btn-primary col-md-2 col-sm-6 col-xs-12"><i class="fa fa-undo"></i>&nbsp;Actualizar</button>  
                    <?php if (isset($permisos_modulo) && in_array('add', $permisos_modulo)) { ?>
                        <button id="btn_agregar" data-toggle="modal"  class="btn btn-primary col-md-2 col-sm-6 col-xs-12"><i class="fa fa-plus"></i>&nbsp;Agregar</button> 
                    <?php } ?>
                    <?php if (isset($permisos_modulo) && in_array('exp', $permisos_modulo)) { ?>
                        <button id="btn_exportarBloque" class="btn btn-primary col-md-2 col-sm-6 col-xs-12"><i class="fa fa-paper-plane-o"></i>&nbsp;Exportar scripts de exámenes</button> 
                    <?php } ?>
                    <button id="downloadSql" class="btn btn-success btn-link data_prefix" type="button"><i class="fa fa-download"></i><strong>Descargar sql</strong></button>
                    <label id="msg_zip" class="login-box-msg"></label>
                </div>
            </div>
            <br><br>
            <div class="row">
                <div id="dinamic" class="responsive-table">

                </div>
            </div>
        </div>
    </div><br>
</div><br>

<script type="text/javascript" charset="utf-8">
    var value, valueShowTable, modalForm = '', modalFormUpdate = '';
    function showTable() {
        var ruta = '';
        ruta = "index.php/examenes/get_datos";
        //var table = $('#dtdata').DataTable();
        // table.destroy();//Se destruye la tabla vacía y se vuelve a pintar con los nuevos datos
        if ($.fn.DataTable.isDataTable("#dtdata")) {
            $("#dtdata").DataTable().destroy();
            $('#dtdata tbody > tr').remove();
        }

        var tabla = '<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered display" id="dtdata">';
        tabla += '<thead>';
        tabla += '<tr>';
        tabla += '<th width="40px"></th>';
        tabla += '<th></th>';
        tabla += '<th></th>';
        tabla += '<th></th>';
        tabla += '<th></th>';
        tabla += '<th width="10%"></th>';
        tabla += '<th width="10%"></th>';
        tabla += '</tr>';
        tabla += '</thead>';
        tabla += '<tbody>';
        tabla += '</tbody>';
        tabla += '</table>';
        $('#dinamic').html(tabla);
        dt_data = $('#dtdata').dataTable({
            //dt_data.fnFilter("Libro", 1, true, false);
            "bJQueryUI": true,
            "oLanguage": {
                "sProcessing": "<div class=\"ui-widget-header boxshadowround\"><strong>Procesando...</strong></div>",
                "sLengthMenu": "Mostrar _MENU_ examenes",
                "sZeroRecords": "No se encontraron resultados",
                "sInfo": "Mostrando desde _START_ hasta _END_ de _TOTAL_ examenes",
                "sInfoEmpty": "Mostrando desde 0 hasta 0 de 0 casos",
                "sInfoFiltered": "(filtrado de _MAX_ examenes)",
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
            columns: [
                {title: "id", "bVisible": false},
                {title: "Clave del examen", "bVisible": true},
                {title: "Nombre del examen", "bVisible": true},
                {title: "Número de reactivos", "bVisible": true},
                {title: "Fecha de alta del examen", "bVisible": true},
                {title: "", "bVisible": true, "bSortable": false},
                {title: "", "bVisible": true, "bSortable": false}

            ],
            "aaSorting": [[0, 'desc']],
            "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
            "sPaginationType": "full_numbers",
            "bProcessing": true,
            "bServerSide": true,
            "sServerMethod": "POST",
            "sAjaxSource": ruta,
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
    }
    showTable();

    $(document).ready(function () {
        $("#btn_agregar").click(function () {
            var html = '';
            var modalForm = "<form id='form_add_examen' role='form'><div id='formulario_examen'><div class='form-group'><label for='clave'>Clave del examen*: </label><input type='text' value='' class='form-control' name='clave_examen' id='clave_examen' maxlength='20' minlength='4' required></div><div class='form-group'><label for='name'> Nombre del examen*: </label><input type='text' value='' class='form-control' name='name_examen' id='name_examen' maxlength='50' minlength='5' required></div><div class='has-warning'><div class='checkbox'><label><input type='checkbox' id='continue_exam' value='' > ¿Agregar reactivos al examen?</label></div></div></div></form>";
            $('#modal_examen').append('<div id="form_add_examen" style="display: none;">' + $('#modal_examen [role="form"]').html() + '</div>');
            $('#form_add_examen .form-control').val('').attr('disabled', false);
            var dialogo_agrega = new BootstrapDialog({
                title: 'Agregar nuevo examen',
                message: modalForm,
                buttons: [{
                        label: 'Continuar',
                        cssClass: 'btn-primary',
                        autodestroy: true,
                        action: function (dialog) {
                            if ($('#form_add_examen').validate().form()) {
                                var resp = get_object("examenes/crea_examen", {
                                    nombre: $('#form_add_examen #name_examen').val(),
                                    clave: $('#form_add_examen #clave_examen').val()
                                });
                                if (resp.resp == 'ok') {
                                    if ($('#continue_exam').prop('checked')) {
                                        redirectTo('examenes/editar_examen/' + resp.id);
                                    } else {
                                        dt_data.fnDraw();
                                        notify_block('Agregar Examen', 'El examen se agregó exitosamente, podrás añadir reactivos en el momento que desees.', '', 'success');
                                    }
                                } else {
                                    mensaje_center('Error al agregar el examen, ', resp.msg, 'Intente de nuevo.', 'error');
                                }
                                modalForm = '';
                                $('#form_update_examen #name_examen').val("");
                                $('#form_update_examen #clave_examen').val("");
                                dialog.close();
                                // redirect_to('examenes/crea_examen');                            
                            }
                        }
                    },
                    {
                        label: 'Cancelar',
                        cssClass: 'btn-default',
                        action: function (dialog) {
                            //clearForm();
                            dialog.close();
                        }
                    }]
            });
            dialogo_agrega.open();
        });

        var tempPrefix = '';
        $('#btn_exportarBloque').click(function () {
            $(document).ajaxStart(function () {
                $.blockUI({message: '<h1><i class="fa fa-spinner fa-spin"></i></h1>'});
            }).ajaxStop(function () {
                $.unblockUI()
            });
            var mensajeZip = $('#msg_zip');

            var checkeds = $('.examenSeleccionado:checked');
            var elements = [];
            if (checkeds.length > 0) {
                $(checkeds).each(function (index, valor) {
                    var idExamen = $(valor).attr('data-id');
                    if (idExamen != null && idExamen != 0) {
                        var obj = {};
                        obj.idExamen = idExamen;
                        obj.prefijo = $(valor).attr('data-prefijo');
                        elements.push(obj);
                    }
                });
                $('#name_prefix').hide();
                $('#iconGenerateSql').addClass('fa-spin fa-1x fa-fw');
                getObject('examenes/exportBloqueEl', {examenes: elements}, function (resp) {
                    if (resp.res == 'ok') {
                        mensajeZip.html(resp.msg);
                        mensajeZip.addClass(resp.msg_class);
                        mensajeZip.show('slow');
                        $('#msg_zip').empty();
                        $('#msg_zip').hide('slow');
                        $('#downloadSql').show("slow");
                    } else {
                        mensajeZip.html(resp.msg);
                        mensajeZip.addClass(resp.msg_class);
                        $('#msg_zip').empty();
                        $('#msg_zip').hide('slow');
                        $('#downloadSql').hide("slow");
                    }
                });
            }
        });


        $('#downloadSql').click(function () {
            redirectTo('examenes/downloadSql/0/none/1');
            $('#downloadSql').hide("slow");
        });

        $(document).on('change', '.examenSeleccionado', function () {
            ($('.examenSeleccionado:checked').length <= 0) ? $("#btn_exportarBloque").prop("disabled", true) : $("#btn_exportarBloque").prop("disabled", false);
        });
    });//fin marco jquery

    $("#btn_actualiza").click(function () {
        dt_data.fnDraw();
    });

    function elimina(id) {
        var reply_elimina = get_object('examenes/getExamData', {id: id});
        BootstrapDialog.show({
            title: 'Eliminar examen',
            message: 'Se <strong>eliminará</strong> el examen <strong>' + reply_elimina.clave + ' </strong> con todos <strong>los reactivos y planes asociados.</strong> <strong>¿Deseas continuar?</strong>',
            buttons: [{
                    cssClass: 'btn-primary',
                    label: 'Si, Eliminar Examen',
                    action: function (dialog) {
                        var datos = "id=" + id,
                                urll = "examenes/elimina",
                                respuesta = get_object(urll, datos);
                        if (respuesta.resp == 'ok') {
                            dt_data.fnDraw();//recargar los datos del datatable
                            notify_block('Eliminar examen', 'El examen se eliminó satisfactoriamente', '', 'success');
                        } else {
                            mensaje_center('Eliminar examen', 'Error', 'Error al eliminar el examen. Intente más tarde.', 'error');
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

    function contenidoExamen(id) {
        redirectTo('examenes/editar_examen/' + id);
    }

    function modifica(id) {
        var reply = get_object('examenes/getExamData', {id: id});
        var modalUpdate = "<form id='form_update_examen' role='form'><div id='formulario_examen'><div class='form-group'><label for='clave'>Clave del examen*: </label><input type='text' value='" + reply.clave + "' class='form-control' name='clave_examen' id='clave_examen' maxlength='40' minlength='4' required></div><div class='form-group'><label for='name'> Nombre del examen*: </label><input type='text' value='" + reply.nombre + "' class='form-control' name='name_examen' id='name_examen' maxlength='50' minlength='5' required></div><div class='has-warning'><div class='checkbox'><label><input type='checkbox' id='update_react_exam' value='' > ¿Editar reactivos del examen?</label></div></div></div></form>";
        var html = '';
        $('#modal_examen').append('<div id="modal_examen" style="display: none;">' + $('#modal_examen [role="form"]').html() + '</div>');
        $('#form_update_examen .form-control').val('').attr('disabled', false);
        var dialogo_modificar = new BootstrapDialog({
            title: 'Modificar examen',
            message: modalUpdate,
            buttons: [{
                    cssClass: 'btn-primary',
                    label: 'Modificar',
                    autodestroy: true,
                    action: function (dialog) {
                        if ($('#form_update_examen').validate().form()) {
                            var resp = get_object("examenes/update_examen", {
                                id: id,
                                nombre: $('#form_update_examen #name_examen').val(),
                                clave: $('#form_update_examen #clave_examen').val()
                            });
                            if (resp.resp == 'ok') {
                                if ($('#update_react_exam').prop('checked')) {
                                    redirect_to('examenes/editar_examen/' + id);
                                } else {
                                    dt_data.fnDraw();
                                    notify_block('Modificar Examen', 'El examen se modificó exitosamente, podrás añadir reactivos en el momento que desees.', '', 'success');
                                }
                            } else {
                                mensaje_center('Error al modificar el examen, ', resp.msg, 'Intente de nuevo.', 'error');
                            }
                            modalUpdate = '';
                            $('#form_update_examen #name_examen').val("");
                            $('#form_update_examen #clave_examen').val("");
                            dialog.close();
                        }
                    }
                }, {
                    label: 'No, Cancelar',
                    cssClass: 'btn-default',
                    action: function (dialog) {
                        dialog.close();
                        //location.reload(true); Recarga la página completa
                        dt_data.fnDraw();//recargar los datos del datatable
                    }
                }]
        });
        dialogo_modificar.open();
    }

    window.onload = function () {
        ($('.examenSeleccionado:checked').length <= 0) ? $("#btn_exportarBloque").prop("disabled", true) : $("#btn_exportarBloque").prop("disabled", false);
    };
</script>