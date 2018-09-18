// A dirty fix for broken materialize selects if JS is disabled.
var sheet = window.document.styleSheets[window.document.styleSheets.length - 1];
sheet.insertRule('select { display: none; }', sheet.cssRules.length);

// sprintf polyfill, string.format
if (!String.prototype.format) {
  String.prototype.format = function() {
    var args = arguments;
    return this.replace(/{(\d+)}/g, function(match, number) { 
      return typeof args[number] != 'undefined'
        ? args[number]
        : match
      ;
    });
  };
}

// A self-made custom XHRFormHandler object to handle XHR form submission.
var XHRForm = function (formElement, disabledText) {
    var _THIS = this;
    _THIS._enabledText = '';
    _THIS._disabledText = disabledText || '...';
    _THIS._form = formElement;
    _THIS._method = (formElement && formElement.getAttribute) ? formElement.getAttribute('method') : 'POST';
    _THIS._action = (formElement && formElement.getAttribute) ? formElement.getAttribute('action') : window.location;
    _THIS._data = new FormData(formElement);
    _THIS._xhr = null;
    _THIS._response = null;
    _THIS.addData = function (formData) {
        var entries = formData.entries();
        var next;
        while (next = entries.next()) {
            _THIS._data.append(next[0], next[1]);
        }
        return _THIS;
    };
    _THIS.append = function (entry, value) {
        _THIS._data.append(entry, value);
        return _THIS;
    };
    _THIS.reset = function (formElement = undefined) {
        if (formElement) {
            return _THIS.call(_THIS, formElement);
        }
        return _THIS.call(_THIS, _THIS._form);
    };
    _THIS.setMethod = function (method) {
        _THIS._method = method;
        return _THIS;
    };
    _THIS.setAction = function (action) {
        _THIS._action = action;
        return _THIS;
    };
    _THIS.getMethod = function () {
        return _THIS._method;
    };
    _THIS.getAction = function () {
        return _THIS._action;
    };
    _THIS.getResponse = function () {
        return _THIS._response;
    };
    _THIS._events = ['progress', 'success', 'error'];
    _THIS.onprogress = null; // function (event) this refers to xhr
    _THIS.onsuccess = null; // function (response) this refers to xhr
    _THIS.onerror = null; // function (response) this refers to xhr
    _THIS.oncomplete = function (complete_json) { // function(response) this refers to xhr
        if (complete_json.location)
            window.location = complete_json.location;
        if (typeof(complete_json.error) == 'string')
            addNotice('error', error);
        if (['array', 'object'].includes(typeof(complete_json.error)))
            for (i in complete_json.error)
              addNotice('error', complete_json.error[i]);
        if (typeof(complete_json.error) == 'string')
            addNotice('success', complete_json.success);
    };
    _THIS.on = function (key, handler) {
        if (!_THIS._events.includes(key)) {
            throw 'Error: XHRForm: Unknown event ' + key + '. The allowed events are ' + _THIS._events.join(', ') + '.';
        }
        if (_THIS['on'+key]) {
            console.warn('XHRForm: Setting an event listener using XHRForm.on overrides any previously set values of that event.');
        }
        _THIS['on'+key] = handler;
    };
    _THIS.submit = function (logResponse = false) {
        _THIS._data.append('jsenabled', 'true');
        _THIS._xhr = new XMLHttpRequest();
        if (_THIS.onprogress && _THIS.onprogress.call) _THIS._xhr.upload.addEventListener('progress', function (event) {
            _THIS.onprogress.call(_THIS._xhr, event);
        });
        _THIS._xhr.open(_THIS._method, _THIS._action);
        _THIS._xhr.onreadystatechange = function () {
            if (_THIS._xhr.readyState === 4) {
                try {
                    _THIS._response = JSON.parse(_THIS._xhr.responseText);
                } catch (e) {
                    console.warn('XHRForm: Unable to parse xhr.responseText as JSON. Response passed to handler is { "text" : xhr.responseText } instead.');
                    _THIS._response = { 'text' : _THIS._xhr.responseText };
                }
                if (logResponse) {
                    console.log('XHRForm: XHR returned status:', _THIS._xhr.status, 'and parsed response:', _THIS._response);
                }
                if (_THIS.oncomplete && _THIS.oncomplete.call) _THIS.oncomplete.call(_THIS._xhr, _THIS._response);
                if (_THIS._xhr.status === 200) {
                    if (_THIS.onsuccess && _THIS.onsuccess.call) _THIS.onsuccess.call(_THIS._xhr, _THIS._response);
                } else {
                    if (_THIS.onerror && _THIS.onerror.call) _THIS.onerror.call(_THIS._xhr, _THIS._response);
                }
            }
        };
        _THIS._xhr.send(_THIS._data);
    };

    return _THIS;
};

// Adds a notice to the top. Designer can fix this to add toasts or whatever.
function addNotice(type, text) {
  $('.notices').append($('<div></div>').addClass(type).html(text));
}

$(document).ready(function(){
  $('.confirm').on('click', function(ev) { return confirm(SURE_MSG); });
});
