<script>
    $.blockUI({
        message: '<img src="./images/cargando.gif">'
    });
</script>
<style>
    body {
        padding-top: 0px !important;
    }

    div#Vopciones .row {
        margin-top: 5px;
    }

    #div_panel1 {
        margin-bottom: 20px;
        background-color: #f8fdff;
        padding: 10px;
        border: 2px solid #beddf7;
        border-radius: 6px;
    }

    div#panel_botones button {
        margin-left: 8px;
    }

    .panelrea {
        margin-top: 20px;
    }

    .rectivo_line {
        margin-top: 5px;
        border: 1px dotted #DDDDDD;
        border-left: 0px;
        border-right: 0px;
    }

    .tab-content {
        float: left;
        width: 100%;
        border: 2px solid #ddd;
        border-top: 0px;
        background-color: #FAFAFA;
    }

    div#mostrarAutor .col-md-7 {
        margin-bottom: 10px;
    }

    div#mostrarLibro .col-md-7 {
        margin-bottom: 10px;
    }

    /* css navbar */
    .nav-tabs>li.active>a {
        color: #555;
        cursor: default;
        background-color: #FAFAFA;
        border: 2px solid #ddd !important;
        border-bottom-color: transparent !important;
    }

    .nav-tabs {
        border-bottom: 2px solid #ddd !important;
    }

    .nav-tabs>li {
        margin-bottom: -2px !important;
    }

    .nav-tabs>li>a:hover {
        margin-right: 2px;
        line-height: 1.42857143;
        border: 1px solid #ABABAB;
        border-radius: 4px 4px 0 0;
        background-color: #D9D9DA;
        color: #898989;
        border-bottom: 0px;
        margin-bottom: 0px;
    }

    .nav-tabs>li>a {
        margin-right: 2px;
        line-height: 1.42857143;
        border: 1px solid #E2E1E1;
        border-radius: 4px 4px 0 0;
        background-color: #FAFAFA;
        color: #A1A1A1;
        border-bottom: 0px;
        margin-bottom: 0px;
    }

    #cap_reactivo {
        margin-right: -2px;
    }

    #caso {
        margin-right: -3px;
    }

    .line2round {
        border: 2px solid #ddd;
        margin-top: 10px;
        padding-bottom: 10px;
    }

    #sinCaso .row {
        margin-left: 0px;
        margin-right: 0px;
    }

    .rowconespacios {
        margin-left: 0px;
        margin-right: 0px;
    }

    .rectivo_line .col-md-11 {
        padding-left: 0px;
    }

    .titulo_subopc {
        background-color: #DFDFDF;
        font-size: 16px;
        padding: 7px;
        border: 2px solid #BDB8B8;
        border-left: none;
        border-right: none;
        margin-bottom: 10px;
        margin-left: -10px;
        margin-right: -10px;
    }

    .dtcontent {
        padding: 10px;
    }

    .letter_min {
        font-size: 11px;
        margin-top: 10px;
    }

    ul#lista li div {
        padding-left: 10px;
    }

    div#aciertos {
        padding: 6px;
    }

    div#Vopciones .col-md-11 {
        padding-left: 0px;
    }

    div#Vopciones .col-md-1 {
        text-align: right;
    }

    div#vistaPreliminar {
        margin-top: 10px;
    }

    div#resultados_busqueda_rectivo {
        margin-top: 25px;
    }

    .table td button {
        padding: 2px;
        padding-left: 10px;
        padding-right: 10px;
        font-size: 12px;
    }

    .table>thead>tr>th,
    .table>tbody>tr>th,
    .table>tfoot>tr>th,
    .table>thead>tr>td,
    .table>tbody>tr>td,
    .table>tfoot>tr>td {
        padding: 3px;
    }

    table.dataTable tr.odd td.sorting_1 {
        background-color: #E6F5FD;
    }

    table.dataTable tr.even td.sorting_1 {
        background-color: #F4F9FD;
    }

    .table {
        font-size: 12px;
    }

    .chk_sel_row {
        border-bottom: 2px solid #BDB8B8;
        padding-bottom: 4px;
        margin-top: -6px;
    }

    .label_fun label {
        padding-left: 13px;
        background-color: rgb(228, 228, 228);
        margin-left: 20px;
        width: 19%;
        border: 1px dotted gray;
        padding-top: 2px;
        padding-bottom: 2px;
        border-radius: 4px;
    }

    .modal {
        overflow: auto;
    }

    /* css para subir archivos multimedia */
    .fileinput-button {
        font-size: 12px;
        padding: 6px;
    }

    .files {
        min-height: 100px;
        background-color: #F0F0F0;
        border-radius: 4px;
        border: 1px dotted #A1A1A1;
        margin-right: 4px;
        padding: 6px;
    }

    .files canvas {
        border: 1px solid #ADADAD;
        border-radius: 4px;
        float: left;
    }

    .files button {
        float: left;
        margin-left: 6px;
        font-size: 12px;
        width: 44px;
        padding-left: 2px;
        padding-right: 2px;
    }

    .opcresp_render {
        border: 1px dotted #D7D4D4;
        background-color: #F4F4F4;
        margin-bottom: 0px;
        margin-left: -5px;
        margin-right: -5px;
        padding: 1px;
    }

    .media_opcresp_render img {
        border: 1px solid #ADADAD;
        border-radius: 4px;
        width: 100px;
        height: 100px;
    }

    /* estilo para arbol */
    .padre {
        margin-bottom: 1px;
        background-color: #e1e1e1;
        border: 1px solid #f5f5f5;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);
        -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);
    }

    .hijo {
        margin-bottom: 1px;
        background-color: #f5f5f5;
        border: 1px solid #e3e3e3;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);
        -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);
    }

    .opcresp_div {
        margin-bottom: 10px;
    }

    #area_opciones .progress {
        padding: 0px;
        margin-top: 10px;
        margin-left: 10px;
    }

    #area_opciones audio,
    #area_opciones video {
        width: 100%;
    }

    .media_opcresp_render video {
        width: 80%;
    }

    .opcresp_div {
        background-color: #F2FBFF;
        border: 1px solid #D4EEF4;
        border-radius: 4px;
        padding: 3px;
    }

    .edt_opcres {
        background-color: #F2F4E0;
        border: 2px solid #CED0B7;
        border-radius: 4px;
        padding: 3px;
    }

    .icn {
        position: initial !important;
    }

    .cas_media_div {
        border: 1px solid #EAEAEA;
        float: left;
        background-color: #fafafa;
        padding: 10px;
        width: 100%;
    }

    .responsive-accordion-panel {
        min-height: 190px;
        padding: 10px !important;
    }

    #caso audio,
    #caso video {
        width: 100%;
    }

    #caso .files span {
        font-size: 9px;
    }

    span.info_min {
        font-size: 8px;
    }

    div#hd_media_casos_files {
        padding-left: 0px;
        padding-right: 0px;
    }

    #hd_media_casos_files .files span {
        padding-left: 10px;
    }

    #opciones label {
        width: 100%;
    }

    /** css para vista previa */
    div#div_vp_caso {
        margin-top: 10px;
        margin-bottom: 20px;
    }

    .caso_data {
        margin-bottom: 10px;
    }

    .marco_dot {
        border: 1px dotted #D7D4D4;
        background-color: #F4F4F4;
        padding: 2px;
        padding-right: 10px;
        padding-left: 10px
    }

    div#vp_cas_tit {
        font-weight: bolder;
        font-size: 16px;
    }

    div#vp_cas_con {
        font-size: 12px;
        text-align: justify !important;
    }

    div#div_vp_opcresp {
        margin-top: 10px;
    }

    div#div_vp_opcresp .opcresp_render:hover {
        background-color: #EDEDED;
        cursor: pointer;
    }

    div#div_vp_opcresp label {
        width: 100%;
    }

    .S_respuesta {
        background-color: #BFD2C5 !important;
        border-color: #3E684B !important;
        color: #3E684B !important;
    }

    .N_respuesta {
        background-color: #D8C5C5 !important;
        border-color: #884C4C !important;
        color: #884C4C !important;
    }

    .alert_respuestas i {
        font-size: 30px;
        margin-right: 10px;
        margin-top: -8px;
    }

    #cas_media .imagen img {
        width: 150px;
        height: auto;
        padding-right: 20px;
    }

    .comments_rea {
        background-color: #9EC8DF;
        border: 1px dotted #0C3B54;
        overflow: auto;
        padding: 6px;
        margin-bottom: 10px;
        border-radius: 4px;
        color: #0C3B54;
    }

    .comments_rea i {
        font-size: 35px;
        margin-top: -3px;
    }

    div#cas_contenido {
        height: auto;
    }

    div#btn_group_st button {
        padding-left: 4px;
        padding-right: 4px;
        margin-bottom: 8px;
    }

    #listaPadre li {
        cursor: pointer;
    }

    .seleccionado {
        background: #d3d3d3;
        color: #555;
        border-radius: 4px;
        margin-top: 2px;
    }

    .sinPlan {
        border-color: rgb(197, 70, 55);
    }

    .page_navigation,
    .alt_page_navigation {
        padding-bottom: 5%;
    }

    .page_navigation a,
    .alt_page_navigation a {
        padding: 3px 5px;
        margin: 2px;
        color: white;
        text-decoration: none;
        float: left;
        font-family: Tahoma;
        font-size: 12px;
        background-color: #3c8dbcdb;
    }

    .active_page {
        background-color: white !important;
        color: black !important;
    }

    .listaPaginada,
    .alt_content {
        color: black;
    }

    .listaPaginada li,
    .alt_content li,
    .listaPaginada>p {
        padding: 5px
    }

    .containerNotas,
    #jstree_demo_div {
        margin-top: 2%;
    }

    #textoConocimiento {
        border: 1px;
        border-style: dotted;
        border-color: #337AB7;
        margin: 2% auto 2% auto;
        padding: 2%;
    }
    input.opcresp {width: 31px;}
</style>
<link rel="stylesheet" href="./css/jquery.fileupload-ui.css">
<link rel="stylesheet" href="./css/jquery.fileupload-ui.css">
<link rel="stylesheet" href="./css/jquery.fileupload-ui-noscript.css">
<link rel="stylesheet" href="./js/fileupload/css/style.css">
<link rel="stylesheet" href="./js/fileupload/css/jquery.fileupload.css">
<script src="./js/fileupload/js/vendor/jquery.ui.widget.js"></script>
<script src="./js/fileupload/js/external/load-image.all.min.js"></script>
<script src="./js/fileupload/js/external/canvas-to-blob.min.js"></script>
<script src="./js/fileupload/js/jquery.iframe-transport.js"></script>
<script src="./js/fileupload/js/jquery.fileupload.js"></script>
<script src="./js/fileupload/js/jquery.fileupload-process.js"></script>
<script src="./js/fileupload/js/jquery.fileupload-image.js"></script>
<script src="./js/fileupload/js/jquery.fileupload-audio.js"></script>
<script src="./js/fileupload/js/jquery.fileupload-video.js"></script>
<script src="./js/fileupload/js/jquery.fileupload-validate.js"></script>
<!--<script language="javascript" type="text/javascript" src="./js/tiny_mce/jquery.tinymce.js"></script-->
<script type="text/javascript" src="./js/modal_bootstrap_extend/js/bootstrap-dialog.js"></script>
<script src="./js/tinymce/js/tinymce/jquery.tinymce.min.js" type="text/javascript"></script>
<script src="./js/tinymce/js/tinymce/tinymce.min.js" type="text/javascript"></script>
<script type="text/javascript" src="./js/reactivo.js"></script>

<script type="text/javascript" language="javascript" src="./js/DataTables/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="./js/DataTables/js/dataTables.bootstrap.min.js"></script>
<script src="./js/pagination/jquery.paginate.js" type="text/javascript"></script>
<link rel="stylesheet" href="./js/DataTables/css/dataTables.bootstrap.min.css" type="text/css" media="screen, projection">
<link rel="stylesheet" href="./css/responsive-table.css" type="text/css" media="screen, projection">
<script src="./plugins/jsTree/jstree.js" type="text/javascript"></script>
<link href="./plugins/jsTree/themes/default/style.css" rel="stylesheet" type="text/css" />
<!--REACTIVO-->
<div class="">
    <!---------------------------------------DATOS GENERALES-------------------------------------->
    <div id="div_panel1">
        <div class="rowsinespacios">
            <input type="hidden" name="rea_clave" id="rea_clave" value="" />
            <input type="hidden" name="id_habilidad" id="id_habilidad" value="" />
            <input type="hidden" name="texto_habilidad" id="texto_habilidad" value="" />
            <input type="hidden" name="id_padre_habilidad" id="id_padre_habilidad" value="" />
            <input type="hidden" name="texto_padre_habilidad" id="texto_padre_habilidad" value="" />
            <div class="col-md-3"> Estado:
                <div class="btn-group" data-toggle="buttons-radio" id="btn_group_st">
                    <button type="button" class="btn btn-default btn-danger active" title="Capturando" id="estado_c">En captura</button>
                    <button type="button" class="btn btn-default" title="Pendiente" id="estado_r">Pendiente</button>
                    <button type="button" class="btn btn-default " title="Revisado" id="estado_a">Revisado</button>
                </div>
            </div>
            <div class="col-md-9">
                <label class="col-md-1" for="pla_clave">Plan: </label>
                <div class="col-md-2 form-group">
                    <input type="text" class="form-control" placeholder="Clave" name="pla_clave" id="pla_clave" disabled />
                </div>
                <div class="input-group">
                    <div id="contenedorPadres" class="dropdown">
                        <button style="text-align: left; " class=" form-control btn btn-default dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Nombre del plan <span class="caret"></span></button>
                        <ul id="listaPadre" class="dropdown-menu dropdown-menu-left" style="z-index:100000;">
                            <li class="elementoPadre" id="one" style="padding-left:20%;"></li>
                        </ul>
                    </div>
                    <!--<input type="text" class="form-control" placeholder="Nombre del Plan" name="pla_nombre" id="pla_nombre" disabled>-->
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-default" name="btn_plan" id="btn_plan" title="Buscador Plan de Estudio"><span class="caret"></span> Seleccionar Plan</button>
                    </div><!-- /btn-group -->
                </div><!-- /input-group -->
                <label id="msgpla_clave" class="label-error" style="display:none">Capture Plan de Estudio.</label>
            </div>
        </div>
        <div class="row">
            <div id="panel_botones" class="col-md-12">
                <button class="btn btn-primary col-md-2" title="Buscar Reactivo" onclick="muestraBusquedaReactivo(true)" id="btn_reactivo"><i class="fa fa-search"></i> Buscar </button>
                <button class="btn btn-success col-md-2" title="Nuevo Reactivo" onclick="limpia_reactivo();"><i class="fa fa-plus"></i> Nuevo</button>
                <?php if (isset($anterior) && $anterior != 0 && ($loadreactivo * 1 != 0)) { ?>
                    <button id="btn_anteriorReactivo" class="btn btn-default col-md-2" title="Antetior Reactivo" onclick="redirect_to('reactivo/update/<?php echo @$anterior; ?>');"><i class="fa fa-backward"></i> Anterior</button>
                <?php } ?>
                <?php if (isset($siguiente) && $siguiente != 0 && ($loadreactivo * 1 != 0)) { ?>
                    <button id="btn_siguienteReactivo" class="btn btn-default col-md-2" title="Siguiente Reactivo" onclick="redirect_to('reactivo/update/<?php echo @$siguiente; ?>');"> Siguiente <i class="fa fa-forward"></i></button>
                <?php } ?>
                <button class="btn btn-primary col-md-2" id="btn_guarda_rea" title="Guarda Reactivo" onclick="validarReactivo();"><i class="fa fa-save"></i> Guardar </button>
            </div>
        </div>
    </div>
    <!-------CONTAINER PLAN-------->
    <div class="div_contenedor row rowconespacios" id="mostrarPlan" style="display:none">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">PLAN DE ESTUDIOS <button class="btn btn_closepanel btn-warning" style="margin-top: -3px;" onclick="cierraBusquedaPlan();"><i class="fa fa-close"></i></button></h3>
            </div>
            <div class="panel-body">
                <!---buscador plan ------------------------------------>
                <div class="col-md-6 " id="div_busca_plan_datatables">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Seleccionar plan de estudios por búsqueda</h3>
                        </div>
                        <div class="panel-body">
                            <div class="input-group">
                                <input type="text" name="cadenaPlan" class="form-control" id="cadenaPlan" placeholder="Plan de estudios a buscar">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default " onclick="buscarPlan(cadenaPlan.value);"><i class="fa fa-search"></i> &nbsp;&nbsp;&nbsp;&nbsp;Buscar&nbsp;&nbsp;&nbsp;&nbsp;</button>
                                </div><!-- /btn-group -->
                            </div><!-- /input-group -->
                            <div class="letter_min">
                                <table id="datosPlan" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered display">
                                    <thead>
                                        <tr>
                                            <th>Clave</th>
                                            <th>Nombre</th>
                                            <th>Descripción</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!---buscador plan------------------------------------>
                <!-- arbol jerarquía -->
                <div class="col-md-5" style="overflow:scroll; height: 450px;">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Seleccionar plan de estudios por jerarquía</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row" align="center">
                                <h5>JERARQUÍA</h5>
                            </div>
                            <div class="row" id="arbol">
                                <ul class="nav" id="lista">
                                    <?php
                                    if (isset($lista)) {
                                        foreach ($lista as $lista) { // muestra la base de plan de estudio.
                                            if ($lista['hij'] > 0) {
                                                echo '<li><div class="container-fluid"><div onclick="desplegarHijos(' . $lista['id'] . ')" class="row-fluid padre"><i id="i' . $lista['id'] . '" class="icon-chevron-up"></i><label class="span11">' . $lista['nom'] . '</label></div><div class="row-fluid" id="' . $lista['id'] . '"></div></div></li>';
                                            } else {
                                                echo '<li><div class="container-fluid"><div onclick="desplegarHijos(' . $lista['id'] . ')" class="row-fluid padre"><i id="i' . $lista['id'] . '" class="icon-chevron-up"></i><label class="span11">' . $lista['nom'] . '</label></div><div class="row-fluid" id="' . $lista['id'] . '"></div></div></li>';
                                            }
                                        }
                                    }
                                    ?>
                                </ul>

                            </div>
                        </div>

                    </div>
                </div><!-- arbol jerarquía -->
            </div>
        </div>
    </div>
    <!-------  CONTAINER REACTIVO  -------->
    <div class="div_contenedor" id="mostrarReactivo" style="display:none">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">BÚSCAR REACTIVO</h3><button class="btn btn_closepanel btn-warning" onclick="muestraBusquedaReactivo(false);"><i class="fa fa-close"></i></button>
                </div>
                <div class="panel-body">
                    <div role="form">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="buscar_estado">Estado rectivo:</label>
                                    <select name="buscar_estado" id="buscar_estado" class="form-control">
                                        <option selected="selected" value="">Todos</option>
                                        <option value="C">C</option>
                                        <option value="I">I</option>
                                        <option value="A">A</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="buscar_tipo">Tipo de reactivo:</label>
                                    <select name="buscar_tipo" id="buscar_tipo" class="col-md-2 form-control">
                                        <?php
                                        if (!empty($tipos)) {
                                            $btipos = $tipos;
                                            foreach ($btipos as $btipos) {
                                                echo '<option value="' . $btipos['clave'] . '">' . $btipos['nombre'] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <label>Fecha alta (especifique rango):</label><br>
                                <div class="col-md-6">
                                    <input class="form-control" type="date" id="rea_date1" />
                                </div>
                                <div class="col-md-6">
                                    <input class="col-md-2 form-control" type="date" id="rea_date2" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="buscar_usuario">Usuario agregó:</label>
                                    <input type="text" id="buscar_usuario" class="form-control" placeholder="Usuario" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" id="textReactivo" class="form-control" placeholder="Texto a buscar">
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-primary col-md-4" onclick="buscarReactivo();"><i class="fa fa-search fa fa-white"></i> Buscar </button>
                            </div>
                        </div>
                        <!-- resultados busqueda reactivo-->
                        <div class="row rowconespacios" id="resultados_busqueda_rectivo">
                            <div class="col-md-12">
                                <table id="datosReactivo" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th align="center">Clave</th>
                                            <th>Contenido</th>
                                            <th>Estado</th>
                                            <th>Tipo</th>
                                            <th>Opciones</th>
                                            <th>Calificar</th>
                                            <th>Fecha</th>
                                            <th>Caso</th>
                                            <th>Plan</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div> <!-- resultados busqueda reactivo-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="div_contenedor">
        <!--container tabs-->
        <div>
            <!--tabbable  ondblclick="tabbable"-->
            <ul class="nav nav-tabs" id="menutabs">
                <li class="active"><a href="#datosVisuales" data-toggle="pill">Datos Visuales</a></li>
                <li><a href="#referencia" data-toggle="pill">Referencia</a></li>
                <li id="vista_preliminar" style=" display: none;"><a href="#vistaPreliminar" data-toggle="pill">Vista Preliminar</a></li>
                <li><a href="#notas" data-toggle="pill">Notas</a></li>
            </ul>
            <div class="tab-content">
                <!--Datos Visuales-->
                <div class="tab-pane active" id="datosVisuales">
                    <div class="col-md-7 panelrea" id="cap_reactivo">
                        <!--COLUMNA DATOS DE REACTIVO -->
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">Captura reactivo</h3>
                            </div>
                            <div class="panel-body">
                                <div id="reactivo">
                                    <label for="sec_rea_contenido">Reactivo: </label>
                                    <div class="" align="center" id="sec_rea_contenido">
                                        <!-- ("EDITOR TEXTO REACTIVO") -->
                                        <textarea name="rea_contenido" id="rea_contenido" class="textareas" style="width:100% !important;"></textarea>
                                        <label id="msgrea_contenido" class="label-error" style="display:none">Llene campo de reactivo.</label>
                                    </div>
                                    <br />
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="clvreactivo">Clave (opcional):<span class="info_min">Identificar reactivos fácilmente</span></label>
                                            <input type="text" name="clvreactivo" id="clvreactivo" class="form-control" />

                                        </div>
                                        <div class="col-md-6">
                                            <label for="modocalif">Modo de Calificar:</label>
                                            <select name="modocalif" id="modocalif" class="form-control">
                                                <option value="unico" selected="selected" title="Una sola Respuesta">Único</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- ("TIPO OPCION DE RESPUESTA") -->
                                    <div class="row ">
                                        <div class="col-md-4" id="sec_tipo">
                                            <label for="tipo_reactivo">Tipo de Reactivo: </label>
                                            <select name="tipo_reactivo" id="tipo_reactivo" class="form-control">
                                                <option value="0" selected="selected">Seleccionar</option>
                                                <?php
                                                if (!empty($tipos)) {
                                                    foreach ($tipos as $tipos) {
                                                        echo '<option value="' . $tipos['clave'] . '">' . $tipos['nombre'] . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <label id="msgtipo_reactivo" class="label-error" style="display:none">Seleccione tipo de reactivo.</label>
                                        </div>
                                        <div class="col-md-4">
                                        <label for="clvreactivo">Puntos:</label>
                                            <input type="number" name="puntos_reactivo" id="puntos_reactivo" class="form-control" />
                                        </div>
                                        <br>
                                        <div class="col-md-4" align="right"><button type="button" class="btn btn-success" id="btn_opciones" title="Agregar Opciones de Respuesta al Reactivo"><i class="fa fa-check-square-o"></i> Capturar opciones de respuesta</button> </div>
                                    </div>
                                    <div id="containerMateriasCompetencias" style="display:none">
                                        <div class="row ">
                                            <div class="col-md-4">
                                                <label for="materia_reactivo">Materia: </label>
                                                <select name="materia_reactivo" id="materia_reactivo" class="form-control">
                                                    <option value="0" selected="selected">Seleccionar</option>
                                                    <?php
                                                    if (!empty($materias)) {
                                                        foreach ($materias as $materia) {
                                                            echo '<option value="' . $materia['mat_blo_id'] . '">' . $materia['mat_blo_nombre'] . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>

                                            </div>
                                            <div class="col-md-4">
                                                <label for="competencia_materia">Competencia: </label>
                                                <select name="competencia_materia" id="competencia_materia" class="form-control">
                                                    <option value="0" selected="selected">Seleccionar</option>
                                                    <?php
                                                    if (!empty($competencias)) {
                                                        foreach ($competencias as $competencia) {
                                                            echo "<option title='" . $competencia['comp_mat_descripcion'] . "' value='" . $competencia['comp_mat_id'] . "'>" . $competencia['comp_mat_nombre'] . "</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4" align="right">
                                                <label for="bloque_competencia">Bloque: </label>
                                                <select name="bloque_competencia" id="bloque_competencia" class="form-control">
                                                    <option value="0" selected="selected">Seleccionar</option>
                                                    <?php
                                                    if (!empty($bloques)) {
                                                        foreach ($bloques as $bloque) {
                                                            echo "<option title='" . $bloque['bloque_comp_proposito'] . "' value='" . $bloque['bloque_comp_id'] . "'>" . $bloque['bloque_comp_nombre'] . "</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="textoConocimiento"></div>

                                    </div>

                                    <h3>Habilidad</h3>
                                    <div id="jstree_demo_div"></div>
                                    <!--OPCIONES-->
                                    <div class="div_contenedor col-md-12 line2round">
                                        <div class="row" align="center">
                                            <h4>Opciones de Respuesta</h4>
                                        </div>
                                        <div class="div_contenedor" id="opciones" data-tipomedia=""></div>
                                    </div>
                                </div>
                            </div>
                            <!--/body panel datos reactivo-->
                        </div>
                        <!--/panel datos reactivo-->
                    </div>
                    <!------final columna 1 DATOS REACTIVO--->

                    <!-------------COLUMNA 2 ("CASO")-------------->
                    <div class="col-md-5 panelrea" id="caso" data-add="0">
                        <!--COLUMNA DATOS DE CASO -->
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">Caso</h3>
                            </div>
                            <div class="panel-body">
                                <?php
                                if (isset($casos_html)) {
                                    echo $casos_html;
                                }
                                ?>
                            </div>
                            <!--/body panel datos reactivo-->
                        </div>
                        <!--/panel datos reactivo-->
                    </div>
                </div>
                <!--/Datos Visuales-->
                <!-----------------------REFERENCIAS----------------------------->
                <?php
                if (isset($referencias_html)) {
                    echo $referencias_html;
                }
                ?>
                <!----------Vista Prelimiar-------------->
                <div class="tab-pane" id="vistaPreliminar">
                    <div class="">
                        <div class="col-md-8">
                            <div class=" alert alert-info ">
                                <div class="col-m1" id="Vintrucciones"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <!--button onclick="vistaPreliminar(rea_clave.value);" class="btn btn-primary col-md-10 col-md-offset-1">Reiniciar</button-->
                        </div>
                    </div>
                    <div class="">
                        <div id="vp_cas_ins" class="caso_data"></div>
                        <div id="vp_contenido" class="col-md-12 marco_dot"> </div>
                    </div>
                    <div class="">
                        <div class="col-md-5">
                            <div class="" id="div_vp_opcresp">

                            </div>
                        </div>
                        <div class="col-md-6 marco_dot" id="div_vp_caso">
                            <div id="vp_cas_tit" class="caso_data"></div>
                            <div id="vp_cas_con" class="caso_data"></div>
                            <div id="vp_cas_img" class="caso_data"></div>
                            <div id="vp_cas_aud" class="caso_data"></div>
                            <div id="vp_cas_vid" class="caso_data"></div>
                        </div>
                    </div>
                </div>
                <!---NOTAS--->
                <div class="tab-pane" id="notas">
                    <div class="row containerNotas">
                        <div class="col-md-6">
                            <div class="box box-primary box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title">
                                        <p>Este reactivo se a ocupado en:</p>
                                    </h3>
                                    <div class="box-tools pull-right">
                                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    </div><!-- /.box-tools -->
                                </div><!-- /.box-header -->
                                <div class="box-body" id="paginateNotas">
                                    <div class="page_navigation"></div>
                                    <ul class="listaPaginada list-group">
                                        <?php
                                        if (isset($reactivo_en_examen)) {
                                            foreach ($reactivo_en_examen as $valRow) {
                                                ?>
                                                <li class="list-group-item">
                                                    <p><?php echo $valRow['exa_nombre']; ?></p>
                                                </li>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div><!-- /.box -->
                        </div>
                        <div class="col-md-6">
                            <div class="box box-primary box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title">
                                        <p>Notas adicionales:</p>
                                    </h3>
                                    <div class="box-tools pull-right">
                                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    </div><!-- /.box-tools -->
                                </div><!-- /.box-header -->
                                <div class="box-body" id="paginateNotas">
                                    <textarea name="textoNotas" id="textoNotas" class="textAreaNotas" style="width:100% !important;"></textarea>
                                </div><!-- /.box -->
                            </div>
                        </div>
                    </div>
                </div>
                <!--/tab-content-->
            </div>
            <!--tabbable-->
        </div>
        <!--/container tabs-->
    </div>
    <!--/container-->

    <!------------------DIALOGOS PARA LA CAPTURA DE OPCIONES DE RESPUESTA----------------------------->
    <!-------------OPCIONES------------>
    <!-- Modal -->
    <div id="dialogo_opciones" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Agregar Opciones de respuesta</h4>
                </div>
                <div class="modal-body">
                    <div role="form">
                        <div class="row titulo_subopc" id="tipo_opcion1" align="center"></div>
                        <div class="row checkbox chk_sel_row label_fun">
                            <label><input type="radio" name="tipo_opcresp" value="txt" checked="checked"> Modo texto</label>
                            <!--label><input type="radio" name="tipo_opcresp" value="img"> Imagen</label>
                            <label><input type="radio" name="tipo_opcresp" value="aud"> Audio</label>
                            <label><input type="radio" name="tipo_opcresp" value="vid"> Video</label-->
                        </div>
                        <div class="div_opcres form-group" id="txt">
                            <div id="area" class="form-group">

                                <div class="rowsinespacios" align="center"><textarea id="contenido" class="textareas mceEditor" style="width:100% !important;"></textarea></div>
                                <div class="rowsinespacios" align="center">

                                </div>
                                <input type="text" id="num_opcion" class="form-control" disabled="disabled" style="display:none" />
                                <input type="text" id="edita_opcion" class="form-control" disabled="disabled" style="display:none" />
                            </div>
                            <div class="form-group">
                                <button id="btn_upd_opcresp" class="btn btn-primary" data-modid="-1" onclick="insertarMultiple();"><i class="fa fa-file-picture-o"></i> <span>Agregar</span> Opción de Respuesta</button>
                            </div>
                        </div>
                        <div class="div_opcres form-group" id="img">
                            <div class="form-group">
                                <button class="add_media_opc btn btn-primary" data-tipoopc="img"><i class="fa fa-file-picture-o"></i> Agregar opción de respuesta</button>
                            </div>
                        </div>
                        <div class="div_opcres form-group" id="aud">
                            <div class="form-group">
                                <button class="add_media_opc btn btn-primary" data-tipoopc="aud"><i class="fa fa-file-audio-o"></i> Agregar opción de respuesta</button>
                            </div>
                        </div>
                        <div class="div_opcres form-group" id="vid">
                            <div class="form-group">
                                <button class="add_media_opc btn btn-primary" data-tipoopc="vid"><i class="fa fa-file-video-o"></i> Agregar opción de respuesta</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="panel panel-default">
                                <div class="panel-heading">Opciones de respuesta</div>
                                <div id="area_opciones" class="panel-body" data-numopc="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" onclick="cancelar_opciones_respuesta();">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guarda_opciones_respuesta();">Finalizar</button>
                </div>
            </div>
        </div>
    </div>
    <link href="./js/accordion_smooth/css/normalize.css" rel="stylesheet" type="text/css" media="all" />
    <link href="./js/accordion_smooth/css/responsive-accordion.css" rel="stylesheet" type="text/css" media="all" />
    <script src="./js/accordion_smooth/js/smoothscroll.min.js" type="text/javascript"></script>
    <script src="./js/accordion_smooth/js/responsive-accordion.min.js" type="text/javascript"></script>
    <script>
        $.unblockUI({
            onUnblock: function() {
                <?php
                $clv_sess = $this->config->item('clv_sess');
                $reactivo = $this->session->userdata('id_reativo_tmp' . $clv_sess);
                $user_id = $this->session->userdata('user_id' . $clv_sess);
                if ($loadreactivo != FALSE && $loadreactivo != '' && $loadreactivo != '0') {
                    $this->session->set_userdata('id_reativo_tmp' . $clv_sess, $loadreactivo);
                    ?>
                    llenarReactivo(<?php echo $loadreactivo; ?>);
                    window.onload = function() {
                        tinyMCE_OnInit();
                        $('#estado_a').attr('disabled', false);
                        $('#cat_reactivo').removeAttr('disabled');
                        $('#materia_reactivo').removeAttr('disabled');
                        $('#competencia_materia').removeAttr('disabled');
                        $('#bloque_competencia').removeAttr('disabled');
                        $("#bloque_competencia").val(idBloqueRea);
                        $("#bloque_competencia").change();


                    };
                <?php
                }

                if ($plan != FALSE && $plan != '' && $plan != '0') {
                    $arr = explode('@_@', $plan);
                    echo "seleccionarPlan(" . $arr[0] . ",'" . $arr[1] . "');";
                }

                if ($plan_id != FALSE && $plan_id != '' && $plan_id != '0') {
                    echo "seleccionarPlan(" . $plan_id . ",'" . $plan_nombre . "');";
                }


                ?>
            }

        });

        setTimeout(function() {
            $('#estado_a').removeAttr('disabled');

        }, 1000);
        select_autor(usuario);

        $(document).ready(function() {
            $('#paginateNotas').pajinate({
                items_per_page: 5,
                item_container_id: '.listaPaginada',
                nav_panel_id: '.page_navigation'

            });


            var habilidades = '<?php echo $habilidades ?>';
            //console.log(eval(habilidades));

            $('#jstree_demo_div').jstree({
                'core': {
                    'multiple': false,
                    'data': eval('[' + habilidades + ']')

                },
                "checkbox": {
                    "keep_selected_style": false
                },
                "plugins": ["checkbox"]
            });
        });
        $('#jstree_demo_div').on('changed.jstree', function(e, data) {
            $('#id_habilidad').val(data.node.original.id);
            $('#texto_habilidad').val(data.node.original.text);
            $('#id_padre_habilidad').val(data.node.original.parent);
            $('#texto_padre_habilidad').val(data.node.original.textoPadre);
        })
    </script>