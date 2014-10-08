$(function() {

	$("#userForm").submit(function(event) {
		$.blockUI();
		$(this).ajaxSubmit({
			url: this.action,
			dataType: 'json',
			success: function(data) {
				$('span[error]').removeClass('error');
				if (data.error) {
					for (var i in data.error) {
						$('span[error="'+data.error[i]+'"]').addClass('error');
					}
				} else if (data.logout) {
					document.location.href = LT.adminUrl;
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