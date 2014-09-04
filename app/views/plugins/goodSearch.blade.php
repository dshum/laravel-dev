<script type="text/javascript">
$(function() {

	$('#goodsearchForm').submit(function(event) {
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
<h2>Плагин в поиске элементов типа <b>{{ $currentItem->getTitle() }}</b></h2>
{{ Form::open(array('route' => array('admin.search.ajax', $currentItem->getName(), 'postSend'), 'id' => 'goodsearchForm')) }}
{{ Form::submit('Отправить') }}
{{ Form::close()}}