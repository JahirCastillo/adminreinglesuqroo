<script>
    $.blockUI({message: '<img src="./images/cargando.gif">'});
</script>
<style>
    body{padding-top: 0px !important;}
    div#Vopciones .row { margin-top: 5px; }
    #div_panel1 {
        margin-bottom: 20px;
        background-color: #f8fdff;
        padding: 10px;
        border: 2px solid #beddf7;
        border-radius: 6px;
    }
    div#panel_botones button { margin-left: 8px;}
    .panelrea{ margin-top: 20px;}
    .rectivo_line{margin-top: 5px; border: 1px dotted #DDDDDD; border-left: 0px;border-right: 0px;}
    .tab-content{ float: left; width: 100%; border: 2px solid #ddd; border-top: 0px; background-color: #FAFAFA; }
    div#mostrarAutor .col-md-7 { margin-bottom: 10px; }
    div#mostrarLibro .col-md-7 { margin-bottom: 10px; }
    /* css navbar */
    .nav-tabs>li.active>a {
        color: #555;
        cursor: default;
        background-color: #FAFAFA;
        border: 2px solid #ddd !important;
        border-bottom-color: transparent !important;
    }
    .nav-tabs { border-bottom: 2px solid #ddd !important; }
    .nav-tabs>li { margin-bottom: -2px !important;}
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

    #cap_reactivo{margin-right: -2px;}
    #caso{margin-right: -3px;}
    .line2round{ border: 2px solid #ddd; margin-top: 10px; padding-bottom: 10px;}
    #sinCaso .row{ margin-left: 0px; margin-right: 0px;}
    .rowconespacios{ margin-left: 0px; margin-right: 0px;}
    .rectivo_line .col-md-11 { padding-left: 0px; }
    .titulo_subopc{
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
    .dtcontent{padding: 10px; }
    .letter_min{ font-size: 11px; margin-top: 10px;}
    ul#lista li div { padding-left: 10px; }
    div#aciertos { padding: 6px; }
    div#Vopciones .col-md-11 {padding-left: 0px; }
    div#Vopciones .col-md-1 { text-align: right; }
    div#vistaPreliminar { margin-top: 10px;}
    div#resultados_busqueda_rectivo {margin-top: 25px;}

    .table td button {padding: 2px; padding-left: 10px; padding-right: 10px; font-size: 12px; }
    .table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td { padding: 3px;}
    table.dataTable tr.odd td.sorting_1 { background-color: #E6F5FD; }
    table.dataTable tr.even td.sorting_1 { background-color: #F4F9FD; }
    .table { font-size: 12px; }
    .chk_sel_row{ border-bottom: 2px solid #BDB8B8; padding-bottom: 4px; margin-top: -6px; }
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
    .modal{overflow: auto;}
    /* css para subir archivos multimedia */
    .fileinput-button { font-size: 12px; padding: 6px; }
    .files {min-height: 100px;background-color: #F0F0F0;border-radius: 4px;border: 1px dotted #A1A1A1;margin-right: 4px;padding: 6px;}
    .files canvas { border: 1px solid #ADADAD; border-radius: 4px; float: left; }
    .files button{ float: left; margin-left: 6px;font-size: 12px;width: 44px;padding-left: 2px; padding-right: 2px; }
    .opcresp_render { border: 1px dotted #D7D4D4; background-color: #F4F4F4; margin-bottom: 0px; margin-left: -5px; margin-right: -5px; padding: 1px; }
    .media_opcresp_render img { border: 1px solid #ADADAD; border-radius: 4px;width: 100px; height: 100px;}

    /* estilo para arbol */
    .padre{margin-bottom: 1px;background-color: #e1e1e1;border: 1px solid #f5f5f5; -webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;-webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05); -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);}
    .hijo{ margin-bottom: 1px;background-color: #f5f5f5;border: 1px solid #e3e3e3;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;-webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);-moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);}

    .opcresp_div {margin-bottom: 10px;}
    #area_opciones .progress{ padding: 0px; margin-top: 10px; margin-left: 10px;}
    #area_opciones audio,#area_opciones video { width: 100%; }
    .media_opcresp_render video { width: 80%; }
    .opcresp_div{ background-color: #F2FBFF; border: 1px solid #D4EEF4; border-radius: 4px; padding: 3px; }
    .edt_opcres{ background-color: #F2F4E0; border: 2px solid #CED0B7; border-radius: 4px; padding: 3px; }
    .icn{position: initial !important;}
    .cas_media_div{border: 1px solid #EAEAEA; float: left; background-color: #fafafa; padding: 10px; width: 100%; }
    .responsive-accordion-panel { min-height: 190px; padding: 10px !important; }
    #caso audio,#caso video { width: 100%; }
    #caso .files span { font-size: 9px;}
    span.info_min { font-size: 8px;}
    div#hd_media_casos_files { padding-left: 0px; padding-right: 0px;}

    #hd_media_casos_files .files span{ padding-left: 10px;}
    #opciones label{ width: 100%;}
    /** css para vista previa */
    div#div_vp_caso { margin-top: 10px; margin-bottom: 20px; }
    .caso_data { margin-bottom: 10px; }
    .marco_dot {border: 1px dotted #D7D4D4;background-color: #F4F4F4;padding: 2px;padding-right: 10px;padding-left: 10px}
    div#vp_cas_tit {font-weight: bolder;font-size: 16px;}
    div#vp_cas_con {font-size: 12px; text-align: justify !important; }
    div#div_vp_opcresp {margin-top: 10px; }
    div#div_vp_opcresp .opcresp_render:hover {background-color: #EDEDED;cursor: pointer;}
    div#div_vp_opcresp label {width: 100%;}
    .S_respuesta {background-color: #BFD2C5 !important;border-color: #3E684B !important;color: #3E684B !important;}
    .N_respuesta{background-color: #D8C5C5 !important;border-color: #884C4C !important;color: #884C4C !important;}
    .alert_respuestas i{font-size: 30px;margin-right: 10px;margin-top: -8px;}
    #cas_media .imagen img{width: 150px;height: auto;padding-right: 20px;}
    .comments_rea {background-color: #9EC8DF;border: 1px dotted #0C3B54;overflow: auto;padding: 6px;margin-bottom: 10px;border-radius: 4px;color: #0C3B54;}
    .comments_rea i{font-size: 35px;margin-top: -3px;}
    div#cas_contenido {
        height: auto;
    }
    div#btn_group_st button {
        padding-left: 4px;
        padding-right: 4px;
        margin-bottom: 8px;
    }
</style>
<!--<script language="javascript" type="text/javascript" src="./js/tiny_mce/jquery.tinymce.js"></script-->
<script type="text/javascript" src="./js/modal_bootstrap_extend/js/bootstrap-dialog.js"></script>
<script src="./js/tinymce/js/tinymce/jquery.tinymce.min.js" type="text/javascript"></script>
<script src="./js/tinymce/js/tinymce/tinymce.min.js" type="text/javascript"></script>
<!--script type="text/javascript" src="./js/reactivo.js"></script-->

<script type="text/javascript" language="javascript" src="./js/DataTables/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="./js/DataTables/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="./js/DataTables/css/dataTables.bootstrap.min.css" type="text/css" media="screen, projection">
<link rel="stylesheet" href="./css/responsive-table.css" type="text/css" media="screen, projection">
<!--REACTIVO-->
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Rectivo</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" name="rea_clave" id="rea_clave" value=""/>
                        <div class="col-md-3"> Estado:
                            <div class="btn-group" data-toggle="buttons-radio" id="btn_group_st">
                                <button type="button" class="btn btn-default btn-danger active" title="Capturando" id="estado_c" >En captura</button>
                                <button type="button" class="btn btn-default" title="Pendiente" id="estado_r" >Pendiente</button>
                                <button type="button" class="btn btn-default " title="Revisado" id="estado_a" >Revisado</button>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <label class="col-md-1" for="pla_clave">Plan: </label>
                            <div class="col-md-2 form-group">
                                <input type="text" class="form-control" placeholder="Clave" name="pla_clave" id="pla_clave" disabled/>
                            </div>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Nombre del Plan" name="pla_nombre" id="pla_nombre" disabled>
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default" name="btn_plan" id="btn_plan" title="Buscador Plan de Estudio"><span class="caret"></span> Seleccionar Plan</button>
                                </div><!-- /btn-group -->
                            </div><!-- /input-group -->
                            <label id="msgpla_clave" class="label-error" style="display:none">Capture Plan de Estudio.</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div id="panel_botones" class="col-md-12">
                        <button class="btn btn-primary col-md-2" title="Buscar Reactivo" onclick="muestraBusquedaReactivo(true)" id="btn_reactivo" ><i class="fa fa-search"></i>  Buscar </button>
                        <button class="btn btn-success col-md-2" title="Nuevo Reactivo" onclick="limpia_reactivo();"><i class="fa fa-plus"></i> Nuevo</button>
                        <?php if (isset($siguiente) && $siguiente != 0 && ($loadreactivo * 1 != 0)) { ?>
                            <button id="btn_siguienteReactivo" class="btn btn-default col-md-2" title="Siguiente Reactivo" onclick="redirect_to('reactivo/update/<?php echo @$siguiente; ?>');"><i class="fa fa-forward"></i> Siguiente</button>
                        <?php } ?>
                        <button class="btn btn-primary col-md-2" id="btn_guarda_rea" title="Guarda Reactivo" onclick="validarReactivo();"><i class="fa fa-save"></i> Guardar </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-------CONTAINER PLAN-------->
<div class="div_contenedor row rowconespacios" id="mostrarPlan" style="display:none" > 
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">PLAN DE ESTUDIOS <button class="btn btn_closepanel btn-warning" style="margin-top: -3px;" onclick="cierraBusquedaPlan();"><i class="fa fa-close"></i></button></h3>
        </div>
        <div class="panel-body">             
            <!---buscador plan ------------------------------------>
            <div class="col-md-6 " id="div_busca_plan_datatables" >  
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
            </div><!---buscador plan------------------------------------>
            <!-- arbol jerarquía -->
            <div class="col-md-5" style="overflow:scroll; height: 450px;"> 
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Seleccionar plan de estudios por jerarquía</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row" align="center"><h5>JERARQUÍA</h5></div>
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

<!-------CONTAINER SEARCH REACTIVO-------->
<div class="div_contenedor" id="mostrarReactivo" style="display:none">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">BÚSCAR REACTIVO</h3><button class="btn btn_closepanel btn-warning" onclick="muestraBusquedaReactivo(false);" ><i class="fa fa-close"></i></button>
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
                                <select name="buscar_tipo" id="buscar_tipo" class="col-md-2 form-control"  >
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
                                <input class="form-control" type="date" id="rea_date1"  /> 
                            </div>
                            <div class="col-md-6">
                                <input class="col-md-2 form-control" type="date" id="rea_date2"  />
                            </div>
                        </div>  
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="buscar_usuario">Usuario agregó:</label>
                                <input type="text" id="buscar_usuario" class="form-control" placeholder="Usuario"  /> 
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" id="textReactivo" class="form-control" placeholder="Texto a buscar" >
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

<!-------CONTAINER DATA REACTIVO-------->
<div class="div_contenedor"> <!--container tabs-->
    <div  >  <!--tabbable  ondblclick="tabbable"-->
        <ul class="nav nav-tabs" id="menutabs">
            <li class="active"><a href="#datosVisuales" data-toggle="pill">Datos Visuales</a></li>
            <li><a href="#referencia" data-toggle="pill">Referencia</a></li>
            <li id="vista_preliminar" style=" display: none;"><a href="#vistaPreliminar" data-toggle="pill">Vista Preliminar</a></li>
        </ul>
        <div class="tab-content" >
            <!--Datos Visuales-->
            <div class="tab-pane active"  id = "datosVisuales" >
                <div class="col-md-7 panelrea" id="cap_reactivo"> <!--COLUMNA DATOS DE REACTIVO -->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">Captura reactivo</h3>
                        </div>
                        <div class="panel-body">
                            <div id="reactivo">
                                <label for="sec_rea_contenido">Reactivo: </label> 
                                <div class="" align="center" id="sec_rea_contenido"> <!-- ("EDITOR TEXTO REACTIVO") -->
                                    <textarea name="rea_contenido" id="rea_contenido" class="textareas" style="width:100% !important;" ></textarea>
                                    <label id="msgrea_contenido" class="label-error" style="display:none">Llene campo de reactivo.</label>
                                </div>
                                <br />
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="clvreactivo">Clave (opcional):<span class="info_min">Identificar reactivos fácilmente</span></label>
                                        <input type="text" name="clvreactivo" id="clvreactivo" class="form-control"/>

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
                                    <div class="col-md-6" id="sec_tipo">
                                        <label for="tipo_reactivo">Tipo de Reactivo: </label>  
                                        <select name="tipo_reactivo" id="tipo_reactivo" class="form-control" >
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
                                    <br>
                                    <div class="col-md-6" align="right"><button type="button" class="btn btn-success" id="btn_opciones" title="Agregar Opciones de Respuesta al Reactivo"><i class="fa fa-check-square-o"></i> Capturar opciones de respuesta</button> </div>
                                </div>
                                <!--OPCIONES-->
                                <div class="div_contenedor col-md-12 line2round"> 
                                    <div class="row" align="center"><h4>Opciones de Respuesta</h4></div>
                                    <div class="div_contenedor" id="opciones" data-tipomedio=""></div>
                                </div>
                            </div>
                        </div> <!--/body panel datos reactivo-->
                    </div> <!--/panel datos reactivo-->
                </div> <!------final columna 1 DATOS REACTIVO--->

                <!-------------COLUMNA 2 ("CASO")-------------->
                <div class="col-md-5 panelrea" id="caso" data-add="0"> <!--COLUMNA DATOS DE CASO -->
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
                        </div> <!--/body panel datos reactivo-->
                    </div> <!--/panel datos reactivo-->
                </div>       
            </div> <!--/Datos Visuales-->
            <!-----------------------REFERENCIAS----------------------------->
            <?php
            if (isset($referencias_html)) {
                echo $referencias_html;
            }
            ?>
            <!----------Vista Prelimiar-------------->
            <div class="tab-pane" id="vistaPreliminar" >
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
                    <div class="col-md-6 marco_dot" id="div_vp_caso" >
                        <div id="vp_cas_tit" class="caso_data"></div>
                        <div id="vp_cas_con" class="caso_data"></div>
                        <div id="vp_cas_img" class="caso_data"></div>
                        <div id="vp_cas_aud" class="caso_data"></div>
                        <div id="vp_cas_vid" class="caso_data"></div>
                    </div>
                </div>
            </div> 
        </div> <!--/tab-content-->
    </div> <!--tabbable-->
</div><!--/container tabs--> 

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
                        <div id="area" class="form-group" >

                            <div class="rowsinespacios" align="center"><textarea id="contenido" class="textareas mceEditor" style="width:100% !important;"></textarea></div>
                            <div class="rowsinespacios" align="center">

                            </div>
                            <input type="text" id="num_opcion" class="form-control" disabled="disabled" style="display:none" />
                            <input type="text" id="edita_opcion"  class="form-control" disabled="disabled" style="display:none" />
                        </div>
                        <div class="form-group">
                            <button id="btn_upd_opcresp" class="btn btn-primary" data-modid="-1" onclick="insertarMultiple();"><i class="fa fa-file-picture-o"></i> <span>Agregar</span> Opción de Respuesta</button>
                        </div>
                    </div>
                    <div class="div_opcres form-group" id="img">
                        <div class="form-group">
                            <button class="add_media_opc btn btn-primary" data-tipoopc="img" ><i class="fa fa-file-picture-o"></i> Agregar opción de respuesta</button>
                        </div>
                    </div>
                    <div class="div_opcres form-group" id="aud">
                        <div class="form-group">
                            <button class="add_media_opc btn btn-primary" data-tipoopc="aud" ><i class="fa fa-file-audio-o"></i> Agregar opción de respuesta</button>
                        </div>
                    </div>
                    <div class="div_opcres form-group" id="vid">
                        <div class="form-group">
                            <button class="add_media_opc btn btn-primary" data-tipoopc="vid" ><i class="fa fa-file-video-o"></i> Agregar opción de respuesta</button>
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

</script>
<script>
    function initWorkspace() {
        /****  init editor items ***/
        tinyMCE.init({
            selector: "#rea_contenido", theme: "modern",
            forced_root_block: "",
            relative_urls: true,
            entity_encoding: "raw",
            document_base_url: base_url,
            force_br_newlines: true,
            force_p_newlines: false,
            plugins: [
                "autoresize advlist autolink link image lists charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
                "table contextmenu directionality emoticons paste textcolor responsivefilemanager code"
            ],
            toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
            toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
            image_advtab: true,
            external_filemanager_path: "/filemanager/",
            filemanager_title: "Responsive Filemanager",
            external_plugins: {"filemanager": "/js/tinymce/js/tinymce/plugins/filemanager/plugin.min.js"}
        });
        /****  init editors  ***/
        tinyMCE.init({
            selector: ".mceEditor", theme: "modern",
            forced_root_block: "",
            relative_urls: true,
            entity_encoding: "raw",
            document_base_url: base_url,
            force_br_newlines: true,
            force_p_newlines: false,
            plugins: [
                "autoresize advlist autolink link image lists charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
                "table contextmenu directionality emoticons paste textcolor responsivefilemanager code"
            ],
            toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
            toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
            image_advtab: true,
            external_filemanager_path: "/filemanager/",
            filemanager_title: "Responsive Filemanager",
            external_plugins: {"filemanager": "/js/tinymce/js/tinymce/plugins/filemanager/plugin.min.js"}
        });
        /****  init datatables search items  ****/
        $('#datosReactivo').dataTable({
            "bJQueryUI": true, "oLanguage": {
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
            "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
            "sPaginationType": "full_numbers",
            "aoColumns": [
                {"sWidth": "5%", "visible": false},
                {"sWidth": "40%"},
                {"sWidth": "3%"},
                {"sWidth": "10%"},
                {"sWidth": "4%"},
                {"sWidth": "10%"},
                {"sWidth": "10%"},
                {"sWidth": "5%"},
                {"sWidth": "10%"},
                {"sWidth": "3%", "sClass": "center"}
            ]});
        /****  init datatables search plan  ****/
        $('#datosPlan').dataTable({
            "bJQueryUI": true, "oLanguage": {
                "sProcessing": "<div class=\"ui-widget-header boxshadowround\"><strong>Procesando...</strong></div>",
                "sLengthMenu": "Mostrar _MENU_ planes",
                "sZeroRecords": "No se encontraron resultados",
                "sInfo": "Mostrando desde _START_ hasta _END_ de _TOTAL_ planes",
                "sInfoEmpty": "Mostrando desde 0 hasta 0 de 0 Planes",
                "sInfoFiltered": "(filtrado de _MAX_ planes)",
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
            "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
            "sPaginationType": "full_numbers",
            "aoColumns": [
                {"sWidth": "5%", "sClass": "center"},
                {"sWidth": "45%"},
                {"sWidth": "40%"}, {"sWidth": "5%", "sClass": "center"}
            ]
        });
        /****  init datatables search detail item  ****/
        $('#datosCaso').dataTable({
            "bJQueryUI": true, "oLanguage": {
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
            "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
            "sPaginationType": "simple",
            "aoColumns": [
                {"sWidth": "78"},
                {"sWidth": "22"}
            ]
        });

    }

    function limpia_dialogo_opcres() {
        /*$('#dialogo_opciones [value="txt"]').click();
         $('#area_opciones').html('');
         try {
         tinyMCE.get('contenido').setContent('');
         } catch (e) {
         }
         $('#dialogo_opciones [value="txt"]').click();
         $("#area_opciones").val('data-numopc', '0');*/
    }

    function limpia_reactivo() {
        contenido_reactivo = '';
        //cambia_st_opciones(false);
        limpia_dialogo_opcres();
        $("#rea_clave, #cas_clave, #clvreactivo").val('');
        $("#tipo_reactivo").val(0);
        $("#mostrarCaso").hide();
        $("#mostrarPlan").hide();
        $("#mostrarReactivo").hide();
        $("#editarCaso").hide();
        $("#sinCaso").show();
        $("#estado_c").addClass('btn-danger active');
        $("#estado_r").removeClass('btn-warning');
        $("#estado_a").removeClass('btn-success ');
        $("#btn_group_st button").attr('disabled', true);
        try {
            tinyMCE.get('rea_contenido').setContent('');
        } catch (e) {
        }
        //$("#rea_contenido").html('');
        $("#tipo_reactivo").removeAttr('disabled', 'disabled');
        $("#btn_opciones").removeAttr('disabled', 'disabled');
        $("#opciones").empty();
        $("#vista_preliminar").hide();
        //limpia_error();
        get_value('reactivo/setActualReactivo/0');
        $('[href="#datosVisuales"]').click();
    }

    function habilita_reactivo(est) {
        $('.info_noreactivo').remove();
        if (est) {
            $('#cap_reactivo input, select, textarea').attr('disabled', false);
            $('#cap_reactivo button').show();
            $('#caso button').show();
            $('#btn_guarda_rea').show();
            $('#referencia button').show();
            $('#tipo_reactivo').attr('disabled', false);
        } else {
            $('#cap_reactivo input, select, textarea').attr('disabled', true);
            $('#cap_reactivo button').hide();
            $('#caso input, select, textarea').attr('disabled', true);
            $('#caso button').hide();
            $('#btn_guarda_rea').hide();
            $('#referencia button').hide();
            var estado = '', msg = '';
            if ($("#estado_r").hasClass('activo')) {
                estado = '<b>pendiente de revisión</b>';
                msg = ' <b>-Para habilitar el reactivo cambie el estado a Captura.</b>';
            } else if ($("#estado_a").hasClass('activo')) {
                estado = '<b>revisado</b>';
            }
            $('#div_panel1').after('<div class="alert alert-warning info_noreactivo alert-dismissible" role="alert"><button type="button" style="position: inherit;" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><img width="50" style="margin-top: -5px;margin-bottom: -5px; margin-right:10px" src="./images/not/info.png">El reactivo se encuentra en estado: ' + estado + ' por lo tanto no puede ser modificado.' + msg + ' </div>');
        }
        $('#opciones input, select, textarea').attr('disabled', true);//opciones siempre deshabilitadas
        if (est)
            $('#tipo_reactivo').attr('disabled', false);
    }

    function cambia_estado_reactivo(est) {
        $("#btn_group_st button").removeClass('active');
        $("#btn_group_st button").removeClass('activo');
        if (est == 'C') {
            $("#estado_r").removeClass('btn-warning');
            $("#estado_c").addClass('btn-danger active');
            $("#estado_a").removeClass('btn-success');
            $('#estado_a').attr('disabled', true);
            $('#estado_r, #estado_c').attr('disabled', false);
            habilita_reactivo(true);
        } else if (est == 'R') {
            $("#estado_r").addClass('btn-warning active activo');
            $("#estado_c").removeClass('btn-danger');
            $("#estado_a").removeClass('btn-success');
            $('#estado_a').attr('disabled', true);
            $('#estado_r, #estado_c').attr('disabled', false);
            habilita_reactivo(false);
        } else if (est == 'A') {
            $("#estado_r").removeClass('btn-warning');
            $("#estado_c").removeClass('btn-danger');
            $("#estado_a").addClass('btn-success active activo');
            $('#estado_r,#estado_c,#estado_a').attr('disabled', true);
            habilita_reactivo(false);
        }
    }

    function agrega_opres_display(id, index, value, chk, tipo_media, idrea, es_vp) {
        var html = '', chk_check = '';
        if (chk == 'S') {
            chk_check = 'checked="checked"';
        }
        html += '<label id="div_' + id + '" class="opcresp_render row">';
        html += '<div class="control_opcresp_render col-md-1">';
        var value_input = value;
        if (tipo_media == 'txt') {
            value_input = '';
        }
        html += '<input type="radio" id="opcres_' + index + '" name="opcres_' + index + '" class="opcresp" value="' + value_input + '" data-escorrecta="' + chk + '"';
        if (!es_vp) {
            html += 'disabled ' + chk_check;
        } else {
            html += 'onclick=validaOpcVP($(this),"' + chk + '");';
        }
        html += '>';
        html += "</div>";
        html += '<div class="media_opcresp_render col-md-10">';
        if (tipo_media == 'txt') {
            html += '<div>' + value + '</div>';
        } else if (tipo_media == 'img') {
            html += '<img src="./media/reactivo' + idrea + '/' + value + '"/>';
        } else if (tipo_media == 'aud') {
            html += '<audio src="./media/reactivo' + idrea + '/' + value + '" controls=""></audio>';
        } else if (tipo_media == 'vid') {
            html += '<video src="./media/reactivo' + idrea + '/' + value + '" controls=""></video>';
        }
        html += "</div>";
        html += "</label>";
        return html;
    }

    function llenarReactivo(idrea) {
        $('#btn_siguienteReactivo').hide();
        $.blockUI({
            message: '<br><br><br><font style="color: #999999; font-size: 30px;">Espere un momento...</font><br><br><br><br>',
            fadeIn: 1,
            timeout: 2,
            onBlock: function () {
                limpia_reactivo();
                var data = get_object('reactivo/llenarReactivo', {idrea: idrea});
                cambia_estado_reactivo(data.est);
                var id_plan = data.pid;
                if (id_plan && id_plan != '' && id_plan != '0') {
                    $("#pla_clave").val(id_plan);
                } else {
                    $("#pla_clave").val('');
                }
                $("#pla_nombre").val(data.pnom);
                $("#rea_clave").val(data.id);
                $("#clvreactivo").val(data.cla);
                contenido_reactivo = data.con;
                try {
                    tinyMCE.get('rea_contenido').setContent(data.con);
                } catch (e) {
                }
                $("#tipo_reactivo").val(data.tip);
                $(".comments_rea").remove();
//si hay mensajes mostrarlos
                if (data.com && data.com != '') {
                    var com_html = '<div class="comments_rea"><div class="col-md-1"><i class="fa fa-comment"></i></div><div class="col-md-10"><b>Usuario: </b><font>' + data.uva + '</font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Fecha: </b><font>' + data.fva + '</font><br><div class="comm">' + data.com + '</div></div><div class="col-md-1"><i class="fa fa-remove btn" style="font-size:20px;" title="Eliminar comentario" onclick="rm_comment(' + data.id + ')"></i></div></div>';
                    $('#menutabs').prepend(com_html);
                }
                //llenar caso
                //limpiaCaso();
                if (data.cid != 0 && data.cid != '') {
                    datosCaso(data.cid);
                }
                //llenar opciones de respuesta
                var opcs_html = '', tipo = '';
                if (data.tip == 1) {
                    if (data.opcres) {
                        if ((Object.keys(data.opcres).length) > 0) {
                            $.each(data.opcres, function (index, opc) {
                                var chk = '', contenido = '';
                                tipo = opc.tip;
                                switch (tipo) {
                                    case 'txt':
                                        contenido = opc.con;
                                        break;
                                    case 'img':
                                        contenido = opc.img;
                                        break;
                                    case 'aud':
                                        contenido = opc.aud;
                                        break;
                                    case 'vid':
                                        contenido = opc.vid;
                                        break;
                                    default:
                                        break;
                                }
                                opcs_html += agrega_opres_display('opc_' + index, index, contenido, opc.escorrecta, tipo, idrea, false);
                            });
                            $('#opciones').attr('data-tipomedia', tipo);
                            $('#opciones').html(opcs_html);
                            //cambia_st_opciones(true);
                        } else {
                            $('#opciones').html('');
                            //cambia_st_opciones(false);
                        }
                    }
                }//fin llena opcres
                //muestraVistaPrevia(idrea);
                get_value('reactivo/setActualReactivo/' + idrea);
            }
        });
        //asignar clave al reactivo
        if ($('#clvreactivo').val() == '' && idrea != '' && idrea != '0') {
            $('#clvreactivo').val("REA-" + idrea);
        }
        ///
        $('#estado_a').removeAttr('disabled');
        $('[href="#datosVisuales"]').click();
        $('#btn_siguienteReactivo').show();
    }

    initWorkspace();



    $.unblockUI({
        onUnblock: function () {
            llenarReactivo(1655);
<?php
$clv_sess = $this->config->item('clv_sess');
$reactivo = $this->session->userdata('id_reativo_tmp' . $clv_sess);
$user_id = $this->session->userdata('user_id' . $clv_sess);
if ($loadreactivo != FALSE && $loadreactivo != '' && $loadreactivo != '0') {
    $this->session->set_userdata('id_reativo_tmp' . $clv_sess, $loadreactivo);
    ?>
                /*window.onload = function () {
                 tinyMCE_OnInit();
                 $('#estado_a').attr('disabled', false);
                 };*/
    <?php
}

if ($plan != FALSE && $plan != '' && $plan != '0') {
    $arr = explode('@_@', $plan);
    echo "seleccionarPlan(" . $arr[0] . ",'" . $arr[1] . "');";
}
?>
        }

    });

    setTimeout(function () {
        $('#estado_a').removeAttr('disabled');
    }, 1000);
    //select_autor(usuario);

</script>