$(function() {

	$("#groupForm").submit(function(event) {
		$.blockUI();

		$(this).ajaxSubmit({
			url: this.action,
			dataType: 'json',
			success: function(data) {
				$('span[error]').parent().slideUp('fast');

				if (data.debug) {
					LT.Alert.popup(data.debug);
				} else if (data.logout) {
					document.location.href = LT.adminUrl;
				} else if (data.error) {
					var message = '';
					for (var name in data.error) {
						var errorContainer = $('span[error="'+name+'"]');
						var propertyMessage = '';
						for (var i in data.error[name]) {
							propertyMessage +=
								data.error[name][i].message
								+'<br />';
							message +=
								data.error[name][i].title
								+'. '
								+data.error[name][i].message
								+'.<br />';
						}
						errorContainer.html(propertyMessage);
						errorContainer.parent().slideDown('fast');
					}
					LT.Alert.popup(message);
				} else if (data.redirect) {
					document.location.href = data.redirect;
				}

				$.unblockUI();
			},
			error: function() {
				LT.Alert.popup(LT.Error.defaultMessage);
			}
		});
		
		event.preventDefault();
	});

});