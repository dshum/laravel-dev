var LT = function() {
	return {};
}();

LT.Edit = function() {
	return {};
};

LT.Tree = function() {
	return {};
};

LT.urldecode = function(str) {
	return decodeURIComponent((str+'').replace(/\+/g, '%20'));
};

$(function() {

	$.blockUI.defaults.message = '<img src="/LT/img/loader.gif" />';
	$.blockUI.defaults.css.border = 'none';
	$.blockUI.defaults.css.background = 'none';
	$.blockUI.defaults.overlayCSS.opacity = 0.2;
	$.blockUI.defaults.fadeIn = 50;

	var onCtrlS = function(event, form) {
		if(!event) var event = window.event;

		if(event.keyCode) {
			var code = event.keyCode;
		} else if(event.which) {
			var code = event.which;
		}

		if(code == 83 && event.ctrlKey == true) {
			$('form:first').submit();
			return false;
		}

		return true;
	};

	$('body').keypress(function(event) {
		return onCtrlS(event);
	}).keydown(function(event) {
		return onCtrlS(event);
	});

	$('#log').click(function() {
		$(this).fadeOut('fast');
	});

});