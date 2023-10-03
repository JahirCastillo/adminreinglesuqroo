
<?php
$roles = $this->config->item('roles');
$accion = 'AGREGAR';
$id = '0';
$selects_vals = $changerol = '';
$login = $nombre = $email = $telefono = $rol = $estado = $permisos = "";
if (isset($datos_modifica) && $datos_modifica != false) {
    $id = $datos_modifica->id;
    $login = $datos_modifica->login;
    $nombre = $datos_modifica->nombre;
    $email = $datos_modifica->email;
    $telefono = $datos_modifica->telefono;
    $rol = $datos_modifica->rol;
    $estado = $datos_modifica->estado;
    $permisos = $datos_modifica->permisos;
    $changerol = '$("#rol").change();';
    $selects_vals.="$('#estado').val($estado); $('#rol').val('$rol'); ";
}



if ($id == '0') {
    $accion = 'AGREGAR';
} else {
    $accion = 'MODIFICAR';
}
?>
<style>
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
    
    .font_btn_bar{font-size: 20px;}
</style>
<div id="panel_mensajes" style=" display: none;">
    <div id="alert_resultado" class="alert"></div>
</div>
<div id="panel_update" class="col-md-12">
    <div class="panel panel-primary">
        <div class="panel-heading"><?php echo $accion; ?> USUARIO DEL SISTEMA</div>
        <div class="panel-body">
            <form id="form_update" class="" role="form">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="nombre" >Nombre del usuario:</label>
                        <input type="text"  value="<?php echo $nombre; ?>"name="nombre" id="nombre" maxlength="100"  class="form-control required" />
                    </div>
                    <div class="form-group col-md-2">
                        <label for="login" >Login*:</label>
                        <input type="text"  value="<?php echo $login; ?>"name="login" id="login" maxlength="15"  class="form-control required" />
                    </div>
                    <?php if ($id != '0') { ?>
                        <div class=" col-md-6"><br><br><br><br></div>
                        <div class="col-md-5"><div class="alert alert_color2">La contraseña no se cambiará al menos que se proporcione una nueva</div></div>
                    <?php } ?>
                    <div class="form-group col-md-3">
                        <label for="password">Password*:</label>
                        <input type="password" name="password" id="password" maxlength="15" class="form-control required passiguales" />
                    </div>
                    <div class="form-group col-md-3">
                        <label for="conf_password">Confirmaci&oacute;n Password*:</label>
                        <input type="password" name="conf_password" id="conf_password" maxlength="15" class="form-control required passiguales" />
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="email">Email(recomendado):</label>
                        <input type="text"  value="<?php echo $email; ?>"id="email" name="email" maxlength="80"  class="email form-control"/>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="telefono">Teléfono:</label>
                        <input type="text"  value="<?php echo $telefono; ?>"id="telefono" name="telefono" maxlength="80" class="form-control number"/>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="estado">Estado de cuenta:</label>
                        <select id="estado" name="estado" class="form-control">
                            <option value="1">Cuenta activa</option>  
                            <option value="0">Cuenta inactiva</option>  
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">DEFINIR PERMISOS DE ACCESO</div>
                        <div class="panel-body">
                            <div class="row color_panel">
                                <div class="form-group col-md-4">
                                    <label for="rol">Rol:</label>
                                    <select id="rol" name="rol" class="form-control required">
                                        <option value="">Elegir un rol</option>
                                        <?php
                                        foreach ($roles as $clv => $rol) {
                                            echo '<option prmrol="' . $rol['permisos'] . '" value="' . $clv . '">' . $rol['rol'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div id="ver_permisos_div" class="form-group col-md-4">
                                    <input type="checkbox" id="ver_ocular_permisos" checked  class="check_radio">
                                    <label for="ver_ocular_permisos">Ver permisos</label><br>
                                </div>
                            </div>
                            <div id="div_permisos" class="">
                                <div class="panel panel-info">
                                    <div class="panel-heading">MÓDULOS DEL SISTEMA / PERMISOS</div>
                                    <div class="panel-body"> 
                                        <?php
                                        $permisos_modulo = $this->config->item('descripcion_permisos');
                                        $count_mod = 0;
                                        foreach ($permisos_modulo as $modulo => $det_permisos) {
                                            if ($count_mod == 0) {
                                                //echo '<div class="row">';
                                            }
                                            ?>
                                            <div id="modulo_<?php echo $det_permisos['clave']; ?>" class="col-md-2 conten_modulo border_color2">
                                                <div class="head_modulo color2">
                                                    <?php echo $modulo; ?>
                                                </div>
                                                <?php foreach ($det_permisos['permisos'] as $permiso => $descripcion) { ?>
                                                    <div class="permiso_modulo">
                                                        <input type="checkbox" id="<?php echo $det_permisos['clave'] . '_' . $permiso; ?>" name="<?php echo $det_permisos['clave']; ?>[]" class="<?php echo $det_permisos['clave']; ?>" value="<?php echo $permiso; ?>" disabled>
                                                        <label for="<?php echo $det_permisos['clave'] . '_' . $permiso; ?>"><?php echo $descripcion; ?></label>
                                                    </div>
                                                <?php }
                                                ?>
                                                <div class="permiso_modulo">
                                                    <input type="checkbox" id="<?php echo $det_permisos['clave'] . '_t'; ?>" name="<?php echo $det_permisos['clave']; ?>[]" class="<?php echo $det_permisos['clave']; ?>" value="t" disabled>
                                                    <label for="<?php echo $det_permisos['clave'] . '_t'; ?>">Todos</label>
                                                </div>
                                                <div class="permiso_modulo">
                                                    <input type="checkbox" id="<?php echo $det_permisos['clave'] . '_v'; ?>" name="<?php echo $det_permisos['clave']; ?>[]" class="<?php echo $det_permisos['clave']; ?>" value="v" disabled>
                                                    <label for="<?php echo $det_permisos['clave'] . '_v'; ?>">S&oacute;lo Lectura</label>
                                                </div>
                                            </div>
                                            <?php
                                            $count_mod++;
                                            //if ($count_mod == 5) {
                                            //  echo '</div>';
                                            //$count_mod = 0;
                                            //}
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div><!--Fin row  div_permisos-->
                        </div>
                    </div>
                </div>
            </form> 
            <div class="row panel_color">
                <a id="cancelar" class="btn btn-primary col-md-4 col-md-offset-1 font_btn_bar" onclick="redirect_to('usuarios_sistema')">Cancelar</a>
                <a id="actualizar_usuario" class="btn btn-primary col-md-4 col-md-offset-1 font_btn_bar" ><?php echo $accion; ?> usuario del sistema</a>              
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function confirmacionCampo(t1, t2) {
        if (t1 != t2) {
            return false;
        } else {
            return true;
        }
    }
    
    $.validator.addMethod("passiguales", function(value, element) {
        return (confirmacionCampo($('#password').val(), $('#conf_password').val()));
    }, "Los password no coinciden.");

    $(document).ready(function() {
<?php
if ($id == '0') {
    
} else {
    echo "$('#password').removeClass('required'); ";
    echo "$('#conf_password').removeClass('required');";
}
echo $selects_vals;
?>
        ;

        $('#rol').change(function() {
            $('#div_permisos input:checkbox').attr("checked", false);
            var i = $("#rol option:selected").attr('prmrol');
            var arr_per = i.split('|');
            if ($(this).val() == '') {
                $('#div_permisos input:checkbox').attr("checked", false);
                $('.conten_modulo').show();
            } else {
                $('.conten_modulo').hide();
                try {
                    $.each(arr_per, function(k, v) {
                        var prm = v.split('>');
                        $('#modulo_' + prm[0]).show();
                        $('#div_permisos .' + prm[0]).each(function() {
                            if ($('#rol').val() == 0) {
                                $('#div_permisos input:checkbox').attr("checked", false);
                            } else {
                                if (($.inArray($(this).val(), prm[1])) != -1) {
                                    $(this).attr('checked', true);
                                } else {
                                    $(this).attr('checked', false);
                                }
                            }
                        });
                    });
                } catch (e) {
                }
            }
        });

        $("#ver_ocular_permisos").click(function() {
            if ($(this).attr("checked")) {
                $('#div_permisos').show();
            } else {
                $('#div_permisos').hide();
            }
        });

        $('#btn_regresar').unbind("click");
        $("#btn_regresar").click(function() {
            redirect_to('usuarios_sistema');
        });

        $("#actualizar_usuario").click(function(e) {
            e.preventDefault();
            if ($('#form_update').validate().form()) {
                $('#panel_update').hide();
                try {
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
<?php echo $changerol; ?>
    });
</script>