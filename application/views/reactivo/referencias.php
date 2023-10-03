<style>
    div#autor ,div#referencia{margin-top: 10px;}
    #div_busca_autor .col-md-12 {margin-bottom: 10px}
    #searchautf{margin-bottom: 10px}
</style>
<div class="tab-pane" id="referencia" > 
    <!--------------------AUTOR----------------------->
    <div class="col-md-6 " id="autor">  
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Autor</h3>
            </div>
            <div class="panel-body">
                <!----------------FORMULARIO AUTOR---------------------->
                <div class="div_contenedor" id="formulario_autor">
                    <div class="form-horizontal" role="form">
                        <input type="hidden" name="aut_clave" id="aut_clave" value=""/>
                        <div class="form-group"><label for="aut_login" class="col-md-2 control-label">Cuenta:</label><div class="col-md-10"><input type="text" name="aut_login" id="aut_login" class="form-control" disabled="disabled"/></div></div>
                        <div class="form-group"><label for="aut_nombre" class="col-md-2 control-label">Nombre:</label><div class="col-md-10"><input type="text" name="aut_nombre" id="aut_nombre" class="form-control" disabled="disabled"/></div></div>
                        <div class="form-group"><label for="aut_telefono" class="col-md-2 control-label">Teléfono:</label><div class="col-md-10"><input type="tel" name="aut_telefono" id="aut_telefono" class="form-control" disabled="disabled" /></div></div>
                        <div class="form-group"><label for="aut_email" class="col-md-2 control-label">Email:</label><div class="col-md-10"><input type="email" name="aut_email" id="aut_email" class="form-control" disabled="disabled" data-validation-email-message="El e-mail no es valido"  /></div></div>
                        <div class="form-group">
                            <button class="btn btn-default col-md-4 col-md-offset-1" title="Cambiar autor" onclick="ver_busqueda_autor(true)"> Cambiar autor</button>
                        </div>
                    </div>
                </div> 
                <div id="div_busca_autor" style="display: none"> 
                    <div class="row">
                        <div id="searchautf" class="col-md-10"> 
                            <div class="input-group">
                                <input id="busca_autor_txt" type="text" class="form-control" placeholder="Ingresa un dato del autor (nombre, login o correo)">
                                <span class="input-group-btn"><button id="btn_busca_autor" class="btn btn-default" type="button"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-search"></i> Buscar&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button></span>
                            </div><!-- /input-group -->
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-danger form-control" onclick="ver_busqueda_autor(false)"><i class="fa fa-remove"></i> </button>
                        </div>
                    </div>
                    <div class="row">
                        <div id="tbl_result_autor" class="responsive-table col-md-12" style="display:none"> 
                            <table id="tbl_search_autor" class="table table-bordered table-condensed table-striped">
                                <thead>
                                    <tr>
                                        <th>Login</th>
                                        <th>Nombre</th>
                                        <th>.</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div> 
                    </div> 
                </div> 
            </div>
        </div>
    </div>
    <!--------------------LIBRO----------------------->
    <div class="col-md-6" id="referencia">  
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Referencia</h3>
            </div>
            <div class="panel-body">
                <!-------------------BUSQUEDA LIBRO--------------------->
                <div id="div_busca_referencia" style="display: none"> 
                    <div class="row">
                        <div id="searchautf" class="col-md-10">
                            <div class="input-group">
                                <input id="busca_referencia_txt" type="text" class="form-control" placeholder="Ingresa un dato de la referencia (título, autor o editorial)">
                                <span class="input-group-btn"><button id="btn_busca_referencia" class="btn btn-default" type="button"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-search"></i> Buscar&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button> </span>
                            </div><!-- /input-group -->
                        </div>
                        <div class="col-md-2"><button class="btn btn-danger form-control" onclick="ver_busqueda_referencia(false)"><i class="fa fa-remove"></i></button></div>
                    </div> 
                    <div class="row">
                        <div id="tbl_result_referencia" class="responsive-table col-md-12" style="display:none"> 
                            <table id="tbl_search_referencia" class="table table-bordered table-condensed table-striped">
                                <thead>
                                    <tr>
                                        <th>Título</th>
                                        <th>Editorial</th>
                                        <th>Autor</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div> 
                    </div> 
                </div> 
                <!----------------------FORMULARIO REFERENCIA----------------------------->
                <div class="div_contenedor" id="formulario_referencia" >
                    <div class="form-horizontal" role="form">
                        <input type="hidden" name="lib_clave" id="lib_clave" value=""/>
                        <div class="form-group"><label for="ref_titulo" class="col-md-2 control-label">Titúlo:</label><div class="col-md-10"><input type="text" name="ref_titulo" id="ref_titulo" class="form-control" disabled="disabled" value="" /></div></div>
                        <div class="form-group"><label for="ref_editorial" class="col-md-2 control-label">Editorial:</label><div class="col-md-10"><input type="text" name="ref_editorial" id="ref_editorial" class="form-control" disabled="disabled" value="" /></div></div>
                        <div class="form-group"><label for="ref_autores" class="col-md-2 control-label">Autor(es):</label><div class="col-md-10"><input type="text" name="ref_autores" id="ref_autores" class="form-control" disabled="disabled" value="" /></div></div>
                        <div class="form-group"><label for="ref_descripcion" class="col-md-2 control-label">Descripción:</label><div class="col-md-10"><textarea name="ref_descripcion" id="ref_descripcion" class="form-control" disabled="disabled"></textarea> </div></div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-default col-md-4 col-md-4 col-md-offset-1" id="btn_cambiareferecia" title="Cambiar Referencia" onclick="ver_busqueda_referencia(true)"><i class="fa fa-search"></i> Cambiar Referencia</button>
                        <button class="btn btn-default col-md-4 col-md-offset-1" id="btn_nuevaReferencia" title="Agregar Referencia"><i class="fa fa-plus"></i> Agregar referencia</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 
<script>
    function select_autor(id) {
        var resp = get_object('reactivo/datosAutor', {id: id});
        if (resp) {
            $('#aut_clave').val(resp.id);
            $('#aut_login').val(resp.log);
            $('#aut_nombre').val(resp.nom);
            $('#aut_telefono').val(resp.tel);
            $('#aut_email').val(resp.ema);
        }
        ver_busqueda_autor(false);
    }

    function ver_busqueda_autor(ver) {
        if (ver) {
            $("#formulario_autor").hide();
            $("#div_busca_autor").show();
        } else {
            $("#formulario_autor").show();
            $("#div_busca_autor").hide();
            $('#tbl_result_autor').hide();
            $("#busca_autor_txt").val('');
        }
    }

    function select_referencia(id) {
        var resp = get_object('reactivo/datosReferencia', {id: id});
        if (resp) {
            $('#lib_clave').val(resp.id);
            $('#ref_titulo').val(resp.tit);
            $('#ref_editorial').val(resp.edi);
            $('#ref_autores').val(resp.aut);
            $('#ref_descripcion').val(resp.des);
        }
        ver_busqueda_referencia(false);
    }

    function ver_busqueda_referencia(ver) {
        if (ver) {
            $("#formulario_referencia").hide();
            $("#div_busca_referencia").show();
        } else {
            $("#formulario_referencia").show();
            $("#div_busca_referencia").hide();
            $('#tbl_result_referencia').hide();
            $("#busca_referencia_txt").val('');
        }
    }

    $(document).ready(function () {
        $("#btn_busca_autor").click(buscarAutor);
        $("#btn_busca_referencia").click(buscarReferencia);
        //-----botón nuevo libro ----->
        $("#btn_nuevaReferencia").click(function () {
            var html = '';
            $('#form_agrega_ref').remove();
            $('#formulario_referencia').append('<div id="form_agrega_ref" style="display: none;">' + $('#formulario_referencia [role="form"]').html() + '</div>');
            $('#form_agrega_ref .form-control').val('').attr('disabled', false);
            var dialogo_agrega = new BootstrapDialog({
                title: 'Agregar referencia',
                message: '<div id="frm_agrega_referencia">' + $('#form_agrega_ref').html() + '</div>',
                buttons: [{
                        label: 'Agregar',
                        cssClass: 'btn-primary',
                        action: function (dialog) {
                            var resp = get_object("reactivo/agregaReferencia", {
                                tit: $('#frm_agrega_referencia #ref_titulo').val(),
                                edi: $('#frm_agrega_referencia #ref_editorial').val(),
                                aut: $('#frm_agrega_referencia #ref_autores').val(),
                                des: $('#frm_agrega_referencia #ref_descripcion').val()
                            });
                            if (resp.resp == 'ok') {
                                $('#formulario_referencia #lib_clave').val(resp.id);
                                $('#formulario_referencia #ref_titulo').val($('#frm_agrega_referencia #ref_titulo').val());
                                $('#formulario_referencia #ref_editorial').val($('#frm_agrega_referencia #ref_editorial').val());
                                $('#formulario_referencia #ref_autores').val($('#frm_agrega_referencia #ref_autores').val());
                                $('#formulario_referencia #ref_descripcion').val($('#frm_agrega_referencia #ref_descripcion').val());
                                notify_block('Agergar referencia', 'Se <b>agregó</b> la referencia de manera satisfactoria', '* Se agregara automáticamente como referencia del reactuvo actual.', 'success');
                            } else {
                                mensaje_center('Error', resp.msg, 'Intente de nuevo.', 'error');
                            }
                            dialog.close();
                        }
                    }, {
                        label: 'Cancelar',
                        cssClass: 'btn-default',
                        action: function (dialog) {
                            dialog.close();
                        }
                    }]
            });
            dialogo_agrega.open();
        });
    });

    //-----------buscar registros de autor------------------
    function buscarAutor() {
        var resp = get_object('reactivo/buscarAutor', {texto: $('#busca_autor_txt').val()});
        if (resp) {
            $.each(resp, function (i, v) {
                $('#tbl_search_autor tbody').append('<tr><td data-title="Login">' + v.log + '</td><td data-title="Nombre">' + v.nom + '</td><td><button class="btn btn-primary" onclick="select_autor(' + v.id + ')">Seleccionar</button></td></tr>');
            });
            $('#tbl_result_autor').show();
        }
        $('#tbl_search_autor').dataTable().fnDestroy();
        $('#tbl_search_autor').dataTable({
            "bJQueryUI": true, "oLanguage": {
                "sProcessing": "<div class=\"ui-widget-header boxshadowround\"><strong>Procesando...</strong></div>",
                "sLengthMenu": "Mostrar _MENU_ autores",
                "sZeroRecords": "No se encontraron resultados",
                "sInfo": "Mostrando desde _START_ hasta _END_ de _TOTAL_ autores",
                "sInfoEmpty": "Mostrando desde 0 hasta 0 de 0 autores",
                "sInfoFiltered": "(filtrado de _MAX_ autores)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sPrevious": "Anterior",
                    "sNext": "Siguiente",
                    "sLast": "Último"
                }
            }
        });
    }

    //-----------buscar registros de referencia------------------
    function buscarReferencia() {
        var resp = get_object('reactivo/buscarReferencia', {texto: $('#busca_referencia_txt').val()});
        if (resp) {
            $.each(resp, function (i, v) {
                $('#tbl_search_referencia tbody').append('<tr><td data-title="Título">' + v.tit + '</td><td data-title="Editorial">' + v.edi + '</td><td data-title="Autor(es)">' + v.aut + '</td><td><button class="btn btn-primary" onclick="select_referencia(' + v.id + ')">Seleccionar</button></td></tr>');
            });
            $('#tbl_result_referencia').show();
        }

        $('#tbl_search_referencia').dataTable().fnDestroy();
        $('#tbl_search_referencia').dataTable({
            "bJQueryUI": true, "oLanguage": {
                "sProcessing": "<div class=\"ui-widget-header boxshadowround\"><strong>Procesando...</strong></div>",
                "sLengthMenu": "Mostrar _MENU_ referencias",
                "sZeroRecords": "No se encontraron resultados",
                "sInfo": "Mostrando desde _START_ hasta _END_ de _TOTAL_ referencias",
                "sInfoEmpty": "Mostrando desde 0 hasta 0 de 0 referencias",
                "sInfoFiltered": "(filtrado de _MAX_ referencias)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sPrevious": "Anterior",
                    "sNext": "Siguiente",
                    "sLast": "Último"
                }
            }
        });
    }

</script>   