<style>
    .dtcontent .row {
        margin: 0px;
    }
</style>
<!-------------------BUSCAR CASO----------------->
<div id="mostrarCaso" hidden="hide" class="div_contenedor">
    <div class=" titulo_subopc" align="center">BUSCAR CASO</div>
    <div class="row">
        <div class="col-md-7">
            <div class="input-group">
                <input type="text" name="cadenaCaso" id="cadenaCaso" class="form-control" placeholder="Caso a buscar">
                <div class="input-group-btn">
                    <button type="button" id="btn_buscarCaso" onclick="buscarCaso(cadenaCaso.value);" class="btn btn-default"> <i class="fa fa-search"></i> Buscar&nbsp;&nbsp;&nbsp;&nbsp;</button>
                </div><!-- /btn-group -->
            </div><!-- /input-group -->
        </div><!-- /input-group -->
        <div class="col-md-5" align="right">
            <button class="btn btn-default" onclick="cerrarBusquedaCaso();" title="Cerrar busqueda de Caso" ><i class="fa fa-remove"></i> Cerrar</button>
        </div>
        <div class="dtcontent col-md-12">
            <table id="datosCaso" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="width: 78%;">Titulo</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Titulo</th>
                        <th>&nbsp;</th>
                    </tr>
                </tfoot>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div> 
<!---------------------NO CASO---------------------->
<div class="div_contenedor" id="sinCaso">
    <div class="row panel_buttopc" align="right">
        <button class="btn btn-default" onclick="caso();" title="Buscar Caso"><i class="fa fa-search"></i> Buscar</button>
        <button class="btn btn-success" id="btn_open_newCaso" title="Nuevo Caso"><i class="fa fa-plus"></i> Nuevo</button>
    </div>
    <div class="row" align="center"><i class="fa fa-ban" style="font-size: 100px;"></i></div>
    <div class="row" align="center"><h3>SIN CASO</h3></div>
</div>
<!-----------------FORMULARIO CASO----------------------->
<div class="col-md-12" id="viewCaso" style="display: none">
    <div class="row panel_buttopc" align="right">
        <button class="btn btn-primary" onclick="caso();" title="Buscar Caso"><i class="fa fa-search"></i> Buscar</button>
        <button class="btn btn-info" onclick="actualizarCaso();" title="Actuializar Caso"><i class="fa fa-refresh"></i></button>
        <button class="btn btn-warning" onclick="modificaCaso();" title="Modifica Caso"><i class="fa fa-edit"></i></button>
        <button class="btn btn-danger" onclick="sinCaso();" title="Anular Caso"><i class="fa fa-times"></i></button>
    </div>
    <div class="row">
        <input type="hidden" name="cas_clave" id="cas_clave"/>
        <div class="form-group">
            <label for="cas_titulo">Titulo</label>
            <input type="text" class="form-control" id="cas_titulo" disabled="disabled">
        </div>
        <div class="form-group">
            <label for="cas_instruccion">Instrucción</label>
            <textarea class="form-control" id="cas_instruccion" disabled="disabled"></textarea>
        </div>
        <div class="form-group">
            <label for="cas_contenido">Contenido</label>
            <div class="form-control altoauto" id="cas_contenido" disabled="disabled"></div>
        </div>
        <div id="cas_media" class="form-group">
            <div class="img">
                <label class="title">Imagen</label>
                <div class="imagen"></div>
            </div>
            <div class="aud">
                <label class="title">Audio</label>
                <div class="audio"></div>
            </div>
            <div class="vid">
                <label class="title">Video</label>
                <div class="video"></div>
            </div>
        </div>
    </div>
</div> 

<div class="modal fade" id="dialog_agrega_caso">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Agregar Caso</h4>
            </div>
            <div class="modal-body">
                <div id="form_upd_caso" class="" role="form" >
                    <div class="form-group">
                        <label for="cas_titulo">Titulo:</label>
                        <input type="text" name="cas_titulo_add" id="cas_titulo_add" class="form-control required" />
                        <label id="cas_titulo_lblerr" class="label-error" style="display:none">Se requiere título.</label>
                    </div>
                    <div class="form-group">
                        <label for="cas_instruccion_add">Instrucción:</label>
                        <textarea name="cas_instruccion_add" class="form-control" id="cas_instruccion_add"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="cas_contenido_add">Texto:</label>
                        <textarea name="cas_contenido_add" class="textareas mceEditor" id="cas_contenido_add" style="width: 100%"></textarea>
                        <label id="cas_contenido_lblerr" class="label-error" style="display:none">Se requiere Contenido.</label>
                    </div>                     
                    <div class="form-group col-md-12" id="hd_media_casos_files">
                        <ul class="responsive-accordion responsive-accordion-default bm-larger">
                            <li>
                                <div class="responsive-accordion-head" id="hd_media_caso_img"><i class="fa fa-file-image-o icn"></i> Imagen  <i class="fa fa-chevron-down responsive-accordion-plus fa-fw"></i><i class="fa fa-chevron-up responsive-accordion-minus fa-fw"></i></div>
                                <div class="responsive-accordion-panel">
                                    <div class="cas_media_div ">
                                        <div id="filesimagen" class="files col-md-7" data-select="0"></div>
                                        <span id="btn_sel_files_imagenvideo" class="btn btn-success fileinput-button" >
                                            <i class="fa fa-plus-circle"></i>Seleccionar<br> archivo
                                            <input id="fileupload_cas_imagen" type="file" name="files[]">
                                        </span>
                                        <div class="col-md-2" style="float: right;"><button class="btn btn-danger remove_media_caso" onclick="remove_media_caso('imagen')"><i class="fa fa-remove"></i></button></div>
                                    </div>
                                </div>
                                <input type="hidden" id="media_caso_hidden_imagen" class="media_caso_hidden"/>
                            </li>
                            <li>
                                <div class="responsive-accordion-head" id="hd_media_caso_aud"><i class="fa fa-file-audio-o icn"></i> Audio <i class="fa fa-chevron-down responsive-accordion-plus fa-fw"></i><i class="fa fa-chevron-up responsive-accordion-minus fa-fw"></i></div>
                                <div class="responsive-accordion-panel">
                                    <div class="cas_media_div ">
                                        <div id="filesaudio" class="files col-md-7" data-select="0"></div>
                                        <span id="btn_sel_files_audio" class="btn btn-success fileinput-button" >
                                            <i class="fa fa-plus-circle"></i>Seleccionar<br> archivo
                                            <input id="fileupload_cas_audio" type="file" name="files[]">
                                        </span>
                                        <div class="col-md-2" style="float: right;"><button class="btn btn-danger remove_media_caso" onclick="remove_media_caso('audio')"><i class="fa fa-remove"></i></button></div>
                                    </div>
                                </div>
                                <input type="hidden" id="media_caso_hidden_audio" class="media_caso_hidden"/>
                            </li>
                            <li>
                                <div class="responsive-accordion-head" id="hd_media_caso_vid"><i class="fa fa-file-video-o icn"></i> Video  <i class="fa fa-chevron-down responsive-accordion-plus fa-fw"></i><i class="fa fa-chevron-up responsive-accordion-minus fa-fw"></i></div>
                                <div class="responsive-accordion-panel" style="min-height: 300px;">
                                    <div class="cas_media_div ">
                                        <div id="filesvideo" class="files col-md-7" data-select="0"></div>
                                        <span id="btn_sel_files_video" class="btn btn-success fileinput-button">
                                            <i class="fa fa-plus-circle"></i>Seleccionar<br> archivo
                                            <input id="fileupload_cas_video" type="file" name="files[]">
                                        </span>
                                        <div class="col-md-2" style="float: right;"><button class="btn btn-danger remove_media_caso" onclick="remove_media_caso('video')"><i class="fa fa-remove"></i></button></div>
                                    </div>
                                </div>
                                <input type="hidden" id="media_caso_hidden_video" class="media_caso_hidden"/>
                            </li>
                        </ul>
                    </div>
                </div> 

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary"  id="btn_agrega_caso" >Agregar Caso</button> 
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
    function actualizarCaso() {
        var idcaso = $('#cas_clave').val() * 1;
        if (idcaso !== 0) {
            datosCaso(idcaso);
        }
    }

    function modificaCaso() {
        var idcaso = $('#cas_clave').val() * 1;
        if (idcaso !== 0) {
            open_in_new('caso/update/' + idcaso);
        }
    }
</script>