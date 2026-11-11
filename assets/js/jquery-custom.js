$.fn.setCaretPosition = function (pos) {
  this.each(function (index, elem) {
    elem.focus();
    if (elem.setSelectionRange) {
      elem.setSelectionRange(pos, pos);
    } else if (elem.createTextRange) {
      var range = elem.createTextRange();
      range.collapse(true);
      range.moveEnd('character', pos);
      range.moveStart('character', pos);
      range.select();
    }
    $(elem).trigger( "caretPosUpdate");
  });
  return this;
};

$.fn.selectRange = function(start, end) {
  if(end === undefined) {
    end = start;
  }
  return this.each(function() {
    if('selectionStart' in this) {
      this.selectionStart = start;
      this.selectionEnd = end;
    } else if(this.setSelectionRange) {
      this.setSelectionRange(start, end);
    } else if(this.createTextRange) {
      var range = this.createTextRange();
      range.collapse(true);
      range.moveEnd('character', end);
      range.moveStart('character', start);
      range.select();
    }
    $(this).focus();
  });
};

$.fn.confirmAnchorMessage = function (options = {}) {
  var linkEls = $(this).filter('a[href]');

  var includeCurrentQueryString = false;

  if (typeof options === 'object') {
    includeCurrentQueryString = options.includeCurrentQueryString || false;
  }

  $(linkEls).on('click', function(event) {
    event.preventDefault();
    var confirmMessage = $(this).data('confirm-message') || 'Are you sure?'
    var url = $(this).attr('href');
    var currentQueryString = includeCurrentQueryString && window.location.search
      ? (url.includes('?') ? '&' : '?') + window.location.search.replace(/^[?]/, '')
      : '';

    if (confirm(confirmMessage)) {
      window.location = url + currentQueryString;
    }
  });
};

$.fn.onDelayedKeyUp = function (callback, msDelay = 300) {
  var delay = (cb) => {
    var timer = 0;

    return function() {
      var context = this, args = arguments;
      clearTimeout(timer);
      timer = setTimeout(function () {
        cb.apply(context, args);
      }, msDelay || 0);
    };
  };

  $(this).keyup(delay(callback));

  return $(this);
};