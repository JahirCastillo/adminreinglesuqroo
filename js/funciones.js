// JavaScript Document



//--------limpia vista preliminar------
/** limpia el espacio de vista preliminar. */
function limpia_vista() {
    $("#Vintrucciones").empty();
    $("#Vreactivo").empty();
    $("#Vopciones").empty();
    $("#VCtexto").empty();
    $("#VCvideo").empty();
    $("#VCaudio").empty();
}
//





//-----------VISTA PRELIMINAR-----------
//-----opcion correcta multiple----//
/**
 * verifica si la opcion seleccionada es la correcta del reactivo.
 * 
 * @param int clave, identificador del registro.
 * @param int i, numero de ubicacion en la pantalla.
 * var int num, cantidad de opciones multiples.
 * var int x, contador para el borrado de la clase que muestra el estado correcto o incorrecto de la opcion seleccionada.
 * @return .
 */
function opc_correcta_multiple(cla, i) {
    var num = $("#rea_numopcion").val();
    var x = 0;
    while (x < num) {
        $("#ver_radio" + x).removeClass('btn-success');
        $("#ver_radio" + x).removeClass('btn-danger');
        x++;
    }
    $.post('index.php/reactivo/opc_correcta_multiple', {clave: cla},
            function (data) {
                console.log('data:' + data);
                if (data == 1) {
                    $("#ver_radio" + i).addClass('btn-success');
                    $("#aciertos").html('El elemento seleccionado es correcto!!!');
                } else {
                    $("#ver_radio" + i).addClass('btn-danger');
                    $("#aciertos").html('El elemento seleccionado es incorrecto.');
                }
            });
}

//-----opcion correcta ordenada----//
/**
 * verifica si los elementos estan ordenados correctamente del reactivo.
 * var int cla, clave del reactivo a calificar.
 * var int count, contador de elementos odenerados correctamente.
 * var array char lista, nombre del los identificadores de los elementos ordenados.
 * var int a, numero para identificar el elementos a verificar su posición.
 */
function opc_correcta_ordenada() {
    var cla = $("#rea_clave").val();
    var count = 0;
    var lista = $("#elementos").sortable("toArray");
    $.post('index.php/reactivo/datosOpciones', {clave: cla},
            function (data) {
                $.each(data, function (i, aDatos) {
                    // int aDatos.cor - posicion correcta de una opcion del reactivo.
                    // int a - indice para el array lista, para checar el elemento en el misma posicion que la opcion correcta.
                    var a = aDatos.cor - 1;
                    // limpia los elemento removiendo la clase, si ya han sido calificados antes.
                    $("#" + lista[a]).removeClass('marco-correcto');
                    $("#" + lista[a]).removeClass('marco-incorrecto');
                    con = $("#" + lista[a]).html();
                    // char aDatos.con - contenido de la opcion correcta.
                    // char con - contenido del elemento en la misma posicion que la opion correcta.
                    // si es el mismo contenido en ambas, es correcto el elemento en la misma posicion a calificar.
                    if (aDatos.con == con) {
                        console.log('bien' + aDatos.con + '-' + con);
                        $("#" + lista[a]).addClass('marco-correcto');
                        count++;
                    } else {
                        console.log('mal' + aDatos.con + '-' + con);
                        $("#" + lista[a]).addClass('marco-incorrecto');
                    }
                });
                $("#aciertos").html('Se obtuvo ' + count + ' aciertos de ' + $("#rea_numopcion").val() + ' elementos a ordenar.');
            }, 'json');
}

//-----opcion correcta relacionada y clasificada----//
/**
 * verifica si los elementos de grupo de clasificación o si los elementos relacionados estan correctamente posicionados.
 *
 * var int cla, clave del reactivo a calificar.
 * var array int clasif[], clave de las opciones de clasificacion o definicion dado por la BD.
 * var int num1, cantidad de elementos que existe para clasificar o relacionar.
 * var int num2, cantidad de elementos clasificatorios o definiciones a relacionar.
 * var int i, indice usada para el array clasif[].
 * var int x, indice para seleccionar el identificador de elemento a comparar con la clave de la opción.
 * var int y, indice para seleccionar el identificador de los elementos a clasificar o relacionar.
 * var int count, contador de los elementos clasificados o relacionados que son correctamente posicionados. 
 */
function opc_correcta_clasificada() {
    var cla = $("#rea_clave").val();
    var count = 0;
    var clasif = [];
    num1 = $("#rea_numcolumna1").val();
    num2 = $("#rea_numcolumna2").val();
    $.post('index.php/reactivo/datosOpciones', {clave: cla},
            function (data) {
                $.each(data, function (i, aDatos) {
                    clasif[i] = aDatos.cla;
                });
                i = x = 0;
                do {
                    if ($("#Vrea_columna1" + x).hasClass(clasif[i])) {
                        console.log($("#Vrea_columna1" + x).text() + ' ' + clasif[i]);
                        contenido = $("#Vrea_columna1" + x).html();
                        y = 0;
                        while (y < num1) {
                            palabra = 'Vrea_columna2' + y;
                            var n = contenido.search(palabra);
                            if (n > 0) {
                                if ($("#Vrea_columna2" + y).hasClass(clasif[i])) {
                                    $("#Vrea_columna2" + y).addClass(' marco-correcto');
                                    count++;
                                } else
                                    $("#Vrea_columna2" + y).addClass(' marco-incorrecto');
                            }
                            y++;
                        }
                    }
                    x++;
                    if (x == num2) {
                        i++;
                        x = 0;
                        console.log('segunda')
                    }
                } while (i < num2);
                $("#aciertos").html('Se obtuvo ' + count + ' aciertos de ' + num1 + ' elementos.');
            }, 'json');
}

//-----ver opciones multiples---------------
/**
 * muestra en vista preliminar las opciones de respuesta de un reactivo con la dinamica de tipo de reactivo opción multiple .
 * 
 * @param int clave, identificador del registro.
 */
function ver_multiple(clave) {
    $.post('index.php/reactivo/datosOpciones', {clave: clave},
            function (opcdata) {
                if ($("#tipo_reactivo").val() == 1) {
                    $.each(opcdata, function (i, aDatos) {
                        cla = "'" + aDatos.cla + "'";
                        $("#Vopciones").append('<div class="row" id="ver_opcion_' + i + '"><div class="col-md-1"><input type="button" class="btn btn-default" id="ver_radio' + i + '" onClick="opc_correcta_multiple(' + cla + ',' + i + ');" value="' + aDatos.car + '"></button></div><div class="col-md-11" id="ver_contenido' + i + '">' + aDatos.con + '</div></div>');
                    });
                }
            }, 'json');
    $("#Vopciones").append('<div class="row"><div class="col-md-10 col-md-offset-1 alert alert-warning" id="aciertos"></div></div>');
}

//-----ver opciones relacionar---------------
/**
 * muestra en vista preliminar las opciones de respuesta de reactivo con la dinamica de tipo de reactivo relacionar columnas.
 * 
 * @param int clave, identificador del registro.
 */
function ver_relacionar(clave) {
    $("#Vopciones").append('<div class="row" id="Varea_relacion"></div><div class="row"><div class="col-md-7" id="Vcol1"></div><div class="col-md-5" id="Vcol2"></div></div>');
    $("#Vcol2").append('<div id="elementos" style="height:300px;"></div>');
    $("#elementos").droppable({
        drop: function (event, ui) {
            var $drop = $(this);
            $(ui.draggable).draggable({
                "disabled": false
            }).appendTo($drop);
            $(this).css("background-color", "white")
        },
        over: function (event, ui) {
            $(this).css("background-color", "#e5e5e5")
        },
        out: function (event, ui) {
            $(this).css("background-color", "white")
        }
    });
    $.post('index.php/reactivo/datosOpciones', {clave: clave},
            function (opcdata) {
                $.each(opcdata, function (i, aDatos) {
                    $("#elementos").append('<div class="' + aDatos.cla + ' bloque drag" id="Vrea_columna2' + i + '">' + aDatos.con + '</div>');
                    $("#Vrea_columna2" + i).draggable({
                        helper: "clone",
                        revert: "invalid"
                    });
                });
                $.post('index.php/reactivo/datosOpciones1', {clave: clave},
                        function (opcdata) {
                            console.log('datos_columna1: ' + opcdata);
                            $.each(opcdata, function (i, aDatos) {
                                $("#Vcol1").append('<div class="' + aDatos.opc + ' row" id="Vrea_columna1' + i + '">' + aDatos.con + '</div>');
                                $("#Vrea_columna1" + i).droppable({
                                    drop: function (event, ui) {
                                        var $drop = $(this);
                                        $(ui.draggable).draggable({
                                            "disabled": false
                                        }).appendTo($drop);
                                        $(this).css("background-color", "lightblue"),
                                                $(this).droppable('option', 'accept', ui.draggable)
                                    },
                                    over: function (event, ui) {
                                        $(this).css("background-color", "lightgrey")
                                    },
                                    out: function (event, ui) {
                                        $(this).css("background-color", "lightblue"),
                                                $(this).droppable('option', 'accept', '.drag')
                                        $(ui.draggable).removeClass('marco-correcto');
                                        $(ui.draggable).removeClass('marco-incorrecto');
                                    }
                                });
                            });
                        }, 'json');
            }, 'json');
    $("#Vopciones").append('<div class="row"><button class="col-md-3 btn btn-primary" onClick="opc_correcta_clasificada()">Calificar</button><div class="col-md-9 alert alert-info" id="aciertos"></div></div>');
}

//-----ver opciones clasificar---------------
/**
 * muestra en vista preliminar las opciones de respuesta de reactivo con la dinamica de tipo de reactivo 
 * clasificacion de elementos.
 * 
 * @param int clave, identificador del registro.
 */
function ver_clasificar(clave) {
    $("#Vopciones").append('<div class="row" id="Varea_relacion"></div><div class="row"><div class="col-md-7" id="Vcol1"></div><div class="col-md-5" id="Vcol2"></div></div>');
    $.post('index.php/reactivo/datosOpciones', {clave: clave},
            function (opcdata) {
                $.each(opcdata, function (i, aDatos) {
                    console.log(aDatos.cla + ' ' + aDatos.con);
                    $("#Vcol1").append('<div class="' + aDatos.cla + ' marco" id="Vrea_columna1' + i + '" style="background:Lightblue; height:100px, auto, inherit;">' + aDatos.con + '</div>');
                    $("#Vrea_columna1" + i).droppable({
                        //scope: aDatos.cla,
                        drop: function (event, ui) {
                            var $drop = $(this);
                            $(ui.draggable).draggable({
                                "disabled": false
                            }).appendTo($drop);
                            $(this).css("background-color", "Lightblue")
                        },
                        over: function (event, ui) {
                            $(this).css("background-color", "lightgrey")
                        },
                        out: function (event, ui) {
                            $(this).css("background-color", "Lightblue")
                            $(ui.draggable).removeClass('marco-correcto');
                            $(ui.draggable).removeClass('marco-incorrecto');
                        }
                    });
                });
                $("#Vcol2").append('<div id="elementos" style="height:300px;"></div>');
                $("#elementos").droppable({
                    drop: function (event, ui) {
                        var $drop = $(this);
                        $(ui.draggable).draggable({
                            "disabled": false
                        }).appendTo($drop);
                        $(this).css("background-color", "")
                    },
                    over: function (event, ui) {
                        $(this).css("background-color", "")
                    },
                    out: function (event, ui) {
                        $(this).css("background-color", "")
                    }
                });
                $.post('index.php/reactivo/datosOpciones1', {clave: clave},
                        function (opcdata) {
                            console.log('datos_columna1: ' + opcdata);
                            $.each(opcdata, function (i, aDatos) {
                                $("#elementos").append('<div class="' + aDatos.opc + ' bloque" id="Vrea_columna2' + i + '">' + aDatos.con + '</div>');
                                $("#Vrea_columna2" + i).draggable({
                                    //scope: aDatos.opc,
                                    connectToSortable: "#elementos",
                                    helper: "clone",
                                    revert: "invalid"
                                });
                            });
                        }, 'json');
            }, 'json');
    $("#Vopciones").append('<div class="row"><button class="col-md-3 btn btn-primary" onClick="opc_correcta_clasificada()">Calificar</button><div id="aciertos" class="col-md-9 alert alert-info"></div></div>');
}

//-----ver opciones ordenar---------------
/**
 * muestra en vista preliminar las opciones de respuesta de reactivo con la dinamica de tipo de ordenar los elementos.
 * @param int clave, identificador del registro.
 */
function ver_ordenar(clave) {
    $("#Vopciones").append('<div class="row" id="Varea_relacion"></div><div class="row"><div class="col-md-7" id="Vcol1"></div><div class="col-md-5" id="Vcol2"></div></div>');
    $("#Vopciones").append('<div class="contenedor_f" id="elementos"></div>');
    $("#elementos").sortable({
        placeholder: "ui-state-highlight"
    });
    $("#elementos").disableSelection();
    $.post('index.php/reactivo/datosOpciones', {clave: clave},
            function (opcdata) {
                $.each(opcdata, function (i, aDatos) {
                    $("#elementos").append('<div class="bloque" id="Vrea_opcion' + i + '">' + aDatos.con + '</div>');
                });
            }, 'json');
    $("#Vopciones").append('<div class="row"><button class="col-md-3 btn btn-primary" onClick="opc_correcta_ordenada();">Calificar</button><div id="aciertos" class="col-md-9 alert alert-info"></div></div>');

}

//----------controles play y pause video y audio
//------------pause video
/**
 * pausea video.
 */
function vpause() {
    var myVideo = document.getElementById("video1");
    myVideo.pause();
    //$("#video_pause").attr('disabled','disabled'); 
}

//-------play de video-----
/**
 * reproduce video.
 */
function vplay() {
    var myVideo = document.getElementById("video1");
    var rep = $("#rep").val();
    console.log('reproduccion: ' + $("#rep").val());
    if (rep > 0) {
        myVideo.play();
        rep = rep - 1;
        $("#rep").val(rep);
        console.log('reproduccion: ' + $("#rep").val());
        if (rep == 0)
            $("#video_play").attr('disabled', 'disabled');
    }
}

//---------play de audio---
/**
 * reproduce audio.
 */
function aplay(audio) {
    var myAudio = document.getElementById('audio1');
    var rep = $("#arep").val();
    console.log('reproduccion: ' + $("#arep").val());
    if (rep > 0) {
        myAudio.play();
        rep = rep - 1;
        $("#arep").val(rep);
        console.log('reproduccion: ' + $("#arep").val());
        if (rep == 0)
            $("#audio_play").attr('disabled', 'disabled');
    }
}

//---------pausea audio
/**
 * pausea audio.
 */
function apause() {
    var myAudio = document.getElementById('audio1');
    myAudio.pause();
}

//---------ver vista preliminar------
/**
 * muestra como se visualiza el reactivo con todos sus componentes (opciones de respuesta y caso).
 * 
 * @param int clave, identificador del registro.
 */
function vistaPreliminar(clave) {
    limpia_vista();
    if (clave == '')
        clave = $("#rea_clave").val();
    //console.log('vista clave:'+clave);
    $.post("index.php/reactivo/existeReactivo", {clave: clave},
            function (data) {
                if (data == 1) {
                    $.post("index.php/reactivo/llenarReactivo", {clave: clave},
                            function (rea) {
                                console.log('reactivo tipo:' + rea.tip);
                                if (rea.tip != 0) {
                                    $.post("index.php/reactivo/dato_instruccion", {instruccion: rea.tip},
                                            function (inst) {
                                                $("#Vintrucciones").append('<strong>Intrucciones:</strong> ' + inst);
                                            });
                                    if (rea.tip == 1)
                                        ver_multiple(clave);
                                    else if (rea.tip == 2)
                                        ver_radios(clave);
                                    else if (rea.tip == 3)
                                        ver_relacionar(clave);
                                    else if (rea.tip == 4)
                                        ver_corta(clave);
                                    else if (rea.tip == 5)
                                        ver_ordenar(clave);
                                    else if (rea.tip == 6)
                                        ver_clasificar(clave);
                                    else if (rea.tip == 7)
                                        ver_numerico(clave);
                                }
                                Rcontenido = rea.con;
                                console.log('con 1:' + Rcontenido);
                                Rcontenido = Rcontenido.replace('src="../', 'src="./');
                                //console.log('con 1:'+Rcontenido);
                                $("#Vreactivo").html(Rcontenido);
                                if (rea.cas != 0) {
                                    //$("#Vcaso").addClass('marco');
                                    $.post("index.php/reactivo/datosCaso", {clave: rea.cas},
                                            function (cas) {
                                                Ccontenido = cas.contenido;
                                                //console.log('con 1:'+Ccontenido);
                                                Ccontenido = Ccontenido.replace('src="../', 'src="./');
                                                //console.log('con 2:'+Ccontenido);
                                                $("#VCtexto").html(Ccontenido);
                                                if (cas.video != "" && cas.video != 0) { //---video--
                                                    $("#VCvideo").html('<div class="well" style="text-align:center"><video id="video1" width="420"><source src="' + cas.video + '" type="video/mp4"><source src="' + cas.video + '" type="video/ogg"> Your browser does not support HTML5 video. </video><br>Reproducir:<input type="text" class="col-md-1" id="rep" disabled="disabled" />&nbsp;<button onclick="vplay();" id="video_play" class="btn"><i class="icon-play"></i></button><button onclick="vpause()" id="video_pause" class="btn"><i class="icon-pause"></i></button></div>');
                                                    //
                                                    if (cas.vreproduccion != 0) {
                                                        $("#rep").val(cas.vreproduccion);
                                                    } else {
                                                        $("#rep").val(0);
                                                    }
                                                    if (cas.vpauseo == 0) {
                                                        $("#pause").attr('disabled', 'disabled');
                                                    }
                                                    if (cas.vauto == 1) {
                                                        $("#video1").attr('autoplay');
                                                    }
                                                    if (cas.vreproduccion == 0) {
                                                        $("#video_play").attr('disabled', 'disabled');
                                                    }
                                                }
                                                if (cas.audio != "" && cas.audio != 0) { //---audio---
                                                    var id_audio = "'" + "audio1" + "'";
                                                    $("#VCaudio").html('<audio id="audio1" src="' + cas.audio + '"></audio><div>Reproducir:<input type="text" class="col-md-1" id="arep" disabled="disabled" />&nbsp;<button class="btn" id="audio_play" onclick="aplay();">Play</button>&nbsp;<button class="btn" id="audio_pause" onclick="apause();">Pausa</button></div>');
                                                    if (cas.areproduccion != 0) {
                                                        $("#arep").val(cas.areproduccion);
                                                    } else {
                                                        $("#arep").val(0);
                                                    }
                                                    if (cas.apauseo == 0) {
                                                        $("#apause").attr('disabled', 'disabled');
                                                    }
                                                    if (cas.aauto == 1) {
                                                        $("#audio1").attr('autoplay');
                                                    }
                                                    if (cas.areproduccion == 0) {
                                                        $("#audio_play").attr('disabled', 'disabled');
                                                    }
                                                }
                                            }, 'json');
                                }
                            }, 'json');
                } else {
                    $("#Vreactivo").html('<p><h3>El reactivo ' + clave + ' es nuevo y no ha sido guardado!.<h3></p>');
                }
            });
}


//------------------------------------CASO-----------------------------------------
// muestra y oculta la capturar el link de video o audio
$(document).ready(function () {
    $("#lab_video").click(function () {
        $("#casoVideo").toggle();
    });
    $("#lab_audio").click(function () {
        $("#casoAudio").toggle();
    });
});




//-----------------subir video----------
/** 
 * guarda link y carga video al servidor, para la visualizacion al momento de consultar el caso relacionado al video. 
 */
function subirVideo() {
    var inputFileImage = document.getElementById('cas_video');
    var file = inputFileImage.files[0];
    console.log(file);
    var data = new FormData();
    data.append('archivo', file);
    var url = 'index.php/reactivo/subirVideo';
    $.ajax({
        url: url,
        type: 'POST',
        contentType: false,
        data: data,
        processData: false,
        cache: false,
        success: function (res) {
            res;
        }
    });
}

//-----------------subir audio----------
/** 
 * guarda link y carga audio al servidor, para la visualizacion al momento de consultar el caso relacionado al audio. 
 */
function subirAudio() {
    var inputFileImage = document.getElementById('cas_audio');
    var file = inputFileImage.files[0];
    console.log(file);
    var data = new FormData();
    data.append('archivo', file);
    var url = 'index.php/reactivo/subirAudio';
    $.ajax({
        url: url,
        type: 'POST',
        contentType: false,
        data: data,
        processData: false,
        cache: false,
        success: function (res) {
            res;
        }
    });
}

//------------------------------------REACTIVOS-----------------------------------
//--------------------------------------TABS---------------------------------------------
$('#menutabs a').click(function (e) {
    e.preventDefault();
    $(this).tab('show');
})
$('#menutabs a[href="#datosVisuales"]').tab('show'); // Select tab by name
$('#menutabs a[href="#referencia"]').tab('show'); // Select tab by name
$('#menutabs a[href="#vistaPreliminar"]').tab('show'); // Select tab by name

$(document).ready(function () {


    $("#check_plan").click(function () {
        if ($("#check_plan").is(':checked')) {
            $("#searchPlan").show();
        } else {
            $("#searchPlan").hide();
        }
    });

    $("#check_caso").click(function () {
        if ($("#check_caso").is(':checked')) {
            $("#searchCaso").show();
        } else {
            $("#searchCaso").hide();
        }
    });

    $("#check_reactivo").click(function () {
        if ($("#check_reactivo").is(':checked')) {
            $("#searchReactivo").show();
        } else {
            $("#searchReactivo").hide();
        }
    });
});

//-------limpia pantalla reactivo---------------
/** 
 * oculta las advertencias de errores dadas anteriormente por la función de validacion de datos del reactivo. 
 */
function limpia_reactivo() {
    $("input").val('');
    $.post("index.php/reactivo/claveReactivo",
            function (data) {
                $("#rea_clave").val(data);
            });
    $("#mostrarCaso").hide();
    $("#mostrarPlan").hide();
    $("#mostrarReactivo").hide();
    $("#editarCaso").hide();
    $("#cas_clave").val('');
    $("#sinCaso").show();
    $("#estado_c").addClass('btn-danger active');
    $("#estado_i").removeClass('btn-warning active');
    $("#estado_a").removeClass('btn-success active');
    tinyMCE.get('rea_contenido').setContent('');
    //$("#rea_contenido").html('');
    $("#tipo_reactivo").val(0);
    $("#tipo_reactivo").removeAttr('disabled', 'disabled');
    $("#btn_opciones").removeAttr('disabled', 'disabled');
    $("#opciones").empty();
    limpia_error();
}

//------------------guardar reactivo-------------------
//------------------guardar reactivo 1.3----(opciones)---------------
/** 
 * obtiene las opciones insertadas de acuerdo al tipo de reactivo y los envia para insertar o actulizar los datos de las tabla ADM_OPCON y ADM_OPCION1.
 */
function saveOpcion() {
    var cla = $("#rea_clave").val();
    tipo = $("#tipo_reactivo").val();
    if (tipo == 1 || tipo == 5) { // multiple
        var cont = 0;
        var numopc = $("#rea_numopcion").val();
        while (cont < numopc) {
            n = cont + 1;
            claopc = cla + '-' + n;
            opccont = $("#opc_contenido" + cont).html();
            if (tipo == 1) {
                if ($("#rea_radio" + cont).is(':checked'))
                    radio = 1;
                else
                    radio = 0;
            } else if (tipo == 5) {
                radio = $("#rea_orden" + cont).val();
            }
            $.post("index.php/reactivo/guardarOpcion", {clave: claopc, radio: radio, opcion: opccont, reactivo: cla},
                    function (datas) {
                        console.log(datas);
                    });
            cont++;
        }
    } else if (tipo == 3 || tipo == 6) { // relacionar ó clasificar
        var cont = 0;
        var num_col1 = $("#rea_numcolumna1").val();
        var num_col2 = $("#rea_numcolumna2").val();
        while (cont < num_col2) {
            n = cont + 1;
            claopc = cla + '-' + n;
            contenido = $("#rea_columna2" + cont).html();
            $.post("index.php/reactivo/guardarOpcion", {clave: claopc, radio: 0, opcion: contenido, reactivo: cla},
                    function (datas) {
                        console.log(datas);
                    });
            cont++;
        }
        cont = 0;
        while (cont < num_col1) {
            n = cont + 1;
            claopc = cla + '-' + n;
            contenido = $("#rea_columna1" + cont).html();
            relacion = '';
            x = 0;
            while (x < num_col2) {
                if ($("#rea_columna1" + cont).hasClass('color' + x)) {
                    a = x + 1;
                    relacion = cla + '-' + a;
                }
                x++;
            }
            $.post("index.php/reactivo/guardarOpcion1", {clave: claopc, contenido: contenido, relacion: relacion, reactivo: cla},
                    function (datas) {
                        console.log(datas);
                    });
            cont++;
        }
    }
}





//---------datos de opciones multiples y ordenar 1,5------------
/**
 * Muestra las opciones multiples del reactivo.
 *@param int clave, identificador del reactivo.
 *@param char modo, modo de calificar el reactivo.
 *@param int num, cantidad de opciones a mostrar.
 */
function datosMultiples(clave, modo, num) {
    tipo = $("#tipo_reactivo").val();
    $("#opciones").append('<div class="row"><div class="col-md-8"> Modo de Calificar: <input type="text" id="rea_modocalif" disabled="disabled" value="' + modo + '" /></div><div class="col-md-4" align="right"><button class="btn btn-warning" id="btn_editaropciones" onClick="editarOpciones();"><i class="fa fa-pencil"></i> </button>&nbsp;<button class="btn btn-danger" id="btn_borraropciones" onClick="borrarOpciones();"><i class="fa fa-remove"></i> </button></div> </div><input type="text" id="rea_numopcion" class="input-small" disabled="disabled" style="display:none" />');
    $("#rea_numopcion").val(num);
    $("#modocalif").val(modo);
    //style="display:none"
    $.post('index.php/reactivo/datosOpciones', {clave: clave},
            function (opcdata) {
                $.each(opcdata, function (i, aDatos) {
                    if (tipo == 1) {
                        $("#opciones").append('<div class="row rectivo_line" id="rea_opcion_' + i + '"><div class="col-md-1"><input type="radio"  id="rea_radio' + i + '" disabled="disabled" /></div><div class="col-md-11" id="opc_contenido' + i + '">' + aDatos.con + '</div></div>');
                        if (aDatos.cor == 1)
                            $("#rea_radio" + i).attr("checked", "checked");
                    } else if (tipo == 5) {
                        $("#opciones").append('<div class="row rectivo_line" id="rea_opcion_' + i + '"><div class="col-md-1"><input type="text" id="rea_orden' + i + '" class="col-md-12" disabled="disabled" /></div><div class="col-md-11" id="opc_contenido' + i + '">' + aDatos.con + '</div></div>');
                        $("#rea_orden" + i).val(aDatos.cor);
                    }
                });
            }, 'json');
}

//------datos de opciones de relacionar columnas y clasificar 3,6---
/**
 * Muestra las opciones de relacionar columnas.
 *@param int clave, identificador del reactivo.
 *@param char modo, modo de calificar el reactivo.
 *@param int num, cantidad de opciones a mostrar.
 */
function datosRelacionar(clave, modo, num) {
    console.log('clave:' + clave + ' modo: ' + modo + ' num: ' + num);
    $("#opciones").append('<div class="row"><div class="col-md-8"> Modo de Calificar: <input type="text" id="rea_modocalif" disabled="disabled" value="' + modo + '" /></div><div class="col-md-4" align="right"><button class="btn btn-warning" id="btn_editaropciones" onClick="editarOpciones();"><i class="fa fa-pencil"></i> </button>&nbsp;<button class="btn btn-danger" id="btn_borraropciones" onClick="borrarOpciones();"><i class="fa fa-danger"></i> </button></div> </div><input type="text" id="rea_numcolumna1" class="input-small" disabled="disabled" style="display:none"/><input type="text" id="rea_numcolumna2" class="input-small" disabled="disabled" style="display:none"/><br /><div class="row" id="area_relacion" style="display:none"></div><div class="row"><div class="col-md-7" id="col1"></div><div class="col-md-5" id="col2"></div></div>');
    //style="display:none"		
    $("#rea_numcolumna2").val(num);
    //style="display:none"
    $.post('index.php/reactivo/datosOpciones', {clave: clave},
            function (opcdata) {
                $.each(opcdata, function (i, aDatos) {
                    $("#area_relacion").append('<input type="text" id="clave' + i + '" class="col-md-2" value="' + aDatos.cla + '"/>');
                    if ($("#tipo_reactivo").val() == 3)
                        $("#col2").append('<div class="row color' + i + '" id="rea_columna2' + i + '">' + aDatos.con + '</div>');
                    else if ($("#tipo_reactivo").val() == 6)
                        $("#col2").append('<div class="container_fluid color' + i + '" id="clasif' + i + '"><div class="row" align="center" id="rea_columna2' + i + '">' + aDatos.con + '</div></div>');

                });
                $.post('index.php/reactivo/datosOpciones1', {clave: clave},
                        function (opc1data) {
                            $.each(opc1data, function (i, aDatos) {
                                x = 0;
                                while (x < num) {
                                    if ($("#clave" + x).val() == aDatos.opc) {
                                        $("#area_relacion").append('<input type="text" id="arelacion' + i + '" class="col-md-1" value="' + x + '"/>');
                                        $("#col1").append('<div class="row  color' + x + '" id="rea_columna1' + i + '">' + aDatos.con + '</div>');
                                        if ($("#tipo_reactivo").val() == 6)
                                            $("#clasif" + x).append('<div class="row bloque">' + aDatos.con + '</div>');
                                    }
                                    x++;
                                }
                                $("#rea_numcolumna1").val(i + 1);
                            });
                        }, 'json');
                if ($("#rea_numcolumna1").val() == '')
                    $("#rea_numcolumna1").val(0);
            }, 'json');
}

//-----------------datos Reativo-----------------------
/**
 * obtine datos de un registro de la tabla reactivo.
 * @param int clave, identificador del registro de datos a buscar.
 * @return array datos, datos del registro.
 */
function llenarReactivo(clave) {
    $("input").val('');
    $("#mostrarCaso").hide();
    $("#mostrarPlan").hide();
    $("#mostrarReactivo").hide();
    $("#editarCaso").hide();
    $("#cas_clave").val('');
    $("#sinCaso").show();
    $("#estado_c").addClass('btn-danger active');
    $("#estado_i").removeClass('btn-warning active');
    $("#estado_a").removeClass('btn-success active');
    tinyMCE.get('rea_contenido').setContent('');
    //$("#rea_contenido").html('');
    $("#tipo_reactivo").val(0);
    $("#tipo_reactivo").removeAttr('disabled', 'disabled');
    $("#btn_opciones").removeAttr('disabled', 'disabled');
    $("#opciones").empty();
    limpia_error();
    $("#opciones").empty();
    $.post('index.php/reactivo/llenarReactivo', {clave: clave},
            function (data) {
                $("#rea_clave").val(data.cla);
                if (data.est == 'C') {
                    $("#estado_i").removeClass('btn-warning');
                    $("#estado_c").addClass('btn-danger active');
                    $("#estado_a").removeClass('btn-success');
                } else if (data.est == 'I') {
                    $("#estado_i").addClass('btn-warning active');
                    $("#estado_c").removeClass('btn-danger');
                    $("#estado_a").removeClass('btn-success');
                } else if (data.est == 'A') {
                    $("#estado_i").removeClass('btn-warning');
                    $("#estado_c").removeClass('btn-danger');
                    $("#estado_a").addClass('btn-success active');
                }
                $("#pla_clave").val(data.pla);
                $("#pla_nombre").val(data.nom);
                tinyMCE.get('rea_contenido').setContent(data.con);
                //$("#rea_contenido").html(data.con);
                $("#tipo_reactivo").val(data.tip);
                if (data.cas != 0) {
                    datosCaso(data.cas);
                }
                if (data.lib != 0) {
                    datosLibro(data.lib);
                }
                if (data.aut != 0) {
                    datosAutor(data.aut);
                }
                if (data.tip == 1) {
                    datosMultiples(clave, data.cal, data.num);
                } else if (data.tip == 3) {
                    datosRelacionar(clave, data.cal, data.num);
                } else if (data.tip == 5) {
                    datosMultiples(clave, data.cal, data.num);
                } else if (data.tip == 6) {
                    datosRelacionar(clave, data.cal, data.num);
                }
                if (data.num > 0) {
                    $("#tipo_reactivo").attr("disabled", "disabled");
                    $("#btn_opciones").attr("disabled", "disabled");
                }
            }, 'json');
    //------llenar opciones reactivo---
    $("#mostrarReactivo").hide();
    vistaPreliminar(clave);
}

//--------------------------PLAN------------------------------------------



$(document).ready(function () {


    //--------------mostrar nombre plan ------------
    $("#pla_clave").change(function () {
        clave = $("#pla_clave").val();
        $.post("index.php/reactivo/nombrePlan", {clave: clave},
                function (data) {
                    if (data != 'N') {
                        $("#pla_nombre").val(data);
                    } else {
                        $("#pla_clave").val('');
                        $("#pla_nombre").val('');
                    }
                });
    });

    //--------obtener nombre de la clave del campo padre------
    $("#add_cpadrePlan").change(function () {
        clave = $("#add_cpadrePlan").val();
        $.post("index.php/reactivo/nombrePlan", {clave: clave},
                function (data) {
                    if (data != 'N')
                        $("#add_npadrePlan").val(data);
                    else {
                        $("#add_cpadrePlan").val('');
                        $("#add_npadrePlan").val('');
                    }
                });
    });

    //--------mostrar formulario nuevo plan-----
    $("#btn_nuevoPlan").click(function () {
        limpiarPlan();
        $("#nuevoPlan").show();
        $("#label_nuevo").show();
        $("#label_editar").hide();
        $("#label_borrar").hide();
        $("#btn_guardarPlan").show();
        $("#btn_borrarPlan").hide();
        $("#add_clavePlan").attr('disabled', 'disabled');
        $(".no_hay_padre").show();
        $("#no_padre").attr('checked', false);
        $.post("index.php/reactivo/clavePlan",
                function (data) {
                    $("#add_clavePlan").val(data);
                });
    });

    //-------borrar plan de estudios---------
    $("#btn_borrarPlan").click(function () {
        clave = $("#add_clavePlan").val();
        console.log(clave);
        $.post("index.php/reactivo/eliminarPlan", {clave: clave},
                function (data) {
                    console.log(data);
                    if (data == 1) {
                        mensaje('Plan Eliminado!!');
                        limpiarPlan();
                        $("#nuevoPlan").hide();
                    } else {
                        mensaje('ERROR al ELIMINAR el Plan.');
                    }
                });
    });

});

//---------limpiar formulario de plan----
/**
 * vacia todos los campos del formulario plan, para una nueva captura.
 */
function limpiarPlan() {
    $("#add_clavePlan").val('');
    $("#add_nombrePlan").val('');
    $("#add_cpadrePlan").val('');
    $("#add_npadrePlan").val('');
    $("#add_descripcionPlan").val('');
}

//---------ocultar formulario de plan-------
/**
 * oculta formulario plan
 */
function cancelarPlan() {
    limpiarPlan();
    $("#nuevoPlan").hide();
}

//--------datos de plan de estudios--------
/**
 * muestra los datos de plan de estudio seleccionada en su respectivo formulario. 
 */
function datosPlan(clave) {
    $.post("index.php/reactivo/datosPlan", {clave: clave},
            function (data) {
                $("#add_clavePlan").val(data.cla);
                $("#add_nombrePlan").val(data.nom);
                $("#add_cpadrePlan").val(data.pad);
                $("#add_npadrePlan").val(data.pnom);
                $("#add_descripcionPlan").val(data.des);
                rea = data.rea;
                hij = data.hij;
            }, 'json');
}

//-------mostrar datos para editar plan de estudio----
/**
 * muestra en el formulario de plan los componentes para la edición del plan seleccionada.
 */
function editarPlan(clave) {
    $("#nuevoPlan").show();
    $("#label_nuevo").hide();
    $("#label_borrar").hide();
    $("#label_editar").show();
    $("#btn_guardarPlan").show();
    $("#btn_borrarPlan").hide();
    datosPlan(clave);
}

//-------muestra plan y un mensaje para la eliminacion------ 
/**
 * elimina el plan seleccionada con la condicion de que no exista otro plan de estudio que dependa de él (hijo) o un 
 * reactivo que se encuentra dentro de su plan de estudio.
 */
function borrarPlan(clave) {
    $("#msg_eliminarPlan").empty();
    $("#nuevoPlan").show();
    $("#label_nuevo").hide();
    $("#label_borrar").show();
    $("#label_editar").hide();
    $("#btn_guardarPlan").hide();
    $.post("index.php/reactivo/datosPlan", {clave: clave},
            function (data) {
                $("#add_clavePlan").val(data.cla);
                $("#add_nombrePlan").val(data.nom);
                $("#add_cpadrePlan").val(data.pad);
                $("#add_npadrePlan").val(data.pnom);
                $("#add_descripcionPlan").val(data.des);
                if (data.hij > 0)
                    $("#msg_eliminarPlan").append('NO SE PUEDE ELIMINAR!. Existen ' + data.hij + ' planes que dependen de este Plan de Estudio');
                else if (data.rea > 0)
                    $("#msg_eliminarPlan").append('NO SE PUEDE ELIMINAR!. Existen ' + data.rea + ' reactivos que dependen de este Plan de Estudio');

                else {
                    $("#msg_eliminarPlan").append('Se encuentra seguro de eliminar este Plan de Estudios?');
                    $("#btn_borrarPlan").show();
                }
                rea = data.rea;
                hij = data.hij;
            }, 'json');
}

//---------guardar plan----------------------
/**
 * valida y guarda un nuevo registro de plan o actuliza uno existente.
 */
function guardarPlan() {
    $("#add_nombrePlan").removeClass('error');
    $("#msg_addnomPlan").hide();
    $("#add_cpadrePlan").removeClass('error');
    $("#msg_addclaPlan").hide();
    res = 1;
    var pcla_tmp = 0;
    if ($("#add_nombrePlan").val() == '') {
        res = 0;
        $("#add_nombrePlan").addClass('error');
        $("#msg_addnomPlan").show();
    }

    if ($("#no_padre").is(":checked")) {
        pcla_tmp = 0;
    } else {
        pcla_tmp = $("#add_cpadrePlan").val();
        if ($("#add_cpadrePlan").val() == '') {
            res = 0;
            $("#add_cpadrePlan").addClass('error');
            $("#msg_addclaPlan").show();
        }
    }
    if (res == 1) {
        var cla = $("#add_clavePlan").val(),
                nom = $("#add_nombrePlan").val(),
                pcla = pcla_tmp,
                des = $("#add_descripcionPlan").val(),
                ins = $("#add_descripcionPlan").val();
        $.post("index.php/reactivo/guardarPlan", {clave: cla, nombre: nom, pclave: pcla, descripcion: des},
                function (data) {
                    mensaje(data);
                    $("#nuevoPlan").hide();
                    $("#add_clavePlan").val('');
                    $("#add_nombrePlan").val('');
                    $("#add_npadrePlan").val('');
                    $("#add_cpadrePlan").val('');
                    $("#add_descripcionPlan").val('');
                });
    }
}

//-----------buscar registros de plan------------------
/**
 * busca concidencia de registros de plan de estudios
 *
 *@param varchar palabra, cadena a buscar en reactivos.
 *@return array, registros que contiene la cadena parametro palabra.
 */
function buscarPlan(palabra) {
    $.post("index.php/reactivo/buscarPlan", {palabra: palabra},
            function (data) {
                $('#datosPlan').dataTable().fnClearTable();
                $.each(data, function (i, aDatos) {
                    console.log(aDatos.cla + ' ' + aDatos.hij);
                    if (aDatos.hij == 0) {
                        nombre = "'" + aDatos.nom + "'";
                        clave = '<button class="btn btn-link" onClick="llenarPlan(' + aDatos.cla + ',' + nombre + ')">' + aDatos.cla + '</button>';
                        console.log(clave);
                    } else
                        clave = aDatos.cla;
                    $('#datosPlan').dataTable().fnAddData([
                        clave,
                        aDatos.nom,
                        aDatos.des,
                        '<button class="btn btn-xs btn-warning" onClick="editarPlan(' + aDatos.cla + ')"><i class="fa fa-edit"></i></button>',
                        '<button class="btn btn-xs btn-danger" onClick="borrarPlan(' + aDatos.cla + ')"><i class="fa fa-remove"></i></button>',
                    ]);
                });
            }, 'json');
}

//-------------------------AUTOR-------------------------------------
$(document).ready(function () {
    $("#no_padre").click(function () {
        if ($("#no_padre").is(":checked")) {
            $(".no_hay_padre").hide();
            $("#add_cpadrePlan").removeClass('error');
            $("#msg_addclaPlan").hide();
        } else {
            $(".no_hay_padre").show();
        }
    });
});



//-------------------------LIBRO-------------------------------------
$(document).ready(function () {


});

//-------------------------OPCIONES---------------------
$(document).ready(function () {
    //------------DIALOGO MULTIPLES Y ORDENAR-------------------------------------


    /**------mostrar y ocultar instrucciones de dialogo de opciones de respuestas----*/
    $("#instruccion_multiple").click(function () {
        console.log($("#tipo_reactivo").val());
        if ($("#tipo_reactivo").val() == 1)
            $("#instruccionMultiple").toggle(); // instrucciones opción multiple
        else if ($("#tipo_reactivo").val() == 5)
            $("#instruccionOrdenar").toggle(); // instrucciones de modo ordenar las opciones
    });
    $("#instruccion_relacionar").click(function () {
        if ($("#tipo_reactivo").val() == 3)
            $("#instruccionRelacionar").toggle();  // instrucciones relacionar
        else if ($("#tipo_reactivo").val() == 6)
            $("#instruccionClasificar").toggle();  // instrucciones de modo clasificar las opciones
    });



    /** Respuesta SI o NO al borrado de las opciones de respuestas ya capturadas del reactivo actual */
    //----respuesta Si al borrar contenido de opciones de respuesta------
    $('#siOpcion').click(function () {
        $("#rea_numopcion").val('');
        $("#opciones").empty();
        $("#area_opciones").empty();
        $("#tipo_reactivo").removeAttr("disabled", "disabled");
        $("#btn_opciones").removeAttr("disabled", "disabled");
        setTimeout($.unblockUI, 0);
    });
    //----respuesta no al borrar el contenido de opciones de respuesta----
    $('#noOpcion').click(function () {
        setTimeout($.unblockUI, 0);
    });

});


/**--------insertar elemento en Columna 1 en relacionar o clasificar columnas--------------*/
function insertarColumna1() {
    columna1 = tinyMCE.get('columna1').getContent();
    if (columna1 == "")
        aviso('El campo de columna1 se encuentra vacío.'); // no ingresar campos vacios en columna1	
    else {
        if ($("#editar_columna1").val() != "") {
            var parte = $("#editar_columna1").val();
            columna1 = columna1.replace('src="../', 'src="./');
            $("#columna1" + parte).html(columna1);
            tinyMCE.get('columna1').setContent('');
            $("#editar_columna1").val('');
            $("#columna1" + parte).css('background-color', '#FFF');
        } else {
            var count = $("#num_columna1").val();
            columna1 = tinyMCE.get('columna1').getContent();
            columna1 = columna1.replace('src="../', 'src="./');
            count++;
            $("#num_columna1").val(count);
            cant = count - 1;
            $("#area_columna1").append('<div class="row" id="opcColumna1_' + cant + '"><div class="col-md-1"><label class="label label-info">' + count + '</label></div><div class="col-md-7 " id="columna1' + cant + '">' + columna1 + '</div><div class="col-md-3" align="center"><button type="button" class="btn btn-warning" onclick="editarColumna1(' + cant + ');"><i class="icon-pencil"></i></button>&nbsp;<button type="button" class="btn btn-danger" onclick="eliminarColumna1(' + cant + ');"><i class="fa fa-remove"></i></button></div><input type="text" class="col-md-1" id="relacion' + cant + '" style="display:none" /></div>');
            tinyMCE.get('columna1').setContent('');
        }
    }
    $("#columna1").focus();
}

/**--------insertar elemento en Columna 2 en relacionar o clasificar columnas--------------*/
function insertarColumna2() {
    columna2 = tinyMCE.get('columna2').getContent();
    if (columna2 == "")
        aviso('El campo de columna2 se encuentra vacío.'); // no ingresar campos vacios en columna2	
    else {
        if ($("#editar_columna2").val() != "") {
            var parte = $("#editar_columna2").val();
            columna2 = columna2.replace('src="../', 'src="./');
            $("#columna2" + parte).html(columna2);
            tinyMCE.get('columna2').setContent('');
            $("#editar_columna2").val('');
            $("#columna2" + parte).css('background-color', '#FFF');
        } else {
            var count = $("#num_columna2").val();
            columna2 = tinyMCE.get('columna2').getContent();
            columna2 = columna2.replace('src="../', 'src="./');
            count++;
            $("#num_columna2").val(count);
            cant = count - 1;
            $("#area_columna2").append('<div class="row" id="opcColumna2_' + cant + '"><div class="col-md-1"><label class="label label-info">' + count + '</label></div><div class="col-md-7" id="columna2' + cant + '">' + columna2 + '</div><div class="col-md-4" align="center"><button type="button" class="btn btn-warning" onclick="editarColumna2(' + cant + ');"><i class="icon-pencil"></i></button>&nbsp;<button type="button" class="btn btn-danger" onclick="eliminarColumna2(' + cant + ');"><i class="fa fa-remove"></i></button></div></div>');
            tinyMCE.get('columna2').setContent('');
            res = $("#num_columna2").val();


        }
    }
    $("#columna2").focus();
}

/**-----eliminar opcion de respuesta------*/
function eliminarOpcion(parte) {
    $("#opc_" + parte + "").empty();
}

/**-----editar opcion de respuesta------*/
function editarOpcion(parte) {
    $("#edita_opcion").val(parte);
    tinyMCE.get('contenido').setContent('');
    contenido = $("#opcion" + parte).html();
    contenido = contenido.replace('src="./', 'src="../');
    tinyMCE.get('contenido').setContent(contenido);
    $("#opcion" + parte).css('background-color', '#FC3');
}

//------editar opciones-----------
/** editar las opciones ya capturadas del reactivo actual, abriendo de nuevo el cuadro de dialogo de captura de opciones 
 de respuesta segun el tipo de reactivo seleccionado  */
function editarOpciones() {
    $("#area_opciones").empty();
    tipo = $("#tipo_reactivo").val();
    if (tipo == 0) {
        aviso('Seleccione Tipo de Reactivo');
    }
    //-----1.- multiples--- 5.- ordenar------
    else if (tipo == 1 || tipo == 5) {
        var num = $("#rea_numopcion").val();
        $("#num_opcion").val(num);
        $("#modocalif").val($("#rea_modocalif").val());
        $("#dialogo_opciones").dialog("open");
        var i = 0;
        while (i < num) {
            //console.log('opc: '+i+' '+$("#opc_contenido"+i).html());
            if (tipo == 1) {
                $("#area_opciones").append('<div class="row" id="opc_' + i + '"><div class="col-md-9"><div class="row"><div class="col-md-1" align="center"><input type="radio" id="radio' + i + '" /></div><div class="col-md-11" id="opcion' + i + '">' + $("#opc_contenido" + i).html() + '</div></div></div><div class="col-md-3" align="center"><button type="button" class="btn btn-warning" onclick="editarOpcion(' + i + ');"><i class="icon-pencil"></i></button>&nbsp;<button type="button" class="btn btn-danger" onclick="eliminarOpcion(' + i + ');"><i class="fa fa-remove"></i></button></div></div>');
                if ($("#rea_radio" + i).is(':checked')) {
                    $("#radio" + i).attr("checked", "checked");
                }
            } else if (tipo == 5) {
                $("#area_opciones").append('<div class="row" id="opc_' + i + '"><div class="col-md-1" align="center"><input type="text" id="orden' + i + '" class="col-md-12" /></div><div class="col-md-8" id="opcion' + i + '">' + $("#opc_contenido" + i).html() + '</div><div class="col-md-3" align="center"><button type="button" class="btn btn-warning" onclick="editarOpcion(' + i + ');"><i class="icon-pencil"></i></button>&nbsp;<button type="button" class="btn btn-danger" onclick="eliminarOpcion(' + i + ');"><i class="fa fa-remove"></i></button></div></div>');
                $("#orden" + i).val($("#rea_orden" + i).val());
            }
            i++;
        }
    }
    //-----3.- relacionar columnas ---6.- clasificar -----
    else if (tipo == 3 || tipo == 6) {
        $("#area_columna1").empty();
        $("#area_columna2").empty();
        var count1 = $("#rea_numcolumna1").val();
        var count2 = $("#rea_numcolumna2").val();
        $("#num_columna1").val(count1);
        $("#num_columna2").val(count2);
        $("#dialogo_relacionar").dialog("open");
        console.log('count1: ' + count1 + ' count2: ' + count2);
        if (count1 > 0)
            $("#captura_columna1").hide();
        else
            $("#captura_columna1").show();
        if (count2 > 0)
            $("#captura_columna2").hide();
        else
            $("#captura_columna2").show();
        var i = 0;
        while (i < count1) {
            count = i + 1;
            console.log('columna1: ' + i + ' ' + $("#rea_columna1" + i).html());
            $("#area_columna1").append('<div class="row" id="opcColumna1_' + i + '"><div class="col-md-1"><label class="label label-info">' + count + '</label></div><div class="col-md-7 " id="columna1' + i + '">' + $("#rea_columna1" + i).html() + '</div><div class="col-md-3" align="center"><button type="button" class="btn btn-warning" onclick="editarColumna1(' + i + ');"><i class="icon-pencil"></i></button>&nbsp;<button type="button" class="btn btn-danger" onclick="eliminarColumna1(' + i + ');"><i class="fa fa-remove"></i></button></div><input type="text" class="col-md-1" id="relacion' + i + '" /></div>');
            i++;
        }
        i = 0;
        while (i < count2) {
            count = i + 1;
            $("#area_columna2").append('<div class="row" id="opcColumna2_' + i + '"><div class="col-md-1"><label class="label label-info">' + count + '</label></div><div class="col-md-7 " id="columna2' + i + '">' + $("#rea_columna2" + i).html() + '</div><div class="col-md-4" align="center"><button type="button" class="btn btn-warning" onclick="editarColumna2(' + i + ');"><i class="icon-pencil"></i></button>&nbsp;<button type="button" class="btn btn-danger" onclick="eliminarColumna2(' + i + ');"><i class="fa fa-remove"></i></button></div></div>');
            i++;
        }
        count = i = 0;
        if (tipo == 3)
            count = count2;
        else if (tipo == 6)
            count = count1;
        while (i < count) {
            pos = $("#arelacion" + i).val();
            pos++;
            $("#relacion" + i).val(pos);
            i++;
        }
        $("#modocalif_relacionar").val($("#rea_modocalif").val());
    }

}



//---------ocultar area de captura de columna 1-----
/** oculta el area de acptura para los elementos de la columna 1 */
function capturaColumna1() {
    if ($("#num_columna1").val() > 0) {
        $("#captura_columna1").hide();
        if ($("#captura_columna2").is(":hidden")) {
            i = 0;
            num = $("#num_columna1").val();
            console.log('num:' + num);
            while (i < num) {
                console.log('visualiza input:' + i);
                $("#relacion" + i).show();
                i++;
            }
        }
        $("#btn_vercaptura1").show();
    } else
        aviso('Se requiere mínimo un elemento en la columna 1');
}

//---------ocultar area de captura de columna 2-----
/** oculta el area de acptura para los elementos de la columna 2 */
function capturaColumna2() {
    if ($("#num_columna2").val() > 0) {
        $("#captura_columna2").hide();
        if ($("#captura_columna1").is(":hidden")) {
            i = 0;
            num = $("#num_columna1").val();
            console.log('num:' + num);
            while (i < num) {
                console.log('visualiza input:' + i);
                $("#relacion" + i).show();
                i++;
            }
        }
        $("#btn_vercaptura2").show();
    } else
        aviso('Se requiere mínimo un elemento en la columna 2');

}

//-----editar elemento de columna 1------
/** 
 * edita elemento seleccionado en la columna 1 
 * @param char parte, nombre del identificador del elemento a editar.
 */
function editarColumna1(parte) {
    if ($("#captura_columna1").is(':hidden'))
        $("#captura_columna1").show();
    $("#area_relacionar").empty();
    i = 0;
    num = $("#num_columna1").val();
    while (i < num) {
        $("#relacion" + i).hide();
        i++;
    }
    $("#editar_columna1").val(parte);
    tinyMCE.get('columna1').setContent('');
    columna1 = $("#columna1" + parte).html()
    columna1 = columna1.replace('src="./', 'src="../');
    tinyMCE.get('columna1').setContent(columna1);
    $("#columna1" + parte).css('background-color', '#FC3');
}

//-----editar elemento de columna 2------
/** 
 * edita elemento seleccionado en la columna 2 
 * @param char parte, nombre del identificador del elemento a editar.
 */
function editarColumna2(parte) {
    if ($("#captura_columna2").is(':hidden'))
        $("#captura_columna2").show();
    $("#area_relacionar").empty();
    i = 0;
    num = $("#num_columna1").val();
    while (i < num) {
        $("#relacion" + i).hide();
        i++;
    }
    $("#editar_columna2").val(parte);
    tinyMCE.get('columna2').setContent('');
    columna2 = $("#columna2" + parte).html()
    columna2 = columna2.replace('src="./', 'src="../');
    tinyMCE.get('columna2').setContent(columna2);
    $("#columna2" + parte).css('background-color', '#FC3');
}

//-----eliminar elemento de columna 1------
/** 
 * elimina elemento seleccionado en la columna 1 
 * @param char parte, nombre del identificador del elemento a eliminar.
 */
function eliminarColumna1(parte) {
    $("#opcColumna1_" + parte + "").empty();
    count = $("#num_columna1").val();
    if (count == 0)
        $("#captura_columna1").show();
    else {
        pas = 0;
        x = 0;
        while (x < count) {
            if ($("#opcColumna1_" + x).html() != '')
                pas = 1;
            x++;
        }
        if (pas == 0)
            $("#captura_columna1").show();
    }
}

//-----eliminar elemento de columna 2------
/** 
 * elimina elemento seleccionado en la columna 2 
 * @param char parte, nombre del identificador del elemento a eliminar.
 */
function eliminarColumna2(parte) {
    $("#opcColumna2_" + parte + "").empty();
    count = $("#num_columna2").val();
    if (count == 0)
        $("#captura_columna2").show();
    else {
        pas = 0;
        x = 0;
        while (x < count) {
            if ($("#opcColumna2_" + x).html() != '')
                pas = 1;
            x++;
        }
        if (pas == 0)
            $("#captura_columna2").show();
    }
}





