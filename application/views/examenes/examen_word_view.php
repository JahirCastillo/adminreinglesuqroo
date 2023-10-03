<?php $nombreArchWord = (isset($nombreWord)) ? urldecode($nombreWord) : ''; ?>

<link rel="stylesheet" href="./css/jquery.fileupload-ui.css">
<link rel="stylesheet" href="./css/jquery.fileupload-ui.css">
<script src="./js/tinymce/js/tinymce/jquery.tinymce.min.js" type="text/javascript"></script>
<script src="./js/tinymce/js/tinymce/tinymce.min.js" type="text/javascript"></script>
<script type="text/javascript" language="javascript" src="./js/jquery-validation/jquery.validate.min.js"></script>
<script type="text/javascript" language="javascript" src="./js/jquery-validation/messages_es.js"></script>
<script src="./js/FileSaver.js" type="text/javascript"></script>
<script src="./js/jquery.wordexport.js" type="text/javascript"></script>
<style>

</style>
<div id="panel_mensajes" style=" display: none;">
    <div id="alert_resultado" class="alert"></div>
</div>
<div id="panel_update" class="col-md-12">
    <div class="panel panel-primary">
        <div class="panel-heading">Examen <?php echo @$nombreArchWord;?> </div>
        <div class="panel-body">
            <div  id="containerMce"class="form-group col-md-12">
                <textarea name="contenido" class="textareas mceEditor" id="contenido" style="width: 100% !important">
                    <?php echo $examenContent; ?>
                </textarea>
                <label id="cas_contenido_lblerr" class="label-error" style="display:none">Se requiere Contenido.</label>
            </div>                     
        </div>
    </div>
</div>
<div id="exam" style="display: none;"></div>
<script type="text/javascript">
    tinyMCE.init({
        selector: "#contenido",
        theme: "modern",
        height: 700,
        fontsize_formats: "8pt 9pt 10pt 11pt 12pt 14pt 16pt 18pt 20pt 22pt 24pt 26pt 28pt 36pt 48pt 76pt",
        font_formats: 'Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats,AkrutiKndPadmini=Akpdmi-n',
        setup: function (editor) {
            editor.addButton('btnExp', {
                text: 'Descargar word',
                title: 'Descargar word',
                icon: 'save',
                onclick: function () {
                    var content = tinyMCE.get('contenido').getContent();
                    $('#exam').html(content);
                    $('#exam').wordExport('examen'+"<?php echo @$nombreArchWord;?>");
                    $('#exam').empty();
                    
                }});
        },
        forced_root_block: "",
        relative_urls: true,
        language: "es_MX",
        paste_as_text: true,
        document_base_url: base_url,
        force_br_newlines: true,
        force_p_newlines: false,
        plugins: [
            " advlist autolink link image lists charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars insertdatetime media fullscreen nonbreaking",
            "table contextmenu directionality emoticons paste textcolor responsivefilemanager code nonbreaking"
        ],
        toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect | fontselect | fontsizeselect ",
        toolbar2: "| responsivefilemanager | link unlink anchor | forecolor backcolor  | print preview code btnExp",
        image_advtab: true,
        external_filemanager_path: "/Nube/adminre/filemanager/",
        filemanager_title: "Responsive Filemanager",
        external_plugins: {
            "filemanager": "/Nube/adminre/js/tinymce/js/tinymce/plugins/filemanager/plugin.min.js"
            //"filemanager": "/js/tinymce/js/tinymce/plugins/filemanager/plugin.min.js"
        }
    });


    $(document).ready(function () {
    });

    function tinyMCE_OnInit() {
        /*var cont = '<?php ##echo str_replace(array("\n", "\r", "\r\n"), '', $examenContent);              ?>';
         tinyMCE.get('contenido').setContent(cont);*/
    }
</script>