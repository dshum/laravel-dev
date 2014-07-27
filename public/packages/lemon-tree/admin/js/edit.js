$(function() {

	LT.Edit.setTimestamp = function(propertyName) {
		if ($('#'+propertyName+'_date').val()) {
			var hour = $('#'+propertyName+'_hour').val();
			var minute = $('#'+propertyName+'_minute').val();
			var second = $('#'+propertyName+'_second').val();

			if ( ! hour) hour = '00';
			if ( ! minute) minute = '00';
			if ( ! second) second = '00';
		}

		$('#'+propertyName).val(
			$('#'+propertyName+'_date').val()
			+' '+$('#'+propertyName+'_hour').val()
			+':'+$('#'+propertyName+'_minute').val()
			+':'+$('#'+propertyName+'_second').val()
		);
	};

	LT.Edit.setTime = function(propertyName) {
		if(
			$('#'+propertyName+'_hour').val()
			|| $('#'+propertyName+'_minute').val()
			|| $('#'+propertyName+'_second').val()
		) {
			var hour = $('#'+propertyName+'_hour').val();
			var minute = $('#'+propertyName+'_minute').val();
			var second = $('#'+propertyName+'_second').val();

			if ( ! hour) hour = '00';
			if ( ! minute) minute = '00';
			if ( ! second) second = '00';

			$('#'+propertyName).val(hour+':'+minute+':'+second);
		} else {
			$('#'+propertyName).val(null);
		}
	};

	$('div.main input').each(function () {
		var value = $(this).val();
		$(this).val('').focus().val(value);
	});

	$('span.textarea').click(function() {
		var propertyName = $(this).attr('propertyName');
		var textarea = $('textarea[name="'+propertyName+'"]');
		var rows = textarea.attr('rows');
		if (rows == 40) textarea.attr('rows', 8);
		else textarea.attr('rows', 40);
	});

	$('body').on('click', 'input[onetoone="name"]', function() {
		var defaultText = $(this).attr('default');
		var url = $(this).attr('url');
		var propertyName = $(this).attr('propertyName');

		if ($(this).val() == defaultText) {
			$(this).removeClass('grey').val('');
		}

		$(this).autocomplete({
			source: url,
			select: function(event, ui) {
				$('input[name="'+propertyName+'_name"]').removeClass('grey').val(ui.item.value);
				$('#'+propertyName+'_show').addClass('grey').html(ui.item.value);
				$('input[name="'+propertyName+'"]').val(ui.item.id);
			},
			minLength: 0
		});
	}).on('focus', 'input[onetoone="name"]', function() {
		var defaultText = $(this).attr('default');

		if ($(this).val() == defaultText) {
			$(this).removeClass('grey').val('');
		}
	}).on('change', 'input[onetoone="name"]', function() {
		var defaultText = $(this).attr('default');

		if ($(this).val() == '') {
			$(this).addClass('grey').val(defaultText);
		}
	}).on('blur', 'input[onetoone="name"]', function() {
		var defaultText = $(this).attr('default');

		if ($(this).val() == '') {
			$(this).addClass('grey').val(defaultText);
		}
	});

	$('body').on('click', 'span[onetoone="reset"]', function() {
		var propertyName = $(this).attr('propertyName');
		var input = $('input[name="'+propertyName+'"]');
		var inputName = $('input[name="'+propertyName+'_name"]');
		var defaultText = inputName.attr('default');

		if (input.val()) {
			input.val('');
			$('#'+propertyName+'_show').addClass('grey').html('Не определено');
		}

		inputName.addClass('grey').val(defaultText);
	});

	$('body').on('click', 'span[onetoone="title"]', function() {
		var name = $(this).attr('name');
		$('#'+name+'_block').slideToggle('fast');
	});

	$('body').on('click', 'input:radio[onetoone="radio"]', function() {
		var id = $(this).attr('id');
		var name = $(this).attr('name');
		var title = $('label[for="'+id+'"]').html();
		$('#'+name+'_block').slideToggle('fast', function() {
			$('#'+name+'_title').html(title);
		});
	});

	$('body').on('click', 'div.plus[node1], div.minus[node1]', function() {
		var node = $(this).attr('node1');
		var itemName = $(this).attr('itemName');
		var propertyName = $(this).attr('propertyName');
		var opened = $(this).attr('opened');

		if (opened == 'open') {
			$.post(
				"{{ URL::route('admin.tree.open1') }}",
				{itemName: itemName, propertyName: propertyName, classId: node},
				function(data) {
					$('div.padding[node1="'+node+'"]').html(data).slideDown('fast', function() {
						$('div.plus[node1="'+node+'"]').removeClass('plus').addClass('minus').attr('opened', 'true');
						$('span[node1="'+node+'"]').attr('opened', 'true');
					});
				},
				'html'
			);
		} else if (opened == 'true') {
			$('div.padding[node1="'+node+'"]').slideUp('fast', function() {
				$('div.minus[node1="'+node+'"]').removeClass('minus').addClass('plus').attr('opened', 'false');
				$('span[node1="'+node+'"]').attr('opened', 'false');
			});
		} else if (opened == 'false') {
			$('div.padding[node1="'+node+'"]').slideDown('fast', function() {
				$('div.plus[node1="'+node+'"]').removeClass('plus').addClass('minus').attr('opened', 'true');
				$('span[node1="'+node+'"]').attr('opened', 'true');
			});
		}
	});

	$('#editForm').submit(function(event) {
		$.blockUI();

		$('textarea[tinymce="true"]').each(function() {
			$(this).val(tinyMCE.get(this.name).getContent());
		});

		$('input[onetoone="name"]').blur();

		$(this).ajaxSubmit({
			url: this.action,
			dataType: 'json',
			success: function(data) {
//				alert(data);
				$('#message').html('').hide();
				$('span[error]').removeClass('error');

				if (data.error) {
					for (var i in data.error) {
						$('span[error="'+data.error[i]+'"]').addClass('error');
					}
				} else if (data.logout) {
					document.location.href = LT.adminUrl;
				} else if (data.redirect) {
					document.location.href = data.redirect;
				} else if (data.refresh) {
					for (var name in data.refresh) {
						var view = LT.urldecode(data.refresh[name]);
						$('#'+name+'_container').html(view);
					}
				}

				$.unblockUI();
			}
		});
		event.preventDefault();
	});

});