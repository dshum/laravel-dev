$(function() {

	$('span[sortItem]').click(function() {
		if ($(this).attr('active') == 'true') return false;

		var link = $(this);
		var sortItem = $(this).attr('sortItem');

		$('#itemListContainer').slideUp('fast', function() {
			$.post(
				LT.searchItemListUrl,
				{sort: sortItem},
				function(html) {
					$('#itemListContainer')
						.html(html)
						.slideDown('fast');
					$('span[sortItem][active="true"]')
						.attr('active', 'false')
						.removeClass('active')
						.addClass('dashed hand');
					link
						.attr('active', 'true')
						.removeClass('dashed hand')
						.addClass('active');
				},
				'html'
			);
		});
	});

	$('body').on('click', 'span[sortProperty]', function() {
		if ($(this).attr('active') == 'true') return false;

		var link = $(this);
		var item = $(this).attr('item');
		var sortProperty = $(this).attr('sortProperty');

		$('#propertyListContainer').slideUp('fast', function() {
			$.post(
				LT.searchPropertyListUrl,
				{item: item, sort: sortProperty},
				function(html) {
					$('#propertyListContainer')
						.html(html)
						.slideDown('fast');
					$('span[sortProperty][active="true"]')
						.attr('active', 'false')
						.removeClass('active')
						.addClass('dashed hand');
					link
						.attr('active', 'true')
						.removeClass('dashed hand')
						.addClass('active');
				},
				'html'
			);
		});
	});

	$('body').on('click', 'span[list="item"]', function() {
		var item = $(this).attr('item');

		if (item == $('#item').val()) return false;

		$('span[list="item"]').parent().removeClass('item-search-active').addClass('item-search');
		$('span[list="item"]').addClass('dashed hand');

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

	$('#searchForm').submit(function(event) {
		if ( ! $('#item').val()) event.preventDefault();
	});

});