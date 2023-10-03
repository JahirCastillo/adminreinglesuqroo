<link href="./plugins/zTreeJS/css/zTreeStyle/zTreeStyle.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="./js/modal_bootstrap_extend/js/bootstrap-dialog.js"></script>
<script src="./plugins/zTreeJS/js/jquery.ztree.core.min.js" type="text/javascript"></script>
<script src="./plugins/zTreeJS/js/jquery.ztree.excheck.min.js" type="text/javascript"></script>
<script src="./plugins/zTreeJS/js/jquery.ztree.exedit.min.js" type="text/javascript"></script>
<SCRIPT type="text/javascript">
    var setting = {
        async: {
            enable: true,
            url: "index.php/plan/getArbol/planes/0",
            autoParam: ["id", "name=n", "level=lv"],
            otherParam: {
                "otherParam": "zTreeAsyncTest"
            },
            dataFilter: filter
        },
        view: {
            expandSpeed: "",
            <?php if (isset($permisos_modulo) && (in_array('add', $permisos_modulo))) { ?>addHoverDom: addHoverDom,
        <?php } ?>

        removeHoverDom: removeHoverDom,
        addDiyDom: addDiyDom,

        selectedMulti: false
        },
        edit: {
            enable: true,
            showRemoveBtn: <?php if (isset($permisos_modulo) && (in_array('del', $permisos_modulo))) { ?>true<?php } else { ?>false<?php } ?>,
            showRenameBtn: <?php if (isset($permisos_modulo) && in_array('upd', $permisos_modulo)) { ?>true<?php } else { ?>false<?php } ?>
        },
        data: {
            simpleData: {
                enable: true
            }
        },
        callback: {
            beforeRemove: beforeRemove,
            beforeRename: beforeRename,
            onDblClick: onDblClick,
            beforeDrop: beforeDrop,
            onDrop: zTreeOnDrop,
            onRightClick: OnRightClick
        }
    };

    function zTreeBeforeDrag(treeId, treeNodes) {
        return treeId;
    }



    function onDblClick(event, treeId, treeNode) {
        try {
            if (!treeNode.isParent && (treeNode.name.substring(0, 1) === '~')) {
                openInNew('reactivo/update/' + treeNode.id);
            }
        } catch (e) {}
    }

    function myBeforeMouseUp(treeId, treeNode) {
        return false;
    }

    function beforeDrop(treeId, treeNodes, targetNode, moveType) {
        var
            nodoActual = treeNodes[0].id,
            nodeName = treeNodes[0].name,
            typeNode = 'P',
            typeTar = 'P',
            nodoPadre = targetNode.id;
        if (nodeName.substring(0, 1) === '~') {
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
                try {
                    var resp = get_object('plan/moveNodo', {
                        id: nodoActual,
                        pid: nodoPadre,
                        type: typeNode,
                        typeTar: typeTar
                    });
                    if (resp.res && resp.res == 'ok') {
                        return true;
                    } else if (resp.res == 'no') {
                        mensaje_center('Error', resp.msg, '', 'error');
                    }
                } catch (e) {

                }
                //sino si se movió a otra posicion dentro del mismo nodo
            } else if (moveType == 'next') {

            }
        }
        return false;
    }

    function zTreeOnDrop(event, treeId, treeNodes, targetNode, moveType) {

    }

    function filter(treeId, parentNode, childNodes) {
        if (!childNodes)
            return null;
        for (var i = 0, l = childNodes.length; i < l; i++) {
            childNodes[i].name = childNodes[i].name.replace(/\.n/g, '.');
        }
        return childNodes;
    }

    function beforeRemove(treeId, treeNode) {
        var uriAct = 'plan/deleteNodo';
        if (treeNode.isParent === false && (treeNode.name.substring(0, 1) === '~')) {
            uriAct = 'reactivo/delete';
        }
        var zTree = $.fn.zTree.getZTreeObj("treeDemo");
        zTree.selectNode(treeNode);
        var siborro = confirm("¿Estas seguro de borrar el plan '" + treeNode.name + "'? * Los hijos de este plan también serán borrados. * Los reativos asociados al nodo quedarán sin plan.");
        if (siborro) {
            var resp = get_object(uriAct, {
                id: treeNode.id
            });
            if (resp.res && resp.res == 'ok') {
                siborro = true;
            } else {
                siborro = false;
            }
        }
        return siborro;
    }

    function beforeRename(treeId, treeNode, newName) {
        if (!treeNode.isParent && (treeNode.name.substring(0, 1) === '~')) {
            openInNew('reactivo/update/' + treeNode.id);
            return false;
        } else {
            if (newName.length == 0) {
                alert("Node name can not be empty.");
                return false;
            }
            var resp = get_object('plan/editNodoName', {
                id: treeNode.id,
                nom: newName
            });
            if (resp.res && resp.res == 'ok') {
                return true;
            } else {
                return false;
            }
        }
    }

    var newCount = 1;

    function addHoverDom(treeId, treeNode) {
        if (!treeNode.isParent && (treeNode.name.substring(0, 1) === '~')) {
            //$("#"+treeNode.tId+"_remove" ).remove();
            $("#" + treeNode.tId + "_edit").remove();
            return false;
        } else {
            var sObj = $("#" + treeNode.tId + "_span");
            if (treeNode.editNameFlag || $("#addBtn_" + treeNode.tId).length > 0)
                return;
            var addStr = "<span class='button add' id='addBtn_" + treeNode.tId +
                "' title='add node' onfocus='this.blur();'></span>";
            sObj.after(addStr);
            var btn = $("#addBtn_" + treeNode.tId);
            if (btn)
                btn.bind("click", function() {
                    var nom = "Nuevo Plan " + (newCount++);
                    var resp = get_object('plan/addNodo', {
                        pid: treeNode.id,
                        nom: nom
                    });
                    if (resp.res && resp.res == 'ok') {
                        var zTree = $.fn.zTree.getZTreeObj("treeDemo");
                        zTree.addNodes(treeNode, {
                            id: resp.insert_id,
                            pId: treeNode.id,
                            name: nom
                        });
                    }
                    return false;
                });

            if (treeNode.editNameFlag || $("#diyBtn_" + treeNode.id).length > 0)
                return;
            let editStr = "<span class='demoIcon' id='diyBtn_" + treeNode.id + "' title='Agregar reactivo a: " + treeNode.name + "' onfocus='this.blur();'><span class='button icon01'></span></span>";
            sObj.append(editStr);
            var btn2 = $("#diyBtn_" + treeNode.id);
            if (btn2) btn2.bind("click", function() {
               agregaReactivoARama(treeNode.id,treeNode.name);
            });
        }
    }

    function removeHoverDom(treeId, treeNode) {
        $("#addBtn_" + treeNode.tId).unbind().remove();
        $("#diyBtn_" + treeNode.id).unbind().remove();

    }
    function addDiyDom(treeId, treeNode) {
       

    }

    function agregaReactivoARama(idPlan,nombrePlan){
        console.log(idPlan);
        redirectByPost('reactivo/update/',{plan_id:idPlan,plan_nombre:nombrePlan},true);
    }
  

    var seleccionado;

    function OnRightClick(event, treeId, treeNode) {
        var headerHeight = $('header').outerHeight();
        headerHeight = headerHeight + 90;
        if (treeNode && (treeNode.name.substring(0, 1) === '~')) {
            seleccionado = treeNode; //zTree.selectNode(treeNode);
            showRMenu("reactivo", event.clientX - 10, event.clientY - headerHeight);
        } else if (treeNode.name.substring(0, 1) != '~') {
            seleccionado = treeNode;
            showRMenu("rama", event.clientX - 10, event.clientY - headerHeight);
        }
    }

    function hideRMenu() {
        if (rMenu)
            rMenu.css({
                "visibility": "hidden"
            });
        $("body").unbind("mousedown", onBodyMouseDown);
    }

    function showRMenu(type, x, y) {
        $("#containerMenu").show();
        if (type == "reactivo") {
            $("#info").hide();
            $("#copiar").show();
            $("#pegar").hide();
            $("#hijosRama").hide();
        } else if (type == "rama") {
            $("#info").show();
            $("#copiar").show();
            $("#pegar").show();
            $("#hijosRama").show();
        }
        rMenu.css({
            "top": y + "px",
            "left": x + "px",
            "visibility": "visible"
        });
        //$("body").bind("mousedown", onBodyMouseDown);
    }

    function onBodyMouseDown(event) {
        if (!(event.target.id == "rMenu" ||
                $(event.target).parents("#rMenu").length > 0)) {
            rMenu.css({
                "visibility": "hidden"
            });
        }
    }

    function fontCss(treeNode) {
        var aObj = $("#" + treeNode.tId + "_a");
        aObj.removeClass("copy").removeClass("cut");
        if (treeNode === curSrcNode) {
            if (curType == "copy") {
                aObj.addClass(curType);
            } else {
                aObj.addClass(curType);
            }
        }
    }
    var curSrcNode, curType;

    function setCurSrcNode(treeNode) {
        var zTree = $.fn.zTree.getZTreeObj("treeDemo");
        if (curSrcNode) {
            delete curSrcNode.isCur;
            var tmpNode = curSrcNode;
            curSrcNode = null;
            fontCss(tmpNode);
        }
        curSrcNode = treeNode;
        if (!treeNode)
            return;

        curSrcNode.isCur = true;
        zTree.cancelSelectedNode();
        fontCss(curSrcNode);
    }

    function copiar() {
        curType = "copy";
        setCurSrcNode(seleccionado);
        hideRMenu();
    }

    function pegar() {
        if (!curSrcNode) {
            alert("Porfavor primero selecciona un reactivo o una rama.");
            hideRMenu();
            return;
        }
        var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
            nodes = zTree.getSelectedNodes(),
            targetNode = nodes.length > 0 ? nodes[0] : null;
        if (curSrcNode === targetNode) {
            alert("No se puede copiar el mismo nodo de origen al mismo nodo de destino.");
            return;
        }
        if (curType === "copy") {
            targetNode = zTree.copyNode(targetNode, curSrcNode, "inner");
            if (!targetNode.isParent && (targetNode.name.substring(0, 1) === '~')) {
                getObject('reactivo/copy', {
                    nodoACopiar: targetNode.id,
                    nodoDestino: nodes[0].id
                }, function(resp) {
                    if (resp.res == 'ok') {
                        notify_block('Copiar reactivos', 'Reactivo copiado', '', 'success');
                        /* var treeObj = $.fn.zTree.getZTreeObj("treeDemo");
                         treeObj.reAsyncChildNodes(null, "refresh");*/
                    } else {
                        mensaje_center('Error', resp.msg, 'Intente de nuevo.', 'error');
                        var treeObj = $.fn.zTree.getZTreeObj("treeDemo");
                        $.fn.zTree.init($("#treeDemo"), setting);
                    }
                });
            } else {
                if (targetNode.isParent && (targetNode.name.substring(0, 1) != '~')) {
                    getObject('plan/copyRama', {
                        nodoOrigen: targetNode.id,
                        nodoDestino: nodes[0].id,
                        nodoContenedor: targetNode.name
                    }, function(resp) {
                        if (resp.res == 'ok') {
                            notify_block('Copiar reactivos', 'Reactivos copiados satisfactoriamente.', '', 'success');
                            /*var treeObj = $.fn.zTree.getZTreeObj("treeDemo");
                            treeObj.reAsyncChildNodes(null, "refresh");*/
                        } else {
                            mensaje_center('Error', resp.msg, 'Intente de nuevo.', 'error');
                            var treeObj = $.fn.zTree.getZTreeObj("treeDemo");
                            $.fn.zTree.init($("#treeDemo"), setting);
                        }
                    });
                    //console.log(targetNode.id + ' ' + targetNode.name);
                }

            }
        }
        hideRMenu();
    }
    var listaInfo = '';

    function info() {
        var resp = get_object('plan/contar', {
            id: seleccionado.id
        });
        hideRMenu();
        listaInfo = ' <ul class="list-group">\n\
                        <li onclick="listRamas();" class="list-group-item">\n\
                            <span  class="badge">' + resp.totalRamas + '</span>Total de ramas en ' + seleccionado.name + '</li><br>\n\
                        <li class="list-group-item"><span class="badge">' + resp.totalReactivos + '</span>Total de reactivos en ' + seleccionado.name + ' </li>\n\
                    </ul>';
        $.each(resp.ramasEnExamen, function(i, item) {
            console.log(item.exa_nombre);
        });
        $('#contenidoInformacion').append(listaInfo);
        $('#informacionModal').modal('show');
    }

    function despliegaHijos() {
        var stringLista = "";
        console.log(seleccionado.name);
        getObject('plan/getRamasContenidas', {
            idPlan: seleccionado.id
        }, function(resp) {
            stringLista = "<ul><li> " + seleccionado.name + "</li>";
            $.each(resp, function(i, item) {
                stringLista += "<li> " + item + "</li>";
            });
            stringLista += "</ul>";
            BootstrapDialog.show({
                title: 'Ramas contenidas',
                message: stringLista,
                buttons: [{
                    cssClass: 'btn-primary',
                    label: 'Aceptar',
                    action: function(dialog) {
                        dialog.close();
                    }
                }]
            });
        });
        stringLista = "";
        hideRMenu();
    }

    var rMenu;
    $(document).ready(function() {
        rMenu = $("#containerMenu");
        $.fn.zTree.init($("#treeDemo"), setting);
        //agregar nuevo nodo master
        $('#btn_new_nodo_master').click(function() {
            var nom = "Nuevo Plan " + (newCount++);
            var resp = get_object('plan/addNodo', {
                pid: '0',
                nom: nom
            });
            if (resp.res && resp.res == 'ok') {
                var zTree = $.fn.zTree.getZTreeObj("treeDemo");
                zTree.addNodes(null, {
                    id: resp.insert_id,
                    pId: 0,
                    name: nom
                });
            }
        });
        //actualizar arbol (realmente se reinicia el plugin)
        $('#btn_reset_tree').click(function() {
            $.fn.zTree.init($("#treeDemo"), setting);
        });
        $('#treeDemo').click(function() {
            hideRMenu();
        });
        $('#aceptarInfo').click(function() {
            $('#informacionModal').modal('hide');
            $('#contenidoInformacion').empty();
            listaInfo = '';
        });
    });
</SCRIPT>
<style type="text/css">
    ul.ztree {
        margin-top: 10px;
        border: 2px dotted #CCCCCC;
        background: #F0F0F0;
        width: 220px;
        height: 360px;
        overflow-y: scroll;
        overflow-x: auto;
    }

    ul#treeDemo {
        width: 100%;
    }

    .ztree li span.button.add {
        margin-left: 2px;
        margin-right: -1px;
        background-position: -144px 0;
        vertical-align: top;
        *vertical-align: middle
    }

    .ztree li a.copy {
        padding-top: 0;
        background-color: #316AC5;
        color: white;
        border: 1px #316AC5 solid;
    }

    .ztree li a.cut {
        padding-top: 0;
        background-color: silver;
        color: #111;
        border: 1px #316AC5 dotted;
    }

    #containerMenu {
        visibility: hidden;
        position: absolute;
        left: 0px;
        top: 0px;
    }

    .list-group li {
        padding: 5px 5px;
        cursor: pointer;
    }

    .ztree li span.demoIcon {
        padding: 0 2px 0 10px;
    }

    .ztree li span.button.icon01 {
        margin: 0;
        background: url(../../../Nube/adminre/images/add-button.png) no-repeat scroll 0 0 transparent;
        vertical-align: top;
        *vertical-align: middle
    }
</style>
<div class="row">
    <div class="col-md-8">
        <div>
            <?php if (isset($permisos_modulo) && (in_array('add', $permisos_modulo))) { ?><button id="btn_new_nodo_master" class="btn btn-default"><i class="fa fa-plus"></i> Agregar Plan (Raíz)</button><?php } ?>
            <button id="btn_reset_tree" class="btn btn-default"><i class="fa fa-refresh"></i> Actualizar</button>
        </div>
    </div>
    <div class="col-md-4">
        <div class="label label-info">
            <i class="fa fa-info-circle"></i> Dá doble clic sobre el reactivo para editar
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <ul id="treeDemo" class="ztree"></ul>
    </div>
</div>
<div id="containerMenu" class="box-solid box-info">
    <ul class="list-group">
        <li id="info" class="list-group-item" onclick="info();"><i class="fa fa-info-circle" aria-hidden="true"></i> Información</li>
        <li id="copiar" class="list-group-item" onclick="copiar();" title="copy"><i class="fa fa-files-o" aria-hidden="true"></i> copiar</li>
        <li id="pegar" class="list-group-item" onclick="pegar();" title="paste"><i class="fa fa-clipboard" aria-hidden="true"></i> pegar</li>
        <li id="hijosRama" class="list-group-item" onclick="despliegaHijos();" title="Ramas"><i class="fa fa-level-down" aria-hidden="true"></i> Desplegar ramas contenidas</li>
    </ul>
</div>
<div id="informacionModal" class="modal fade in" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalTitle">Información</h4>
            </div>
            <div id="contenidoInformacion" class="modal-body">

            </div>
            <div class="modal-footer">
                <button id="aceptarInfo" type="button" class="btn btn-primary">Aceptar</button>
            </div>
        </div>
    </div>
</div>