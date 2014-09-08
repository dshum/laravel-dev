$(function() {

	$('span[item]').click(function() {
		var item = $(this).attr('item');

		if (item == $('#item').val()) return false;

		$('span[item]').parent().removeClass('item-search-active').addClass('item-search');
		$('span[item]').addClass('dashed hand');

		$(this).parent().removeClass('item-search').addClass('item-search-active');
		$(this).removeClass('dashed hand');

		$('#itemContainer').slideUp('fast', function() {
			$.post(
				LT.searchItemUrl,
				{item: item},
				function(html) {
					$('#itemContainer').html(html).slideDown('fast');
					$('#item').val(item);
				},
				'html'
			);
		});
	});

	$('body').on('click', 'span[switch="true"]', function() {
		var propertyName = $(this).attr('name');
		var block = $('#'+propertyName+'_block');

		if(block.css('display') == 'block') {
			block.hide();
			block.children('input').attr('disabled', true);
			$('#'+propertyName+'_sign').hide();
		} else {
			$('#'+propertyName+'_sign').show();
			block.children('input').removeAttr('disabled');
			block.show();
			block.children('input:text:first').focus();
		}
	});

	$('body').on('focus', 'input[onetoone="name"]', function() {
		var url = $(this).attr('url');
		var propertyName = $(this).attr('propertyName');

		$(this).autocomplete({
			source: url,
			select: function(event, ui) {
				$('input[name="'+propertyName+'_name"]').val(ui.item.value);
				$('#'+propertyName+'_show').html(ui.item.id);
				$('input[name="'+propertyName+'"]').val(ui.item.id);
			},
			minLength: 0
		});
	});

	$('body').on('click', 'span[onetoone="reset"]', function() {
		var propertyName = $(this).attr('propertyName');
		var input = $('input[name="'+propertyName+'"]');
		var inputShow = $('#'+propertyName+'_show');
		var inputName = $('input[name="'+propertyName+'_name"]');

		input.val('');
		inputShow.html('не определен');
		inputName.val('').focus();
	});

});