/* Prevent error: console is undefined */
if (!console) {
    console = {};
    console.log = function () {};
}
if (typeof console == "undefined") {
    this.console = {log: function () {}};
}

if (typeof String.prototype.trim != 'function') {
    String.prototype.trim = function (str) {
        return $.trim(str);
    };
}

if (typeof String.prototype.startsWith != 'function') {
    String.prototype.startsWith = function (str) {
        return this.slice(0, str.length) == str;
    };
}

if (typeof String.prototype.endsWith != 'function') {
    String.prototype.endsWith = function (str) {
        return this.slice(-str.length) == str;
    };
}

if (typeof String.prototype.replaceAll != 'function') {
    String.prototype.replaceAll = function (find, replace) {
        var str = this;
        return str.replace(new RegExp(find.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&'), 'g'), replace);
    };
}

if (typeof (base_url) == "undefined") {
    var getUrl = window.location;
    var base_url = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
    if (!(base_url.endsWith('/'))) {
        base_url = base_url + '/';
    }
}

/**
 *@brief Returns a valid URL considering whether relative or absolute url
 *@param {String} purl URL to valid
 *@return {String} URL valid
 *@example fixUrl("http://www.someurl.com/") -> http://www.someurl.com/ 
 *@example fixUrl("someFragmentURL") -> http://www.baseurl.com/someFragmentURL 
 **/
function fixUrl(purl) {
    if ((purl.startsWith('http://')) || (typeof (base_url) == "undefined")) {
        return purl;
    } else {
        return base_url + '' + purl;
    }
}

var ResultData = {};

/**
 *@brief Implements a call to JQuery AJAX function returning a String
 *@param {String} purl URL to request
 *@param {String/Object} pparameters Parameters send to request URL
 *@param {Function} callbackfunction Callback Function
 *@return {String} outputValue Output value
 *@example getValue('someFragmentURL',{var1:"value1"},function (data){alert(data);});
 **/
function getValue(purl, pparameters, callbackfunction) {
    var outputValue = 'N/A_';
    if (callbackfunction) {
        asinc = true;
    } else {
        asinc = false;
    }
    $.ajax({
        url: fixUrl(purl),
        type: 'POST',
        data: pparameters,
        async: asinc,
        cache: false,
        dataType: 'text',
        timeout: 30000,
        error: function (a, b) {
            outputValue = b;
        },
        success: function (msg) {
            outputValue = msg;
            if (callbackfunction) {
                callbackfunction(outputValue);
            }
        }
    });
    return outputValue;
}

/**
 *@brief Implements a call to JQuery AJAX function returning an Object
 *@param {String} purl URL to request
 *@param {String/Object} pparameters Parameters send to request URL
 *@param {Function} callbackfunction Callback Function
 *@return {Object} outputValue Output value
 *@example getValue('someFragmentURL',{var1:"value1"},function (data){alert(data);});
 **/
function getObject(purl, pparameters, callbackfunction) {
    if (callbackfunction) {
        getValue(purl, pparameters, function (s) {
            var obj = (new Function('return ' + s))();
            callbackfunction(obj);
        });
    } else {
        var t = getValue(purl, pparameters);
        return (new Function('return ' + t))();
    }
}

function redirectTo(purl) {
    setTimeout(function () {
        window.location.href = fixUrl(purl);
    }, 0);
}

function openInNew(purl) {
    window.open(fixUrl(purl));
    //window.open(fixUrl(purl), "_new");
}

function redirectByPost(purl, pparameters, in_new_tab) {
    var url = fixUrl(purl);
    pparameters = (typeof pparameters == 'undefined') ? {} : pparameters;
    in_new_tab = (typeof in_new_tab == 'undefined') ? true : in_new_tab;
    var form = document.createElement("form");
    $(form).attr("id", "reg-form").attr("name", "reg-form").attr("action", url).attr("method", "post").attr("enctype", "multipart/form-data");
    if (in_new_tab) {
        $(form).attr("target", "_blank");
    }
    $.each(pparameters, function (key) {
        $(form).append('<input type="text" name="' + key + '" value="' + this + '" />');
    });
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
    return false;
}

function trim(inputString) {
    return $.trim(inputString);
}

function refreshPage() {
    location.reload();
}

function getHourNumber() {
    return (new Date().getTime());
}

var timestamp = getHourNumber();

function moveElement(elem, to) {
    elem.appendTo(to);
}

$.fn.serializeObject = function () {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function () {
        if (o[this.name]) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

$.fn.shake = function (options) {
    var settings = {
        'shakes': 2,
        'distance': 10,
        'duration': 400
    };
    if (options) {
        $.extend(settings, options);
    }
    var pos;
    return this.each(function () {
        var $this = $(this);
        pos = $this.css('position');
        if (!pos || pos === 'static') {
            $this.css('position', 'relative');
        }
        for (var x = 1; x <= settings.shakes; x++) {
            $this.animate({left: settings.distance * -1}, (settings.duration / settings.shakes) / 4)
                    .animate({left: settings.distance}, (settings.duration / settings.shakes) / 2)
                    .animate({left: 0}, (settings.duration / settings.shakes) / 4);
        }
    });
};

/*$(document).ajaxStart(function () {
    $.blockUI({message: '<h1><i class="fa fa-spinner fa-spin"></i></h1>'});
}).ajaxStop(function () {
    $.unblockUI();
});*/