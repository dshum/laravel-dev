$(function() {

	var itemChecked = [], itemCountChecked = [], countChecked = 0;
	var countCopyableChecked = 0, countUpdatableChecked = 0, countDeletableChecked = 0;
	var countChanged = 0;
	var stopToggle = false;

	$('#button-save').click(function() {
		if ($(this).hasClass('disabled')) return false;

		$("#browseForm").submit();
	});

	$('#button-move').click(function() {
		if ($(this).hasClass('disabled')) return false;
		if ( ! countChecked) return false;

		var form = $('#browseForm').clone();

		form.attr('action', LT.movingUrl);

		form.submit();
	});

	$('#button-copy').click(function() {
		if ($(this).hasClass('disabled')) return false;
		if ( ! countChecked) return false;

		var form = $('#browseForm').clone();

		form.attr('action', LT.copyingUrl);

		form.submit();
	});

	$('#button-delete').click(function() {
		if ($(this).hasClass('disabled')) return false;
		if ( ! countChecked) return false;

		$.blockUI();

		$('#message').html('').hide();

		$('#browseForm').ajaxSubmit({
			url: LT.deleteUrl,
			dataType: 'json',
			success: function(data) {
				if (data.error) {
					LT.Alert.popup(data.error);
				} else {
					document.location.reload();
				}

				$.unblockUI();
			},
			error: function() {
				LT.Alert.popup(LT.Error.defaultMessage);
			}
		});

		event.preventDefault();
	});

	$('#button-restore').click(function() {
		if ($(this).hasClass('disabled')) return false;
		if ( ! countChecked) return false;

		$.blockUI();

		$('#message').html('').hide();

		$('#browseForm').ajaxSubmit({
			url: LT.restoreUrl,
			dataType: 'json',
			success: function(data) {
				document.location.reload();
			},
			error: function() {
				LT.Alert.popup(LT.Error.defaultMessage);
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
				var copyable = $(this).attr('copyable');
				var updatable = $(this).attr('updatable');
				var deletable = $(this).attr('deletable');

				if( ! this.checked && ! this.disabled) {
					this.checked = true;

					$(this).parents('tr').addClass('light');

					countChecked++;

					if (copyable == 'true') countCopyableChecked++;
					if (updatable == 'true') countUpdatableChecked++;
					if (deletable == 'true') countDeletableChecked++;

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
				var copyable = $(this).attr('copyable');
				var updatable = $(this).attr('updatable');
				var deletable = $(this).attr('deletable');

				if (this.checked && ! this.disabled) {
					this.checked = false;

					$(this).parents('tr').removeClass('light');

					countChecked--;

					if (copyable == 'true') countCopyableChecked--;
					if (updatable == 'true') countUpdatableChecked--;
					if (deletable == 'true') countDeletableChecked--;

					if (itemCountChecked[itemName]) {
						itemCountChecked[itemName]--;
					}

					if ( ! itemCountChecked[itemName]) {
						itemChecked--;
					}
				}
			});
		}

		if (countDeletableChecked > 0) {
			$('#button-delete').removeClass('disabled');
			$('#button-restore').removeClass('disabled');
		} else {
			$('#button-delete').addClass('disabled', 'disabled');
			$('#button-restore').addClass('disabled', 'disabled');
		}

		if (itemChecked == 1 && countCopyableChecked > 0) {
			$('#button-copy').removeClass('disabled');
		} else {
			$('#button-copy').addClass('disabled', 'disabled');
		}

		if (itemChecked == 1 && countUpdatableChecked > 0) {
			$('#button-move').removeClass('disabled');
		} else {
			$('#button-move').addClass('disabled', 'disabled');
		}
	});

	$('body').on('click', 'input:checkbox[name="check[]"]', function() {
		var itemName = $(this).attr('item');
		var copyable = $(this).attr('copyable');
		var updatable = $(this).attr('updatable');
		var deletable = $(this).attr('deletable');

		if (this.checked && ! this.disabled) {
			$(this).parents('tr').addClass('light');

			countChecked++;

			if (copyable == 'true') countCopyableChecked++;
			if (updatable == 'true') countUpdatableChecked++;
			if (deletable == 'true') countDeletableChecked++;

			if (itemCountChecked[itemName]) {
				itemCountChecked[itemName]++;
			} else {
				itemCountChecked[itemName] = 1;
				itemChecked++;
			}
		} else if (! this.disabled) {
			$(this).parents('tr').removeClass('light');

			countChecked--;

			if (copyable == 'true') countCopyableChecked--;
			if (updatable == 'true') countUpdatableChecked--;
			if (deletable == 'true') countDeletableChecked--;

			if (itemCountChecked[itemName]) {
				itemCountChecked[itemName]--;
			}

			if ( ! itemCountChecked[itemName]) {
				itemChecked--;
			}
		}

		if (countDeletableChecked > 0) {
			$('#button-delete').removeClass('disabled');
			$('#button-restore').removeClass('disabled');
		} else {
			$('#button-delete').addClass('disabled', 'disabled');
			$('#button-restore').addClass('disabled', 'disabled');
		}

		if (itemChecked == 1 && countCopyableChecked > 0) {
			$('#button-copy').removeClass('disabled');
		} else {
			$('#button-copy').addClass('disabled', 'disabled');
		}

		if (itemChecked == 1 && countUpdatableChecked > 0) {
			$('#button-move').removeClass('disabled');
		} else {
			$('#button-move').addClass('disabled', 'disabled');
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
//			dataType: 'html',
			success: function(data) {
//				alert(LT.urldecode(data));
				if (data.error) {
					var message = '';

					for (var classId in data.error) {
						for (var name in data.error[classId]) {
							var errorContainer = $('span[error="'+classId+'.'+name+'"]');
							var propertyMessage = '';

							for (var i in data.error[classId][name]) {
								propertyMessage +=
									data.error[classId][name][i].message
									+'<br />';
								message +=
									data.error[classId][name][i].title
									+'. '
									+data.error[classId][name][i].message
									+'.<br />';
							}

							errorContainer.html(propertyMessage);
							errorContainer.parent().slideDown('fast');
						}
					}

					LT.Alert.popup(message);
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
			},
			error: function() {
				LT.Alert.popup(LT.Error.defaultMessage);
			}
		});

		event.preventDefault();
	});

});