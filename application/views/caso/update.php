
<?php
$roles = $this->config->item('roles');
$accion = 'AGREGAR';
$id = '0';
$selects_vals = $js = '';
$titulo = $contenido = $imagen = $video = $audio = $instruccion = "";
if (isset($datos_modifica) && $datos_modifica != false) {
    $id = $datos_modifica->id;
    $titulo = $datos_modifica->tit;
    $instruccion = $datos_modifica->ins;
    $contenido = $datos_modifica->con;
    $imagen = $datos_modifica->img;
    $audio = $datos_modifica->aud;
    $video = $datos_modifica->vid;
    $js = '';
    $selects_vals.="";
}

if ($id == '0') {
    $accion = 'AGREGAR';
} else {
    $accion = 'MODIFICAR';
}
?>
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
<script src="./js/tinymce/js/tinymce/jquery.tinymce.min.js" type="text/javascript"></script>
<script src="./js/tinymce/js/tinymce/tinymce.min.js" type="text/javascript"></script>
<script src="http://www.wiris.com/plugins/demo/tinymce/php/js/prism.jss" type="text/javascript"></script>

<script type="text/javascript" language="javascript" src="./js/jquery-validation/jquery.validate.min.js"></script>
<script type="text/javascript" language="javascript" src="./js/jquery-validation/messages_es.js"></script>
<style>
    body{
        padding-top: 0;
    }
    #div_permisos .panel {  margin-top: 15px; }
    .check_radio{
        height: 34px;
        width: 8%;
        font-size: 14px;
        line-height: 1.428571429;
        color: #555555;
        vertical-align: middle;
        background-color: #ffffff;
        background-image: none;
        border: 1px solid #cccccc;
        border-radius: 4px;
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
        -webkit-transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
        transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
    }
    .conten_modulo {
        background-color: #F4F4F4;
        padding: 0px;
        margin-left: 18px;
        margin-top: 10px;
        border: 1px dotted #C0BEBE;
    }
    .head_modulo {
        padding: 6px !important;
        background-color: #848484 !important;
        color: #fff !important;
    }
    .permiso_modulo label {
        font-weight: 300 !important;
        color: #595959 !important;
    }
    .permiso_modulo{padding-left: 10px;}
    #ver_permisos_div{margin-top: 20px;}
    div#hd_media_casos_files {margin-top: 10px;}
    .font_btn_bar{font-size: 20px;}

    div#filesaudio {margin-top: 80px;margin-left: -15px;}
    div#filesvideo {margin-top: 80px;margin-left: -15px;}
    div#filesimagen {margin-left: -15px;}
</style>
<div id="panel_mensajes" style=" display: none;">
    <div id="alert_resultado" class="alert"></div>
</div>
<div id="panel_update" class="col-md-12">
    <div class="panel panel-primary">
        <div class="panel-heading"><?php echo $accion; ?> CASO</div>
        <div class="panel-body">
            <form id="form_update" class="" role="form">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="titulo" >Titulo*:</label>
                        <input type="text"  value="<?php echo $titulo; ?>" name="titulo" id="titulo" maxlength="100"  class="form-control required" />
                    </div>
                    <div class="form-group col-md-6">
                        <label for="cas_instruccion_add">Instrucción:</label>
                        <textarea name="instruccion" class="form-control" id="instruccion"><?php echo $instruccion; ?></textarea>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <label for="cas_contenido_add">Texto*:</label>
                    <textarea name="contenido" class="textareas mceEditor contenidovalido" id="contenido" style="width: 100% !important">
                        <?php echo str_replace(array("\n", "\r", "\r\n"), '', $contenido); ?>
                    </textarea>
                    <label id="cas_contenido_lblerr" class="label-error" style="display:none">Se requiere Contenido.</label>
                </div>                     

            </form> 
            <!--<div class="form-group col-md-12" id="hd_media_casos_files">
                <div class="col-md-4">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-file-image-o icn"></i> Imagen </h3>
                        </div>
                        <div class="panel-body">   
                            <div class="cas_media_div ">
                                <div id="filesimagen" class="files col-md-7" data-select="0">
                                    <?php if ($imagen != '') { ?>
                                        <div><p><img src="./media_casos/caso<?php echo $id; ?>/<?php echo $imagen; ?>" width="100" height="100"/><span><?php echo $imagen; ?></span></p></div>
                                    <?php } ?>
                                </div>
                                <span id="btn_sel_files_imagenvideo" class="btn btn-success fileinput-button" >
                                    <i class="fa fa-plus-circle"></i>Seleccionar<br> archivo
                                    <input id="fileupload_cas_imagen" type="file" name="files[]">
                                </span>
                                <div class="col-md-2" style="float: right;"><button class="btn btn-danger remove_media_caso" onclick="remove_media_caso('imagen')"><i class="fa fa-remove"></i></button></div>
                            </div>
                            <input type="hidden" id="media_caso_hidden_imagen" class="media_caso_hidden"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-file-audio-o icn"></i> Audio </h3>
                        </div>
                        <div class="panel-body">
                            <div class="cas_media_div ">
                                <div id="filesaudio" class="files col-md-7" data-select="0">
                                    <?php if ($audio != '') { ?>
                                        <div><p><audio src="./media_casos/caso<?php echo $id; ?>/<?php echo $audio; ?>" controls></audio><span><?php echo $audio; ?></span></p></div>
                                    <?php } ?>
                                </div>
                                <span id="btn_sel_files_audio" class="btn btn-success fileinput-button" >
                                    <i class="fa fa-plus-circle"></i>Seleccionar<br> archivo
                                    <input id="fileupload_cas_audio" type="file" name="files[]">
                                </span>
                                <div class="col-md-2" style="float: right;"><button class="btn btn-danger remove_media_caso" onclick="remove_media_caso('audio')"><i class="fa fa-remove"></i></button></div>
                            </div>
                            <input type="hidden" id="media_caso_hidden_audio" class="media_caso_hidden"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-file-video-o icn"></i> Video </h3>
                        </div>
                        <div class="panel-body">
                            <div class="cas_media_div ">
                                <div id="filesvideo" class="files col-md-7" data-select="0">
                                    <?php if ($video != '') { ?>
                                        <div><p><video src="./media_casos/caso<?php echo $id; ?>/<?php echo $video; ?>" controls></video><span><?php echo $video; ?></span></p></div>
                                    <?php } ?>
                                </div>
                                <span id="btn_sel_files_video" class="btn btn-success fileinput-button">
                                    <i class="fa fa-plus-circle"></i>Seleccionar<br> archivo
                                    <input id="fileupload_cas_video" type="file" name="files[]">
                                </span>
                                <div class="col-md-2" style="float: right;"><button class="btn btn-danger remove_media_caso" onclick="remove_media_caso('video')"><i class="fa fa-remove"></i></button></div>
                            </div>
                            <input type="hidden" id="media_caso_hidden_video" class="media_caso_hidden"/>
                        </div>
                    </div>
                </div>
            </div>-->
            <div class="row panel_color">
                <a id="cancelar" class="btn btn-primary col-md-4 col-md-offset-1 font_btn_bar" onclick="redirect_to('caso')">Cancelar</a>
                <a id="btn_actualizar" class="btn btn-primary col-md-4 col-md-offset-1 font_btn_bar" ><?php echo $accion; ?> caso</a>              
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    /*tinyMCE.init({
     // General options
     mode: "textareas",
     editor_selector: "mceEditor",
     editor_deselector: "mceNoEditor",
     // General options
     theme: "advanced",
     plugins: "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,fmath_formula,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",
     theme_advanced_buttons1: "newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,fontselect,fontsizeselect",
     theme_advanced_buttons2: "bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,image,cleanup,code,|,forecolor,backcolor,|,sub,sup",
     theme_advanced_buttons3: "fmath_formula,|,charmap,iespell,advhr,|,tablecontrols",
     theme_advanced_toolbar_location: "top",
     theme_advanced_toolbar_align: "left",
     theme_advanced_statusbar_location: "bottom",
     theme_advanced_resizing: true,
     // Example content CSS (should be your site CSS)
     //content_css: "<?php echo $this->config->item('base_url') . 'css/content.css'; ?>",
     // Drop lists for link/image/media/template dialogs
     template_external_list_url: "lists/template_list.js",
     external_link_list_url: "lists/link_list.js",
     external_image_list_url: "lists/image_list.js",
     media_external_list_url: "lists/media_list.js", width: 'auto',
     init_instance_callback: tinyMCE_OnInit,
     // Replace values for the template plugin
     template_replace_values: {
     username: "Some User",
     staffid: "991234"
     }
     });*/

    tinyMCE.init({
        selector: "#contenido", theme: "modern",
        forced_root_block: "",
        relative_urls: true,
        language: "es_MX",
        paste_as_text: true,
        document_base_url: base_url,
        force_br_newlines: true,
        force_p_newlines: false,
        plugins: [
            "autoresize advlist autolink link image lists charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
            "table contextmenu directionality emoticons paste textcolor responsivefilemanager code"
        ],
        toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
        toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code | tiny_mce_wiris_formulaEditor | tiny_mce_wiris_formulaEditorChemistry ",
        image_advtab: true,
        //external_filemanager_path: "/filemanager/",
        external_filemanager_path: "/Nube/adminre/filemanager/",
        filemanager_title: "Responsive Filemanager",
        external_plugins: {
            "filemanager": "/Nube/adminre/js/tinymce/js/tinymce/plugins/filemanager/plugin.min.js"
            //"filemanager": "/js/tinymce/js/tinymce/plugins/filemanager/plugin.min.js"
        }
    });

    function remove_media_caso(tipo) {
        $('#files' + tipo).html('');
        $('#files' + tipo).attr('data-select', '0');
        $('#media_caso_hidden_' + tipo).val('');
    }

    function validaContenido() {
        var contenido = tinyMCE.get('contenido').getContent();
        if (contenido == '') {
            return false;
        } else {
            return true;
        }
    }

    $.validator.addMethod("contenidovalido", function (value, element) {
        return (validaContenido());
    }, "Este campo es obligatorio.");

    $(document).ready(function () {
        addPluginFileUpCaso('fileupload_cas_imagen', 'img', 'imagen');
        addPluginFileUpCaso('fileupload_cas_audio', 'aud', 'audio');
        addPluginFileUpCaso('fileupload_cas_video', 'vid', 'video');

        var url = 'index.php/reactivo/up',
                uploadButton = $('<button/>').addClass('btn btn-primary').prop('disabled', true).text('Processing...')
                .on('click', function () {
                    var $this = $(this), data = $this.data();
                    $this.off('click').text('Abort').on('click', function () {
                        $this.remove();
                        data.abort();
                    });
                    data.submit().always(function () {
                        $this.remove();
                    });
                });

        function addPluginFileUpCaso(id, tipo, num) {
            var acceptFile = /(\.|\/)(gif|jpe?g|png)$/i;
            var fileSize = 2000000; //2 MB
            var acceptFile_txt = 'gif,jpe,jpeg,png';
            var tipodesc = 'imagen';
            switch (tipo) {
                case 'img':
                    acceptFile = /(\.|\/)(gif|jpe?g|png)$/i;
                    fileSize = 2000000;
                    acceptFile_txt = 'gif,jpe,jpeg,png';
                    tipodesc = 'imagen';
                    break;
                case 'aud':
                    acceptFile = /(\.|\/)(mp3|acc|wma|ogg|wav)$/i;
                    fileSize = 5000000;
                    acceptFile_txt = 'mp3,acc,wma,ogg,wav';
                    tipodesc = 'audio';
                    break;
                case 'vid':
                    acceptFile = /(\.|\/)(mp4|flv|avi|wmv)$/i;
                    fileSize = 10000000;
                    acceptFile_txt = 'mp4,flv,avi,wmv';
                    tipodesc = 'video';
                    break;
                default:
                    break;
            }
            $('#' + id).fileupload({
                url: 'index.php/caso/up',
                dataType: 'json',
                autoUpload: false,
                acceptFileTypes: acceptFile,
                maxFileSize: fileSize, // 5 MB
                disableImageResize: /Android(?!.*Chrome)|Opera/.test(window.navigator.userAgent),
                previewMaxWidth: 100,
                previewMaxHeight: 100,
                previewCrop: true,
                messages: {
                    maxNumberOfFiles: 'Maximum number of files exceeded',
                    acceptFileTypes: 'Archivo de ' + tipodesc + ' inválido.<br> Intenta con alguna de las siguientes extensiones: ' + acceptFile_txt,
                    maxFileSize: 'El archivo es demasiado grande. Intenta que sea menor o igual ' + (fileSize / 1000000) + 'MB.',
                    minFileSize: 'El archivo es muy pequeño.'
                }
            }).on('fileuploadadd', function (e, data) {
                $('#files' + num).html('');
                data.context = $('<div/>').appendTo('#files' + num);
                $.each(data.files, function (index, file) {
                    var node = $('<p/>').append($('<span/>').text(file.name));
                    if (!index) {
                        node.append(uploadButton.clone(true).data(data));
                    }
                    node.appendTo(data.context);
                    $('#media_caso_hidden_' + num).val(file.name);
                });
            }).on('fileuploadprocessalways', function (e, data) {
                var index = data.index,
                        file = data.files[index],
                        node = $(data.context.children()[index]);
                if (file.preview) {
                    //.prepend('<br>')
                    node.prepend(file.preview);
                    $('#progress' + num).show();
                    //$("#files" + num).append('<span class="name_file">' + file.name + '</span>');
                    $("#files" + num).attr('data-select', '1');
                }
                if (file.error) {
                    node.append('<br>').append($('<span class="text-danger"/>').text(file.error));
                    $('#btn_sel_' + num).show();
                    $('#files' + num).html('');
                    mensaje_center('Error', 'Archivo de ' + tipodesc + ' inválido', file.error, 'error');
                }
                if (index + 1 === data.files.length) {
                    data.context.find('button').text('Subir').addClass('btn_subir').prop('disabled', !!data.files.error);
                }
                $('#hd_media_casos_files .btn_subir').hide();
            }).on('fileuploadprogressall', function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                /*$('#progress' + num + ' .progress-bar').css(
                 'width',
                 progress + '%'
                 );
                 $('#progress' + num + ' .progress-bar').text(progress + '%');*/
            }).on('fileuploaddone', function (e, data) {
                $.each(data.result.files, function (index, file) {
                    if (file.url) {
                        var link = $('<a>').attr('target', '_blank').prop('href', file.url);
                        $(data.context.children()[index])
                                .wrap(link);
                        $('#btn_sel_' + num).hide();
                        $('#opc_' + num).val(file.name);
                    } else if (file.error) {
                        var error = $('<span class="text-danger"/>').text(file.error);
                        $(data.context.children()[index]).append('<br>').append(error);
                        $('#btn_sel_' + num).show();
                        $('#progress' + num).hide();
                    }
                });
                $("#opciones").html($("#opciones").html());
            }).on('fileuploadfail', function (e, data) {
                $.each(data.files, function (index, file) {
                    var error = $('<span class="text-danger"/>').text('File upload failed.');
                    $(data.context.children()[index]).append('<br>').append(error);
                });
                $('#btn_sel_' + num).show();
                $('#progress' + num).hide();
            }).prop('disabled', !$.support.fileInput).parent().addClass($.support.fileInput ? undefined : 'disabled');
        }
<?php
echo $selects_vals;
?>
        $('#btn_regresar').unbind("click");
        $("#btn_regresar").click(function () {
            redirect_to('caso');
        });

        $("#btn_actualizar").click(function (e) {
            e.preventDefault();
            if ($('#form_update').validate().form()) {
                $('#panel_update').hide();
                try {
                    var id_cas, ctit = $("#titulo").val(),
                            ccon = tinyMCE.get('contenido').getContent(),
                            inst = $("#instruccion").val(),
                            img = $('#filesimagen div p span').text(),
                            aud = $('#filesaudio div p span').text(),
                            vid = $('#filesvideo div p span').text();
                    var resp = get_object('caso/getupdate/<?php echo $id; ?>', {cas_titulo: ctit, cas_contenido: ccon, cas_instruccion: inst, aud: aud, img: img, vid: vid});
                    if (resp.resultado && resp.resultado === 'ok') {
                        if (resp.act && resp.act === 'add') {
                            id_cas = resp.id_insert;
                            $.blockUI();
                            get_value('caso/setActualCaso/' + id_cas, '');
                            setTimeout(function () {
                                $.unblockUI({
                                    onUnblock: function () {
                                        $("#hd_media_casos_files .btn_subir").click();//subir archivos
                                    }
                                });
                            }, 1);
                        } else if (resp.act && resp.act === 'upd') {
                            id_cas = <?php echo $id; ?>;
                            $.blockUI();
                            get_value('caso/setActualCaso/' + id_cas, '');
                            setTimeout(function () {
                                $.unblockUI({
                                    onUnblock: function () {
                                        $("#hd_media_casos_files .btn_subir").click();//subir archivos
                                    }
                                });
                            }, 1);
                            sepudo = true;
                        } else {
                            mensaje_center('Error', 'Error al actualizar caso.', 'Intente de nuevo. Recargue la página.', 'error');
                        }

                        $('#alert_resultado').addClass('alert-success');
                        $('#alert_resultado').html('<i class="fa fa-check-circle"></i> ' + resp.mensaje + ' <button class="btn btn-primary" onclick="redirect_to(\'caso\')"><i class="fa fa-arrow-left"></i> Regresar a lista de casos</button>');
                        $('#panel_mensajes').show();
                    } else {
                        $('#alert_resultado').addClass('alert-danger');
                        $('#alert_resultado').html('<i class="fa fa-times-circle"></i> Error: ' + resp.mensaje + ' <button class="btn btn-primary" onclick="redirect_to(\'caso\')"><i class="fa fa-arrow-left"></i> Regresar a lista de casos</button>');
                        $('#panel_mensajes').show();
                    }
                } catch (e) {
                    alert(e);
                }
            }
        });


<?php echo $js; ?>
    });

    function tinyMCE_OnInit() {
        var cont = '<?php echo str_replace(array("\n", "\r", "\r\n"), '', $contenido); ?>';
        tinyMCE.get('contenido').setContent(cont);
    }
</script>