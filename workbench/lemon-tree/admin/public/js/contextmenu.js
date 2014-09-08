$(function() {

	var contextmenuId = 0;

	$('body').on('contextmenu', '#tree a', function(e) {

		var classId = $(this).attr('classId');

		var browseUrl = LT.adminUrl+'/browse/'+classId;
		var browseUrlAddTab = browseUrl+'/addtab';
		var editUrl = LT.adminUrl+'/edit/'+classId;
		var editUrlAddTab = editUrl+'/addtab';
		var deleteUrl = LT.adminUrl+'/delete/'+classId;
		var moveUrl = LT.adminUrl+'/moving';

		var items = [
			{
				title: 'Редактировать',
				onclick: function () {
					document.location.href = editUrl;
				}
			},
			{
				title: 'Редактировать в новой вкладке',
				onclick: function () {
					document.location.href = editUrlAddTab;
				}
			},
			{
				separator: true
			},
			{
				title: 'Открыть',
				onclick: function () {
					document.location.href = browseUrl;
				}
			},
			{
				title: 'Открыть в новой вкладке',
				onclick: function () {
					document.location.href = browseUrlAddTab;
				}
			},
			{
				separator: true
			},
			{
				title: 'Переместить',
				onclick: function () {
					var html =
						'<form action="'+moveUrl+'" method="post">'
						+'<input type="hidden" name="check[]" value="'+classId+'">'
						+'<input type="hidden" name="redirect" value="'+document.location.href+'">'
						+'</form>';
					var form = $(html);
					form.submit();
				}
			},
			{
				title: 'Удалить',
				onclick: function () {
					$.blockUI();

					$('#message').html('').hide();

					$.post(
						deleteUrl,
						{},
						function(data) {
							if (data.error) {
								$('#message').html(data.error).show();
								$.unblockUI();
							} else {
								document.location.reload();
							}
						},
						'json'
					);
				}
			}
		];

		var menu = $('<div class="contextmenu" />');

		for (var i in items) {
			var item = items[i];
			var el = $('<div />');

			if (item.separator) {
				el.addClass('contextmenu-separator');
			} else if (item.disabled) {
				el.addClass('contextmenu-item-disabled');
				el.text(item.title);
			} else {
				el.addClass('contextmenu-item');
				el.text(item.title);
				el.on('click', item.onclick);
			}

			menu.append(el);
		}

		menu.attr('data-contextmenuId', contextmenuId);

		$('body').append(menu);

		$(this).attr('data-contextmenuId', contextmenuId);

		var menu = $('.contextmenu[data-contextmenuId="'+ $(this).attr('data-contextmenuId') +'"]');

		e.preventDefault();

		var size = {
			'width': $(window).width(),
			'height': $(window).height(),
			'sT': $(window).scrollTop(),
			'cW': menu.width(),
			'cH': menu.height()
		};

		var left = ((e.clientX + size.cW) > size.width ? (e.clientX - size.cW) : e.clientX);
		var top = ((e.clientY + size.cH) > size.height && e.clientY > size.cH ? (e.clientY + size.sT - size.cH) : e.clientY + size.sT);

		menu.css('top', top).css('left', left).show();

		contextmenuId++;

	}).on('mouseup', function () {
		$('.contextmenu:visible').hide();
	});

});