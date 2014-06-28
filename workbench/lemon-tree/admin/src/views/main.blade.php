@extends('admin::layout')

@section('js')
<script type="text/javascript">
$(function() {

	var countChecked = 0;
	var itemChecked = [], itemCountChecked = [];

@if ($currentElement)
	@if ($currentElement->getParent())
	$('#button-up').click(function() {
		document.location.href = '{{ $currentElement->getParent()->getBrowseUrl() }}';
	});
	@else
	$('#button-up').click(function() {
		document.location.href = '{{ URL::route("admin") }}';
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

	$('#button-move').click(function() {
		$('#browseForm').attr('action', '{{ \URL::route("admin.moving") }}');
		$('#browseForm').each(function() {
			this.submit();
		});
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

	$('#browseForm').submit(function(event) {
		event.preventDefault();
	});

});
</script>
@stop

@section('path')
@if ($currentElement)
<a href="{{ URL::route('admin') }}">Корень сайта</a>
	@if ($parentList)
		@foreach ($parentList as $parent)
&rarr;&nbsp;<a href="{{ URL::route('admin.browse', array('class' => $parent->getClass(), 'id' => $parent->id)) }}">{{ $parent->{$parent->getItem()->getMainProperty()} }}</a>
		@endforeach
	@endif
&rarr;&nbsp;<a href="{{ URL::route('admin.edit', array('class' => $currentElement->getClass(), 'id' => $currentElement->id)) }}">{{ $currentElement->{$currentElement->getItem()->getMainProperty()} }}</a>
@else
Корень сайта
@endif
@stop

@section('browse')
<p>
@if ($currentElement)
{{ Form::button('Наверх', array('id' => 'button-up', 'class' => 'btn')) }}
{{ Form::button('Редактировать', array('id' => 'button-edit', 'class' => 'btn')) }}
@else
{{ Form::button('Наверх', array('id' => 'button-up', 'class' => 'btn', 'disabled' => 'disabled')) }}
{{ Form::button('Редактировать', array('id' => 'button-edit', 'class' => 'btn', 'disabled' => 'disabled')) }}
@endif
{{ Form::button('Сохранить', array('id' => 'button-save', 'class' => 'btn', 'disabled' => 'disabled')) }}
{{ Form::button('Удалить', array('id' => 'button-delete', 'class' => 'btn', 'disabled' => 'disabled')) }}
{{ Form::button('Переместить', array('id' => 'button-move', 'class' => 'btn', 'disabled' => 'disabled')) }}
</p>
@if ($bindItemList)
<p>Добавить:
	{? $count = sizeof($bindItemList) ?}
	@foreach ($bindItemList as $itemName => $item)
<a href="{{ \URL::route('admin.create', array($itemName, $currentElement ? $currentElement->getClassId() : null)) }}">{{ $item->getTitle() }}</a>@if (--$count > 0), @endif
	@endforeach
</p>
@endif
<p class="error"><span id="message" class="dnone"></span></p>
@if ($itemList)
{{ Form::open(array('route' => 'admin.browse.save', 'method' => 'post', 'id' => 'browseForm')) }}
{{ Form::hidden('redirect', \Request::path()) }}
@foreach ($itemList as $itemName => $item)
@include('admin::list')
@endforeach
{{ Form::close() }}
@elseif ($currentElement)
<p>В данном разделе элементы отсутствуют.<br>
Вы можете <a href="{{ URL::route('admin.edit', array('class' => $currentElement->getClass(), 'id' => $currentElement->id)) }}">редактировать</a> раздел.</p>
@else
<p>В данном разделе элементы отсутствуют.</p>
@endif
@stop

