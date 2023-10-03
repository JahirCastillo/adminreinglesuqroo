<?php
$accion = 'AGREGAR';
$id = '0';
$selects_vals = $changerol = '';
$foto = $login = $nombre = $apaterno = $amaterno = $email = $telefono = $lada = $celular = $rol = $estado = $permisos = "";
if (isset($datos_modifica) && $datos_modifica != false) {
    $id = $datos_modifica->id;
    $login = $datos_modifica->login;
    $foto = $datos_modifica->foto;
    $nombre = $datos_modifica->nombre;
    $apaterno = $datos_modifica->apaterno;
    $amaterno = $datos_modifica->amaterno;
    $email = $datos_modifica->email;
    $lada = $datos_modifica->lada;
    $telefono = $datos_modifica->telefono;
    $celular = $datos_modifica->celular;
    $rol = $datos_modifica->rol;
    $estado = $datos_modifica->estado;
    //$permisos = $datos_modifica->permisos;
    if (($rol * 1) != 0) {
        $changerol = '$("#rol").change();';
    }
    $selects_vals.="$('#estado').val($estado); $('#rol').val('$rol'); ";
}

if ($id == '0') {
    $accion = 'AGREGAR';
} else {
    $accion = 'MODIFICAR';
}
?>
<style>
    body{padding-top: 0px !important;}
    #div_permisos .panel {  margin-top: 15px; }
    .head_modulo {padding: 6px !important;background-color: #848484 !important;color: #fff !important;}
    .head_modulo .ico {width: 20px;float: left;}
    .head_modulo .ico i {font-size: 16px;}
    .head_modulo .ico img {width: 16px !important;}
    .permiso_modulo label {font-weight: 300 !important;color: #595959 !important;}
    .permiso_modulo{padding-left: 10px;}
    .conten_modulo{background-color: #F4F4F4;padding: 0px;margin-left: 18px;margin-top: 10px;border: 1px dotted #C0BEBE; }
    .font_btn_bar{font-size: 20px;}
    #lada_div {padding-right: 0px;padding-left: 0px;}
    #des_rol{display: none;}
    .des_rol {padding: 5px;min-height: 50px;}
    #txt_cambia_pass{font-size: 10px;}
    .btn_subir {display: none;}
    .remove_media{width: 100%}
    #fileupload_cas_imagen{ width: 100%; height: 100%}
    #btn_sel_file_foto{ width: 69%; float: left}
    #btn_borrarfoto{ width: 29%; float: left;margin-left: 2%;}
    #foto_div{overflow: hidden;margin-bottom: 10px;}
    div#filesimagen {background-color: #E7E7E7;border: 1px solid #AEAEAE;border-radius: 7px;text-align: center;padding-top: 10px;padding: 0px; min-height: 90px; background-image: url('./images/photo.png');background-repeat: no-repeat;background-position: center; margin-bottom: 10px; background-size: 70px;}
    #filesimagen canvas {border-radius: 5px;border: 1px solid #FFFFFF;margin-top: 10px; background-color: #fff;}
    #filesimagen img {width: 100px !important;height: 100px !important;margin-bottom: 10px;border-radius: 4px;background-color: #fff;margin-top: 10px;}
</style>
<link rel="stylesheet" href="./js/fileupload/css/style.css">
<link rel="stylesheet" href="./js/fileupload/css/jquery.fileupload.css">
<script src="./js/fileupload/js/vendor/jquery.ui.widget.js"></script>
<script src="./js/fileupload/js/external/load-image.all.min.js"></script>
<script src="./js/fileupload/js/external/canvas-to-blob.min.js"></script>
<script src="./js/fileupload/js/jquery.iframe-transport.js"></script>
<script src="./js/fileupload/js/jquery.fileupload.js"></script>
<script src="./js/fileupload/js/jquery.fileupload-process.js"></script>
<script src="./js/fileupload/js/jquery.fileupload-image.js"></script>
<script src="./js/fileupload/js/jquery.fileupload-validate.js"></script>
<script type="text/javascript" language="javascript" src="./js/jquery-validation/jquery.validate.min.js"></script>
<script type="text/javascript" language="javascript" src="./js/jquery-validation/messages_es.js"></script>
<div id="panel_mensajes" style=" display: none;">
    <div id="alert_resultado" class="alert"></div>
</div>
<div id="panel_update" class="col-md-12">
    <form id="form_update" class="" role="form">
        <div class="row">
            <div id="foto_div" class="col-md-2">
                <div class="cas_media_div ">
                    <div id="filesimagen" class="files col-md-12" data-select="0">
                        <?php if ($id != '0' && $foto != '') { ?>
                            <img src="./images/perfil_usuarios_sistema/<?php echo $foto; ?> " alt="Foto Usuario">
                        <?php } ?>
                    </div>
                    <div id="btn_sel_file_foto" class="btn btn-success fileinput-button " >
                        <i class="fa fa-plus-circle"></i> Seleccionar<br> archivo
                        <input id="fileupload_cas_imagen" type="file" name="files[]">
                    </div>
                    <button class="btn btn-danger remove_media" id="btn_borrarfoto"><i class="fa fa-remove"></i></button>
                </div>
                <input type="hidden" id="foto" name="foto" value="sc"/>
                <input type="hidden" id="foto_ant" name="foto_ant" value="<?php echo $foto; ?>"/>
            </div>
            <div class="col-md-10">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="login" >Login*:</label>
                        <input type="text"  value="<?php echo $login; ?>"name="login" id="login" maxlength="15"  class="form-control required" />
                    </div>
                    <?php if ($id != '0') { ?>
                        <div class="col-md-4" id="no_modifica_pass"><div class="myalert mywarning alert-min"><div id="txt_cambia_pass">La contraseña no se cambiará al menos que se proporcione una nueva.</div> <div > <label> <input type="checkbox" value="S" id="cambia_pass"/> Cambiar contraseña</label></div></div></div>
                    <?php } ?>
                    <div class="form-group col-md-3 pass col-sm-6">
                        <label for="password">Password*:</label>
                        <input type="password" name="password" id="password" maxlength="15" class="form-control required passiguales" />
                    </div>
                    <div class="form-group col-md-3 pass col-sm-6">
                        <label for="conf_password">Confirmaci&oacute;n Password*:</label>
                        <input type="password" name="conf_password" id="conf_password" maxlength="15" class="form-control required passiguales" />
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <div class="form-group col-md-4 col-sm-6" id="lada_div">
                            <label for="lada">Lada:</label>
                            <input type="text"  value="<?php echo $lada; ?>" id="lada" name="lada" maxlength="80" class="form-control number"/>
                        </div>
                        <div class="form-group col-md-8 col-sm-6">
                            <label for="telefono">Teléfono:</label>
                            <input type="text"  value="<?php echo $telefono; ?>" id="telefono" name="telefono" maxlength="80" class="form-control number"/>
                        </div>
                    </div>
                    <div class="form-group col-md-2 ">
                        <label for="celular">Celular:</label>
                        <input type="text"  value="<?php echo $celular; ?>" id="celular" name="celular" maxlength="80" class="form-control number"/>
                    </div>
                    <div class="form-group col-md-4 col-sm-6">
                        <label for="email">Email(recomendado):</label>
                        <input type="text"  value="<?php echo $email; ?>" id="email" name="email" maxlength="80"  class="email form-control"/>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-4 col-sm-6">
                        <label for="nombre" >Nombre (s)*:</label>
                        <input type="text"  value="<?php echo $nombre; ?>" name="nombre" id="nombre" maxlength="100"  class="form-control required" />
                    </div>
                    <div class="form-group col-md-3 col-sm-6">
                        <label for="apaterno" >Apellido Paterno:</label>
                        <input type="text"  value="<?php echo $apaterno; ?>" name="apaterno" id="apaterno" maxlength="100"  class="form-control" />
                    </div>
                    <div class="form-group col-md-3 col-sm-6">
                        <label for="amaterno" >Apellido Materno:</label>
                        <input type="text"  value="<?php echo $amaterno; ?>" name="amaterno" id="amaterno" maxlength="100"  class="form-control" />
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 paddin0">
            <div class="panel panel-primary margint10">
                <div class="panel-heading">DEFINIR PERMISOS DE ACCESO</div>
                <div class="panel-body">
                    <div class="row color_panel">
                        <div class="form-group col-md-5">
                            <label for="rol">Rol:</label>
                            <select id="rol" name="rol" class="form-control required">
                                <option value="">Elegir un rol</option>
                                <?php
                                foreach ($roles as $clv => $rol) {
                                    echo '<option data-desc="' . $rol['des'] . '" value="' . $clv . '">' . $rol['nom'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div id="des_rol" class="form-group col-md-4" ></div>
                        <div class="form-group col-md-2">
                            <label for="estado">Estado de cuenta:</label>
                            <select id="estado" name="estado" class="form-control">
                                <option value="1">Cuenta activa</option>  
                                <option value="0">Cuenta inactiva</option>  
                            </select>
                        </div>
                    </div>
                    <div id="div_permisos" class="">
                        <div class="box box-success">
                            <div class="box-header with-border">
                                <h3 class="box-title">MÓDULOS DEL SISTEMA / PERMISOS</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                </div>
                            </div>
                            <div class="box-body panel_body" style="display: block;">
                                <div id="msg_verpermisosselecciona" class="myalert mywarning alert_permisos_show"><i class="fa fa-info-circle"></i> Seleccione un rol para ver los permisos.</div>
                                <?php
                                $numod = $count_mod = 0;
                                $cuantos_modulos = count($modulos);
                                foreach ($modulos as $idmodulo => $mod) {
                                    if ($count_mod == 0) {
                                        echo '<div class="row">';
                                    }
                                    ?>
                                    <div id="modulo_<?php echo $idmodulo; ?>" class="col-md-2 col-sm-10 col-xs-10 conten_modulo border_color2">
                                        <div class="head_modulo color2">
                                            <div class="ico"><?php echo $mod['ico']; ?></div> <div class="nom"><?php echo $mod['nom']; ?></div>
                                        </div>
                                        <?php foreach ($mod['permisos'] as $per) { ?>
                                            <div class="permiso_modulo" title="<?php echo $per['pdes']; ?>">
                                                <input type="checkbox" id="prm_<?php echo $idmodulo . '_' . $per['pid']; ?>" name="mod_<?php echo $per['pid']; ?>[]" value="<?php echo $per['pid']; ?>" disabled>
                                                <label for="<?php echo $idmodulo . '_' . $per['pid']; ?>"><?php echo $per['pnom']; ?></label>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <?php
                                    $count_mod++;
                                    $numod++;
                                    if ($count_mod == 5) {
                                        echo '</div>';
                                        $count_mod = 0;
                                    }
                                    if ($cuantos_modulos == $numod) {
                                        echo '</div>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div><!--Fin row  div_permisos-->
                </div>
            </div>
        </div>
        <?php
        if (isset($datos_modifica) && $datos_modifica != false) {
            ?>
            <div class="col-md-12">
                <div class="panel panel-success">
                    <div class="panel-heading">LOG</div>
                    <div class="panel-body">
                        <div>
                            <div class="col-md-3 col-sm-6 col-xs-6 log_data"><b>Usuario agregó: </b><br><?php echo @$datos_modifica->usuagrego; ?></div>
                            <div class="col-md-3 col-sm-6 col-xs-6 log_data"><b>Fecha alta: </b><br><?php echo @$datos_modifica->fechaagrego; ?></div>
                            <div class="col-md-3 col-sm-6 col-xs-6 log_data"><b>Usuario modificó: </b><br><?php echo @$datos_modifica->usumodifico; ?></div>
                            <div class="col-md-3 col-sm-6 col-xs-6 log_data"><b>Última modificación: </b><br><?php
                                try {
                                    $date = new DateTime($datos_modifica->fechamodifico);
                                    echo $date->format('d/m/Y');
                                } catch (Exception $e) {
                                    
                                }
                                ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </form> 
    <div class="row panel_color">
        <a id="cancelar" class="btn btn-primary col-md-4 col-md-offset-1 font_btn_bar col-xs-10 col-xs-offset-1" onclick="redirect_to('usuarios_sistema')">Cancelar</a>
        <a id="actualizar_usuario" class="btn btn-primary col-md-4 col-md-offset-1 font_btn_bar col-xs-10 col-xs-offset-1" ><?php echo $accion; ?> usuario del sistema</a>              
    </div>

</div>
<script type="text/javascript">
    var permisos_rol =<?php echo json_encode($roles); ?>;
    function confirmacionCampo(t1, t2) {
        if (t1 != t2) {
            return false;
        } else {
            return true;
        }
    }
    
    $.validator.addMethod("passiguales", function (value, element) {
        return (confirmacionCampo($('#password').val(), $('#conf_password').val()));
    }, "Los password no coinciden.");
    
    $(document).ready(function () {
        $('#btn_borrarfoto').click(function (e) {
            e.preventDefault();
            $('#filesimagen').html('');
            $('#filesimagen').attr('data-select', '0');
            $('#foto').val('');
            $('#media_caso_hidden_imagen').val('');
        });
        var acceptFile = /(\.|\/)(gif|jpe?g|png)$/i;
        var fileSize = 2000000; //2 MB
        var acceptFile_txt = 'gif,jpe,jpeg,png';
        var tipodesc = 'imagen';
        var num = 'imagen';
        var url = 'index.php/usuarios_sistema/upfoto',
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
        
        $('#fileupload_cas_imagen').fileupload({
            url: url,
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
            data.context = $('<div/>').appendTo('#files' + num);
            $.each(data.files, function (index, file) {
                var node = $('<p/>');
                if (!index) {
                    node.append(uploadButton.clone(true).data(data));
                }
                node.appendTo(data.context);
                $('#filesimagen img').remove();
            });
        }).on('fileuploadprocessalways', function (e, data) {
            var index = data.index,
                    file = data.files[index],
                    node = $(data.context.children()[index]);
            $('#foto').val(data.files[index].name);
            if (file.preview) {
                //.prepend('<br>')
                node.prepend(file.preview);
                $('#btn_sel_' + num).hide();
                $('#progress' + num).show();
                $('#btn_sel_' + num).hide();
            }
            if (file.error) {
                node.append('<br>').append($('<span class="text-danger"/>').text(file.error));
                $('#btn_sel_' + num).show();
                $('#progress' + num).hide();
                $('#files' + num).html('');
                mensaje_center('Error', 'Archivo de ' + tipodesc + ' inválido', file.error, 'error');
            }
            if (index + 1 === data.files.length) {
                data.context.find('button').text('Subir').addClass('btn_subir').prop('disabled', !!data.files.error);
            }
            $('#foto_div .btn_subir').hide();
            $('#foto_div .btn_subir').click(function () {
                $("form").submit(function (e) {
                    return false;
                });
            });
        }).on('fileuploadprogressall', function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress' + num + ' .progress-bar').css(
                    'width',
                    progress + '%'
                    );
            $('#progress' + num + ' .progress-bar').text(progress + '%');
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
<?php
if ($id == '0') {
    
} else {
    echo "$('.pass').hide();";
    echo "$('#password').removeClass('required'); ";
    echo "$('#conf_password').removeClass('required');";
}
echo $selects_vals;
?>
        $('.conten_modulo').hide();
        $('#rol').change(function () {
            $('#div_permisos input:checkbox').attr("checked", false);
            var rol = $('#rol').val();
            $('#des_rol').hide().html('');
            if ((rol * 1) !== 0) {
                $('#des_rol').html('<div class="myalert myinfo des_rol"><b>Descripción Rol: </b>' + $('#rol option:selected').attr('data-desc') + '</div>').show();
                $('.alert_permisos_show').remove();
                $('.conten_modulo').hide();
                $.each(permisos_rol[rol]['modulos'], function (idmodulo, permisos) {
                    $('#modulo_' + idmodulo).show();
                    $.each(permisos, function (index, idpermiso) {
                        $('#prm_' + idmodulo + '_' + idpermiso).attr("checked", true);
                    });
                });
                if ($('#rol option:selected').text().toUpperCase().indexOf("OPERADOR")!=-1) {
                    $('#it').addClass('required');
                    $('#it').parent().show();
                }else{
                    $('#it').removeClass('required');
                    $('#it').parent().hide();
                }
            } else {
                $('#div_permisos .panel_body').html('<div class="myalert mywarning alert_permisos_show"><img src="./images/not/info.png">Seleccione un rol para ver los permisos.</div>');
            }
        });
        
        $("#ver_ocular_permisos").click(function () {
            if ($(this).attr("checked")) {
                $('#div_permisos').show();
            } else {
                $('#div_permisos').hide();
            }
        });
        
        $('#btn_regresar').unbind("click");
        $("#btn_regresar").click(function () {
            redirect_to('usuarios_sistema');
        });
        $("#btn_sel_file_foto").unbind("submit");
        
        $('#cambia_pass').click(function () {
            if ($(this).is(":checked")) {
                $('#no_modifica_pass').hide();
                $('.pass').show();
            }
        });
        
        $("#actualizar_usuario").click(function (e) {
            e.preventDefault();
            if ($('#form_update').validate().form()) {
                $('#panel_update').hide();
                try {
                    $('#foto_div .btn_subir').click();
                    var resp = get_object('usuarios_sistema/getupdate/<?php echo $id; ?>', $('#form_update').serialize());
                    if (resp.resultado && resp.resultado == 'ok') {
                        var msg = '';
                        if (resp.mensaje) {
                            msg = resp.mensaje;
                        }
                        $('#alert_resultado').addClass('alert-success');
                        $('#alert_resultado').html('<i class="fa fa-check-circle"></i> ' + msg + ' <button class="btn btn-primary" onclick="redirect_to(\'usuarios_sistema\')"><i class="fa fa-arrow-left"></i> Regresar a lista de usuarios</button>');
                        $('#panel_mensajes').show();
                    } else {
                        var msg = '';
                        if (resp.mensaje) {
                            msg = resp.mensaje;
                        }
                        $('#alert_resultado').addClass('alert-danger');
                        $('#alert_resultado').html('<i class="fa fa-times-circle"></i> Error: ' + msg + ' <button class="btn btn-primary" onclick="redirect_to(\'usuarios_sistema\')"><i class="fa fa-arrow-left"></i> Regresar a lista de usuarios</button>');
                        $('#panel_mensajes').show();
                    }
                } catch (e) {
                    alert(e);
                }
            }
        });
<?php
echo $changerol;
?>
    });
</script>
