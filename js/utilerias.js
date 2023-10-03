var timestamp = get_hour_number();

var ResultData = {};

function get_value(purl, pparameters) {
    var valor = "N/A_";

    //var v = show_as_modal_dialog("<img src='./images/loader.gif'> Espere",'...');

    $.ajax({
        url: base_url + '' + purl,
        type: 'POST',
        data: pparameters,
        async: false,
        cache: false,
        dataType: 'text',
        timeout: 30000,
        error: function(a, b) {
            valor = b;
        },
        success: function(msg) {
            valor = msg;
        }
    });

    //close_dialog(v); 

    document.body.style.cursor = "wait";
    setTimeout(function() {
        document.body.style.cursor = "default";
    }, 400);
    return valor;
}

function get_valueimg(purl, pparameters) {
    var valor = "N/A_";

    //var v = show_as_modal_dialog("<img src='./images/loader.gif'> Espere",'...');

    $.ajax({
        url: base_url + '' + purl,
        type: 'POST',
        data: pparameters,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        success: function(returndata) {
            console.log('entro');
        }
    });

    //close_dialog(v); 

    document.body.style.cursor = "wait";
    setTimeout(function() {
        document.body.style.cursor = "default";
    }, 400);
    return valor;
}

function get_object(purl, pparameters) {
    var t = get_value(purl, pparameters);
    var j = eval('(' + t + ')');
    return j;
}

function redirect_to(purl) {
    setTimeout(function() {
        window.location.href = base_url + 'index.php/' + purl;
    },
            0);
}
function redirect(purl) {
    setTimeout(function() {
        window.location.href = purl;
    },
            0);
}

function open_in_new(purl) {
    window.open(base_url + '' + purl, "_new");
}

function trim(inputString) {
    return $.trim(inputString);
}

function close_dialog(d) {
    d.dialog('close');
}

function show_as_modal_dialog(t, tit, callb) {
    var l = "<div>" + t + "</div>";
    var d = $(l).dialog({
        autoOpen: false,
        dialogClass: "dialogwindow",
        closeOnEscape: false,
        minHeight: 50,
        title: tit,
        modal: true,
        buttons: {},
        resizable: false,
        open: function() {
            $('body').css('overflow', 'hidden');
        },
        close: function() {
            $('body').css('overflow', 'auto');
            if (callb) {
                callb();
            }
        }
    });
    d.dialog('open');
    return d;
}

function get_hour_number() {
    return (new Date().getTime());
}

jQuery.fn.reset = function() {
    $(this).each(function() {
        this.reset();
    });
}


/*Mensajito*/
function mensajito(mensaje) {
    $.blockUI({
        message: '<table><tr><td></td><td style="text-align:left;"><div style="padding-left:10px"><span class="textomensaje" !important;color:#FFF; font-weight:normal; margin-top:90px !important">' + mensaje + '</span></div></td></tr></table>',
        fadeIn: 800,
        fadeOut: 800,
        timeout: 5000,
        showOverlay: false,
        centerY: true,
        css: {
            width: '350px',
            top: '10px',
            left: '',
            right: '10px',
            border: 'none',
            padding: '5px',
            backgroundColor: '#89B7DA',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            color: '#FFF'
        }
    });
}

function mensajeallscreen(mensaje) {
    $.blockUI({
        message: '<h3><span style="color:red">IMPORTANTE</span><hr space>' + mensaje + '</h3>',
        timeout: 8000,
        css: {
            width: '350px',
            border: 'none',
            padding: '10px',
            backgroundColor: '#FFF',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            color: '#000'
        }
    });
}




function mensajetimeout(cabecera, mensaje, time) {
    $(document).bind('keydown', function(e) {
        if (e.which == 27) {
            $.unblockUI();
        }
        ;
    });

    $('.blockOverlay').live('click', function() {
        $.unblockUI();
    });

    $.blockUI({
        message: '<h4><span style="color:red">' + cabecera + '</h4><h5></span><hr space>' + mensaje + '</h5>',
        timeout: time,
        css: {
            width: '350px',
            border: 'none',
            padding: '10px',
            backgroundColor: '#FFF',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            color: '#000'
        }
    });
}

function notify_block(title, subtitle, msg, img) {
    var html = '<div class="not_div">';
    html += '<div class="not_title" style="padding-left: 10px;font-size: 18px; border-top-left-radius: 5px;border-top-right-radius: 5px;border-bottom-right-radius: 5px;border-bottom-left-radius: 5px;background-color: rgb(189, 189, 189); color: #444444;">' + title + '</div>';
    if (img != false) {
        html += '<div class="not_img" style="width: 60px;float: left;padding-top: 28px;padding-right: 10px;margin-top: -25px;"><img src="images/not/' + img + '.png" width="50" height="50"></div>';
    }
    html += '<div class="not_info"><div class="not_subtitle" style="text-align: left;font-size: 14px;margin-top: 2px;">' + subtitle + '</div>';
    html += '<div class="not_message" style="font-size: 12px;text-align: justify;padding: 10px;padding-top: 2px;">' + msg + '</div>';
    html += '</div></div>';
    $.blockUI({
        message: html,
        fadeIn: 700,
        fadeOut: 700,
        timeout: 2000,
        showOverlay: false,
        centerY: false,
        css: {
            width: '350px',
            top: '10px',
            left: '',
            right: '10px',
            border: 'none',
            padding: '5px',
            opacity: .8,
            backgroundColor: '#000',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            color: '#fff'
        }
    });
}

function mensaje_center(title, subtitle, msg, img) {
    var html = '<div class="not_div">';
    html += '<div class="not_title" style="padding-left: 10px;font-size: 18px; border-top-left-radius: 5px;border-top-right-radius: 5px;border-bottom-right-radius: 5px;border-bottom-left-radius: 5px;background-color: rgb(189, 189, 189); color: #444444;">' + title + '</div>';
    if (img != false) {
        html += '<div class="not_img" style="width: 60px;float: left;padding-top: 28px;padding-right: 10px;margin-top: -25px;"><img src="images/not/' + img + '.png" width="50" height="50"></div>';
    }
    html += '<div class="not_info"><div class="not_subtitle" style="text-align: left;font-size: 14px;margin-top: 2px;">' + subtitle + '</div>';
    html += '<div class="not_message" style="font-size: 12px;text-align: justify;padding: 10px;padding-top: 2px;">' + msg + '</div>';
    html += '</div></div>';
    $.blockUI({
        message: html,
        fadeIn: 700,
        fadeOut: 700,
        timeout: 3500,
        showOverlay: false,
        centerY: false,
        css: {
            width: '350px',
            border: 'none',
            padding: '5px',
            backgroundColor: '#444444',
            '-webkit-border-radius': '8px',
            '-moz-border-radius': '8px',
            opacity: .9,
            color: '#fff'
        }
    });
}