@extends('admin::layout')

@section('js')
<script type="text/javascript">
$(function() {

	var countChecked = 0;

@if ($currentElement)
	@if ($currentElement->getParent())
	$('#button-up').click(function() {
		document.location.href = '{{ $currentElement->getParent()->getBrowseUrl() }}';
	});
	@else
	$('#button-up').click(function() {
		document.location.href = '{{ URL::route("admin.trash") }}';
	});
	@endif
@endif

@if ($currentElement)
	$('#button-edit').click(function() {
		document.location.href = '{{ $currentElement->getEditUrl() }}';
	});
@endif

	$('#button-delete').click(function() {
		$.blockUI();

		$('#message').html('').hide();

		$('#browseForm').attr('action', '{{ \URL::route("admin.browse.delete") }}');

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

		$('#browseForm').attr('action', '{{ \URL::route("admin.browse.restore") }}');

		$('#browseForm').ajaxSubmit({
			url: this.action,
			dataType: 'json',
			success: function(data) {
//				alert(data);
				document.location.reload();
			}
		});

		event.preventDefault();
	});

	$('input:checkbox[name="checkAll[]"]').on('click', function(){
		var itemName = $(this).attr('item');
		if(this.checked) {
			$('input:checkbox[name="check[]"][item="'+itemName+'"]').each(function() {
				if(!this.checked && !this.disabled) {
					this.checked = true;
					$(this).parents('tr').addClass('light');
					countChecked++;
				}
			});
		} else {
			$('input:checkbox[name="check[]"][item="'+itemName+'"]').each(function() {
				if(this.checked && !this.disabled) {
					this.checked = false;
					$(this).parents('tr').removeClass('light');
					countChecked--;
				}
			});
		}

		if(countChecked > 0) {
			$('#button-delete').removeAttr('disabled');
			$('#button-restore').removeAttr('disabled');
		} else {
			$('#button-delete').attr('disabled', 'disabled');
			$('#button-restore').attr('disabled', 'disabled');
		}
	});

	$('input:checkbox[name="check[]"]').on('click', function() {
		if(this.checked) {
			$(this).parents('tr').addClass('light');
			countChecked++;
		} else {
			$(this).parents('tr').removeClass('light');
			countChecked--;
		}

		if(countChecked > 0) {
			$('#button-delete').removeAttr('disabled');
			$('#button-restore').removeAttr('disabled');
		} else {
			$('#button-delete').attr('disabled', 'disabled');
			$('#button-restore').attr('disabled', 'disabled');
		}
	}).on('mouseover', function() {
		$(this).parents('tr').addClass('light-hover');
	}).on('mouseout', function() {
		$(this).parents('tr').removeClass('light-hover');
	});

	$('#browseForm').submit(function(event) {
		event.preventDefault();
	});

});
</script>
@stop

@section('path')
@if ($currentElement)
<a href="{{ URL::route('admin.trash') }}">Корзина</a>
	@if ($parentList)
		@foreach ($parentList as $parent)
&rarr;&nbsp;<a href="{{ URL::route('admin.trash', array('class' => $parent->getClass(), 'id' => $parent->id)) }}">{{ $parent->{$parent->getItem()->getMainProperty()} }}</a>
		@endforeach
	@endif
&rarr;&nbsp;<a href="{{ URL::route('admin.edit', array('class' => $currentElement->getClass(), 'id' => $currentElement->id)) }}">{{ $currentElement->{$currentElement->getItem()->getMainProperty()} }}</a>
@else
Корзина
@endif
@stop

@section('browse')
<p>
@if ($currentElement)
<div id="button-up" class="button hand"><img src="/LT/img/button-up.png" alt="Наверх" title="Наверх" /><br />Наверх</div>
<div id="button-edit" class="button hand"><img src="/LT/img/button-edit.png" alt="Редактировать" title="Редактировать" /><br />Редактировать</div>
@else
<div id="button-up" class="button hand"><img src="/LT/img/button-up.png" alt="" /><br />Наверх</div>
<div id="button-edit" class="button hand"><img src="/LT/img/button-edit.png" alt="Редактировать" title="Редактировать" /><br />Редактировать</div>
@endif
<div id="button-save" class="button hand"><img src="/LT/img/button-save.png" alt="Сохранить" title="Сохранить" /><br />Сохранить</div>
<div id="button-move" class="button hand"><img src="/LT/img/button-restore.png" alt="Восстановить" title="Восстановить" /><br />Восстановить</div>
<div id="button-delete" class="button hand"><img src="/LT/img/button-remove.png" alt="Удалить" title="Удалить" /><br />Удалить</div>
</p>
<br clear="both" />
@if ($itemList)
{{ Form::open(array('route' => 'admin.browse.save', 'method' => 'post', 'id' => 'browseForm')) }}
	@foreach ($itemList as $itemName => $item)
		@include('admin::list')
	@endforeach
{{ Form::close() }}
@else
<p>В данном разделе элементы отсутствуют.<br>
	@if ($currentElement)
Вы можете <a href="{{ URL::route('admin.edit', array('class' => $currentElement->getClass(), 'id' => $currentElement->id)) }}">редактировать</a> раздел.</p>
	@endif
@endif
@stop
