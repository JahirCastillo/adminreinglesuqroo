<link href="./plugins/zTreeJS/css/zTreeStyle/zTreeStyle.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="./js/zTreeJS/jquery.ztree.core-3.5.js"></script>
<script type="text/javascript" src="./js/zTreeJS/jquery.ztree.excheck-3.5.js"></script>
<script type="text/javascript" src="./js/zTreeJS/jquery.ztree.exedit-3.5.js"></script>
<script src="./js/FileSaver.js" type="text/javascript"></script>
<script src="./js/jquery.wordexport.js" type="text/javascript"></script>
<script type="text/javascript" src="./js/modal_bootstrap_extend/js/bootstrap-dialog.js"></script>
<?php
if (!isset($idExamen)) {
    redirect_to('examenes');
}
?>
<SCRIPT type="text/javascript">
    $(document).ajaxStart(function () {
        $.blockUI({message: '<h1><i class="fa fa-spinner fa-spin"></i></h1>'});
    }).ajaxStop(function () {
        $.unblockUI()
    });
    var numReactivos = 0;
    var idExamen =<?php echo $idExamen; ?>;
    var setting = {
        async: {
            enable: true,
            url: "index.php/examenes/getArbol",
            autoParam: ["id", "name=n", "level=lv"],
            otherParam: {"otherParam": "zTreeAsyncTest"},
            dataFilter: filter
        },
        view: {expandSpeed: "",
            removeHoverDom: removeHoverDom,
            selectedMulti: false
        },
        edit: {
            enable: true,
            showRemoveBtn: false,
            showRenameBtn: false,
            drag: {
                isCopy: true,
                isMove: false
            }
        },
        data: {
            simpleData: {
                enable: true
            }
        },
        callback: {
            onDrop: zTreeOnDrop
        }
    };

    var setting2 = {
        async: {
            enable: true,
            url: "index.php/examenes/getArbolEdit/" + idExamen,
            autoParam: ["id", "name=n", "level=lv"],
            otherParam: {"otherParam": "zTreeAsyncTest"},
            dataFilter: filter
        },
        view: {expandSpeed: "",
            selectedMulti: false
        },
        edit: {
            enable: true,
            showRemoveBtn: true,
            showRenameBtn: setRenameBtn,
            drag: {
                isCopy: false,
                isMove: true
            }
        },
        data: {
            simpleData: {
                enable: true
            }
        },
        callback: {
            beforeRemove: beforeRemove,
            beforeRename: beforeRename,
            beforeDrop: beforeDrop
        }
    };

    function setRenameBtn(treeId, treeNode) {
        return treeNode.isParent;
    }
    function zTreeBeforeDrag(treeId, treeNodes) {
        return treeId;
    }

    function myBeforeMouseUp(treeId, treeNode) {
        return false;
    }

    function zTreeOnDrop(event, treeId, treeNodes, targetNode, moveType) {
        var containerDrop = event.target.id;
        if (containerDrop.substring(0, 8) === 'treeDemo') {
            alert("Suelta el elemento en una sección del examen.");
            $.fn.zTree.init($("#treeDemo"), setting);
            return false;
        } else if (targetNode === null) {
            if (treeNodes[0].name.substring(0, 1) === '~') {
                alert("Suelta el elemento en una sección del examen.");
                $.fn.zTree.init($("#container"), setting2);
                return false;
            } else {
                if (containerDrop === 'container') {
                    try {
                        var nodoActual = treeNodes[0].id, nodeName = treeNodes[0].name;
                        var resp = get_object('examenes/addHojasNodo/' + idExamen, {totalReactivos: numReactivos, idNodoOrigen: nodoActual, nodeNameOrigin: nodeName, idNodoDestino: 0});
                        if (resp.res == 'ok') {
                            numReactivos = parseInt(resp.newTotalReact);
                            document.getElementById("total_react").value = numReactivos;
                            notify_block('Actualizar reactivos', 'Los reactivos se agregaron de manera satisfactoria.', '', 'success');
                            if (numReactivos == 0) {
                                $('#btn_export').prop("disabled", true);
                                $('#btn_download').prop("disabled", true);
                                $('#btn_export_word').prop("disabled", true);
                            } else {
                                $('#btn_export').prop("disabled", false);
                                $('#btn_download').prop("disabled", false);
                                $('#btn_export_word').prop("disabled", false);
                            }
                            return true;
                        }
                    } catch (e) {

                    }
                }
            }
        } else {
            var nodoActual = treeNodes[0].id, nodeName = treeNodes[0].name, nodoPadre = targetNode.id;
            if (nodeName.substring(0, 1) === '~') {
                if (moveType == 'inner') {
                    numReactivos = numReactivos + 1;
                    var resp = get_object('examenes/newReactivosExam/' + idExamen, {
                        id_reactivo: nodoActual,
                        numReact: numReactivos,
                        nodo: nodoPadre
                    });
                    if (resp.res == 'ok') {
                        if (numReactivos == 0) {
                            $('#btn_export').prop("disabled", true);
                            $('#btn_download').prop("disabled", true);
                            $('#btn_export_word').prop("disabled", true);
                        } else {
                            $('#btn_export').prop("disabled", false);
                            $('#btn_download').prop("disabled", false);
                            $('#btn_export_word').prop("disabled", false);
                        }
                        notify_block('Actualizar reactivos', 'Los reactivos se agregaron de manera satisfactoria.', '', 'success');
                        document.getElementById("total_react").value = numReactivos;
                    } else {
                        numReactivos = numReactivos - 1;
                        document.getElementById("total_react").value = numReactivos;
                        mensaje_center('Error', resp.msg, 'Intente de nuevo.', 'error');
                    }
                } else {
                    $.fn.zTree.init($("#container"), setting2);
                }
            } else {
                if (nodoPadre !== null) {
                    //si se movió a otro nodo padre
                    if (moveType == 'inner') {
                        try {
                            var resp = get_object('examenes/addHojasNodo/' + idExamen, {totalReactivos: numReactivos, idNodoOrigen: nodoActual, nodeNameOrigin: nodeName, idNodoDestino: nodoPadre});
                            if (resp.res == 'ok') {
                                numReactivos = parseInt(resp.newTotalReact);
                                document.getElementById("total_react").value = numReactivos;
                                notify_block('Actualizar reactivos', 'Los reactivos se agregaron de manera satisfactoria.', '', 'success');
                                if (numReactivos == 0) {
                                    $('#btn_export').prop("disabled", true);
                                    $('#btn_download').prop("disabled", true);
                                    $('#btn_export_word').prop("disabled", true);
                                } else {
                                    $('#btn_export').prop("disabled", false);
                                    $('#btn_download').prop("disabled", false);
                                    $('#btn_export_word').prop("disabled", false);
                                }
                                return true;
                            }
                        } catch (e) {
                        }
                    } else {
                        $.fn.zTree.init($("#container"), setting2);
                    }
                }
            }
        }
        return false;
    }

    //mover reactivos o secciones del examen
    function beforeDrop(treeId, treeNodes, targetNode, moveType) {
        var containerDrop = event.target.id, typeNode = 'P', typeTar = 'P';
        if (containerDrop.substring(0, 8) === 'treeDemo') {
            alert("Suelta el elemento en una sección del examen.");
            $.fn.zTree.init($("#container"), setting2);
            return false;
        }
        if (targetNode === null && event.target.id === "container") {
            if (treeNodes[0].name.substring(0, 1) === '~') {
                alert("Suelta el elemento en una sección del examen.");
                $.fn.zTree.init($("#container"), setting2);
                return false;
            } else {
                var nodoActual = treeNodes[0].id, typeNode = 'P', typeTar = 'P';
                try {
                    var resp = get_object('examenes/moveNodo/' + idExamen, {id: nodoActual, pid: 0, type: typeNode, typeTar: typeTar});
                    if (resp.res == 'ok') {
                        return true;
                    }
                } catch (e) {

                }
            }
        }
        if (treeNodes[0].name.substring(0, 1) === '~') {
            typeNode = 'R';
        }
        if ((targetNode.name).substring(0, 1) === '~') {
            typeTar = 'R';
        }
        if (typeNode === 'R' && typeTar === 'R') {
            return false;
        }
        if (nodoPadre !== null) {
            //si se movió a otro nodo padre
            if (moveType == 'inner') {
                var nodoActual = treeNodes[0].id, nodoPadre = targetNode.id;
                try {
                    var resp = get_object('examenes/moveNodo/' + idExamen, {id: nodoActual, pid: nodoPadre, type: typeNode, typeTar: typeTar});
                    if (resp.res && resp.res == 'ok') {
                        return true;
                    }
                } catch (e) {
                }
                //sino si se movió a otra posicion dentro del mismo nodo
            } else if (moveType == 'next') {

            }
        }
        return false;
    }

    //Renombrar
    function beforeRename(treeId, treeNode, newName) {
        if (!treeNode.isParent && (treeNode.name.substring(0, 1) === '~')) {
            return false;
        } else {
            if (newName.length == 0) {
                alert("Node name can not be empty.");
                return false;
            }
            getObject('examenes/editNodoName', {id: treeNode.id, nom: newName}, function (resp) {
                if (resp.res && resp.res == 'ok') {
                    return true;
                } else {
                    return false;
                }
            });
        }
    }

    function filter(treeId, parentNode, childNodes) {
        if (!childNodes)
            return null;
        for (var i = 0, l = childNodes.length; i < l; i++) {
            childNodes[i].name = childNodes[i].name.replace(/\.n/g, '.');
        }
        return childNodes;
    }

    //funcion para eliminar un reactivo asociado a un examen
    function beforeRemove(treeId, treeNode) {
        var uriAct = 'examenes/deleteNodo/' + idExamen;
        var zTree = $.fn.zTree.getZTreeObj("container");
        zTree.selectNode(treeNode);
        var siborroReactivo;
        if (!treeNode.isParent && (treeNode.name.substring(0, 1) === '~')) {
            uriAct = 'examenes/deleteReactivoExam/' + idExamen;
            siborroReactivo = confirm("¿Estas seguro de borrar el reactivo '" + treeNode.name + "'? del examen");
            if (siborroReactivo) {
                numReactivos = numReactivos - 1;
                getObject(uriAct, {totalReactivos: numReactivos, idReactivo: treeNode.id}, function (resp) {
                    if (resp.resp == 'ok') {
                        siborroReactivo = true;
                        document.getElementById("total_react").value = numReactivos;
                        if (numReactivos == 0) {
                            $('#btn_export').prop("disabled", true);
                            $('#btn_download').prop("disabled", true);
                            $('#btn_export_word').prop("disabled", true);
                        }
                        else {
                            $('#btn_export').prop("disabled", false);
                            $('#btn_download').prop("disabled", false);
                            $('#btn_export_word').prop("disabled", false);
                        }
                        notify_block('Actualizar reactivos', 'El reactivo se desligó del examen.', '', 'success');
                    } else {
                        numReactivos = numReactivos + 1;
                        document.getElementById("total_react").value = numReactivos;
                        mensaje_center('Error', resp.msg, 'Intente de nuevo.', 'error');
                        siborroReactivo = false;
                    }
                });
            }
            return siborroReactivo;
        } else {
            var siborro = confirm("¿Estas seguro de borrar la sección '" + treeNode.name + "'? * Los hijos de esta sección también serán borrados. * Los reactivos asociados al nodo quedarán sin sección.");
            if (siborro) {
                getObject(uriAct, {id: treeNode.id, numReact: numReactivos}, function (resp) {
                    if (resp.res == 'ok') {
                        numReactivos = parseInt(resp.NumeroReactivos);
                        document.getElementById("total_react").value = numReactivos;
                        if (numReactivos == 0) {
                            $('#btn_export').prop("disabled", true);
                            $('#btn_download').prop("disabled", true);
                            $('#btn_export_word').prop("disabled", true);
                        } else {
                            $('#btn_export').prop("disabled", false);
                            $('#btn_download').prop("disabled", false);
                            $('#btn_export_word').prop("disabled", false);

                        }
                        notify_block('Eliminar sección', 'La sección se eliminó satisfactoriamente.', '', 'success');
                        siborro = true;
                    } else {
                        mensaje_center('Error', resp.msg, 'Intente de nuevo.', 'error');
                        siborro = false;
                    }
                });
            }
            return siborro;
        }
    }

    var newCount = 1;
    function addHoverDom(treeId, treeNode) {
        if (!treeNode.isParent && (treeNode.name.substring(0, 1) === '~')) {
            $("#" + treeNode.tId + "_remove").remove();
            //$("#"+treeNode.tId+"_edit" ).remove();
            return false;
        } else {
            var sObj = $("#" + treeNode.tId + "_span");
            if (treeNode.editNameFlag || $("#addBtn_" + treeNode.tId).length > 0)
                return;
            var addStr = "<span class='button add' id='addBtn_" + treeNode.tId
                    + "' title='add node' onfocus='this.blur();'></span>";
            sObj.after(addStr);
            var btn = $("#addBtn_" + treeNode.tId);
            if (btn)
                btn.bind("click", function () {
                    var nom = "Nueva sección " + (newCount++);
                    var id =<?php echo $this->uri->segment(3); ?>;
                    var resp = get_object('examenes/addNodo', {pid: treeNode.id, nom: nom, idExamen: id});
                    if (resp.res && resp.res == 'ok') {
                        var zTree = $.fn.zTree.getZTreeObj("container");
                        zTree.addNodes(treeNode, {id: resp.insert_id, pId: treeNode.id, name: nom});
                    }
                    return false;
                });
        }
    }

    function removeHoverDom(treeId, treeNode) {
        $("#addBtn_" + treeNode.tId).unbind().remove();
    }

    function assingValueA() {
        getObject('examenes/getExamData', {id: idExamen}, function (resp) {
            if (resp === null) {
                redirectTo('examenes/notFound');
            } else {
                var clave = resp.clave, name = resp.nombre;
                numReactivos = parseInt(resp.totalReactivos);
                document.getElementById("clv_exam").value = clave;
                document.getElementById("name_exam").value = name;
                document.getElementById("total_react").value = numReactivos;
                if (numReactivos == 0) {
                    $('#btn_export').prop("disabled", true);
                    $('#btn_download').prop("disabled", true);
                    $('#btn_export_word').prop("disabled", true);
                } else {
                    $('#btn_export').prop("disabled", false);
                    $('#btn_download').prop("disabled", false);
                    $('#btn_export_word').prop("disabled", false);
                }
            }
        });
    }
    $(document).ready(function () {
        assingValueA();
        $('#btn_export').click(function () {
            getObject('examenes/export', {idExamen: idExamen}, function (resp) {
                if (resp.res == 'ok') {
                    notify_block('Exportar examen', 'Los datos del examen se exportaron satisfactoriamente.', '', 'success');
                } else {
                    mensaje_center('Error', resp.msg, '', 'error');
                }
            });
        });
        $('#btn_download').click(function () {
            redirectTo('examenes/download_arch/' + idExamen);
        });
        /*$('#btn_export_word').click(function () {
            var answersMark=0;
            BootstrapDialog.show({
                title: 'Exportar examen',
                message: "<div class='has-warning'><div class='checkbox'><label><input type='checkbox' id='markAnswers' value='' > Marcar opciones de respuesta correcta</label></div>",
                buttons: [{
                        cssClass: 'btn-primary',
                        label: 'Exportar',
                        action: function (dialog) {
                            if($('#markAnswers').prop('checked')){
                                answersMark=1;
                            }
                            getObject('examenes/exportWord', {idExamen: idExamen, ansMark: answersMark}, function (resp) {
                                if (resp.res == 'ok') {
                                    $('#modalTitle').append('Examen');
                                    $('#containerReactivos').append(resp.content);
                                    $('#modalExamen').modal('show');
                                } else {
                                    //$('#containerReactivos').html('<p>No hay reactivos asociados al examen</p>');
                                }
                            });
                            dialog.close();
                        }
                    }, {
                        label: 'Cancelar',
                        action: function (dialog) {
                            dialog.close();
                        }
                    }]
            });
        });*/
    
    $('#btn_export_word').click(function () {
            var answersMark = 0;
            BootstrapDialog.show({
                title: 'Exportar examen',
                message: "<div class='has-warning'><div class='checkbox'><label><input type='checkbox' id='markAnswers' value='' > Marcar opciones de respuesta correcta</label></div>",
                buttons: [{
                cssClass: 'btn-primary',
                label: 'Exportar',
                action: function (dialog) {
                    if ($('#markAnswers').prop('checked')) {
                        answersMark = 1;
                    }
                    var nombreWord = document.getElementById("name_exam").value;
                    redirectTo('examenes/exportWord2/' + nombreWord + '/' + idExamen + '/' + answersMark);
                    dialog.close();
                    }
                }, {
                    label: 'Cancelar',
                    action: function (dialog) {
                        dialog.close();
                    }
                }]
            });
        });
        
        $('#cancelarWord').click(function () {
            $('#containerReactivos').empty();
            $('#modalTitle').empty();
        });
        
        $.fn.zTree.init($("#treeDemo"), setting);
        $.fn.zTree.init($("#container"), setting2);
        $('#btn_update_react').click(function () {
            redirect_to('examenes/');
        });
        //actualizar arbol (realmente se reinicia el plugin)
        $('#btn_reset_tree').click(function () {
            $.fn.zTree.init($("#treeDemo"), setting);
        });

        $('#btn_new_nodo_master').click(function () {
            var nom = "Nueva sección " + (newCount++);
            getObject('examenes/addNodo', {pid: '0', nom: nom, idExam: idExamen}, function (resp) {
                if (resp.res == 'ok') {
                    var zTree = $.fn.zTree.getZTreeObj("container");
                    zTree.addNodes(null, {id: resp.insert_id, pId: 0, name: nom});
                }
            });
        });
    });
    $('[data-toggle="tooltip"]').tooltip();//mostrar información del <i></i>
    jQuery(document).ready(function ($) {
        $("#downloadWord").click(function (event) {
            var nombreArchivoWord = document.getElementById("name_exam").value;
            $("#containerReactivos").wordExport('examen-' + nombreArchivoWord);
            $('#modalExamen').modal('hide');
        });
    });
</SCRIPT>

<style type="text/css">
    ul.ztree  {
        margin-top: 10px;
        border: 2px dotted #CCCCCC;
        background: #F0F0F0;
        width: 100%;
        height: 360px;
        overflow-y: scroll;
        overflow-x: auto;
    }
    .container_delete{
        padding-left: 10%;
    }

    ul#treeDemo {
        width: 100%;
    }
    .ztree li span.button.add {margin-left:2px; margin-right: -1px; background-position:-144px 0; vertical-align:top; *vertical-align:middle}  
    .domBtn {display:inline-block;cursor:pointer;padding:2px;margin:2px 10px;border:1px gray solid;background-color:#24200F;color:white;font-weight: 600;border-radius: 20px;}
    .dom_tmp {position:absolute;font-size:12px;}/*Mantiene el span visible*/

    @media (min-width: 1200px) {}
    @media (min-width: 992px) and (max-width: 1199px) {#btn_download{margin-top:1%;}}
    @media (min-width: 768px) and (max-width: 991px) {#btn_export{margin-top: 1%;}#btn_export_word{margin-top:1%;}#btn_download{margin-top:1%;}}
    @media (max-width: 767px) {#btn_export{margin-top: 2%;}#btn_export_word{margin-top:2%;}#btn_download{margin-top:2%;}}
    @media (max-width: 480px) {#btn_export{margin-top: 2%;}#btn_export_word{margin-top:2%;}#btn_download{margin-top:2%;}}
</style>
<div class="row" id='containerButtons'>
    <div class="col-xs-12 col-md-12">
        <div class="box box-primary box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Datos del examen</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                </div><!-- /.box-tools -->
            </div><!-- /.box-header -->
            <div class=" row box-body">
                <form id='form_completar_examen' role='form'>   

                    <div class="col-md-2" id='buttonsEdicion'>
                        <strong >Clave del examen:</strong><input class="form-control" name='clv_exam' id="clv_exam" type="text" value='' disabled><br>    
                    </div>
                    <div class="col-md-2">
                        <strong> Nombre del examen:</strong><input class="form-control" name='name_exam' id="name_exam" type="text" value='' disabled>
                    </div>
                    <div class="col-md-2">
                        <strong> Total de reactivos:</strong><input class="form-control" name='total_react' id="total_react" type="text" value='' disabled>
                    </div>
                    <div class="col-md-2">

                    </div>
                    <div class="col-md-2" id='buttonsEdicion'></div>
                </form>
                <div class="col-md-4" id="buttons_export" >
                    <button id="btn_export" class="btn btn-primary " data-toggle="tooltip" title="Exporta el examen en formato csv."><i class="fa fa-paper-plane-o"></i>&nbsp;Exportar csv</button>
                    <button id="btn_export_word" class="btn btn-primary " data-toggle="tooltip" title="Exporta el examen en formato word."  ><i class="fa fa-file-word-o"></i>&nbsp;Exportar word</button>
                    <button id="btn_download" class="btn btn-primary " ><i class="fa fa-download"></i>&nbsp;Descargar csv</button> 
                </div> 
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>
</div>
<div class="row" id='datos'>
    <div class="col-md-6">
        <div class="box box-primary box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Reactivos disponibles</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                </div><!-- /.box-tools -->
            </div><!-- /.box-header -->
            <div class="box-body" id="bancoReactivos">
                <button id="btn_reset_tree" class="btn btn-default" ><i class="fa fa-refresh"></i> Actualizar</button>
                <ul id="treeDemo" class="ztree"></ul>
            </div><!-- /.box-body -->
        </div><!-- /.box -->

    </div>
    <div class="col-md-6 exam" >
        <div class="box box-primary box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Reactivos ligados al examen</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                </div><!-- /.box-tools -->
            </div><!-- /.box-header -->
            <div class="box-body">
                <button id="btn_new_nodo_master" class="btn btn-default"><i class="fa fa-plus"></i> Agregar Sección (Raíz)</button> 
                <button id="btn_update_react" class="btn btn-default" ><i class="fa fa-check"></i> Terminar examen</button>
                <ul id="container" class="ztree"></ul>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>  
</div>
<!-- Modal -->
<div id="modalExamen" class="modal fade in" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalTitle"></h4>
            </div>
            <div id="containerReactivos"class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="downloadWord">Descargar word</button>
                <button type="button" class="btn btn-default" id="cancelarWord" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div> 