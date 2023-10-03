var contenido_reactivo = '',
    textoNotasReac = '';

function tinyMCE_OnInit() {
    if (contenido_reactivo != '') {
        tinyMCE.get('rea_contenido').setContent(contenido_reactivo);
    }
    if (textoNotasReac != "") {
        tinyMCE.get('textoNotas').setContent(textoNotasReac);
    }
}
/**
 * Comment
 */
function muestraBusquedaReactivo(estado) {
    $("#mostrarPlan").hide();
    if (estado) {
        $("#cadenaReactivo").focus();
        $("#mostrarReactivo").show();
    } else {
        $("#mostrarReactivo").hide();
    }
}

function cierraBusquedaPlan() {
    $('#mostrarPlan').hide();
}
//-----------buscar registros de plan------------------
/**
 * busca concidencia de registros de plan de estudios
 *
 *@param varchar palabra, cadena a buscar en reactivos.
 *@return array, registros que contiene la cadena parametro palabra.
 */
function buscarPlan(palabra) {
    $.post("index.php/reactivo/buscarPlan", {
            palabra: palabra
        },
        function (data) {
            $('#datosPlan').dataTable().fnClearTable();
            $('#div_busca_plan_datatables .letter_min .max').remove();
            var clave, nombre, btn;
            $.each(data, function (i, aDatos) {
                if (i < 30) {
                    if (aDatos.hij == 0) {
                        nombre = "'" + aDatos.nom + "'";
                        btn = '<button class="btn btn-primary" onClick="llenarPlan(' + aDatos.cla + ',' + nombre + ')">Seleccionar</button>';
                    } else
                        btn = '';
                    clave = aDatos.cla;
                    $('#datosPlan').dataTable().fnAddData([
                        clave,
                        aDatos.nom,
                        aDatos.des,
                        btn
                    ]);
                } else {
                    $('#div_busca_plan_datatables .letter_min').prepend('<div class="max alert alert-warning" role="alert">Hay más de 30 resultados. Sea más específico en la búsqueda.</div>');
                }
            });
        }, 'json');
}

function cargaVistaPrevia(idrea) {
    var ops_temp = $('#opciones').html();
    $('#div_vp_opcresp, #vp_contenido, .caso_data').html('');
    $('#Vintrucciones').parent().hide();
    try {
        var data = get_object('reactivo/dataReaVP', {
            idrea: idrea
        });
        if (data && data.id) {
            $('#vp_contenido').html(data.con);
            //cargar opciones de respuesta
            if (data.opcres) {
                var opcs_html = '';
                $.each(data.opcres, function (index, opc) {
                    var chk = '',
                        tipo = opc.tip,
                        contenido = '';
                    switch (tipo) {
                        case 'txt':
                            contenido = opc.con;
                            break;
                        case 'img':
                            contenido = opc.img;
                            break;
                        case 'aud':
                            contenido = opc.aud;
                            break;
                        case 'vid':
                            contenido = opc.vid;
                            break;
                        default:
                            break;
                    }
                    opcs_html += agrega_opres_display('opc_' + index, index, contenido, opc.escorrecta, tipo, idrea, opc.escorrecta, true);
                });

                $('#opciones .control_opcresp_render').each(function (index) {
                    $(this).find('.opcresp').attr('name', 'opcres_vp_' + index);
                });
                var ops = $('#opciones').html();
                $('#opciones').html(ops_temp);
                $('#div_vp_opcresp').html(ops);
            }

            //datos de caso
            if (data.caso.id) {
                $('#Vintrucciones').parent().show();
                $('#div_vp_caso,#vp_cas_instruccion').show();
                $('#Vintrucciones').text(data.caso.ins);
                $('#vp_cas_tit').text(data.caso.tit);
                $('#vp_cas_con').html(data.caso.con);

                if (data.caso.img != '' && data.caso.img + '' != 'null') {
                    $('#vp_cas_img').html('<div class="col-md-10 col-md-offset-1"><img src="./media_casos/caso' + data.caso.id + '/' + data.caso.img + '"/></div>');
                }
                if (data.caso.aud != '' && data.caso.img + '' != 'null') {
                    $('#vp_cas_aut').html('<audio src="./media_casos/caso' + data.caso.id + '/' + data.caso.aud + '" controls></audio>');
                }
                if (data.caso.vid != '' && data.caso.img + '' != 'null') {
                    $('#vp_cas_vid').html('<video src="./media_casos/caso' + data.caso.id + '/' + data.caso.vid + '" controls></video>');
                }
            } else {
                $('#div_vp_caso,#vp_cas_instruccion').hide();
            }
        }
    } catch (e) {

    }
}

function habilita_reactivo(est) {
    $('.info_noreactivo').remove();
    if (est) {
        $('#cap_reactivo input, select, textarea').attr('disabled', false);
        $('#cap_reactivo button').show();
        $('#caso button').show();
        $('#btn_guarda_rea').show();
        $('#referencia button').show();
        $('#tipo_reactivo').attr('disabled', false);
    } else {
        $('#cap_reactivo input, select, textarea').attr('disabled', true);
        $('#cap_reactivo button').hide();
        $('#caso input, select, textarea').attr('disabled', true);
        $('#caso button').hide();
        $('#btn_guarda_rea').hide();
        $('#referencia button').hide();
        var estado = '',
            msg = '';
        if ($("#estado_r").hasClass('activo')) {
            estado = '<b>pendiente de revisión</b>';
            msg = ' <b>-Para habilitar el reactivo cambie el estado a Captura.</b>';
        } else if ($("#estado_a").hasClass('activo')) {
            estado = '<b>revisado</b>';
        }
        $('#div_panel1').after('<div class="alert alert-warning info_noreactivo alert-dismissible" role="alert"><button type="button" style="position: inherit;" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><img width="50" style="margin-top: -5px;margin-bottom: -5px; margin-right:10px" src="./images/not/info.png">El reactivo se encuentra en estado: ' + estado + ' por lo tanto no puede ser modificado.' + msg + ' </div>');
    }
    $('#opciones input, select, textarea').attr('disabled', true); //opciones siempre deshabilitadas
    if (est)
        $('#tipo_reactivo').attr('disabled', false);
}

function cambia_estado_reactivo(est) {
    $("#btn_group_st button").removeClass('active');
    $("#btn_group_st button").removeClass('activo');
    if (est == 'C') {
        $("#estado_r").removeClass('btn-warning');
        $("#estado_c").addClass('btn-danger active');
        $("#estado_a").removeClass('btn-success');
        $('#estado_a').attr('disabled', true);
        $('#estado_r, #estado_c').attr('disabled', false);
        habilita_reactivo(true);
    } else if (est == 'R') {
        $("#estado_r").addClass('btn-warning active activo');
        $("#estado_c").removeClass('btn-danger');
        $("#estado_a").removeClass('btn-success');
        $('#estado_a').attr('disabled', true);
        $('#estado_r, #estado_c').attr('disabled', false);
        habilita_reactivo(false);
    } else if (est == 'A') {
        $("#estado_r").removeClass('btn-warning');
        $("#estado_c").removeClass('btn-danger');
        $("#estado_a").addClass('btn-success active activo');
        $('#estado_r,#estado_c,#estado_a').attr('disabled', true);
        habilita_reactivo(false);
    }
}

function rm_comment(id) {
    var data = get_object('reactivo/rmComment', {
        id: id
    });
    if (data.resp == 'ok') {
        $('.comments_rea').remove();
    }
}

//-----------------datos Reativo-----------------------
/**
 * obtine datos de un registro de la tabla reactivo.
 * @param int clave, identificador del registro de datos a buscar.
 * @return array datos, datos del registro.
 */
var idBloqueRe;

function llenarReactivo(idrea) {
    $('#btn_siguienteReactivo').hide();
    $.blockUI({
        message: '<br><br><br><font style="color: #999999; font-size: 30px;">Espere un momento...</font><br><br><br><br>',
        fadeIn: 1,
        timeout: 2,
        onBlock: function () {
            limpia_reactivo();
            var data = get_object('reactivo/llenarReactivo', {
                idrea: idrea
            });
            if (!data.permisoVerReactivo) {
                alert("No tienes permiso de consultar este reactivo");
                redirectTo('plan');
            }
            cambia_estado_reactivo(data.est);
            var id_plan = data.pid;
            if (id_plan && id_plan != '' && id_plan != '0') {
                $("#pla_clave").val(id_plan);
                $("#one").val(id_plan);
                $("#one").text(data.pnom);
                $("#dropdownMenu2").text(data.pnom);
                let arrayPlan = [3079, 3080, 3081, 3082, 3083, 3084, 3085, 3086, 3087, 3088, 3089, 3090, 3091, 3092, 3093, 3094, 3095, 3096, 3097, 3098, 3099, 3100, 3101, 3102, 3103, 3104, 3105, 3106, 3107, 3108, 3109, 3110, 3111, 3112, 3113, 3114, 3115, 3116, 3117, 3118, 3119, 3120, 3121, 3122, 3123, 3124, 3125, 3126, 3127, 3128, 3129, 3130, 3131, 3132, 3133, 3134, 3135, 3137, 3138, 3139, 3140, 3141, 3142, 3143, 3144, 3145, 3146, 3147, 3148, 3149, 3150, 3151, 3152, 3155, 3157, 3158, 3159, 3160, 3161, 3162, 3163, 3164, 3165];
                if (arrayPlan.indexOf(id_plan) >= 0) {
                    $('#containerMateriasCompetencias').show();
                }
                if (arrayPlan.indexOf(id_plan) == -1) {
                    $('#containerMateriasCompetencias').hide();
                }
            } else {
                $("#pla_clave").val('');
                $("#one").val('');
                $("#one").text('');
                $("#dropdownMenu2").text('');
            }
            $("#puntos_reactivo").val(data.puntos);
            $("#pla_nombre").val(data.pnom);
            $("#rea_clave").val(data.id);
            $('#id_habilidad').val(data.rea_habilidad);
            $('#texto_habilidad').val(data.rea_txthabilidad);
            $('#id_padre_habilidad').val(data.rea_idpadrehab);
            $('#texto_padre_habilidad').val(data.rea_txtpadrehab);
            $('#materia_reactivo').val(data.rea_id_materia);
            $('#materia_reactivo').change();
            setTimeout(function () {
                $('#competencia_materia').val(data.rea_id_competencia);
                $('#competencia_materia').change();
            }, 1);
            idBloqueRea = data.rea_id_bloque;


            $("#clvreactivo").val(data.cla);
            paddingLi = 20;
            contenido_reactivo = data.con;

            textoNotasReac = data.textoNotasRea.notas;
            try {
                tinyMCE.get('rea_contenido').setContent(data.con);
                tinyMCE.get('textoNotas').setContent(textoNotasReac);
            } catch (e) {}
            $("#tipo_reactivo").val(data.tip);
            $(".comments_rea").remove();


            //si hay mensajes mostrarlos
            if (data.com && data.com != '') {
                var com_html = '<div class="comments_rea"><div class="col-md-1"><i class="fa fa-comment"></i></div><div class="col-md-10"><b>Usuario: </b><font>' + data.uva + '</font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Fecha: </b><font>' + data.fva + '</font><br><div class="comm">' + data.com + '</div></div><div class="col-md-1"><i class="fa fa-remove btn" style="font-size:20px;" title="Eliminar comentario" onclick="rm_comment(' + data.id + ')"></i></div></div>';
                $('#menutabs').prepend(com_html);
            }
            //llenar caso
            limpiaCaso();
            if (data.cid != 0 && data.cid != '') {
                datosCaso(data.cid);
            }
            //llenar opciones de respuesta
            var opcs_html = '',
                tipo = '';
                let numOpciones=0;
            if (data.tip == 1 || data.tip == 5 ||  data.tip == 8 || data.tip == 9 || data.tip == 10 || data.tip == 11) {
                if (data.opcres) {
                    if ((Object.keys(data.opcres).length) > 0) {
                        $.each(data.opcres, function (index, opc) {
                            numOpciones++;
                            var chk = '',
                                contenido = '';
                            tipo = opc.tip;
                            switch (tipo) {
                                case 'txt':
                                    contenido = opc.con;
                                    break;
                                case 'img':
                                    contenido = opc.img;
                                    break;
                                case 'aud':
                                    contenido = opc.aud;
                                    break;
                                case 'vid':
                                    contenido = opc.vid;
                                    break;
                                default:
                                    break;
                            }
                            opcs_html += agrega_opres_display('opc_' + index, index, contenido, opc.escorrecta, tipo, idrea, opc.escorrecta, false);
                        });
                        $('#opciones').attr('data-tipomedia', tipo);
                        $('#opciones').html(opcs_html);
                        $("#area_opciones").attr('data-numopc', numOpciones);
                        cambia_st_opciones(true);
                    } else {
                        $('#opciones').html('');
                        cambia_st_opciones(false);
                    }
                }
            } //fin llena opcres
            muestraVistaPrevia(idrea);
            get_value('reactivo/setActualReactivo/' + idrea);
        }
    });
    llena_clvreactivo(idrea);
    $('#estado_a').removeAttr('disabled');
    $('[href="#datosVisuales"]').click();
    $('#btn_siguienteReactivo').show();
}

/**
 * ---------desplagarHijos------------
 * muestra los hijos de una rama en la lista de plan de estudios
 * @param int clave, identificador del registro de datos a buscar.
 * @return array data, datos del registros que dependen de plan de .
 */
function desplegarHijos(idrea) {
    if ($("#i" + idrea).hasClass('icon-chevron-up')) {
        $.post("index.php/reactivo/desplegarHijos", {
                idrea: idrea
            },
            function (data) {
                $("#" + idrea).empty();
                $("#" + idrea).html(data);
                $("#i" + idrea).removeClass('icon-chevron-up');
                $("#i" + idrea).addClass('icon-chevron-down');
            });
    } else {
        $("#" + idrea).empty();
        $("#i" + idrea).removeClass('icon-chevron-down');
        $("#i" + idrea).addClass('icon-chevron-up');
    }
}

/**
 *---------llenar campos de plan-----------------
 * muestra la clave y nombre de plan de estudio seleccionada en el formulario de reactivo.
 */
function llenarPlan(clave, nombre) {
    $("#pla_clave").val(clave);
    $("#pla_nombre").val(nombre);
    $("#mostrarPlan").hide();
    $('#contenedorPadres li:not(#one)').remove();
    $('#one').prop("disabled", false);
    $("#dropdownMenu2").text(nombre);
    $("#one").val(clave);
    $("#one").text(nombre);
    $('#contenedorPadres .elementoPadre').removeClass('seleccionado');
    paddingLi = 20;

    let arrayPlan = [3079, 3080, 3081, 3082, 3083, 3084, 3085, 3086, 3087, 3088, 3089, 3090, 3091, 3092, 3093, 3094, 3095, 3096, 3097, 3098, 3099, 3100, 3101, 3102, 3103, 3104, 3105, 3106, 3107, 3108, 3109, 3110, 3111, 3112, 3113, 3114, 3115, 3116, 3117, 3118, 3119, 3120, 3121, 3122, 3123, 3124, 3125, 3126, 3127, 3128, 3129, 3130, 3131, 3132, 3133, 3134, 3135, 3137, 3138, 3139, 3140, 3141, 3142, 3143, 3144, 3145, 3146, 3147, 3148, 3149, 3150, 3151, 3152, 3155, 3156, 3157, 3158, 3159, 3160, 3161, 3162, 3163, 3164, 3165];
    if (arrayPlan.indexOf(clave) >= 0) {
        $('#containerMateriasCompetencias').show();
    } else {
        $('#containerMateriasCompetencias').hide();
    }
    get_value('reactivo/setActualPlan/', {
        id: clave,
        nom: nombre
    });
}

function seleccionarPlan(clave, nombre) {
    $("#pla_clave").val(clave);
    $("#pla_nombre").val(nombre);
    $("#mostrarPlan").hide();
    $('#contenedorPadres li:not(#one)').remove();
    $('#one').prop("disabled", false);
    $("#dropdownMenu2").text(nombre);
    $("#one").val(clave);
    $("#one").text(nombre);
    paddingLi = 20;
}


//-----------buscar registros de Libro------------------
/** 
 * muestra todos los registros de libros arrojados por la consulta que considen con la variable palabra
 * @param char palabra, cadena a buscar considencias.
 * @return array data, muestra en el datatable de libro todos los registros con considian con la variable a buscar.
 */
function buscarLibro(palabra) {
    $.post("index.php/reactivo/buscarLibro", {
            palabra: palabra
        },
        function (data) {
            $('#datosLibro').dataTable().fnClearTable();
            $.each(data, function (i, aDatos) {
                $('#datosLibro').dataTable().fnAddData([
                    aDatos.cla,
                    aDatos.tit,
                    aDatos.des
                ]);
            });
        }, 'json');
}



//-------limpia errores de validacion--------
/**
 * oculta todos los mensajes que muestra validacion al guardar un reactivo.
 */
function limpia_error() {
    //$("#msgrea_clave").hide();
    //$("#rea_clave").removeClass('error');
    $("#msgrea_contenido").hide();
    $("#rea_contenido").removeClass('error');
    $("#rea_contenido_tbl").removeClass('error');
    $("#msgtipo_reactivo").hide();
    $("#tipo_reactivo").removeClass('error');
    $("#msgpla_clave").hide();
    $("#pla_clave").removeClass('error');
    $("#pla_nombre").removeClass('error');
    $("#msg_castitulo").hide();
    $("#cas_titulo").removeClass('error');
    $("#msg_casvideo").hide();
    $("#cas_video").removeClass('error');
    $("#msg_casaudio").hide();
    $("#cas_audio").removeClass('error');
    $("#dropdownMenu2").removeClass('sinPlan');

}

//-----------------validar reactivo----------------------
function validaReactivoLog() {
    limpia_error();
    var valido = true,
        msg_log = '';
    try {
        if (tinyMCE.get('rea_contenido').getContent() == '') {
            valido = false;
            $("#msgrea_contenido").show();
            $("#rea_contenido").addClass('error');
            $("#rea_contenido_tbl").addClass('error');
            msg_log += '* Proporcione un texto para el reactivo.<br>';
        }
    } catch (e) {}
    if ($("#tipo_reactivo").val() <= 0) {
        valido = false;
        $("#msgtipo_reactivo").show();
        $("#tipo_reactivo").addClass('error');
        msg_log += '* Seleccione tipo de reactivo.<br>';
    }
    if ($("#pla_clave").val() == '') {
        valido = false;
        $("#msgpla_clave").show();
        $("#pla_clave").focus();
        $("#pla_clave").addClass('error');
        $("#pla_nombre").addClass('error');
        $("#dropdownMenu2").addClass('sinPlan');
        msg_log += '* Seleccione un plan.<br>';
    }
    return {
        valido: valido,
        msg_log: msg_log
    };
}
/** valida y muestra mensajes de error de datos, si no encuentra ningun error redirecciona a guardarReactivo(). */
function validarReactivo() {
    var get_fnvalida = validaReactivoLog();
    if (get_fnvalida.valido) {
        saveReactivo();
    } else {
        mensaje_center('Datos faltantes', 'Proporciona los datos que faltan:', '' + get_fnvalida.msg_log, 'warning');
    }
}

function llena_clvreactivo(id_rea) {
    if ($('#clvreactivo').val() == '' && id_rea != '' && id_rea != '0') {
        $('#clvreactivo').val("REA-" + id_rea);
    }
}
//------------------guardar reactivo 1.1----(reactivo)---------------
/** 
 * obtiene los datos del formulario del reactivo y los envia para insertar o actualizar los datos de la tabla ADM_REACTIVO.
 */
function saveReactivo() {
    var id_rea = 0;
    var cla = $("#rea_clave").val();
    var cont = tinyMCE.get('rea_contenido').getContent();
    var notas = tinyMCE.get('textoNotas').getContent();


    var est = 'C',
        msg_det = '';
    if ($("#estado_c").hasClass('activo')) {
        est = 'C';
    } else if ($("#estado_r").hasClass('activo')) {
        est = 'R';
    } else if ($("#estado_a").hasClass('activo')) {
        est = 'A';
    }
    var agrego = $("#caso").attr('data-add');
    var
        cpla = $("#pla_clave").val(),
        modo = $("#rea_modocalif").val(),
        tipo = $("#tipo_reactivo").val(),
        clib = $("#lib_clave").val(),
        rclv = $("#clvreactivo").val(),
        caut = $("#aut_clave").val(),
        puntosReactivo = $('#puntos_reactivo').val(),
        idHabilidad = $('#id_habilidad').val(),
        txtHabilidad = $('#texto_habilidad').val(),
        idPadreHabilidad = $('#id_padre_habilidad').val(),
        txtPadreHabilidad = $('#texto_padre_habilidad').val(),
        idMateria = $('#materia_reactivo option:selected').val(),
        textoMateria = $('#materia_reactivo option:selected').text(),
        idCompetencia = $('#competencia_materia option:selected').val(),
        textoCompetencia = $('#competencia_materia option:selected').text(),
        idBloque = $('#bloque_competencia option:selected').val(),
        textoBloque = $('#bloque_competencia option:selected').text();

    var ccas = $("#cas_clave").val();
    var resp = get_object("reactivo/guardarReactivo", {
        clave: cla,
        contenido: cont,
        rclv: rclv,
        estado: est,
        pclave: cpla,
        modocalif: modo,
        tiporeactivo: tipo,
        cclave: ccas,
        lclave: clib,
        aclave: caut,
        textonotas: notas,
        idHab: idHabilidad,
        txtHab: txtHabilidad,
        idPadreHab: idPadreHabilidad,
        txtPadreHab: txtPadreHabilidad,
        idMateriaRea: idMateria,
        textoMateriaRea: textoMateria,
        idCompetenciaMateria: idCompetencia,
        textoCompetenciaMateria: textoCompetencia,
        idBloque: idBloque,
        textoBloque: textoBloque,
        puntosReactivo:puntosReactivo
    });
    if (resp.resp && resp.resp === 'ok') {
        if (resp.act && resp.act === 'add') {
            $("#rea_clave").val(resp.idins);
            id_rea = resp.idins;
            notify_block('Notificación', 'Se <b>guardó</b> el reactivo de manera satisfactoria', msg_det, 'success');

        } else if (resp.act && resp.act === 'upd') {
            id_rea = $("#rea_clave").val() * 1;
            notify_block('Notificación', 'Se <b>actualizo</b> el reactivo de manera satisfactoria', msg_det, 'success');
        } else {
            mensaje_center('Error', 'Error al actualizar reactivo.', 'Intente de nuevo.', 'error');
        }
        llena_clvreactivo(id_rea);
        get_value('reactivo/setActualReactivo/' + id_rea);
    }
    muestraVistaPrevia(id_rea);
    return id_rea;
}

function muestraVistaPrevia(idrea) {

    if (valida_vistaprevia().valido) {
        cargaVistaPrevia(idrea);
        $('#estado_r').attr('disabled', false);
        $('#estado_c').attr('disabled', false);
        $('#vista_preliminar').show();
    } else {
        $('#estado_r').attr('disabled', true);
        $('#estado_c').attr('disabled', true);
        $('#vista_preliminar').hide();
    }
}

function validaCasoLog() {
    var valido = true,
        msg_log = '';
    var ctit = $("#cas_titulo_add").val();
    var ccon = tinyMCE.get('cas_contenido_add').getContent();
    if (ctit != '') {
        valido = valido && true;
        $("#cas_titulo_lblerr").hide();
        $("#cas_titulo_lblerr").remove();
        $("#cas_titulo_add").removeClass('error');
    } else {
        valido = valido && false;
        $("#cas_titulo_lblerr").show();
        $("#cas_titulo_add").addClass('error');
        msg_log += 'Titulo de caso<br>';
    }
    if (ccon != '') {
        valido = valido && true;
        $("#cas_contenido_lblerr").hide();
        $("#cas_contenido_tbl").removeClass('error');
    } else {
        valido = valido && false;
        $("#cas_contenido_lblerr").show();
        $("#cas_contenido_tbl").addClass('error');
        msg_log += 'Texto para el caso<br>';
    }
    return {
        valido: valido,
        msg_log: msg_log
    };
}

//------------------guardar reactivo 1.2----(caso)---------------
/** 
 * obtiene los datos del formulario de caso y los envia para insertar o actulizar los datos de la tabla ADM_CASO.
 */
function saveCaso() {
    $.blockUI();
    var id_cas, sepudo = false;
    var casoValido = validaCasoLog();
    if (casoValido.valido) {
        var ctit = $("#cas_titulo_add").val(),
            ccon = tinyMCE.get('cas_contenido_add').getContent(),
            inst = $("#cas_instruccion_add").val(),
            img = $('#filesimagen div p span').text(),
            aud = $('#filesaudio div p span').text(),
            vid = $('#filesvideo div p span').text();
        var resp = get_object('caso/guardarCaso', {
            cas_titulo: ctit,
            cas_contenido: ccon,
            cas_instruccion: inst,
            aud: aud,
            img: img,
            vid: vid
        });
        if (resp.resp && resp.resp === 'ok') {
            if (resp.act && resp.act === 'add') {
                $("#cas_clave").val(resp.idins);
                id_cas = resp.idins;
                $("#caso").attr('data-add', 0);
                $.blockUI();
                get_value('caso/setActualCaso/' + id_cas, '');
                setTimeout(function () {
                    $.unblockUI({
                        onUnblock: function () {
                            $("#hd_media_casos_files .btn_subir").click(); //subir archivos
                        }
                    });
                }, 1);

                $("#sinCaso").hide();
                $("#mostrarCaso").hide();
                $("#viewCaso").show();

                sepudo = true;
            } else if (resp.act && resp.act === 'upd') {
                id_cas = $("#rea_clave").val() * 1;
                $("#caso").attr('data-add', 0);
                $.blockUI();
                get_value('setActualCaso/' + id_cas, '');
                setTimeout(function () {
                    $.unblockUI({
                        onUnblock: function () {
                            $("#hd_media_casos_files .btn_subir").click(); //subir archivos
                        }
                    });
                }, 1);
                sepudo = true;
            } else {
                mensaje_center('Error', 'Error al actualizar caso.', 'Intente de nuevo. Recargue la página.', 'error');
                sinCaso();
            }
        } else {
            mensaje_center('Error', 'Error al actualizar caso.', 'La respuesta del servido es incorrecta. Intente de nuevo. <br> Recargue la página.', 'error');
            sinCaso();
        }
    } else {
        sepudo = false;
        mensaje_center('Datos faltantes', 'Proporciona los datos que faltan:', '' + casoValido.msg_log, 'warning');
    }
    $.unblockUI();
    try {
        $('#cas_clave').val(id_cas);
        $('#cas_titulo').val(ctit);
        $('#cas_instruccion').val(inst);
        $('#cas_contenido').html(ccon);
        $('#cas_media .img,#cas_media .aud, #cas_media .vid ').hide();
        if (img != '') {
            cargaMediaCaso(id_cas, 'imagen', img);
        }
        if (aud != '') {
            cargaMediaCaso(id_cas, 'audio', aud);
        }
        if (vid != '') {
            cargaMediaCaso(id_cas, 'video', vid);
        }
    } catch (e) {
        alert(e);
    }
    return {
        sepudo: sepudo,
        id: id_cas
    };
}

//-----------buscar registros de casos------------------
/**
 * busca concidencia de registros de casos de estudios
 *@param varchar palabra, cadena a buscar en reactivos.
 *@return array, registros que contiene la cadena parametro palabra.
 */
function buscarCaso(palabra) {
    $.post("index.php/reactivo/buscarCaso", {
            palabra: palabra
        },
        function (data) {
            $('#datosCaso').dataTable().fnClearTable();
            $.each(data, function (i, aDatos) {
                $('#datosCaso').dataTable().fnAddData([
                    aDatos.tit,
                    aDatos.con
                ]);
            });
        }, 'json');
}


//---------mostrar datos de un caso--------------------
/**
 * obtiene datos de un registro especifico de la tabla caso
 *@param int id, identificador de registro caso.
 *@return array, datos del registro a mostrar.
 */
function datosCaso(id) {
    limpiaCaso();
    var datos_caso = get_object('reactivo/datosCaso', {
        id: id
    });
    if (datos_caso) {
        try {
            $('#cas_clave').val(datos_caso.clave);
            $('#cas_titulo').val(datos_caso.titulo);
            $('#cas_instruccion').val(datos_caso.instruccion);
            $('#cas_contenido').html(datos_caso.contenido);
            $('#cas_media .img,#cas_media .aud, #cas_media .vid ').hide();
            if (datos_caso.imagen && datos_caso.imagen != '') {
                cargaMediaCaso(id, 'imagen', datos_caso.imagen);
            }
            if (datos_caso.audio && datos_caso.audio != '') {
                cargaMediaCaso(id, 'audio', datos_caso.audio);
            }
            if (datos_caso.video && datos_caso.video != '') {
                cargaMediaCaso(id, 'video', datos_caso.video);
            }
        } catch (e) {
            alert(e);
        }
        $("#sinCaso").hide();
        $("#mostrarCaso").hide();
        $("#viewCaso").show();
    }
}

function cargaMediaCaso(id, tipomedio, name) {
    var medio_html = '';
    if (tipomedio == 'imagen') {
        medio_html = '<img width="150" src="./media_casos/caso' + id + '/' + name + '" />';
    } else if (tipomedio == 'audio') {
        medio_html = '<audio src="./media_casos/caso' + id + '/' + name + '" controls></audio>';
    } else if (tipomedio == 'video') {
        medio_html = '<video src="./media_casos/caso' + id + '/' + name + '" controls></video>';
    }
    var html = '<div><a target="_blank" href="./media_casos/caso' + id + '/' + name + '"><p>' + medio_html + name + '</span></p></a></div>';
    $('#cas_media .' + tipomedio).html(html);
    $('#cas_media .' + tipomedio).parent().show();
}

//---------------visualiza el buscador de caso-------
/** 
 * muestra en pantalla el fomulario para consultar los registros de casos.
 */
function caso() {
    $("#viewCaso").hide();
    $("#sinCaso").hide();
    $("#caso").attr('data-add', '0');
    $("#mostrarCaso").show();
    $("#cadenaCaso").focus();
}



function limpiaCaso() {
    //limpiar multimedia
    $("#hd_media_casos_files .files").html('').attr('data-select', '0');
    $(".media_caso_hidden").val('');
    $("#cas_titulo, #cas_clave").val('');
    $('#opciones').attr('data-tipomedia', '');
    $('#cas_contenido').html('');
}

//-------------muestra que el reactivo no tiene un caso asignado-----------
/** 
 * oculta el formulario de caso y muestra una imagen no hay ningun caso asociado al reactivo.
 */
function sinCaso() {
    limpiaCaso();
    $("#mostrarCaso").hide();
    $("#viewCaso").hide();
    $("#cas_clave").val('');
    $("#sinCaso").show();
    $("#caso").attr('data-add', 0);
}

function cerrarBusquedaCaso() {
    if ($('#cas_clave').val() != '' && $('#cas_clave').val() != '0') {
        $("#sinCaso").hide();
        $("#viewCaso").show();
    } else {
        $("#viewCaso").hide();
        $("#sinCaso").show();
    }
    $("#mostrarCaso").hide();
}

/**--------insertar opcion tipo multiple y ordenar--------------*/
function insertarMultiple() {
    var contenido = tinyMCE.get('contenido').getContent();
    if (contenido == "") {
        mensaje_center('Completa lo que pide', 'El campo de datos se encuentra vacío.', 'Proporciona un texto a la opción de respuesta', 'warning');
    } else {
        var modid = $('#btn_upd_opcresp').attr('data-modid');
        if (modid != '-1') {

            $("#area_opciones #div_opc_" + modid).removeClass('edt_opcres');
            $('#btn_upd_opcresp').attr('data-modid', '-1');
            $("#opc_" + modid).val(contenido);
            $('[for="opc_' + modid + '"').html(contenido);
            $('.opcresp_div .col-md-3').show();
            $('#btn_upd_opcresp span').text('Agregar');
            tinyMCE.get('contenido').setContent('');
        } else {
            var tipo = $("#tipo_reactivo").val();
            if ($("#edita_opcion").val() != "") {
                var parte = $("#edita_opcion").val();
                contenido = contenido.replace('src="../', 'src="./');
                $("#opcion" + parte).html(contenido);
                tinyMCE.get('contenido').setContent('');
                $("#edita_opcion").val('');
                $("#opcion" + parte).css('background-color', '#FFF');
            } else {
                var count = $("#num_opcion").val();
                contenido = tinyMCE.get('contenido').getContent();
                contenido = contenido.replace('src="../', 'src="./');
                count++;
                $("#num_opcion").val(count);
                var num = $("#area_opciones").attr('data-numopc') * 1;
                cant = count - 1;

                if (tipo == 1) {
                    //$("#area_opciones").append('<div class="row" id="opc_' + cant + '"><div class="col-md-9"><div class="row"><div class="col-md-1 " align="center"><input type="radio" id="radio' + cant + '" /></div><div class="col-md-11 " id="opcion' + cant + '">' + contenido + '</div></div></div><div class="col-md-3" align="center"><button type="button" class="btn btn-warning" onclick="editarOpcion(' + cant + ');"><i class="icon-pencil"></i></button>&nbsp;<button type="button" class="btn btn-danger" onclick="eliminarOpcion(' + cant + ');"><i class="fa fa-remove"></i></button></div></div>');
                    $("#area_opciones").append(agrega_texto(num, 'radio', contenido));
                } else if (tipo == 8 || tipo == 9 || tipo == 11 || tipo == 5) {
                    $("#area_opciones").append(agrega_texto(num, 'number', contenido));
                } /*else if (tipo == 5) {
                    $("#area_opciones").append('<div class="row" id="opc_' + cant + '"><div class="col-md-1" align="center"><input type="text" id="orden' + cant + '" class="col-md-12"/></div><div class="col-md-8" id="opcion' + cant + '">' + contenido + '</div><div class="col-md-3" align="center"><button type="button" class="btn btn-warning" onclick="editarOpcion(' + cant + ');"><i class="icon-pencil"></i></button>&nbsp;<button type="button" class="btn btn-danger" onclick="eliminarOpcion(' + cant + ');"><i class="fa fa-remove"></i></button></div></div>');
                    $("#orden" + cant).val(count);
                }*/
                tinyMCE.get('contenido').setContent('');
                $("#area_opciones").attr('data-numopc', num + 1);
            }
        }
    }
    $("#contenido").focus();
}

/**-----editar opcion de respuesta------*/
function editarOpcion(id) {
    $('#btn_upd_opcresp span').text('Modificar');
    $("#edita_opcion").val(id);
    tinyMCE.get('contenido').setContent('');
    var contenido = $('#area_opciones [for="opc_' + id + '"]').html();
    //contenido = contenido.replace('src="./', 'src="../../');
    tinyMCE.get('contenido').setContent(contenido);
    $("#area_opciones #div_opc_" + id).addClass('edt_opcres');
    $('.opcresp_div .col-md-3').hide();
    $('#btn_upd_opcresp').attr('data-modid', id);
}

//-----------buscar registros de reactivo------------------
/**
 * consulta y muestra los reactivos que genera la consulta sql, de acuerdo a las condiciones enviadas.
 * var char where, condiciones para la consulta de reactivos.
 * var char texto, palabra de concidencia entre los reactivos.
 */
function buscarReactivo() {
    $('#datosReactivo').dataTable().fnClearTable(); // limpia datatable, en la tabla de reactivos.
    var data_form = {
        edo: $('#buscar_estado').val(),
        tiprea: $('#buscar_tipo').val(),
        fecha1: $('#rea_date1').val(),
        fecha2: $('#rea_date2').val(),
        usu: $('#buscar_usuario').val(),
        txt: $('#textReactivo').val()
    };
    var resp = get_object('reactivo/buscarReactivo', data_form);
    $.each(resp, function (i, aDatos) {
        $('#datosReactivo').dataTable().fnAddData([
            aDatos.clv,
            aDatos.con,
            aDatos.est,
            aDatos.tip,
            aDatos.opc,
            aDatos.cal,
            aDatos.fec,
            aDatos.cas,
            aDatos.pla,
            aDatos.sel
        ]);
    });
}

function open_dialog_opcres() {
    limpia_dialogo_opcres();
    $("#dialogo_opciones").modal('show');
}

function cancelar_opciones_respuesta() {
    limpia_dialogo_opcres();
    $("#dialogo_opciones").modal('hide');
}

function valida_opcionrespuesta() {
    if (($('#area_opciones .opcresp:checked').length) < 1 && $("#tipo_reactivo").val() == 1) {
        return false;
    } else {
        return true;
    }
}

function valida_opcionrespuestafiles() {
    if (($('#area_opciones [name="files[]"]').length) === ($('#area_opciones [data-select="1"]').length)) {
        return true;
    } else {
        return false;
    }
}

function valida_opcionrespuestavacia() {
    if (($('#area_opciones :input').length) < 1) {
        return false;
    } else {
        return true;
    }
}

function limpia_dialogo_opcres() {
    $('#dialogo_opciones [value="txt"]').click();
    $('#area_opciones').html('');
    try {
        tinyMCE.get('contenido').setContent('');
    } catch (e) {}
    $('#dialogo_opciones [value="txt"]').click();
    $("#area_opciones").val('data-numopc', '0');
}

function limpia_reactivo() {
    contenido_reactivo = '';
    cambia_st_opciones(false);
    limpia_dialogo_opcres();
    $("#rea_clave, #cas_clave, #clvreactivo").val('');
    $("#tipo_reactivo").val(0);
    $("#mostrarCaso").hide();
    $("#mostrarPlan").hide();
    $("#mostrarReactivo").hide();
    $("#editarCaso").hide();
    $("#sinCaso").show();
    $("#estado_c").addClass('btn-danger active');
    $("#estado_r").removeClass('btn-warning');
    $("#estado_a").removeClass('btn-success ');
    $("#btn_group_st button").attr('disabled', true);
    try {
        tinyMCE.get('rea_contenido').setContent('');
        tinyMCE.get('textoNotas').setContent('');
    } catch (e) {}
    //$("#rea_contenido").html('');
    $("#tipo_reactivo").removeAttr('disabled', 'disabled');
    $("#btn_opciones").removeAttr('disabled', 'disabled');
    $("#opciones").empty();
    $("#vista_preliminar").hide();
    limpia_error();
    get_value('reactivo/setActualReactivo/0');
    $('[href="#datosVisuales"]').click();
}

function nuevo_reactivo() {
    $('#btn_siguienteReactivo').hide();
    sinCaso();
    $('[href="#datosVisuales"]').click();
    limpia_reactivo();
    try {
        var planAct = get_object('reactivo/getDataActualPlan', {});
        seleccionarPlan(planAct.id, planAct.nom);
    } catch (e) {
        alert(e);
    }
    habilita_reactivo(true);
}

function cambia_st_opciones(cambia) {
    if (cambia) {
        $('#btn_opciones').addClass('btn-warning').removeClass('btn-success').html('<i class="fa fa-check-square-o"></i> Modificar opciones de respuesta').attr('title', 'Modificar opciones de respuesta');
    } else {
        $('#btn_opciones').addClass('btn-success').removeClass('btn-warning').html('<i class="fa fa-check-square-o"></i> Capturar opciones de respuesta').attr('title', 'Capturar opciones de respuesta');
    }
}

function guarda_opciones_respuesta() {
    $('[for="' + $("#area_opciones .edt_opcres input").attr('id') + '"]').html(tinyMCE.get('contenido').getContent());
    var valid = valida_opcionrespuestavacia();
    var falta = '';
    var tipo_media = $('[name="tipo_opcresp"]:checked').val();

    if (!valid) {
        falta += '* Agrega por lo menos una opción de respuesta.<br>';
    }
    var opcioncorrecta = valida_opcionrespuesta();
    if (!opcioncorrecta) {
        valid = valid && opcioncorrecta;
        falta += '* Selecciona la opción de respuesta correcta para el reactivo.<br>';
    }
    if (tipo_media == 'img' || tipo_media == 'aud' || tipo_media == 'vid') {
        var files_select = valida_opcionrespuestafiles();
        if (!files_select) {
            valid = valid && files_select;
            falta += '* Selecciona los archivos que faltan.';
        }
    }
    if (valid) {
        var idrea = saveReactivo();
        if (idrea !== 0) {
            var dirst = get_object('reactivo/checkDir', {
                idrea: idrea
            });
            var post_var = 'idrea=' + idrea + '&';
            $.blockUI({
                message: '<br><br><br><font style="color: #999999; font-size: 30px;">Espere un momento...</font><br><br><br><br>',
                fadeIn: 1,
                timeout: 2,
                onBlock: function () {
                    var geth = get_htmlFromOpresDisplay(tipo_media, idrea);
                    $('#opciones').html(decodeURIComponent(geth.html));
                    $('#opciones').attr('data-tipomedia', tipo_media);
                    $('#area_opciones .btn_subir').click();
                    var respop = get_object('reactivo/guardarOpciones', post_var + geth.post + +'tipo_medio=' + tipo_media + '&tipo_rea=' + $('#tipo_reactivo').val());
                }
            });
        }
        muestraVistaPrevia(idrea);
        cambia_st_opciones(true);
        $("#dialogo_opciones").modal('hide');
        setTimeout(function () {
            $("#opciones img").attr('src', $("#opciones img").attr('src'));
            $("#opciones audio").attr('src', $("#opciones audio").attr('src'));
            $("#opciones video").attr('src', $("#opciones video").attr('src'));
        }, 1000);
    } else {
        mensaje_center('Datos faltantes', 'Proporciona los datos que faltan:', '' + falta, 'warning');
    }
}

function get_htmlFromOpresDisplay(tipo_media, idrea, escapeToPost) {
    var html = '',
        post_var = '';
    let tipoReactivo = getTipoInputEnReactivo();
    if (tipo_media == 'txt') {
        $("#area_opciones input").each(function (index) {
            let valInput = $(this).val();
            var pst_chk = '';
            if (tipoReactivo == "radio") {
                if ($(this).is(":checked")) {
                    pst_chk = 'S';
                } else {
                    pst_chk = 'N';
                }
            } else if (tipoReactivo == "number") {
                pst_chk = valInput;
            }
            var html_opcres = encodeURIComponent($('[for="' + $(this).attr('id') + '"]').html());
            html += agrega_opres_display($(this).attr('id'), index, html_opcres, pst_chk, tipo_media, idrea, valInput, false);
            post_var += 'opc[]=' + encodeURIComponent(html_opcres + '@_@' + pst_chk + '@_@' + $(this).attr('id')) + '&';
        });
    }

    return {
        html: html,
        post: post_var
    };
}

function agrega_opres_display(id, index, value, chk, tipo_media, idrea, valInput, es_vp) {
    var html = '',
        chk_check = '';
    if (chk == 'S') {
        chk_check = 'checked="checked"';
    }
    let tipoReactivo = $('#tipo_reactivo').val();
    let typeInput = "";

    html += '<label id="div_' + id + '" class="opcresp_render row">';
    html += '<div class="control_opcresp_render col-md-1">';
    var value_input = value;
    if (tipo_media == 'txt' && tipoReactivo == 1) {
        value_input = '';
    }
    if (tipoReactivo == 1) {
        typeInput = "radio";
    } else if (tipoReactivo == 8 || tipoReactivo == 9 || tipoReactivo == 5|| tipoReactivo == 11) {
        typeInput = "number";
        value_input = valInput;

    }
    html += '<input type="' + typeInput + '" id="opcres_' + index + '" name="opcres_' + index + '" class="opcresp" value="' + value_input + '" data-escorrecta="' + chk + '"';
    if (!es_vp) {
        html += 'disabled ' + chk_check;
    } else {
        html += 'onclick=validaOpcVP($(this),"' + chk + '");';
    }
    html += '>';
    html += "</div>";
    html += '<div class="media_opcresp_render col-md-10">';
    if (tipo_media == 'txt') {
        html += '<div>' + value + '</div>';
    } else if (tipo_media == 'img') {
        html += '<img src="./media/reactivo' + idrea + '/' + value + '"/>';
    } else if (tipo_media == 'aud') {
        html += '<audio src="./media/reactivo' + idrea + '/' + value + '" controls=""></audio>';
    } else if (tipo_media == 'vid') {
        html += '<video src="./media/reactivo' + idrea + '/' + value + '" controls=""></video>';
    }
    html += "</div>";
    html += "</label>";
   
    return html;
}

function validaOpcVP(obj, escorrecta) {
    $('.alert_respuestas').remove();
    $('.S_respuesta').removeClass('S_respuesta');
    $('.N_respuesta').removeClass('N_respuesta');
    obj.parent().parent().removeClass('S_respuesta').removeClass('N_respuesta');
    if (obj.is(":checked")) {
        obj.parent().parent().addClass(escorrecta + '_respuesta');
    }
    if (($('#div_vp_opcresp :input[data-escorrecta="S"]:checked').length) == ($('#div_vp_opcresp :input[data-escorrecta="S"]').length)) {
        $('#div_vp_opcresp').append('<div class="alert_respuestas col-md-12 alert alert-success"><i class="fa fa-thumbs-up fa-2"></i> Rectivo contestado correctamente</div>');
    } else {
        $('#div_vp_opcresp').append('<div class="alert_respuestas col-md-12 alert alert-danger"><i class="fa fa-thumbs-down fa-2"></i> Respuesta(s) incorrecta(s) </div>');
    }
}

function agrega_texto(num, tipocontrol, txt, checado, valueInput) {
    var checa = "";
    if (checado) {
        checa = 'checked';
    }

    var html = '<div id="div_opc_' + num + '" class="col-md-12 opcresp_div ">' +
        '<div class="col-md-1 chk_opcresp_dialog">' +
        '<input type="' + tipocontrol + '" id="opc_' + num + '" name="opcresp[]" class="opcresp" value="' + valueInput + '" ' + checa + '>' +
        '</div>' +
        '<div class="col-md-8"><label for="opc_' + num + '"> ' + txt + '</label></div>' +
        '<div class="col-md-3" align="center"><button type="button" class="btn btn-warning" onclick="editarOpcion(' + num + ');"><i class="fa fa-pencil"></i></button>&nbsp;<button type="button" class="btn btn-danger btn_elimina_opcresp"><i class="fa fa-remove"></i></button></div>' +
        '</div>';
    return html;
}

function valida_opciones_vp() {
    return ($('#opciones .opcresp').length > 0);
}

function valida_opcionescorrecta_vp() {
    return ($('#opciones .opcresp:checked').length > 0);
}

function getTipoInputEnReactivo() {
    let tipoReactivo = $("#tipo_reactivo").val();
    let salida = "";
    if (tipoReactivo == 1) {
        salida = "radio";
    }
    if (tipoReactivo == 8 || tipoReactivo == 5 || tipoReactivo == 9 || tipoReactivo == 10 || tipoReactivo == 11) {
        salida = "number";
    }
    return salida;
}

function carga_opciones_dialog(tipomedia) {
    var html = '';
    $('[value="' + tipomedia + '"]').click();

    switch (tipomedia) {
        case 'txt':
            /*$('#opciones .media_opcresp_render div').each(function (index) {
             html += agrega_texto(index, 'radio', $(this).html());
             });*/
            let tipoInput = getTipoInputEnReactivo();
            $('#opciones .opcresp_render').each(function (index) {
                console.log("htmlmpdal");
                var id = $(this).attr('id'),
                    texto = $('#' + id + ' .media_opcresp_render div').html(),
                    valueInput = $('#' + id + ' .control_opcresp_render input').val(),
                    checado = false,
                    checa = $('#' + id + ' .control_opcresp_render .opcresp').attr('data-escorrecta');

                if (tipoInput == "radio") {
                    if (checa == 'S') {
                        checado = true;
                    }
                }

                html += agrega_texto(index, tipoInput, texto, checado, valueInput);


            });
            break;
        case 'img':
            break;
        case 'aud':
            break;
        case 'vid':
            break;
    }
    $('#area_opciones').html(html);
}

function valida_vistaprevia() {
    var validrea = validaReactivoLog(),
        sepuede = validrea.valido,
        logs = validrea.msg_log;
    if ($("#caso").attr('data-add') == 1) {
        var validaCaso = validaCasoLog();
        sepuede = sepuede && validaCaso.valido;
        logs += validaCaso.msg_log;
    }
    var vo = valida_opciones_vp();
    if (!vo) {
        sepuede = sepuede && vo;
        logs += 'Proporcione por lo menos una opción de respuesta.<br>';
    }
    var vom = valida_opcionescorrecta_vp();
    if (!vom) {
        sepuede = sepuede && vom;
        logs += 'Seleccione la respuesta correcta.<br>';
    }
    return {
        valido: sepuede,
        msg_log: logs
    };
}

function agregaCaso() {
    var casoGuardado = saveCaso();
    if (casoGuardado.sepudo) {
        $('#dialog_agrega_caso').modal('hide');
        //datosCaso(casoGuardado.id);  
        setTimeout(function () {
            $("#cas_media .imagen img").attr('src', $("#cas_media .imagen img").attr('src'));
            $("#cas_media .audio audio").attr('src', $("#cas_media .audio audio").attr('src'));
            $("#cas_media .video video").attr('src', $("#cas_media .video video").attr('src'));
        }, 1000);
    }
}

function remove_media_caso(tipo) {
    $('#files' + tipo).html('');
    $('#files' + tipo).attr('data-select', '0');
    $('#media_caso_hidden_' + tipo).val('');
}
var paddingLi, countSelect;
$(document).ready(function () {

    'use strict';
    var tipo;
    $("#contenedorPadres").on("click", ".elementoPadre", function () {
        paddingLi -= 5;
        $("#contenedorPadres").addClass('open');
        $(this).addClass("seleccionado");
        $(this).prop("disabled", true);
        var idPlan = $(this).val();
        if (idPlan != 0) {
            $("#contenedorPadres").addClass('open');
            $.post("index.php/reactivo/desplegarPadres", {
                    idPlan: idPlan,
                    padding: paddingLi
                },
                function (data) {
                    $("#contenedorPadres ul").prepend(data);
                    $("#contenedorPadres").addClass('open');
                });
        } else {

        }
    });
    //-----------limpia formulario para la captura de un nuevo caso-------
    /** 
     * limpia o vacía el formulario de caso, para la captura de uno nuevo registro.
     */
    function nuevoCaso() {
        limpiaCaso();
        $("#cas_titulo_add,#cas_instruccion_add").val('');
        tinyMCE.get('cas_contenido_add').setContent('');
        //$("#sinCaso").hide();
        //$("#mostrarCaso").hide();
        $("#caso").attr('data-add', 1);
        addPluginFileUpCaso('fileupload_cas_imagen', 'img', 'imagen');
        addPluginFileUpCaso('fileupload_cas_audio', 'aud', 'audio');
        addPluginFileUpCaso('fileupload_cas_video', 'vid', 'video');
        $('#dialog_agrega_caso').modal('show');
    }

    function nuevoCaso() {
        limpiaCaso();
        $("#cas_titulo_add,#cas_instruccion_add").val('');
        tinyMCE.get('cas_contenido_add').setContent('');
        //$("#sinCaso").hide();
        //$("#mostrarCaso").hide();
        $("#caso").attr('data-add', 1);
        addPluginFileUpCaso('fileupload_cas_imagen', 'img', 'imagen');
        addPluginFileUpCaso('fileupload_cas_audio', 'aud', 'audio');
        addPluginFileUpCaso('fileupload_cas_video', 'vid', 'video');
        $('#dialog_agrega_caso').modal('show');
    }

    $('#btn_agrega_caso').click(agregaCaso);
    $('#btn_open_newCaso').click(nuevoCaso);
    //js/fileupload/server/php/
    var url = 'index.php/reactivo/up',
        uploadButton = $('<button/>').addClass('btn btn-primary').prop('disabled', true).text('Processing...')
        .on('click', function () {
            var $this = $(this),
                data = $this.data();
            $this.off('click').text('Abort').on('click', function () {
                $this.remove();
                data.abort();
            });
            data.submit().always(function () {
                $this.remove();
            });
        });

    function agrega_opcion(tipo) {
        var num = $('#area_opciones').attr('data-numopc') * 1;
        var tipo_control = 'radio';
        $('#area_opciones').append(agrega_medio(num, tipo_control));
        addPluginFileUp('fileupload_' + num, tipo, num);
        $('#area_opciones').attr('data-numopc', $('#area_opciones').attr('data-numopc') + 1);
    }

    function addPluginFileUp(id, tipo, num) {
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
            });
        }).on('fileuploadprocessalways', function (e, data) {
            var index = data.index,
                file = data.files[index],
                node = $(data.context.children()[index]);
            if (file.preview) {
                //.prepend('<br>')
                node.prepend(file.preview);
                $('#btn_sel_' + num).hide();
                $('#progress' + num).show();
                $('#btn_sel_' + num).hide();
                $('#opc_' + num).val(file.name);
                $("#files" + num).attr('data-select', '1');
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
            $('#area_opciones .btn_subir').hide();
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
    }

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

    //$('.fancybox-media').fancybox({ openEffect: 'none',closeEffect: 'none',helpers: { media: {} } });

    function agrega_medio(num, tipocontrol) {
        var html = '<div class="col-md-12 opcresp_div ">' +
            '<div class="col-md-1 chk_opcresp_dialog">' +
            '<input type="' + tipocontrol + '" id="opc_' + num + '" class="opcresp" name="opcresp[]">' +
            '</div>' +
            '<div id="files' + num + '" class="files col-md-4" data-select="0"></div>' +
            //'<div class="col-md-4" >' +
            '<span id="btn_sel_' + num + '" class="btn btn-success fileinput-button" >' +
            '<i class="fa fa-plus-circle"></i>Seleccionar<br> archivo' +
            '<input id="fileupload_' + num + '" type="file" name="files[]">' +
            '</span>' +
            '<div id="progress' + num + '" class="progress col-md-4" style="display:none;">' +
            //'</div>' +
            '<div class="progress-bar progress-bar-success"></div>' +
            '</div>' +
            '<div class="col-md-2" style="float: right;"><button class="btn btn-danger btn_elimina_opcresp"><i class="fa fa-remove"></i></button></div>' +
            '</div>';
        return html;
    }

    $('.add_media_opc').click(function () {
        agrega_opcion($(this).attr('data-tipoopc'));
    });

    /*$('.btn_elimina_opcresp').on('click', function () {
     $(this).parent().parent().remove();
     });*/
    $(document).on('click', '.btn_elimina_opcresp', function () {
        $(this).parent().parent().remove();
    });

    $('[name="tipo_opcresp"]').click(function () {
        $('#txt,#img,#aud,#vid').hide();
        $('#' + $('[name="tipo_opcresp"]:checked').val()).show();
        $('#area_opciones').html('');
        $("#area_opciones").val('data-numopc', '0');
    });

    //***************** datatables **************************//
    $('#datosPlan').dataTable({
        "bJQueryUI": true,
        "oLanguage": {
            "sProcessing": "<div class=\"ui-widget-header boxshadowround\"><strong>Procesando...</strong></div>",
            "sLengthMenu": "Mostrar _MENU_ planes",
            "sZeroRecords": "No se encontraron resultados",
            "sInfo": "Mostrando desde _START_ hasta _END_ de _TOTAL_ planes",
            "sInfoEmpty": "Mostrando desde 0 hasta 0 de 0 Planes",
            "sInfoFiltered": "(filtrado de _MAX_ planes)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "oPaginate": {
                "sFirst": "Primero",
                "sPrevious": "Anterior",
                "sNext": "Siguiente",
                "sLast": "Último"
            }
        },
        "aLengthMenu": [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, "Todos"]
        ],
        "sPaginationType": "full_numbers",
        "aoColumns": [{
                "sWidth": "5%",
                "sClass": "center"
            },
            {
                "sWidth": "45%"
            },
            {
                "sWidth": "40%"
            }, {
                "sWidth": "5%",
                "sClass": "center"
            }
        ]
    });

    //--CASO-----
    $('#datosCaso').dataTable({
        "bJQueryUI": true,
        "oLanguage": {
            "sProcessing": "<div class=\"ui-widget-header boxshadowround\"><strong>Procesando...</strong></div>",
            "sLengthMenu": "Mostrar _MENU_ casos",
            "sZeroRecords": "No se encontraron resultados",
            "sInfo": "Mostrando desde _START_ hasta _END_ de _TOTAL_ casos",
            "sInfoEmpty": "Mostrando desde 0 hasta 0 de 0 casos",
            "sInfoFiltered": "(filtrado de _MAX_ casos)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "oPaginate": {
                "sFirst": "Primero",
                "sPrevious": "Anterior",
                "sNext": "Siguiente",
                "sLast": "Último"
            }
        },
        "aLengthMenu": [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, "Todos"]
        ],
        "sPaginationType": "simple",
        "aoColumns": [{
                "sWidth": "78"
            },
            {
                "sWidth": "22"
            }
        ]
    });

    //--REACTIVO-----
    $('#datosReactivo').dataTable({
        "bJQueryUI": true,
        "oLanguage": {
            "sProcessing": "<div class=\"ui-widget-header boxshadowround\"><strong>Procesando...</strong></div>",
            "sLengthMenu": "Mostrar _MENU_ reactivos",
            "sZeroRecords": "No se encontraron resultados",
            "sInfo": "Mostrando desde _START_ hasta _END_ de _TOTAL_ reactivos",
            "sInfoEmpty": "Mostrando desde 0 hasta 0 de 0 reactivos",
            "sInfoFiltered": "(filtrado de _MAX_ reactivos)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "oPaginate": {
                "sFirst": "Primero",
                "sPrevious": "Anterior",
                "sNext": "Siguiente",
                "sLast": "Último"
            }
        },
        "aLengthMenu": [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, "Todos"]
        ],
        "sPaginationType": "full_numbers",
        "aoColumns": [{
                "sWidth": "5%",
                "visible": false
            },
            {
                "sWidth": "40%"
            },
            {
                "sWidth": "3%"
            },
            {
                "sWidth": "10%"
            },
            {
                "sWidth": "4%"
            },
            {
                "sWidth": "10%"
            },
            {
                "sWidth": "10%"
            },
            {
                "sWidth": "5%"
            },
            {
                "sWidth": "10%"
            },
            {
                "sWidth": "3%",
                "sClass": "center"
            }
        ]
    });


    /* tinyMCE.init({
     selector: "textarea", theme: "modern",
     plugins: [
     "autoresize advlist autolink link image lists charmap print preview hr anchor pagebreak",
     "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
     "table contextmenu directionality emoticons paste textcolor responsivefilemanager code"
     ],
     toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
     toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
     image_advtab: true,
     external_filemanager_path: "/filemanager/",
     filemanager_title: "Responsive Filemanager",
     external_plugins: {"filemanager": "/js/tinymce/js/tinymce/plugins/filemanager/plugin.min.js"}
     });*/

    //acciones de botones
    //-----------mostrar buscador de planes-----------
    $("#btn_plan").click(function () {
        $("#mostrarReactivo").hide();
        $("#mostrarPlan").toggle();
        $("#cadenaPlan").focus();
    });
    //cambio de estado del reactivo
    $("#estado_r").click(function () {
        $("#btn_group_st button").removeClass('activo');
        $("#estado_r").addClass('btn-warning active');
        $("#estado_c").removeClass('btn-danger');
        $("#estado_a").removeClass('btn-success');
        $(this).addClass('activo');
        saveReactivo();
        habilita_reactivo(false);

    });
    $("#estado_c").click(function () {
        $("#btn_group_st button").removeClass('activo');
        $("#estado_r").removeClass('btn-warning');
        $("#estado_c").addClass('btn-danger active');
        $("#estado_a").removeClass('btn-success');
        $(this).addClass('activo');
        saveReactivo();
        habilita_reactivo(true);
    });
    $("#estado_a").click(function () {
        $("#btn_group_st button").removeClass('activo');
        $("#estado_r").removeClass('btn-warning');
        $("#estado_c").removeClass('btn-danger');
        $("#estado_a").addClass('btn-success active');
        $(this).addClass('activo');
        saveReactivo();
        habilita_reactivo(false);
    });

    /**--evento botón 'btn_opciones' abre dialogo para la captura de opciones de respuesta según el tipo de opcion seleccionado -*/
    $("#btn_opciones").click(function () {
        tipo = $("#tipo_reactivo").val();
        var entra = false;
        $("#area_opciones").empty();
        $("#msgtipo_reactivo").hide();
        $("#tipo_reactivo").removeClass('error');
        if (tipo == 1) { //multiple
            entra = true;
            $("#tipo_opcion1").html('OPCIONES MULTIPLES');
            $("#num_opcion").val(0);
        } else if (tipo == 2) { //radio
            entra = true;

        } else if (tipo == 3) { //relacionar 
            entra = true;
            $("#tipo_opcion2").html('RELACIONAR COLUMNAS');
            $("#num_columna1").val(0);
            $("#num_columna2").val(0);

            $("#desc_col1").html('Insertar definición o pregunta');
            $("#desc_col2").html('Insertar Concepto o Respuesta');
            $("#titulo1").html('Definición');
            $("#titulo2").html('Concepto');
        } else if (tipo == 5) { //ordenar
            entra = true;

        } else if (tipo == 6) { //clasificar
            entra = true;
            $("#tipo_opcion2").html('CLASIFICAR ELEMENTOS');
            $("#num_elemento").val(0);
            $("#num_concepto").val(0);
            $("#msgtipo_clasificacion").hide();
            $("#desc_col1").html('Insertar elementos agrupales o clasificables');
            $("#desc_col2").html('Insertar nombre grupal o clasificación');
            $("#titulo1").html('Elementos');
            $("#titulo2").html('Clasificación');
        } else if (tipo == 8 || tipo == 9 || tipo == 10 || tipo == 11) {
            entra = true;
        }
        if (entra) {
            open_dialog_opcres();
        } else {
            mensaje_center('Información faltante', 'Selecciona tipo de reactivo', '', 'warning');
            $("#msgtipo_reactivo").show();
            $("#tipo_reactivo").addClass('error');
        }
        carga_opciones_dialog($('#opciones').attr('data-tipomedia'));
    });
    tinymce.PluginManager.add('insertarTexto', function (editor, url) {
        // Add a button that opens a window
        editor.addButton('btnInsertaTexto', {
            text: 'Insertar texto',
            icon: false,
            onclick: function () {
                // Open window
                let tipoReactivo = $("#tipo_reactivo").val();
                let disabled = (tipoReactivo == 8) ? 'disabled' : '';

                editor.windowManager.open({
                    title: 'Insertar texto',
                    body: [{
                            type: 'textbox',
                            name: 'texto',
                            label: 'Texto'
                        },
                        {
                            type: 'textbox',
                            name: 'posicion',
                            label: 'Posición'
                        }
                    ],
                    onsubmit: function (e) {
                        if (tipoReactivo == 11) {
                            let anchoInput = (e.data.texto.length + 'em');
                            // Insert content when the window form is submitted
                            tinymce.get("rea_contenido").execCommand('mceInsertContent', false, '<input type="text" onClick="subrayaTexto();" class="inputSubrayar" style="border:none; width:' + anchoInput + ';" data-posicion="' + e.data.posicion + '" value="' + e.data.texto + '"/>')
                        } else if (tipoReactivo == 8 || tipoReactivo == 9) {
                            tinymce.get("rea_contenido").execCommand('mceInsertContent', false, '<input type="text" class="ui-widget-content droppable" data-posicion="' + e.data.posicion + '" style="display: inline-block;"' + disabled + '/>')
                        } else if (tipoReactivo == 10) {
                            tinymce.get("rea_contenido").execCommand('mceInsertContent', false, '<textarea></textarea>')
                        } else {
                            mensaje_center('Alerta', 'Entrada no permitida', 'Selecciona un tipo de reactivo válido', 'warning');
                        }
                    }
                });
                /*else {
                    mensaje_center('Alerta', 'Entrada no permitida', 'Selecciona un tipo de reactivo válido', 'warning');
                }*/
            }
        });

        // Adds a menu item to the tools menu
        editor.addMenuItem('btnInsertaTexto', {
            text: 'Insertar texto',
            context: 'tools',
            onclick: function () {
                // Open window with a specific url
                editor.windowManager.open({
                    title: 'TinyMCE site',
                    url: 'http://www.tinymce.com',
                    width: 400,
                    height: 300,
                    buttons: [{
                        text: 'Close',
                        onclick: 'close'
                    }]
                });
            }
        });
    });

    tinyMCE.init({
        selector: "#rea_contenido",
        branding: true,
        theme: "modern",
        forced_root_block: "",
        relative_urls: true,
        language: "es_MX",
        paste_as_text: true,
        entity_encoding: "raw",
        document_base_url: base_url,
        force_br_newlines: true,
        force_p_newlines: false,
        media_live_embeds: true,
        plugins: [
            "insertarTexto autoresize advlist autolink link image lists charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
            "table contextmenu directionality emoticons paste textcolor responsivefilemanager code"
        ],
        toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
        toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code  | btnInsertaTexto",
        image_advtab: true,
        //external_filemanager_path: "/Nube/adminre/filemanager/",
        external_filemanager_path: "/filemanager/",
        filemanager_title: "Responsive Filemanager",
        external_plugins: {
            //"filemanager": "/Nube/adminre/js/tinymce/js/tinymce/plugins/filemanager/plugin.min.js"
            "filemanager": "/js/tinymce/js/tinymce/plugins/filemanager/plugin.min.js"
        }
    });

    tinyMCE.init({
        selector: ".mceEditor",
        theme: "modern",
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
        toolbar2: "responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
        image_advtab: true,
        //external_filemanager_path: "/Nube/adminre/filemanager/",
        external_filemanager_path: "/filemanager/",
        filemanager_title: "Responsive Filemanager",
        external_plugins: {
            //"filemanager": "/Nube/adminre/js/tinymce/js/tinymce/plugins/filemanager/plugin.min.js"
            "filemanager": "/js/tinymce/js/tinymce/plugins/filemanager/plugin.min.js"
        }
    });
    tinyMCE.init({
        selector: "#textoNotas",
        theme: "modern",
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
            "table contextmenu directionality emoticons paste textcolor code"
        ],
        toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
        toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
        image_advtab: true,
        //external_filemanager_path: "/Nube/adminre/filemanager/",
        external_filemanager_path: "/filemanager/",
        filemanager_title: "Responsive Filemanager",
        external_plugins: {
            //"filemanager": "/Nube/adminre/js/tinymce/js/tinymce/plugins/filemanager/plugin.min.js"
            "filemanager": "/js/tinymce/js/tinymce/plugins/filemanager/plugin.min.js"
        }
    });

    setTimeout(function () {
        $('#mceu_207,#mceu_209,#mceu_210,#mceu_51').before('<a href="https://www.tiny.cloud/" target="_blank">POWERED BY TINY</a>');
    }, 1000);
    $('#materia_reactivo').change(function () {
        let materia = $('#materia_reactivo option:selected').val();
        getValue('reactivo/getCompetencias/', {
            idMateria: materia
        }, function (options) {
            $('#competencia_materia option[value!="0"]').remove();
            $('#bloque_competencia option[value!="0"]').remove();
            $('#competencia_materia').append(options);
            //$('[name="grupos"] option[value="<?php echo valueFromSessionOrDefault('grupos') ?>"]')
        });
    });
    $('#competencia_materia').change(function () {
        let competencia = $('#competencia_materia option:selected').val();
        getValue('reactivo/getBloques/', {
            idCompetencia: competencia
        }, function (options) {
            $('#bloque_competencia option[value!="0"]').remove();
            $('#bloque_competencia').append(options);
            //$('[name="grupos"] option[value="<?php echo valueFromSessionOrDefault('grupos') ?>"]')
        });
    });
    $('#bloque_competencia').change(function () {
        let bloque = $('#bloque_competencia option:selected').val();
        getValue('reactivo/getConocimiento/', {
            idBloque: bloque
        }, function (textoConocimiento) {
            $('#textoConocimiento').empty();
            $('#textoConocimiento').append(textoConocimiento);
            //$('[name="grupos"] option[value="<?php echo valueFromSessionOrDefault('grupos') ?>"]')
        });
    });


});

$(document).on('focusin', function (e) {
    if ($(e.target).closest(".mce-window").length) {
        e.stopImmediatePropagation();
    }
});