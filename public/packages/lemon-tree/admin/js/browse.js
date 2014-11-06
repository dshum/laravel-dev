$(function() {

	var itemChecked = [], itemCountChecked = [], countChecked = 0;
	var countChanged = 0;
	var stopToggle = false;

	$('#button-save').click(function() {
		$("#browseForm").submit();
	});

	$('#button-move').click(function() {
		if ( ! countChecked) return false;

		var form = $('#browseForm').clone();

		form.attr('action', LT.movingUrl);

		form.submit();
	});

	$('#button-copy').click(function() {
		if ( ! countChecked) return false;

		$.blockUI();

		$('#message').html('').hide();

		$('#browseForm').ajaxSubmit({
			url: LT.copyUrl,
			dataType: 'json',
			success: function(data) {
				document.location.reload();
			}
		});

		event.preventDefault();
	});

	$('#button-delete').click(function() {
		if ( ! countChecked) return false;

		$.blockUI();

		$('#message').html('').hide();

		$('#browseForm').ajaxSubmit({
			url: LT.deleteUrl,
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
			$('#button-copy').removeClass('disabled');
			$('#button-delete').removeClass('disabled');
		} else {
			$('#button-copy').addClass('disabled', 'disabled');
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
			$('#button-copy').removeClass('disabled');
			$('#button-delete').removeClass('disabled');
		} else {
			$('#button-copy').addClass('disabled', 'disabled');
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

	$('body').on('mousedown', 'div[edit="container"]', function() {
		stopToggle = true;
	});

	$('body').on('mouseup', 'td[edit="true"]', function() {
		if(stopToggle) {
			return stopToggle = false;
		}

		var titleContainer = $(this).children('div[edit="title"]');
		var inputContainer = $(this).children('div[edit="container"]');
		var input = inputContainer.find(':input');
		var inputText = inputContainer.find('input:text, textarea');
		var show = $(this).attr('show');
		var value = input.val();

		if ( ! show) {
			titleContainer.removeClass('dinline').addClass('dnone');
			inputContainer.removeClass('dnone').addClass('dinline');
			input.removeAttr('disabled');
			inputText.val('').focus().val(value);
			$(this).attr('show', true);
			countChanged++;
		} else {
			inputContainer.removeClass('dinline').addClass('dnone');
			titleContainer.removeClass('dnone').addClass('dinline');
			input.attr('disabled', 'disabled');
			$(this).removeAttr('show');
			countChanged--;
		}

		if (countChanged > 0) {
			$('#button-save').removeClass('disabled');
		} else {
			$('#button-save').addClass('disabled', 'disabled');
		}
	});

	$('body').on('keydown', 'input:text[edit="input"]', function(event) {
		var event = event ? event : window.event;
		var code = event.keyCode ? event.keyCode : event.which;

		if (code == 13) {
			$('form[save="true"]').submit();
			return false;
		}

		return true;
	});

	$('#browseForm').submit(function(event) {
		if ( ! countChanged) return false;

		$('#browseForm').attr('action', LT.saveUrl);

		$.blockUI();

		$('#browseForm').ajaxSubmit({
			url: this.action,
			dataType: 'json',
			success: function(data) {
//				alert(data);
				if (data.error) {
					for (var classId in data.error) {
						for (var i in data.error[classId]) {
							var propertyName = data.error[classId][i];
							var td = $('td[edit="true"][classId="'+classId+'"][propertyName="'+propertyName+'"]');
							var input = td.find(':input');
							input.css({border: '2px solid #E99'});
						}
					}
				}

				if (data.refresh) {
					for (var classId in data.refresh) {
						for (var propertyName in data.refresh[classId]) {
							var td = $('td[edit="true"][classId="'+classId+'"][propertyName="'+propertyName+'"]');
							var html = LT.urldecode(data.refresh[classId][propertyName]);
							td.html(html).removeAttr('show');
							countChanged--;
						}
					}
				}

				if (countChanged > 0) {
					$('#button-save').removeClass('disabled');
				} else {
					$('#button-save').addClass('disabled', 'disabled');
				}

				stopToggle = false;

				$.unblockUI();
			}
		});

		event.preventDefault();
	});

});