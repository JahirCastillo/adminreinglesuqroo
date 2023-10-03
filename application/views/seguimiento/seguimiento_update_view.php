<link href="./plugins/zTreeJS/css/zTreeStyle/zTreeStyle.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" language="javascript" src="./js/jquery-validation/jquery.validate.min.js"></script>
<script type="text/javascript" language="javascript" src="./js/jquery-validation/messages_es.js"></script>
<script type="text/javascript" src="./js/zTreeJS/jquery.ztree.core-3.5.js"></script>
<script type="text/javascript" src="./js/zTreeJS/jquery.ztree.excheck-3.5.js"></script>
<script type="text/javascript" src="./js/zTreeJS/jquery.ztree.exedit-3.5.js"></script>
<script type="text/javascript" src="./js/zTreeJS/jquery.ztree.exhide-3.5.js"></script>

<?php
$accion = 'AGREGAR';
$id = $total_reactivos_capturar = $idRama = 0;
$elaboracion_rea = $nombreRama = $responsable_elaboracion_rea = $fecha_entrega_elaboracion_rea = $seg_captura_adminre = $responsable_captura_rea = $rama_en_revision = $fecha_entrega_captura_rea = $responsable_revision_rea = $fecha_termino_revision = "";
if (isset($datos_modifica) && $datos_modifica != false) {
    $id = $datos_modifica[0]['seg_id'];
    $idRama = $datos_modifica[0]['seg_id_rama'];
    $nombreRama = $datos_modifica[0]['seg_nombre_rama'];
    $elaboracion_rea = $datos_modifica[0]['seg_elaboracion_rea'];
    $responsable_elaboracion_rea = $datos_modifica[0]['seg_responsable_elaboracion'];
    $fecha_entrega_elaboracion_rea = $datos_modifica[0]['seg_fecha_entrega_elaboracion'];
    $seg_captura_adminre = $datos_modifica[0]['seg_captura_adminre'];
    $responsable_captura_rea = $datos_modifica[0]['seg_responsable_captura'];
    $fecha_entrega_captura_rea = $datos_modifica[0]['seg_fecha_entrega_captura'];
    $rama_en_revision = $datos_modifica[0]['seg_rama_enrevision'];
    $responsable_revision_rea = $datos_modifica[0]['seg_responsable_revision'];
    $fecha_termino_revision = $datos_modifica[0]['seg_fecha_termino_revision'];
    $total_reactivos_capturar = $datos_modifica[0]['seg_total_reactivos_a_capturar'];
    $selects = "$('#cambiaRama').show();$('#treeDemo').hide();$('#nombreRama').val('$nombreRama');$('#idRama').val('$idRama');$('#elaboracion_rea').val('$elaboracion_rea');$('#captura_rea').val('$elaboracion_rea');$('#responsable_captura_rea').val('$responsable_captura_rea');$('#rama_en_revision').val('$rama_en_revision');";
    $selects .= ($rama_en_revision == "no") ? "$('#fecha_termino_revision').removeAttr('required');" : '';
}

if ($id == '0') {
    $accion = 'Agregar';
    $per_chk = 'checked';
} else {
    $accion = 'Modificar';
    $disabled = 'disabled';
}
$rutaRama = '';
if (isset($ruta_rama)):
    foreach ($ruta_rama as $value):
        $rutaRama .= $value . ">&nbsp;";
    endforeach;
    $rutaRama = trim($rutaRama, '&nbsp;');
    $rutaRama = trim($rutaRama, '>');
endif;
?>
<div id="panel_mensajes" style=" display: none;">
    <div id="alert_resultado" class="alert"></div>
</div>
<div id="panelFormulario">
    <form id="formRegistro">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary box-solid containerForm">

                    <div class="box-header with-border">
                        <h3 class="box-title">Datos de elaboración</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <?php echo $rutaRama; ?><br/>
                        <input type="hidden" value="" id="idRama" name="idRama">
                        <input type="hidden" value="" id="nombreRama" name="nombreRama">
                        <span class="badge" id="cambiaRama" style="display: none;">Cambiar rama</span><br/>
                        <ul id="treeDemo" class="ztree"></ul><br/>
                        <div class="form-group">
                            <label for="elaboracion_rea">Elaboración de reactivo</label>
                            <select id="elaboracion_rea"name="elaboracion_rea" class="form-control" required>
                                <option value="">Selecciona una opción</option>
                                <option value="si">Si</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="responsable_elaboracion_rea">Responsable de elaboración</label>
                            <div class="form-group">
                                <div class='input-group date'>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-user"></span>
                                    </span>
                                    <input type="text" value="<?php echo $responsable_elaboracion_rea; ?>" minlength="3" maxlength="200" class="form-control" name="responsable_elaboracion_rea" id="responsable_elaboracion_rea" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fecha_entrega_rea">Fecha de entrega de reactivos</label>
                            <div class="form-group">
                                <div class='input-group date'>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                    <input type="date" value="<?php echo $fecha_entrega_elaboracion_rea; ?>" id="fecha_entrega_rea" name="fecha_entrega_rea" class="form-control" required />
                                </div>
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>

            <div class="col-md-6">
                <div class="box box-primary box-solid containerForm">
                    <div class="box-header with-border">
                        <h3 class="box-title">Datos de captura</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="form-group">
                            <label for="captura_rea">Captura de reactivo</label>
                            <select id="captura_rea" name="captura_rea" class="form-control" required>
                                <option value="">Selecciona una opción</option>
                                <option value="si">Si</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="responsable_captura_rea">Responsable de captura</label>
                            <div class="form-group">
                                <div class='input-group date'>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-user"></span>
                                    </span>
                                    <select id="responsable_captura_rea" name="responsable_captura_rea" class="form-control" required>
                                        <option value="">Selecciona una opción</option>
                                        <?php if (isset($usuarios)): ?>
                                            <?php foreach ($usuarios as $valueUsu) : ?>
                                                <option value="<?php echo $valueUsu['usu_id']; ?>"><?php echo $valueUsu['usu_nombre'] . " " . $valueUsu['usu_apaterno']; ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fecha_entrega_captura_rea">Fecha de entrega de captura</label>
                            <div class="form-group">
                                <div class='input-group date'>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                    <input type="date" value="<?php echo $fecha_entrega_captura_rea; ?>"id="fecha_entrega_captura_rea" name="fecha_entrega_captura_rea" class="form-control" required />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="rama_en_revision">Rama en revisión</label>
                            <div class="form-group">
                                <div class='input-group date'>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-ok"></span>
                                    </span>
                                    <select id="rama_en_revision" name="rama_en_revision" class="form-control" required>
                                        <option value="">Selecciona una opción</option>
                                        <option value="si">Si</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="responsable_revision_rea">Responsable de revisión</label>
                            <div class="form-group">
                                <div class='input-group date'>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-user"></span>
                                    </span>
                                    <input type="text" value="<?php echo $responsable_revision_rea; ?>" minlength="3" maxlength="200" class="form-control" name="responsable_revision_rea" id="responsable_revision_rea" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fecha_termino_revision">Fecha de termino de revisión</label>
                            <div class="form-group">
                                <div class='input-group date'>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                    <input type="date" value="<?php echo $fecha_termino_revision; ?>"id="fecha_termino_revision" name="fecha_termino_revision" class="form-control" required />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="total_reactivos_capturar">Número de reactivos a capturar</label>
                            <input type="number" id="total_reactivos_capturar" value="<?php echo $total_reactivos_capturar; ?>" minlength="1" min="1" name="total_reactivos_capturar" class="form-control" required />
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <button type="button" id="guardarCambios" class="btn btn-lg btn-primary">Guardar cambios</button>
                    <button type="button" id="btnCancelar"class="btn btn-lg btn-default">Cancelar</button>
                </div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    var idSeguimiento =<?php echo (isset($id)) ? $id : ''; ?>;
    $(document).ready(function () {

<?php echo (isset($selects)) ? $selects : ''; ?>
        $.fn.zTree.init($("#treeDemo"), setting);
        $("#expandAllBtn").bind("click", {type: "expandAll"}, expandNode);

        $("#btnCancelar").click(function () {
            redirect_to('seguimiento');
        });

        var ramasCheck = [];
        $("#guardarCambios").click(function (e) {
            e.preventDefault();
            ramasCheck = [];
            if ($('#formRegistro').validate().form()) {
                try {
                    var treeObj = $.fn.zTree.getZTreeObj("treeDemo");
                    var nodes = treeObj.getCheckedNodes();
                    /*for (var i = 0, l = nodes.length; i < l; i++) {
                     if (nodes[i].isParent) {
                     var obj = {};
                     obj.id = nodes[i].id;
                     obj.nombre = nodes[i].name;
                     ramasCheck.push(obj);
                     $('#idRama').val(nodes[i].id);
                     $('#nombreRama').val(nodes[i].name);
                     }
                     }*/

                    if ($('#idRama').val() != "" && $('#nombreRama').val() != "") {
                        $('#alert_resultado').empty();
                        $('#alert_resultado').removeClass('alert-danger');
                        var mensajeAlerta = '';

                        if (validaFechaMayorQue('"' + $('#fecha_entrega_rea').val() + '"', '"' + $('#fecha_entrega_captura_rea').val() + '"')) {
                            mensajeAlerta = '<a class="close" id="cerrarAlerta" aria-label="close">&times;</a><i class="fa fa-times-circle"></i> Error: Verifica la fecha de captura de reactivos. La fecha de captura de los reactivos es menor a la fecha de entrega.';
                            $('#alert_resultado').addClass('alert-danger');
                            $('#alert_resultado').html(mensajeAlerta);
                            $('#panel_mensajes').show();
                            return;
                        }
                        if (validaFechaMayorQue($('#fecha_entrega_captura_rea').val(), $('#fecha_termino_revision').val())) {
                            mensajeAlerta += (mensajeAlerta !== '') ? '<br/><br/><i class="fa fa-times-circle"></i> Error: Verifica la fecha de revisión de reactivos. La fecha de revisiós es menor a la fecha de captura de los reactivos.' : '<a class="close" id="cerrarAlerta" aria-label="close">&times;</a><i class="fa fa-times-circle"></i> Error: Verifica la fecha de revision de reactivos. La fecha de revisión es menor a la fecha de captura de los reactivos.';
                            $('#alert_resultado').addClass('alert-danger');
                            $('#alert_resultado').html(mensajeAlerta);
                            $('#panel_mensajes').show();
                            return;
                        }

                        //getObject('seguimiento/getupdate/<?php echo $id; ?>', {elaboracion_rea: $('#elaboracion_rea').val(), responsable_elaboracion_rea: $('#responsable_elaboracion_rea').val(), fecha_entrega_rea: $('#fecha_entrega_rea').val(), captura_rea: $('#captura_rea').val(), responsable_captura_rea: $('#responsable_captura_rea').val(), fecha_entrega_captura_rea: $('#fecha_entrega_captura_rea').val(), rama_en_revision: $('#rama_en_revision').val(), responsable_revision_rea: $('#responsable_revision_rea').val(), fecha_termino_revision: $('#fecha_termino_revision').val(), total_reactivos_capturar: $('#total_reactivos_capturar').val(), ramas: ramasCheck}, function (resp) {
                        getObject('seguimiento/getupdate/<?php echo $id; ?>', $('#formRegistro').serialize(), function (resp) {
                            $('#panelFormulario').hide();
                            if (resp.resultado && resp.resultado == 'ok') {
                                var msg = '';
                                if (resp.mensaje) {
                                    msg = resp.mensaje;
                                }
                                $('#alert_resultado').empty();
                                $('#alert_resultado').removeClass('alert-dangere');
                                $('#alert_resultado').addClass('alert-success');
                                $('#alert_resultado').html('<i class="fa fa-check-circle"></i> ' + msg + ' <button class="btn btn-primary" onclick="redirect_to(\'seguimiento\')"><i class="fa fa-arrow-left"></i> Regresar a lista</button>');
                                $('#panel_mensajes').show();
                            } else {
                                var msg = '';
                                if (resp.mensaje) {
                                    msg = resp.mensaje;
                                }
                                $('#alert_resultado').empty();
                                $('#alert_resultado').addClass('alert-danger');
                                $('#alert_resultado').html('<i class="fa fa-times-circle"></i> Error: ' + msg + ' <button class="btn btn-primary" onclick="redirect_to(\'seguimiento\')"><i class="fa fa-arrow-left"></i> Regresar a lista</button>');
                                $('#panel_mensajes').show();
                            }
                        });
                    } else {
                        alert('Selecciona solo una rama por favor');
                        return;
                    }

                } catch (e) {
                    alert(e);
                }
            }
        });

        $('#rama_en_revision').change(function () {
            if ($(this).val() == "no") {
                $('#responsable_revision_rea').val("No aplica");
                $('#fecha_termino_revision').removeAttr("required");
            } else if ($(this).val() == "si") {
                $('#fecha_termino_revision').attr("required", "true");
                $('#responsable_revision_rea').val("");
            }
        });
        $('#cambiaRama').click(function (e) {
            e.preventDefault();
            $('#treeDemo').show();
            $('#idRama').val();
            $('#nombreRama').val();


        });
        $('#captura_rea').change(function () {
            if ($(this).val() == "no") {
                $('#responsable_captura_rea').val("");
                $('#responsable_captura_rea').removeAttr("required");
                $('#fecha_entrega_captura_rea').removeAttr("required");
            } else if ($(this).val() == "si") {
                $('#responsable_captura_rea').attr("required", "true");
                $('#fecha_entrega_captura_rea').attr("required", "true");
            }
        });
        $('#elaboracion_rea').change(function () {
            if ($(this).val() == "no") {
                $('#responsable_elaboracion_rea').val("Sin revisión");
                $('#fecha_entrega_rea').removeAttr("required");
            } else if ($(this).val() == "si") {
                $('#responsable_elaboracion_rea').val("");
                $('#fecha_entrega_rea').attr("required", "true");
            }
        });

        $(document).on('click', '#cerrarAlerta', function (e) {
            e.preventDefault();
            $('#panel_mensajes').hide();
            $('#alert_resultado').empty();
        });
    });

    var setting = {
        async: {
            enable: true,
            url: "index.php/plan/getArbol/seguimiento/" + idSeguimiento,
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
            chkStyle: "radio",
            radioType: "level"
        },
        data: {
            simpleData: {
                enable: true
            }
        },
        callback: {
            onCheck: onCheck
        }
    };
    function onCheck(e, treeId, treeNode) {
        $('#idRama').val(treeNode.id);
        $('#nombreRama').val(treeNode.name);
    }
    function filter(treeId, parentNode, childNodes) {
        if (!childNodes)
            return null;
        for (var i = 0, l = childNodes.length; i < l; i++) {
            childNodes[i].name = childNodes[i].name.replace(/\.n/g, '.');
        }
        return childNodes;
    }


    function expandNode(e) {
        var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
                type = e.data.type,
                nodes = zTree.getSelectedNodes();
        if (type.indexOf("All") < 0 && nodes.length == 0) {
            alert("Please select one parent node at first...");
        }

        if (type == "expandAll") {
            zTree.expandAll(true);
        } else if (type == "collapseAll") {
            zTree.expandAll(false);
        } else {
            var callbackFlag = $("#callbackTrigger").attr("checked");
            for (var i = 0, l = nodes.length; i < l; i++) {
                zTree.setting.view.fontCss = {};
                if (type == "expand") {
                    zTree.expandNode(nodes[i], true, null, null, callbackFlag);
                } else if (type == "collapse") {
                    zTree.expandNode(nodes[i], false, null, null, callbackFlag);
                } else if (type == "toggle") {
                    zTree.expandNode(nodes[i], null, null, null, callbackFlag);
                } else if (type == "expandSon") {
                    zTree.expandNode(nodes[i], true, true, null, callbackFlag);
                } else if (type == "collapseSon") {
                    zTree.expandNode(nodes[i], false, true, null, callbackFlag);
                }
            }
        }
    }
    function validaFechaMayorQue(fechaUno, fechaDos) {
        var arrayFechaUno = fechaUno.split("-");
        var arrayFechaDos = fechaDos.split("-");
        var mesFechaUno = arrayFechaUno[1];
        var diaFechaUno = arrayFechaUno[2];
        var anioFechaUno = arrayFechaUno[0];
        var mesFechaDos = arrayFechaDos[1];
        var diaFechaDos = arrayFechaDos[2];
        var anioFechaDos = arrayFechaUno[0];
        if (anioFechaUno > anioFechaDos) {
            return true;
        } else {
            if (anioFechaUno === anioFechaDos) {
                if (mesFechaUno > mesFechaDos) {
                    return true;
                } else {
                    if (mesFechaUno === mesFechaDos) {
                        if (diaFechaUno > diaFechaDos)
                            return true;
                        else
                            return false;
                    } else
                        return false;
                }
            } else
                return false;
        }
    }

</script>
