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

	$('body').on('click', 'div.plus[node], div.minus[node]', function() {
		var node = $(this).attr('node');
		var opened = $(this).attr('opened');

		$.post(
			LT.treeOpenUrl,
			{classId: node, open: opened},
			function(data) {
				if (opened == 'open') {
					$('div.padding[node="'+node+'"]').html(data)
					$('div.tree').children('a[classId="'+LT.currentElement+'"]').css('font-weight', 'bold');
					$('div.padding[node="'+node+'"]').slideDown('fast', function() {
						$('div.plus[node="'+node+'"]').removeClass('plus').addClass('minus').attr('opened', 'true');
					});
				} else if (opened == 'true') {
					$('div.padding[node="'+node+'"]').slideUp('fast', function() {
						$('div.minus[node="'+node+'"]').removeClass('minus').addClass('plus').attr('opened', 'false');
					});
				} else if (opened == 'false') {
					$('div.padding[node="'+node+'"]').slideDown('fast', function() {
						$('div.plus[node="'+node+'"]').removeClass('plus').addClass('minus').attr('opened', 'true');
					});
				}
			},
			'html'
		);

	});

	$('#tree-toggler').click(function() {
		var opened = $(this).attr('opened');
		var url = $(this).attr('url');

		if (opened == 'true') {
			$('#tree').animate({left: '-20%'}, 250);
			$('#browse').animate({left: '0%', width: '100%'}, 250, function() {
				$('#tree-toggler').attr('opened', 'false');
			});
		} else if (opened == 'false') {
			$('#tree').animate({left: '0%'}, 250);
			$('#browse').animate({left: '20%', width: '80%'}, 250, function() {
				$('#tree-toggler').attr('opened', 'true');
			});
		}

		$.post(
			url,
			{open: opened},
			function(html) {
				if (opened == 'open') {
					$('#tree').css('left', '-20%');
					$('#tree-container').html(html);
					$('div.tree').children('a[classId="'+LT.currentElement+'"]').css('font-weight', 'bold');
					$('#tree').animate({left: '0%'}, 250);
					$('#browse').animate({left: '20%', width: '80%'}, 250, function() {
						$('#tree-toggler').attr('opened', 'true');
					});
				}
			},
			'html'
		);
	});

	$('#log').click(function() {
		$(this).fadeOut('fast');
	});

	$('#button-refresh').click(function() {
		document.location.reload();

		return false;
	});

});