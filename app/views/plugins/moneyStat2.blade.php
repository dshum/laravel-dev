<script type="text/javascript">
$(function() {

	$('#moneyStatForm').submit(function(event) {
		$.blockUI();

		$(this).ajaxSubmit({
			url: this.action,
			dataType: 'json',
			success: function(data) {
				if (data.hi) {
					alert(data.hi);
				}
				$.unblockUI();
			}
		});

		event.preventDefault();
	});

});
</script>
<h2>{{ $currentElement->name }}</h2>
<p>Платежей за указанный период не найдено.</p>
{{ Form::open(array('route' => array('admin.edit.ajax', $currentElement->getClassId(), 'postSend2'), 'id' => 'moneyStatForm')) }}
{{ Form::submit('Отправить') }}
{{ Form::close()}}