<?php
$clv_sess = $this->config->item('clv_sess');
$user_id = $this->session->userdata('user_id' . $clv_sess);
if ($user_id != 1) {
    ?>
    <div class="col-md-6" style="text-align: center"><font style=" font-size: 60px;">Sitio en mantenimiento</font><br>Infomático trabajando :D</div>
    <div class="col-md-6"><img src="./images/mantenimiento.png"/></div>
<?php } ?>
<script type="text/javascript" language="javascript" src="./js/DataTables/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="./js/DataTables/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="./js/modal_bootstrap_extend/js/bootstrap-dialog.js"></script>
<script src="./js/tinymce/js/tinymce/jquery.tinymce.min.js" type="text/javascript"></script>
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
</style>
<?php
if ($user_id == 1) {
    ?>
    <div class="row" >
        <div class="col-md-12" >
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingOne">
                        <h4 class="panel-title">
                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Selecciona un tipo de referencia para mostrar
                            </a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                        <div class="panel-body">
                            <div class="form-control">
                                <label for="estado">Referencias:</label>
                                <select id="referencia"  onchange="getValueShow()">
                                    <option value="">Selecciona una opción...</option>
                                    <option value="L">Libro</option>
                                    <option value="AR">Artículo de revista</option>
                                    <option value="AP">Artículo de periódico</option>
                                    <option value="SW">Sitio web</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="span-24" >
        <div class="row" style="margin: 20px;">
            <div class="col-md-12">
                <div class=" button_bar col-md-12" >
                    <div id="container_buttons"class="button_bar_content">
                        <button id="btn_actualiza" class="btn btn-primary col-md-2 col-sm-6 col-xs-12"><i class="fa fa-undo"></i>&nbsp;Actualizar</button>  
                        <button id="btn_agregar" data-toggle="modal"  class="btn btn-primary col-md-2 col-sm-6 col-xs-12"><i class="fa fa-plus"></i>&nbsp;Agregar</button> 
                    </div>
                </div><br><br><br>
                <div class="row">
                    <div id="dinamic" class="responsive-table">

                    </div>
                </div>
            </div>
        </div><br>
    </div><br>


    <script type="text/javascript" charset="utf-8">
        jQuery.validator.addMethod("validateLetters", function (value, element) {
            return this.optional(element) || /^[-/.,;:_a-záéíóóúàèìòùäëïöüñ\s]+$/i.test(value);
        });
        jQuery.validator.addMethod("validateNumber", function (value, element) {
            return this.optional(element) || /^[-,0-9]+$/i.test(value);
        });

        var value, valueShowTable, modalForm = '', modalFormUpdate = '';
        $('#container_buttons').hide();
        function showTable(tipo_ref) {
            var ruta = '';
            $('#container_buttons').show();
            $("#dinamic").show();
            if (tipo_ref == 'L') {
                ruta = "index.php/referencias/get_datos";
                destroyTable();
                $('#dinamic').html(headersBodyTable(11));
                dt_data = $('#dtdata').dataTable({
                    //dt_data.fnFilter("Libro", 1, true, false);
                    "bJQueryUI": true,
                    "oLanguage": {
                        "sProcessing": "<div class=\"ui-widget-header boxshadowround\"><strong>Procesando...</strong></div>",
                        "sLengthMenu": "Mostrar _MENU_ referencias",
                        "sZeroRecords": "No se encontraron referencias",
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
                    },
                    columns: [
                        {title: "id", "bVisible": true},
                        {title: "Tipo de referencia", "bVisible": true},
                        {title: "Titulo del libro", "bVisible": true},
                        {title: "Autor(res)", "bVisible": true},
                        {title: "Año de publicación", "bVisible": true},
                        {title: "Ciudad", "bVisible": true},
                        {title: "Editorial", "bVisible": true},
                        {title: "Descripción", "bVisible": true},
                        {title: "Fecha de alta", "bVisible": true},
                        {title: "Fecha de ultima modificación", "bVisible": true},
                        {title: "", "bVisible": true}
                    ],
                    "aaSorting": [[8, 'desc']],
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

            } else if (tipo_ref == 'AR') {
                ruta = "index.php/referencias/get_datos_revista";
                destroyTable();
                $('#dinamic').html(headersBodyTable(12));
                dt_data = $('#dtdata').dataTable({
                    //dt_data.fnFilter("Libro", 1, true, false);
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
                    columns: [
                        {title: "id", "bVisible": true},
                        {title: "Tipo de referencia", "bVisible": true},
                        {title: "Tipo del artículo", "bVisible": true},
                        {title: "Autor(res)", "bVisible": true},
                        {title: "Nombre de la revista", "bVisible": true},
                        {title: "Páginas", "bVisible": true},
                        {title: "Año de publicación", "bVisible": true},
                        {title: "Editorial", "bVisible": true},
                        {title: "Descripción", "bVisible": true},
                        {title: "Fecha de alta", "bVisible": true},
                        {title: "Fecha de ultima modificación", "bVisible": true},
                        {title: "", "bVisible": true}
                    ],
                    "aaSorting": [[9, 'desc']],
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
            } else if (tipo_ref == 'AP') {
                ruta = "index.php/referencias/get_datos_periodico";
                destroyTable();
                $('#dinamic').html(headersBodyTable(11));
                dt_data = $('#dtdata').dataTable({
                    //dt_data.fnFilter("Libro", 1, true, false);
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
                    columns: [
                        {title: "id", "bVisible": true},
                        {title: "Tipo de referencia", "bVisible": true},
                        {title: "Tipo del artículo", "bVisible": true},
                        {title: "Autor(res)", "bVisible": true},
                        {title: "Título del periódico", "bVisible": true},
                        {title: "Fecha de publicación", "bVisible": true},
                        {title: "Páginas", "bVisible": true},
                        {title: "Descripción", "bVisible": true},
                        {title: "Fecha de alta", "bVisible": true},
                        {title: "Fecha de ultima modificación", "bVisible": true},
                        {title: "", "bVisible": true}
                    ],
                    "aaSorting": [[8, 'desc']],
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
            } else if (tipo_ref == 'SW') {
                ruta = "index.php/referencias/get_datos_sitio";
                destroyTable();
                $('#dinamic').html(headersBodyTable(10));
                dt_data = $('#dtdata').dataTable({
                    //dt_data.fnFilter("Libro", 1, true, false);
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
                    columns: [
                        {title: "id", "bVisible": true},
                        {title: "Tipo de referencia", "bVisible": true},
                        {title: "Nombre del sitio web", "bVisible": true},
                        {title: "Autor(res)", "bVisible": true},
                        {title: "Fecha de publicación", "bVisible": true},
                        {title: "URL", "bVisible": true},
                        {title: "Descripción", "bVisible": true},
                        {title: "Fecha de alta", "bVisible": true},
                        {title: "Fecha de ultima modificación", "bVisible": true},
                        {title: "", "bVisible": true}
                    ],
                    "aaSorting": [[7, 'desc']],
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
        }

        function  headersBodyTable(numberCol = 0){
            var table = '<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered display" id="dtdata">';
            table += '<thead>';
            table += '<tr>';
            for (i = 0; i < numberCol; i++) {
                table += '<th></th>';
            }
            table += '</tr>';
            table += '</thead>';
            table += '<tbody>';
            table += '</tbody>';
            table += '</table>';
            return table;
        }

        function destroyTable() {
            // table.destroy();//Se destruye la tabla vacía y se vuelve a pintar con los nuevos datos
            if ($.fn.DataTable.isDataTable("#dtdata")) {
                $("#dtdata").DataTable().destroy();
                $('#dtdata tbody > tr').remove();
            }
        }

        function getValueShow() {
            valueShowTable = document.getElementById("referencia").value;
            if (valueShowTable == '') {
                $('#container_buttons').hide();
                $("#dinamic").hide();
                destroyTable();
                mensaje_center('Selección inválida', '', 'Por favor selecciona una opción válida.', 'error');
            }
            if (valueShowTable == 'L') {
                showTable(valueShowTable);
                dt_data.fnFilter("Libro", 1, true, false);
            }
            if (valueShowTable == 'AR') {
                showTable(valueShowTable);
                dt_data.fnFilter("revista", 1, true, false);
            }
            if (valueShowTable == 'AP') {
                showTable(valueShowTable);
                dt_data.fnFilter("periodico", 1, true, false);
            }
            if (valueShowTable == 'SW') {
                showTable(valueShowTable);
                dt_data.fnFilter("Sitio web", 1, true, false);
            }
        }
       
        function getValue() {
            value = document.getElementById("tipo_referencia_modifica").value;
            if (value == "") {
                $("#formulario_articulo_revista").hide();
                $("#formulario_articulo_periodico").hide();
                $("#formulario_sitio_web").hide();
                $("#formulario_libro").hide();
            } else if (value == "L") {
                $("#formulario_articulo_revista").hide();
                $("#formulario_articulo_periodico").hide();
                $("#formulario_sitio_web").hide();
                $("#formulario_libro").show();
            } else if (value == "AR") {
                $("#formulario_articulo_periodico").hide();
                $("#formulario_sitio_web").hide();
                $("#formulario_libro").hide();
                $("#formulario_articulo_revista").show();
            } else if (value == "AP") {
                $("#formulario_sitio_web").hide();
                $("#formulario_libro").hide();
                $("#formulario_articulo_revista").hide();
                $("#formulario_articulo_periodico").show();

            } else if (value == "SW") {
                $("#formulario_articulo_revista").hide();
                $("#formulario_articulo_periodico").hide();
                $("#formulario_libro").hide();
                $("#formulario_sitio_web").show();
            } else {

            }
        }

        function replacebr(cadena) {
            var newString = "";
            var cadena_array = cadena.split('<br>');
            var newString = cadena_array.join('\n');

            return newString;
        }

        $(document).ready(function () {
            $("#btn_agregar").click(function () {
                clearForm();
                var html = '';
                //modalForm = "<form id='form_modifica_ref' role='form'><input type='hidden' id='id_referencia' name='ref_clave' id='lib_clave' /><div class='form-group'><label for='tipo'> Selecciona un tipo de referencia*</label><select onchange='getValue()' class='form-control' id='tipo_referencia_modifica' required><option value=''>Selecciona una opción...</option><option value='Libro'>Libro</option><option value='Artículo de revista'>Artículo de revista</option><option value='Artículo de periódico'>Artículo de periódico</option><option value='Sitio web'>Sitio Web</option></select></div> <div id='formulario_libro' style='display: none;'><div class='form-group'><label for='titulo'>Título del libro* </label><input type='text' value=''class='form-control' name='titulo_libro_referencia_modifica' id='titulo_referencia_modifica' maxlength='50' minlength='5' required></div><div class='form-group'><label for='autor'> Autor(res)* </label><input type='text' value='' class='form-control' name='autores_referencia_modifica' id='autores_referencia_modifica' maxlength='50' minlength='7' required></div><div class='form-group'><label for='anio'>Año* </label><input type='number' value ='' name='anio_referencia_modifica' maxlength='4' minlength='4'  class='form-control' name='anio_referencia_modifica' id='anio_referencia_modifica' required></div><div class='form-group'><label for='ciudad'>Ciudad* </label><input type='text' value=''class='form-control' name='ciudad_referencia_modifica' id='ciudad_referencia_modifica' maxlength='15' minlength='4' required></div><div class='form-group'><label for='editorial'>Editorial* </label><input type='text' value='' class='form-control' name='editorial_referencia_modifica' id='editorial_referencia_modifica' maxlength='20' minlength='4' required></div><div class='form-group'><label for='descripcion'> Descripción de la referencia </label><textarea type='text' class='form-control'id='descripcion_libro_referencia_modifica'></textarea></div></div><div id='formulario_articulo_revista' style='display: none;'><div class='form-group'><label for='titulo'>Título del artículo* </label><input type='text' value='' class='form-control' name='titulo_artticulo_revista_referencia_modifica' id='titulo_artticulo_revista_referencia_modifica' maxlength='50' minlength='5' required></div><div class='form-group'><label for='autor'> Autor(res)* </label><input type='text' value='' class='form-control' name='autores_revista_referencia_modifica' id='autores_revista_referencia_modifica' maxlength='50' minlength='7' required></div><div class='form-group'><label for='nombre_ciudad'>Nombre de la revista* </label><input type='text' value='' class='form-control' name='nombre_revista_referencia_modifica' id='nombre_revista_referencia_modifica' maxlength='30' minlength='5' required></div><div class='form-group'><label for='npaginas'>Página(as)* </label><input type='text' value=''class='form-control' name='paginas_revista_referencia_modifica' id='paginas_revista_referencia_modifica' maxlength='20' minlength='1' required></div><div class='form-group'><label for='anio'>Año* </label><input type='number' value ='' name='anio_referencia_modifica' maxlength='4' minlength='4'  class='form-control' name='anio_revista_referencia_modifica' id='anio_revista_referencia_modifica' required></div><div class='form-group'><label for='editorial'>Editorial* </label><input type='text' value='' class='form-control' maxlength='25' minlength='4' name='editorial_revista_referencia_modifica' id='editorial_revista_referencia_modifica' required></div><div class='form-group'><label for='descripcion'> Descripción de la referencia </label><textarea type='text' class='form-control' id='descripcion_revista_referencia_modifica'></textarea></div></div><div id='formulario_articulo_periodico' style='display: none;'><div class='form-group'><label for='titulo_periodico'>Título del artículo* </label><input type='text' value=''class='form-control' minlength='4' maxlength='50' name='titulo_articulo_periodico_referencia_modifica' id='titulo_articulo_periodico_referencia_modifica' required></div><div class='form-group'><label for='autor'> Autor(res)* </label><input type='text' value='' class='form-control' minlength='7' maxlength='50' name='autores_periodico_referencia_modifica' id='autores_periodico_referencia_modifica' required></div><div class='form-group'><label for='titulo_periodico'>Título del periódico* </label><input type='text' value=''class='form-control' minlength='2' maxlength='30' name='titulo_periodico_referencia_modifica' id='titulo_periodico_referencia_modifica' required></div><div class='form-group'><label for='fecha'>Fecha* </label><input type='date' value=''class='form-control' name='fecha_periodico_referencia_modifica' id='fecha_periodico_referencia_modifica' required></div><div class='form-group'><label for='nPaginasPeriodico'>Página(as)* </label><input type='text' value=''class='form-control' minlength='1' maxlength='15' name='paginas_periodico_referencia_modifica' id='paginas_priodico_referencia_modifica' required></div><div class='form-group'><label for='descripcion'> Descripción de la referencia </label><textarea type='text' class='form-control' id='descripcion_periodico_referencia_modifica'></textarea></div></div><div id='formulario_sitio_web' style='display: none;'><div class='form-group'><label for='nombre_sitio'>Nombre del sitio web* </label><input type='text' value='' class='form-control' minlength='5' maxlength='45' name='nombre_sitio_web_referencia_modifica' id='nombre_sitio_web_referencia_modifica' required></div><div class='form-group'><label for='autor'> Autor(res)* </label><input type='text' value='' class='form-control' minlength='7' maxlength='50' name='autores_sitio_referencia_modifica' id='autores_sitio_referencia_modifica' required></div><div class='form-group'><label for='fecha'>Fecha* </label><input type='date' value='' class='form-control' name='fecha_sitio_referencia_modifica' id='fecha_sitio_referencia_modifica' required></div><div class='form-group'><label for='url_sitio'>URL* </label><input type='text' placeholder='Ejemplo: http://ejemplo.com' value='' class='form-control' minlength='11' maxlength='50' name='url_sitio_referencia_modifica' id='url_sitio_referencia_modifica' required></div><div class='form-group'><label for='descripcion'> Descripción de la referencia </label><textarea type='text' class='form-control' id='descripcion_sitio_referencia_modifica'></textarea></div></div></form>";
               // $('#modal_referencia').append('<div id="form_amodifica_ref" style="display: none;">' + $('#modal_referencia [role="form"]').html() + '</div>');
                //$('#form_modifica_ref .form-control').val('').attr('disabled', false);
                var dialogo_agrega = new BootstrapDialog({
                    title: 'Agregar referencia',
                    message:"<?php echo (isset($form_referencias)) ? $form_referencias : ''; ?>",
                    buttons: [{
                            label: 'Agregar',
                            cssClass: 'btn-primary',
                            autodestroy: true,
                            action: function (dialog) {
                                $("#form_modifica_ref").validate({
                                    rules: /* Accedemos a los campos a través de su nombre*/
                                    {
                                        titulo_libro_referencia_modifica: {
                                            validateLetters: true,
                                                remote: {
                                                    url: '<?php echo base_url("index.php/referencias/searchReg") ?>',
                                                    type: "post",
                                                    dataFilter: function (data) {
                                                        if (data == 'false') {
                                                            return false;
                                                        } else {
                                                            return true;
                                                        }
                                                    }
                                                }
                                        },
                                        autores_referencia_modifica: {validateLetters: true},
                                        ciudad_referencia_modifica: {validateLetters: true},
                                        editorial_referencia_modifica: {validateLetters: true},
                                        titulo_artticulo_revista_referencia_modifica: {
                                            validateLetters: true,
                                            remote: {
                                                url: '<?php echo base_url("index.php/referencias/searchRev") ?>',
                                                type: "post",
                                                dataFilter: function (data) {
                                                    if (data == 'false') {
                                                        return false;
                                                    } else {
                                                        return true;
                                                    }
                                                }
                                            }
                                        },
                                        autores_revista_referencia_modifica: {validateLetters: true},
                                        paginas_periodico_referencia_modifica: {validateNumber: true},
                                        paginas_revista_referencia_modifica: {validateNumber: true},
                                        nombre_revista_referencia_modifica: {validateLetters: true},
                                        editorial_revista_referencia_modifica: {validateLetters: true},
                                        titulo_articulo_periodico_referencia_modifica: {
                                            validateLetters: true,
                                                remote: {
                                                    url: '<?php echo base_url("index.php/referencias/searchPer") ?>',
                                                    type: "post",
                                                    dataFilter: function (data) {
                                                        if (data == 'false') {
                                                           return false;
                                                        } else {
                                                            return true;
                                                        }
                                                    }
                                                }
                                        },
                                        autores_periodico_referencia_modifica: {validateLetters: true},
                                        autores_sitio_referencia_modifica: {validateLetters: true},
                                        titulo_periodico_referencia_modifica: {validateLetters: true},
                                        url_sitio_referencia_modifica: {url: true}
                                    }
                                });

                                if ($('#form_modifica_ref').validate().form()) {
                                    if (value == "L") {
                                        var resp = get_object("referencias/agregaReferencia", {
                                            tit: $('#form_modifica_ref #titulo_referencia_modifica').val(),
                                            aut: $('#form_modifica_ref #autores_referencia_modifica').val(),
                                            year: $('#form_modifica_ref #anio_referencia_modifica').val(),
                                            ciudad: $('#form_modifica_ref #ciudad_referencia_modifica').val(),
                                            edi: $('#form_modifica_ref #editorial_referencia_modifica').val(),
                                            des: $('#form_modifica_ref #descripcion_libro_referencia_modifica').val(),
                                            tipo: value
                                        });
                                    } else if (value == "AR") {
                                        var resp = get_object("referencias/agregaReferencia", {
                                            tit: $('#form_modifica_ref #titulo_artticulo_revista_referencia_modifica').val(),
                                            aut: $('#form_modifica_ref #autores_revista_referencia_modifica').val(),
                                            nameRevista: $('#form_modifica_ref #nombre_revista_referencia_modifica').val(),
                                            pages: $('#form_modifica_ref #paginas_revista_referencia_modifica').val(),
                                            year: $('#form_modifica_ref #anio_revista_referencia_modifica').val(),
                                            edi: $('#form_modifica_ref #editorial_revista_referencia_modifica').val(),
                                            des: $('#form_modifica_ref #descripcion_revista_referencia_modifica').val(),
                                            tipo: value
                                        });
                                    } else if (value == "AP") {
                                        var resp = get_object("referencias/agregaReferencia", {
                                            tit: $('#form_modifica_ref #titulo_articulo_periodico_referencia_modifica').val(),
                                            aut: $('#form_modifica_ref #autores_periodico_referencia_modifica').val(),
                                            namePeriodico: $('#form_modifica_ref #titulo_periodico_referencia_modifica').val(),
                                            date: $('#form_modifica_ref #fecha_periodico_referencia_modifica').val(),
                                            pages: $('#form_modifica_ref #paginas_priodico_referencia_modifica').val(),
                                            des: $('#form_modifica_ref #descripcion_periodico_referencia_modifica').val(),
                                            tipo: value
                                        });
                                    } else if (value == "SW") {
                                        var resp = get_object("referencias/agregaReferencia", {
                                            name: $('#form_modifica_ref #nombre_sitio_web_referencia_modifica').val(),
                                            aut: $('#form_modifica_ref #autores_sitio_referencia_modifica').val(),
                                            date: $('#form_modifica_ref #fecha_sitio_referencia_modifica').val(),
                                            url: $('#form_modifica_ref #url_sitio_referencia_modifica').val(),
                                            des: $('#form_modifica_ref #descripcion_sitio_referencia_modifica').val(),
                                            tipo: value
                                        });
                                    } else {
                                        mensaje_center('Error', resp.msg, 'No selecciono ningún tipo de referencia.', 'error');
                                    }

                                    if (resp.resp == 'ok') {
                                        dt_data.fnDraw();//recargar los datos del datatable
                                        modalForm = '';
                                        clearForm();
                                        notify_block('Agregar referencia', 'Se <b>agregó</b> la referencia de manera satisfactoria', '', 'success');
                                    }else {
                                        mensaje_center('Error', resp.msg, 'Intente de nuevo.', 'error');
                                    }
                                    dialog.close();
                                }
                            }
                        }, {
                            label: 'Cancelar',
                            cssClass: 'btn-default',
                            action: function (dialog) {
                                clearForm();
                                dialog.close();
                            }
                        }]
                });
                dialogo_agrega.open();
            });
        });//fin marco jquery

        function modifica(id) {
            var select = '', typeUpdate = '';
            var reply = get_object('referencias/getReferencData', {id: id});
            var replace = replacebr(reply.descripcion);
            if (reply.tipo == 'Libro') {
                typeUpdate = 'L';
                select = " <option value='Libro' >Libro</option>";
                modalFormUpdate = "<form id='form_modifica_ref' role='form'><input type='hidden'  name='ref_clave' id='lib_clave'/><div class='form-group'><label for='tipo'> Tipo de referencia* </label><select onchange='getValueModified()' class='form-control' id='tipo_referencia_modified' required>" + select + "</select></div><div class='form-group'><label for='titulo'>Título del libro*</label><input type='text' value='" + reply.titulo + "'class='form-control' maxlength='50' minlength='5' name='titulo_referencia_modifica' id='titulo_referencia_modifica' required></div><div class='form-group'><label for='autor'> Autor(res)* </label><input type='text' value='" + reply.autores + "' class='form-control' maxlength='50' minlength='7' name='autores_referencia_modifica' id='autores_referencia_modifica' required></div><div class='form-group'><label for='year'>Año*</label><input type='number' value='" + reply.year + "' class='form-control' name='anio_referencia_modifica' id='anio_referencia_modifica'  maxlength='4' minlength='4' required></div><div class='form-group'><label for='ciudad'>Ciudad*</label><input type='text' value='" + reply.ciudad + "' class='form-control' maxlength='15' minlength='4' name='ciudad_referencia_modifica' id='ciudad_referencia_modifica' required></div><div class='form-group'><label for='editorial'>Editorial*</label><input type='text' value='" + reply.editorial + "' class='form-control'  maxlength='20' minlength='4' name='editorial_referencia_modifica' id='editorial_referencia_modifica' required></div><div class='form-group'><label for='descripcion'> Descripción de la referencia </label><textarea type='text' class='form-control' id='descripcion_libro_referencia_modifica'>" + replace + "</textarea></div></form>";
            } else if (reply.tipo == 'Artículo de revista') {
                typeUpdate = 'AR';
                select = " <option value='Artículo de revista'>Artículo de revista</option>";
                modalFormUpdate = "<form id='form_modifica_ref' role='form'><input type='hidden' id='id_referencia' name='ref_clave' id='lib_clave'/><div class='form-group'><label for='tipo'> Tipo de referencia* </label><select onchange='getValueModified()' class='form-control' id='tipo_referencia_modified' required>" + select + "</select></div><div class='form-group'><label for='titulo'>Título del artículo* </label><input type='text' value='" + reply.titulo + "' class='form-control' maxlength='50' minlength='5' name='titulo_artticulo_revista_referencia_modifica' id='titulo_artticulo_revista_referencia_modifica' required></div><div class='form-group'><label for='autor'> Autor(res)* </label><input type='text' value='" + reply.autores + "' class='form-control' maxlength='50' minlength='7' name='autores_revista_referencia_modifica' id='autores_revista_referencia_modifica' required></div><div class='form-group'><label for='nombre_ciudad'>Nombre de la revista* </label><input type='text' value='" + reply.nombrerevista + "' class='form-control' maxlength='30' minlength='5' name='nombre_revista_referencia_modifica' id='nombre_revista_referencia_modifica' required></div><div class='form-group'><label for='npaginas'>Página(as)* </label><input type='text' value='" + reply.paginas + "' class='form-control' maxlength='20' minlength='1' name='paginas_revista_referencia_modifica' id='paginas_revista_referencia_modifica' required></div><div class='form-group'><label for='anio'>Año* </label><input type='number' value='" + reply.year + "' name='anio_referencia_modifica' maxlength='4' minlength='4'  class='form-control' name='anio_revista_referencia_modifica' id='anio_revista_referencia_modifica' required></div><div class='form-group'><label for='editorial'>Editorial* </label><input type='text' value='" + reply.editorial + "' class='form-control' maxlength='25' minlength='4' name='editorial_revista_referencia_modifica' id='editorial_revista_referencia_modifica' required></div><div class='form-group'><label for='descripcion'> Descripción de la referencia </label><textarea type='text' class='form-control' id='descripcion_revista_referencia_modifica'>" + replace + "</textarea></div></form>";
            } else if (reply.tipo == 'Artículo de periódico') {
                typeUpdate = 'AP';
                select = "<option value='Artículo de periódico'>Artículo de periódico</option>";
                modalFormUpdate = "<form id='form_modifica_ref' role='form'><input type='hidden' id='id_referencia' name='ref_clave' id='lib_clave'/><div class='form-group'><label for='tipo'> Tipo de referencia* </label><select onchange='getValueModified()' class='form-control' id='tipo_referencia_modified' required>" + select + "</select></div><div class='form-group'><label for='titulo_periodico'>Título del artículo* </label><input type='text' value='" + reply.titulo + "' class='form-control' maxlength='50' minlength='4' name='titulo_articulo_periodico_referencia_modifica' id='titulo_articulo_periodico_referencia_modifica' required></div><div class='form-group'><label for='autor'> Autor (res)* </label><input type='text' value='" + reply.autores + "' class='form-control' maxlength='50' minlength='7' name='autores_periodico_referencia_modifica' id='autores_periodico_referencia_modifica' required></div><div class='form-group'><label for='titulo_periodico'>Título del periódico* </label><input type='text' value='" + reply.tituloperiodico + "' class='form-control' minlength='2' maxlength='30' name='titulo_periodico_referencia_modifica' id='titulo_periodico_referencia_modifica' required></div><div class='form-group'><label for='fecha'>Fecha* </label><input type='date' value='" + reply.fecha + "' class='form-control' name='fecha_periodico_referencia_modifica' id='fecha_periodico_referencia_modifica' required></div><div class='form-group'><label for='nPaginasPeriodico'>Páginas* </label><input type='text' value='" + reply.paginas + "' class='form-control' minlength='1' maxlength='15' name='paginas_periodico_referencia_modifica' id='paginas_priodico_referencia_modifica' required></div><div class='form-group'><label for='descripcion'> Descripción de la referencia </label><textarea type='text' class='form-control' id='descripcion_periodico_referencia_modifica'>" + replace + "</textarea></div></form>";
            } else if (reply.tipo = 'Sitio web') {
                typeUpdate = 'SW';
                select = "<option value='Sitio web'>Sitio Web</option>";
                modalFormUpdate = "<form id='form_modifica_ref' role='form'><input type='hidden' id='id_referencia' name='ref_clave' id='lib_clave'/><div class='form-group'><label for='tipo'> Tipo de referencia* </label><select onchange='getValueModified()' class='form-control' id='tipo_referencia_modified' required>" + select + "</select></div><div class='form-group'><label for='nombre_sitio'>Nombre del sitio web* </label><input type='text' value='" + reply.nombresitio + "' class='form-control' minlength='5' maxlength='50' name='nombre_sitio_web_referencia_modifica' id='nombre_sitio_web_referencia_modifica' required></div><div class='form-group'><label for='autor'> Autor (res)* </label><input type='text' value='" + reply.autores + "' class='form-control' minlength='7' maxlength='50' name='autores_sitio_referencia_modifica' id='autores_sitio_referencia_modifica' required></div><div class='form-group'><label for='fecha'>Fecha* </label><input type='date' value='" + reply.fecha + "' class='form-control' name='fecha_sitio_referencia_modifica' id='fecha_sitio_referencia_modifica' required></div><div class='form-group'><label for='url_sitio'>URL* </label><input type='text' value='" + reply.url + "' class='form-control' minlength='11' maxlength='50' name='url_sitio_referencia_modifica' id='url_sitio_referencia_modifica' required></div><div class='form-group'><label for='descripcion'> Descripción de la referencia </label><textarea type='text' class='form-control' id='descripcion_sitio_referencia_modifica'>" + replace + "</textarea></div></form>";
            }

            var html = '';
            $('#modal_referencia').append('<div id="form_amodifica_ref" style="display: none;">' + $('#modal_referencia [role="form"]').html() + '</div>');
            $('#form_modifica_ref .form-control').val('').attr('disabled', false);

            var dialogo_modificar = new BootstrapDialog({
                title: 'Modificar referencia',
                message: modalFormUpdate,
                buttons: [{
                        cssClass: 'btn-primary',
                        label: 'Modificar',
                        autodestroy: true,
                        action: function (dialog) {
                            $("#form_modifica_ref").validate({
                                rules: /* Accedemos a los campos a través de su nombre*/
                                        {
                                            titulo_libro_referencia_modifica: {validateLetters: true},
                                            autores_referencia_modifica: {validateLetters: true},
                                            ciudad_referencia_modifica: {validateLetters: true},
                                            editorial_referencia_modifica: {validateLetters: true},
                                            titulo_artticulo_revista_referencia_modifica: {
                                                validateLetters: true
                                            },
                                            autores_revista_referencia_modifica: {validateLetters: true},
                                            paginas_periodico_referencia_modifica: {validateNumber: true},
                                            paginas_revista_referencia_modifica: {validateNumber: true},
                                            nombre_revista_referencia_modifica: {validateLetters: true},
                                            editorial_revista_referencia_modifica: {validateLetters: true},
                                            titulo_articulo_periodico_referencia_modifica: {
                                                validateLetters: true
                                            },
                                            autores_periodico_referencia_modifica: {validateLetters: true},
                                            autores_sitio_referencia_modifica: {validateLetters: true},
                                            titulo_periodico_referencia_modifica: {validateLetters: true},
                                            url_sitio_referencia_modifica: {url: true}
                                        }
                            });

                            if ($('#form_modifica_ref').validate().form()) {
                                if (typeUpdate == 'L') {
                                    var resp = get_object("referencias/updateReferencias", {
                                        id: id,
                                        tit: $('#form_modifica_ref #titulo_referencia_modifica').val(),
                                        aut: $('#form_modifica_ref #autores_referencia_modifica').val(),
                                        year: $('#form_modifica_ref #anio_referencia_modifica').val(),
                                        ciudad: $('#form_modifica_ref #ciudad_referencia_modifica').val(),
                                        edi: $('#form_modifica_ref #editorial_referencia_modifica').val(),
                                        des: $('#form_modifica_ref #descripcion_libro_referencia_modifica').val(),
                                        tipo: typeUpdate
                                    });
                                    if (resp.resp == 'ok') {
                                        dt_data.fnDraw();//recargar los datos del datatable
                                        notify_block('Modificar referencia', 'Se <b>modificó</b> la referencia de manera satisfactoria', '', 'success');
                                        typeUpdate = '';
                                        modalFormUpdate = '';
                                        clearForm();
                                    } else {
                                        mensaje_center('Error al modificar la referencia', resp.msg, 'Intente de nuevo.', 'error');
                                    }
                                } else if (typeUpdate == 'AR') {
                                    var resp = get_object("referencias/updateReferencias", {
                                        id: id,
                                        tit: $('#form_modifica_ref #titulo_artticulo_revista_referencia_modifica').val(),
                                        aut: $('#form_modifica_ref #autores_revista_referencia_modifica').val(),
                                        nameRevista: $('#form_modifica_ref #nombre_revista_referencia_modifica').val(),
                                        pages: $('#form_modifica_ref #paginas_revista_referencia_modifica').val(),
                                        year: $('#form_modifica_ref #anio_revista_referencia_modifica').val(),
                                        edi: $('#form_modifica_ref #editorial_revista_referencia_modifica').val(),
                                        des: $('#form_modifica_ref #descripcion_revista_referencia_modifica').val(),
                                        tipo: typeUpdate
                                    });
                                    if (resp.resp == 'ok') {
                                        dt_data.fnDraw();//recargar los datos del datatable
                                        notify_block('Modificar referencia', 'Se <b>modificó</b> la referencia de manera satisfactoria', '', 'success');
                                        typeUpdate = '';
                                        modalFormUpdate = '';
                                        clearForm();
                                    } else {
                                        mensaje_center('Error al modificar la referencia', resp.msg, 'Intente de nuevo.', 'error');
                                    }
                                } else if (typeUpdate == 'AP') {
                                    var resp = get_object("referencias/updateReferencias", {
                                        id: id,
                                        tit: $('#form_modifica_ref #titulo_articulo_periodico_referencia_modifica').val(),
                                        aut: $('#form_modifica_ref #autores_periodico_referencia_modifica').val(),
                                        namePeriodico: $('#form_modifica_ref #titulo_periodico_referencia_modifica').val(),
                                        date: $('#form_modifica_ref #fecha_periodico_referencia_modifica').val(),
                                        pages: $('#form_modifica_ref #paginas_priodico_referencia_modifica').val(),
                                        des: $('#form_modifica_ref #descripcion_periodico_referencia_modifica').val(),
                                        tipo: typeUpdate
                                    });
                                    if (resp.resp == 'ok') {
                                        dt_data.fnDraw();//recargar los datos del datatable
                                        notify_block('Modificar referencia', 'Se <b>modificó</b> la referencia de manera satisfactoria', '', 'success');
                                        typeUpdate = '';
                                        modalFormUpdate = '';
                                        clearForm();
                                    } else {
                                        mensaje_center('Error al modificar la referencia', resp.msg, 'Intente de nuevo.', 'error');
                                    }
                                } else if (typeUpdate == 'SW') {
                                    var resp = get_object("referencias/updateReferencias", {
                                        id: id,
                                        name: $('#form_modifica_ref #nombre_sitio_web_referencia_modifica').val(),
                                        aut: $('#form_modifica_ref #autores_sitio_referencia_modifica').val(),
                                        date: $('#form_modifica_ref #fecha_sitio_referencia_modifica').val(),
                                        url: $('#form_modifica_ref #url_sitio_referencia_modifica').val(),
                                        des: $('#form_modifica_ref #descripcion_sitio_referencia_modifica').val(),
                                        tipo: typeUpdate
                                    });
                                    if (resp.resp == 'ok') {
                                        dt_data.fnDraw();//recargar los datos del datatable
                                        notify_block('Modificar referencia', 'Se <b>modificó</b> la referencia de manera satisfactoria', '', 'success');
                                        typeUpdate = '';
                                        modalFormUpdate = '';
                                        clearForm();
                                    } else {
                                        mensaje_center('Error al modificar la referencia', resp.msg, 'Intente de nuevo.', 'error');
                                    }
                                } else {
                                }
                                dialog.close();
                            }
                        }

                    }, {
                        label: 'No, Cancelar',
                        cssClass: 'btn-default',
                        action: function (dialog) {
                            dialog.close();
                            clearForm();
                            //location.reload(true); Recarga la página completa
                            dt_data.fnDraw();//recargar los datos del datatable
                        }
                    }]
            });
            dialogo_modificar.open();
        }

        function elimina(id) {
            var reply_elimina = get_object('referencias/getReferencData', {id: id});
            BootstrapDialog.show({
                title: 'Borrar referencia',
                message: 'Se borrará la referencia <strong>' + reply_elimina.tipo + ' </strong> del autor(es) <strong>' + reply_elimina.autores + '</strong> . <strong>¿Deseas continuar?</strong>',
                buttons: [{
                        cssClass: 'btn-primary',
                        label: 'Si, Borrar Referencia',
                        action: function (dialog) {
                            var datos = "id=" + id,
                                    urll = "referencias/elimina",
                                    respuesta = get_object(urll, datos);
                            if (respuesta.resp == 'ok') {
                                dt_data.fnDraw();//recargar los datos del datatable
                                notify_block('Eliminar referencia', 'La referencia se eliminó satisfactoriamente', '', 'success');
                            } else {
                                mensaje_center('Eliminar referencia', 'Error', 'Error al eliminar la referencia. Intente más tarde.', 'error');
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

        $("#btn_actualiza").click(function () {
            dt_data.fnDraw();
        });

        function clearForm() {
            $('#form_modifica_ref #lib_clave').val('');
            $('#form_modifica_ref #titulo_referencia_modifica').val('');
            $('#form_modifica_ref #autores_referencia_modifica').val('');
            $('#form_modifica_ref #autores_referencia_modifica').val('');
            $('#form_modifica_ref #anio_referencia_modifica').val('');
            $('#form_modifica_ref #ciudad_referencia_modifica').val('');
            $('#form_modifica_ref #editorial_referencia_modifica').val('');
            $('#form_modifica_ref #descripcion_libro_referencia_modifica').val('');
            $('#form_modifica_ref #titulo_artticulo_revista_referencia_modifica').val('');
            $('#form_modifica_ref #autores_revista_referencia_modifica').val('');
            $('#form_modifica_ref #nombre_revista_referencia_modifica').val('');
            $('#form_modifica_ref #paginas_revista_referencia_modifica').val('');
            $('#form_modifica_ref #anio_revista_referencia_modifica').val('');
            $('#form_modifica_ref #editorial_revista_referencia_modifica').val('');
            $('#form_modifica_ref #descripcion_revista_referencia_modifica').val('');
            $('#form_modifica_ref #titulo_articulo_periodico_referencia_modifica').val('');
            $('#form_modifica_ref #autores_periodico_referencia_modifica').val('');
            $('#form_modifica_ref #titulo_periodico_referencia_modifica').val('');
            $('#form_modifica_ref #fecha_periodico_referencia_modifica').val('');
            $('#form_modifica_ref #paginas_priodico_referencia_modifica').val('');
            $('#form_modifica_ref #descripcion_periodico_referencia_modifica').val('');
            $('#form_modifica_ref #nombre_sitio_web_referencia_modifica').val('');
            $('#form_modifica_ref #autores_sitio_referencia_modifica').val('');
            $('#form_modifica_ref #fecha_sitio_referencia_modifica').val('');
            $('#form_modifica_ref #url_sitio_referencia_modifica').val('');
            $('#form_modifica_ref #descripcion_sitio_referencia_modifica').val('');
        }
    </script>

<?php } ?>

