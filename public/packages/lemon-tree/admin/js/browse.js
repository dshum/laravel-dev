$(function() {

	var countChecked = 0;
	var itemChecked = [], itemCountChecked = [];

	$('#button-move').click(function() {
		if ( ! countChecked) return false;

		$('#browseForm').attr('action', LT.movingUrl);

		$('#browseForm').each(function() {
			this.submit();
		});
	});

	$('#button-delete').click(function() {
		if ( ! countChecked) return false;

		$.blockUI();

		$('#message').html('').hide();

		$('#browseForm').attr('action', LT.deleteUrl);

		$('#browseForm').ajaxSubmit({
			url: this.action,
			dataType: 'json',
			success: function(data) {
//				alert(data);
				if (data.error) {
					$('#message').html(data.error).show();
					$.unblockUI();
				} else {
					document.location.reload();
				}

				$.unblockUI();
			}
		});

		event.preventDefault();
	});

	$('#button-restore').click(function() {
		if ( ! countChecked) return false;

		$.blockUI();

		$('#message').html('').hide();

		$('#browseForm').ajaxSubmit({
			url: LT.restoreUrl,
			dataType: 'json',
			success: function(data) {
				document.location.reload();
			}
		});

		event.preventDefault();
	});

	$('body').on('click', 'span[showlist="true"]', function(){
		var header = $(this);

		var url = $(this).attr('url');
		var opened = $(this).attr('opened');
		var classId = $(this).attr('classId');
		var item = $(this).attr('item');

		if (opened == 'true') {
			$('#element_list_container_'+item).slideUp('fast', function() {
				header.attr('opened', 'false');
			});
		} else if (opened == 'false') {
			$('#element_list_container_'+item).slideDown('fast', function() {
				header.attr('opened', 'true');
			});
		}

		$.post(
			url,
			{open: opened, classId: classId, item: item},
			function(html) {
				if (opened == 'open') {
					$('#item_container_'+item).html(html);
					$('#element_list_container_'+item).slideDown('fast', function() {
						header.attr('opened', 'true');
					});
				}
			},
			'html'
		);
	});

	$('body').on('click', 'a[order="true"]', function() {
		var container = $(this).parents('div[id^=item_container]');
		container.animate({opacity: 0}, 250);
		$.post(
			$(this).attr('href'),
			{},
			function(html) {
				container.html(html).animate({opacity: 1}, 250);
			},
			'html'
		);

		return false;
	});

	$('body').on('click', 'ul.pagination li a', function() {
		var container = $(this).parents('div[id^=item_container]');
		container.animate({opacity: 0}, 250);
		$.post(
			$(this).attr('href'),
			{},
			function(html) {
				container.html(html).animate({opacity: 1}, 250);
			},
			'html'
		);

		return false;
	});

	$('body').on('click', 'input:checkbox[name="checkAll[]"]', function(){
		var itemName = $(this).attr('item');
		if (this.checked) {
			$('input:checkbox[name="check[]"][item="'+itemName+'"]').each(function() {
				if( ! this.checked && ! this.disabled) {
					this.checked = true;
					$(this).parents('tr').addClass('light');
					countChecked++;
					if (itemCountChecked[itemName]) {
						itemCountChecked[itemName]++;
					} else {
						itemCountChecked[itemName] = 1;
						itemChecked++;
					}
				}
			});
		} else {
			$('input:checkbox[name="check[]"][item="'+itemName+'"]').each(function() {
				if (this.checked && ! this.disabled) {
					this.checked = false;
					$(this).parents('tr').removeClass('light');
					countChecked--;
					if (itemCountChecked[itemName]) {
						itemCountChecked[itemName]--;
					}
					if ( ! itemCountChecked[itemName]) {
						itemChecked--;
					}
				}
			});
		}

		if (countChecked > 0) {
			$('#button-delete').removeClass('disabled');
		} else {
			$('#button-delete').addClass('disabled', 'disabled');
		}

		if (itemChecked == 1) {
			$('#button-move').removeClass('disabled');
			$('#button-restore').removeClass('disabled');
		} else {
			$('#button-move').addClass('disabled', 'disabled');
			$('#button-restore').addClass('disabled', 'disabled');
		}
	});

	$('body').on('click', 'input:checkbox[name="check[]"]', function() {
		var itemName = $(this).attr('item');
		if (this.checked) {
			$(this).parents('tr').addClass('light');
			countChecked++;
			if (itemCountChecked[itemName]) {
				itemCountChecked[itemName]++;
			} else {
				itemCountChecked[itemName] = 1;
				itemChecked++;
			}
		} else {
			$(this).parents('tr').removeClass('light');
			countChecked--;
			if (itemCountChecked[itemName]) {
				itemCountChecked[itemName]--;
			}
			if ( ! itemCountChecked[itemName]) {
				itemChecked--;
			}
		}

		if (countChecked > 0) {
			$('#button-delete').removeClass('disabled');
		} else {
			$('#button-delete').addClass('disabled', 'disabled');
		}

		if (itemChecked == 1) {
			$('#button-move').removeClass('disabled');
			$('#button-restore').removeClass('disabled');
		} else {
			$('#button-move').addClass('disabled', 'disabled');
			$('#button-restore').addClass('disabled', 'disabled');
		}
	}).on('mouseover', 'input:checkbox[name="check[]"]', function() {
		$(this).parents('tr').addClass('light-hover');
	}).on('mouseout', 'input:checkbox[name="check[]"]', function() {
		$(this).parents('tr').removeClass('light-hover');
	});

});