$(function() {

	var countChecked = 0;
	var itemChecked = [], itemCountChecked = [];

	$('#button-move').click(function() {
		$('#browseForm').attr('action', LT.movingUrl);
		$('#browseForm').each(function() {
			this.submit();
		});
	});

	$('#button-delete').click(function() {
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

	$('body').on('click', 'ul.pagination li a', function() {
		var container = $(this).parents('div[id^=item_container]');
		container.fadeOut('fast');
		$.post(
			$(this).attr('href'),
			{},
			function(html) {
				container.html(html).fadeIn('fast');
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
			$('#button-delete').removeAttr('disabled');
		} else {
			$('#button-delete').attr('disabled', 'disabled');
		}

		if (itemChecked == 1) {
			$('#button-move').removeAttr('disabled');
		} else {
			$('#button-move').attr('disabled', 'disabled');
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
			$('#button-delete').removeAttr('disabled');
		} else {
			$('#button-delete').attr('disabled', 'disabled');
		}

		if (itemChecked == 1) {
			$('#button-move').removeAttr('disabled');
		} else {
			$('#button-move').attr('disabled', 'disabled');
		}
	}).on('mouseover', 'input:checkbox[name="check[]"]', function() {
		$(this).parents('tr').addClass('light-hover');
	}).on('mouseout', 'input:checkbox[name="check[]"]', function() {
		$(this).parents('tr').removeClass('light-hover');
	});

});