@extends('admin::layout')

@section('js')
<script type="text/javascript">
$(function() {

	var countChecked = 0;

	$('input:checkbox[name="checkAll"]').on('click', function(){
		if(this.checked) {
			$('input:checkbox[name="check"][item="'+this.value+'"]').each(function() {
				if(!this.checked && !this.disabled) {
					this.checked = true;
					$(this).parents('tr').addClass('light');
					countChecked++;
				}
			});
		} else {
			$('input:checkbox[name="check"][item="'+this.value+'"]').each(function() {
				if(this.checked && !this.disabled) {
					this.checked = false;
					$(this).parents('tr').removeClass('light');
					countChecked--;
				}
			});
		}

		if(countChecked > 0) {
			$('#button-delete').removeAttr('disabled');
			$('#button-move').removeAttr('disabled');
		} else {
			$('#button-delete').attr('disabled', 'disabled');
			$('#button-move').attr('disabled', 'disabled');
		}
	});

	$('input:checkbox[name="check"]').on('click', function() {
		if(this.checked) {
			$(this).parents('tr').addClass('light');
			countChecked++;
		} else {
			$(this).parents('tr').removeClass('light');
			countChecked--;
		}

		if(countChecked > 0) {
			$('#button-delete').removeAttr('disabled');
			$('#button-move').removeAttr('disabled');
		} else {
			$('#button-delete').attr('disabled', 'disabled');
			$('#button-move').attr('disabled', 'disabled');
		}
	}).on('mouseover', function() {
		$(this).parents('tr').addClass('light-hover');
	}).on('mouseout', function() {
		$(this).parents('tr').removeClass('light-hover');
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
{{ Form::button('Сохранить', array('id' => 'button-save', 'class' => 'btn', 'disabled' => 'disabled')) }}
{{ Form::button('Удалить', array('id' => 'button-delete', 'class' => 'btn', 'disabled' => 'disabled')) }}
{{ Form::button('Переместить', array('id' => 'button-move', 'class' => 'btn', 'disabled' => 'disabled')) }}
</p>
@if ($itemList)
	@foreach ($itemList as $itemName => $item)
		@include('admin::list')
	@endforeach
@else
<p>В данном разделе элементы отсутствуют.<br>
	@if ($currentElement)
Вы можете <a href="{{ URL::route('admin.edit', array('class' => $currentElement->getClass(), 'id' => $currentElement->id)) }}">редактировать</a> раздел.</p>
	@endif
@endif
@stop
