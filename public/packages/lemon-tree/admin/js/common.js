var LT = function() {
	var object = {};

	object.urldecode = function(str) {
		return decodeURIComponent((str+'').replace(/\+/g, '%20'));
	};

	object.onCtrlS = function(event) {
		if ( ! event) event = window.event;

		if (event.keyCode) {
			var code = event.keyCode;
		} else if (event.which) {
			var code = event.which;
		}

		if (code == 83 && event.ctrlKey == true) {
			$('form[save="true"]').submit();
			return false;
		}

		return true;
	};

	return object;
}();

LT.Edit = function() {
	return {};
}();

LT.Copy = function() {
	return {};
}();

LT.Move = function() {
	return {};
}();

LT.Tree = function() {
	var object = {};

	object.show = function() {
		$.post(
			LT.treeOpenUrl,
			{open: 'open'},
			function(html) {
				$('#tree-container')
					.hide()
					.html(html)
					.fadeIn('fast');
				if (LT.currentElement) {
					$('div.tree')
						.children('a[classId="'+LT.currentElement+'"]')
						.css('font-weight', 'bold');
				}
			},
			'html'
		);
	};

	return object;
}();

LT.Alert = function() {
	var object = {};

	object.popup = function(message) {
		$.unblockUI();

		$.magnificPopup.open({
			items: {
				src: $('<div class="message-popup">'+message+'</div>'),
				type: 'inline'
			},
			closeBtnInside: true
		});
	};

	return object;
}();

LT.Error = function() {
	var object = {};

	object.defaultMessage =
		'<p>Произошла ошибка. Обновите страницу, пожалуйста.</p>'
		+'<p>Если это не поможет, обратитесь к разработчику сайта.</p>';

	return object;
}();

$(function() {

	$.blockUI.defaults.message = '<img src="/packages/lemon-tree/admin/img/loader.gif" />';
	$.blockUI.defaults.css.border = 'none';
	$.blockUI.defaults.css.background = 'none';
	$.blockUI.defaults.overlayCSS.opacity = 0.2;
	$.blockUI.defaults.fadeIn = 50;

	$('body').keypress(function(event) {
		return LT.onCtrlS(event);
	}).keydown(function(event) {
		return LT.onCtrlS(event);
	});

	$('body').on('click', 'div.plus[node], div.minus[node]', function() {
		var node = $(this).attr('node');
		var opened = $(this).attr('opened');

		$.post(
			LT.treeOpenUrl,
			{classId: node, open: opened},
			function(data) {
				if (opened == 'open') {
					$('div.padding[node="'+node+'"]')
						.html(data);
					$('div.tree')
						.children('a[classId="'+LT.currentElement+'"]')
						.css('font-weight', 'bold');
					$('div.padding[node="'+node+'"]')
						.slideDown('fast', function() {
							$('div.plus[node="'+node+'"]')
								.removeClass('plus')
								.addClass('minus')
								.attr('opened', 'true');
						});
				} else if (opened == 'true') {
					$('div.padding[node="'+node+'"]')
						.slideUp('fast', function() {
							$('div.minus[node="'+node+'"]')
								.removeClass('minus')
								.addClass('plus')
								.attr('opened', 'false');
						});
				} else if (opened == 'false') {
					$('div.padding[node="'+node+'"]')
						.slideDown('fast', function() {
							$('div.plus[node="'+node+'"]')
								.removeClass('plus')
								.addClass('minus')
								.attr('opened', 'true');
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
			$('#tree').animate(
				{left: '-20%'},
				250,
				function() {
					$(this).hide();
				}
			);

			$('#browse').animate(
				{left: '0%', width: '100%'},
				250,
				function() {
					$('#tree-toggler').attr('opened', 'false');
				}
			);
		} else if (opened == 'false') {
			$('#tree').show().animate(
				{left: '0%'},
				250
			);

			$('#browse').animate(
				{left: '20%', width: '80%'},
				250,
				function() {
					$('#tree-toggler').attr('opened', 'true');
				}
			);
		}

		$.post(
			url,
			{open: opened},
			function(html) {
				if (opened == 'open') {
					$('#tree')
						.css('left', '-20%');
					$('#tree-container')
						.html(html);

					if (LT.currentElement) {
						$('div.tree')
							.children('a[classId="'+LT.currentElement+'"]')
							.css('font-weight', 'bold');
					}

					$('#tree')
						.show()
						.animate({left: '0%'}, 250);

					$('#browse').animate(
						{left: '20%', width: '80%'},
						250,
						function() {
							$('#tree-toggler').attr('opened', 'true');
						}
					);
				}
			},
			'html'
		);
	});

	$('#button-refresh').click(function() {
		document.location.reload();

		return false;
	});

});