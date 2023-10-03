<?php
$accion = 'AGREGAR';
$id = '0';
$disabled = '';
$per_chk = $clave = $nombre = $descripcion = "";
if (isset($datos_modifica) && $datos_modifica != false) {
    $id = $datos_modifica->id;
    $clave = $datos_modifica->clave;
    $nombre = $datos_modifica->nombre;
    $descripcion = $datos_modifica->descripcion;
}

if ($id == '0') {
    $accion = 'Agregar';
    $per_chk = 'checked';
} else {
    $accion = 'Modificar';
    $disabled = 'disabled';
}
?>
<style>
    #div_permisos .panel {  margin-top: 15px; }
    #div_permisos .panel-heading {overflow: hidden; padding: 0px; font-size: 14px;}
    .head_modulo {padding: 6px !important;background-color: #848484 !important;color: #fff !important;}
    .head_modulo .ico {width: 20px;float: left;}
    .head_modulo .ico i {font-size: 16px;}
    .head_modulo .ico img {width: 16px !important;}
    .permiso_modulo label {font-weight: 300 !important;color: #595959;}
    .permiso_modulo{padding-left: 10px;}
    .conten_modulo{background-color: #F4F4F4;padding: 0px;margin-left: 18px;margin-top: 10px;border: 1px dotted #C0BEBE; }
    #ver_permisos_div{margin-top: 20px;}
</style>
<div id="panel_mensajes" style=" display: none;">
    <div id="alert_resultado" class="alert"></div>
</div>
<link href="./plugins/zTreeJS/css/metroStyle/metroStyle.css" rel="stylesheet" type="text/css"/>
<script src="./plugins/zTreeJS/js/jquery.ztree.core.min.js" type="text/javascript"></script>
<script src="./plugins/zTreeJS/js/jquery.ztree.excheck.min.js" type="text/javascript"></script>
<script src="./plugins/zTreeJS/js/jquery.ztree.exedit.min.js" type="text/javascript"></script>
<script type="text/javascript" language="javascript" src="./js/jquery-validation/jquery.validate.min.js"></script>
<script type="text/javascript" language="javascript" src="./js/jquery-validation/messages_es.js"></script>
<div id="panel_update" class="col-md-12">
    <form id="form_update" class="" role="form">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="form-group col-md-2">
                        <label for="clave" >clave*:</label>
                        <input type="text"  value="<?php echo $clave; ?>"name="clave" id="clave" maxlength="4" minlength="4" class="form-control required" />
                    </div>
                    <div class="form-group col-md-4 col-sm-6">
                        <label for="nombre" >Nombre (s)*:</label>
                        <input type="text"  value="<?php echo $nombre; ?>" name="nombre" id="nombre" maxlength="100"  class="form-control required" />
                    </div>
                    <div class="form-group col-md-6 col-sm-6">
                        <label for="nombre" >Descripción:</label>
                        <textarea name="descripcion" id="descripcion" class="form-control"><?php echo $descripcion; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div id="div_permisos" class="col-md-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title col-md-10">
                            <div class="col-md-4">Permisos</div> 
                            <div class="col-md-4"><label><input type="checkbox" name="chks_permisos" value="S" id="chk_mod_permisos" <?php echo $per_chk; ?>/> Editar permisos</label></div>
                            <?php if ($id != '0') { ?>
                                <div class="col-md-4"><label id="sh_restaura_permisos"><input type="checkbox" name="chk_restaura_permisos" value="S" id="chk_restaura_permisos" <?php echo $per_chk; ?>/> Restaurar permisos de usuarios con este  </label></div>
                            <?php } ?>
                        </h3>
                        <div class="box-tools  pull-right ">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body"> 
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
                                <?php
                                foreach ($mod['permisos'] as $per) {
                                    $chk = '';
                                    if ($id != '0') {
                                        //print_r($permisos_modulo);
                                        if (array_key_exists($mod['clv'], $permisos_modulo)) {
                                            if (in_array($per['permisoclave'], $permisos_modulo[$mod['clv']])) {
                                                $chk = 'checked';
                                            }
                                        }
                                    }
                                    ?>
                                    <div class="permiso_modulo" title="<?php echo $per['pdes']; ?>">
                                        <input type="checkbox" id="prm_<?php echo $idmodulo . '_' . $per['pid']; ?>" class="almenosuno" name="permisos[]" value="<?php echo $per['pid']; ?>" <?php echo $disabled; ?> <?php echo $chk; ?>>
                                        <label for="prm_<?php echo $idmodulo . '_' . $per['pid']; ?>"><?php echo $per['pnom']; ?></label>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <?php
                            $count_mod++;
                            $numod++;

                            if ($cuantos_modulos != $numod) {
                                if ($count_mod == 5) {
                                    echo '</div>';
                                    $count_mod = 0;
                                }
                            } else {
                                ?>
                                <div id="modulo_ramas" class="col-md-2 col-sm-10 col-xs-10 conten_modulo border_color2">
                                    <div class="head_modulo color2">
                                        <div class="ico"><i class="fa fa-list"></i></div> <div class="nom">Ramas</div>
                                    </div>
                                    <ul id="treeDemo" class="ztree"></ul>
                                </div>
                                <?php
                                echo '</div>';
                            }
                        }
                        ?>
                    </div>
                </div>                
            </div>
        </div>
    </form> 
    <div class="row panel_color">
        <a id="cancelar" class="btn btn-primary col-md-4 col-md-offset-1 font_btn_bar col-xs-10 col-xs-offset-1" onclick="redirect_to('roles')">Cancelar</a>
        <a id="btn_actualizar" class="btn btn-primary col-md-4 col-md-offset-1 font_btn_bar col-xs-10 col-xs-offset-1" ><?php echo $accion; ?> rol</a>              
    </div>
</div>
<script type="text/javascript">
    var idRol =<?php echo (isset($id)) ? $id : ''; ?>;
    function validaAlmenosUnPermiso() {
        var uno = false;
        if ($('[name="permisos[]"]:checked').length > 1) {
            uno = true;
        }
        return uno;
    }

    $.validator.addMethod("almenosuno", function (value, element) {
        return (validaAlmenosUnPermiso());
    }, "Selecciona almenos un permiso.");

    /*$(window).load(function () {
     var treeObj = $.fn.zTree.getZTreeObj("treeDemo");
     var nodes = treeObj.getNodes();
     for (var i = 0, l = nodes.length; i < l; i++) {
     console.log(nodes[i]);
     treeObj.setChkDisabled(nodes[i], true);
     }
     });*/
    $(document).ready(function () {
        $.fn.zTree.init($("#treeDemo"), setting);
        var treeObj = $.fn.zTree.getZTreeObj("treeDemo");
        var nodes = treeObj.getSelectedNodes();
        if (nodes.length > 0) {
            treeObj.expandNode(nodes[0], true, true, true);
        }
        $('#sh_restaura_permisos').hide();
        $('#btn_regresar').unbind("click");
        $("#btn_regresar").click(function () {
            redirect_to('roles');
        });

        $("#chk_mod_permisos").click(function () {
            if ($(this).is(':checked')) {
                $('.conten_modulo :checkbox').attr('disabled', false);
                $('#sh_restaura_permisos').show();
            } else {
                $('.conten_modulo :checkbox').attr('disabled', true);
                $('#sh_restaura_permisos').hide();
            }
        });
        var ramasCheck = [];
        $("#btn_actualizar").click(function (e) {
            e.preventDefault();
            if ($('#form_update').validate().form()) {
                $('#panel_update').hide();
                try {
                    $('#foto_div .btn_subir').click();
                    var treeObj = $.fn.zTree.getZTreeObj("treeDemo");
                    var nodes = treeObj.getCheckedNodes();
                    for (var i = 0, l = nodes.length; i < l; i++) {
                        console.log(nodes[i].name);
                        if (nodes[i].isParent) {
                            ramasCheck.push(nodes[i].id);
                        }
                    }
                    console.log(ramasCheck);

                    var resp = get_object('roles/getupdate/<?php echo $id; ?>', $('#form_update').serialize() + '&ramasRol=' + ramasCheck);
                    if (resp.resultado && resp.resultado == 'ok') {
                        var msg = '';
                        if (resp.mensaje) {
                            msg = resp.mensaje;
                        }
                        $('#alert_resultado').addClass('alert-success');
                        $('#alert_resultado').html('<i class="fa fa-check-circle"></i> ' + msg + ' <button class="btn btn-primary" onclick="redirect_to(\'roles\')"><i class="fa fa-arrow-left"></i> Regresar a lista de roles</button>');
                        $('#panel_mensajes').show();
                    } else {
                        var msg = '';
                        if (resp.mensaje) {
                            msg = resp.mensaje;
                        }
                        $('#alert_resultado').addClass('alert-danger');
                        $('#alert_resultado').html('<i class="fa fa-times-circle"></i> Error: ' + msg + ' <button class="btn btn-primary" onclick="redirect_to(\'roles\')"><i class="fa fa-arrow-left"></i> Regresar a lista de roles</button>');
                        $('#panel_mensajes').show();
                    }
                } catch (e) {
                    alert(e);
                }
            }
        });
    });

    var setting = {
        async: {
            enable: true,
            url: "index.php/plan/getArbol/roles/" + idRol,
            autoParam: ["id", "name=n", "level=lv"],
            otherParam: {"otherParam": "zTreeAsyncTest"},
            dataFilter: filter
        },
        view: {expandSpeed: "",
            selectedMulti: false
        },
        edit: {
            enable: true,
            showRemoveBtn: false,
            showRenameBtn: false
        },
        check: {
            enable: true,
            chkDisabledInherit: false
        },
        data: {
            simpleData: {
                enable: true
            }
        }
    };
    function filter(treeId, parentNode, childNodes) {
        if (!childNodes)
            return null;
        for (var i = 0, l = childNodes.length; i < l; i++) {
            childNodes[i].name = childNodes[i].name.replace(/\.n/g, '.');
        }
        return childNodes;
    }
</script>
