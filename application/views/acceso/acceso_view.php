<link rel="stylesheet" href="./plugins/iCheck/flat/blue.css">
<style type="text/css">
    .login-logo, .register-logo {background-color: #3c8dbc;color: #fff;margin-bottom: 0px;}
    .sis_nombre{}
    .sis_version {font-size: 16px;font-weight: bold;}
    .login-page, .register-page, body {background: #d2d6de; }
    .wrapper, .main-sidebar,.left-side {background-color: #D2D6DE !important;}
    #msg_acceso.error{
        background-color: #F7D4D4;
        color: #965555;
        border: 1px dotted;
    }
    #msg_acceso.warning {
        background-color: #E2E4C2;
        color: #72704D;
        border: 1px dotted;
    }
    #msg_acceso.info {
        background-color: #C3F2F2;
        color: #4F809D;
        border: 1px dotted;
    }
    .login-box-msg, .register-box-msg {margin: 0;text-align: center;padding:2px; margin-bottom: 10px; font-size: 12px;}
    .box {
        position: relative;
        border-radius: 3px;
        background:#D2D6DE;
        border: 0px;
        margin-bottom: 0px;
        width: 100%;
        box-shadow: none;
    }
    .main-footer {margin: 0px !important;}
    .content-wrapper, .right-side {background-color: #D2D6DE; }
</style>
<div class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <span class="sis_nombre"><?php echo $this->config->item('sis_nombre'); ?></span>
            <span class="sis_version"><?php echo $this->config->item('sis_version'); ?></span>
        </div>
        <div class="login-box-body">
            <p id="msg_acceso" class="login-box-msg">Ingresa los datos que se piden.</p>
            <form id="frm_acceso">
                <div class="form-group has-feedback">
                    <input type="text" name="usuario" id="usuario" class="form-control required" placeholder="Usuario">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control required" id="pass" name="pass" placeholder="Contraseña">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <div class="checkbox icheck">
                            <label>
                                <input type="checkbox"> Recuérdame
                            </label>
                        </div>

                    </div>
                    <div class="col-xs-6">
                        <button id="bt_entrar" type="button" class="btn btn-primary btn-block btn-flat">Entrar</button>
                    </div>
                </div>
            </form>
            <a href="#">Olvidé mi contraseña</a><br>
        </div>
    </div>
</div>
<script src="./plugins/iCheck/icheck.min.js"></script>
<script type="text/javascript" language="javascript" src="./js/jquery-validation/jquery.validate.min.js"></script>
<script type="text/javascript" language="javascript" src="./js/jquery-validation/messages_es.js"></script>
<script type="text/javascript" charset="UTF-8">
    $(document).keydown(function (tecla) {
        if (tecla.keyCode == 13) {
            $("#bt_entrar").click();
        }
    });

    $(document).ready(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%'
        });

        $("#bt_entrar").click(function (e) {
            e.preventDefault();
            var usuario = $("#usuario"),
                    pass = $("#pass"),
                    tips = $("#msg_acceso");
            tips.hide();
            tips.removeClass('error warning info');
            if ($('#frm_acceso').validate().form()) {
                try {
                    var respuesta = get_object("acceso/acceso_sistema", "usuario=" + usuario.val() + "&pass=" + pass.val());
                    if (respuesta.sientra == 'ok') {
                        redirect_to('inicio');
                    } else {
                        if (respuesta.sientra == 'no') {
                            tips.html(respuesta.mensaje);
                            tips.addClass(respuesta.msg_class);
                            tips.show('slow');
                        }
                    }
                } catch (e) {
                    alert("Ha ocurrido un error comunicate con el administrado del sistema. <?php echo $this->config->item('sis_admin_nombre') . ': ' . $this->config->item('sis_admin_correo'); ?>");
                }
            }
        });
    });
</script>
