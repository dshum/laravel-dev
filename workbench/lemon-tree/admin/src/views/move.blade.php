@extends('admin::layout')

@section('js')
<script type="text/javascript">
$(function() {

	$('body').on('click', 'input[onetoone="name"]', function() {
		var defaultText = $(this).attr('default');
		var url = $(this).attr('url');
		var propertyName = $(this).attr('propertyName');

		if ($(this).val() == defaultText) {
			$(this).removeClass('grey').val('');
		}

		$(this).autocomplete({
			source: url,
			select: function(event, ui) {
				$('input[name="'+propertyName+'_name"]').removeClass('grey').val(ui.item.value);
				$('#'+propertyName+'_show').addClass('grey').html(ui.item.value);
				$('input[name="'+propertyName+'"]').val(ui.item.id);
			},
			minLength: 0
		});
	}).on('focus', 'input[onetoone="name"]', function() {
		var defaultText = $(this).attr('default');

		if ($(this).val() == defaultText) {
			$(this).removeClass('grey').val('');
		}
	}).on('change', 'input[onetoone="name"]', function() {
		var defaultText = $(this).attr('default');

		if ($(this).val() == '') {
			$(this).addClass('grey').val(defaultText);
		}
	}).on('blur', 'input[onetoone="name"]', function() {
		var defaultText = $(this).attr('default');

		if ($(this).val() == '') {
			$(this).addClass('grey').val(defaultText);
		}
	});

	$('body').on('click', 'span[onetoone="reset"]', function() {
		var propertyName = $(this).attr('propertyName');
		var input = $('input[name="'+propertyName+'"]');
		var inputName = $('input[name="'+propertyName+'_name"]');
		var defaultText = inputName.attr('default');

		if (input.val()) {
			input.val('');
			$('#'+propertyName+'_show').addClass('grey').html('Не определено');
		}

		inputName.addClass('grey').val(defaultText);
	});

	$('body').on('click', 'span[onetoone="title"]', function() {
		var name = $(this).attr('name');
		$('#'+name+'_block').slideToggle('fast');
	});

	$('body').on('click', 'input:radio[onetoone="radio"]', function() {
		var id = $(this).attr('id');
		var name = $(this).attr('name');
		var title = $('label[for="'+id+'"]').html();
		$('#'+name+'_block').slideToggle('fast', function() {
			$('#'+name+'_title').html(title);
		});
	});

	$('body').on('click', 'div.plus[node1], span.plus[node1]', function() {
		var node = $(this).attr('node1');
		var itemName = $(this).attr('itemName');
		var propertyName = $(this).attr('propertyName');
		var opened = $(this).attr('opened');

		if (opened == 'open') {
			$.post(
				"{{ URL::route('admin.tree.open1') }}",
				{itemName: itemName, propertyName: propertyName, classId: node},
				function(data) {
					$('div.padding[node1="'+node+'"]').html(data).slideDown('fast', function() {
						$('div.plus[node1="'+node+'"]').html('<div>-</div>').attr('opened', 'true');
						$('span.plus[node1="'+node+'"]').attr('opened', 'true');
					});
				},
				'html'
			);
		} else if (opened == 'true') {
			$('div.padding[node1="'+node+'"]').slideUp('fast', function() {
				$('div.plus[node1="'+node+'"]').html('<div>+</div>').attr('opened', 'false');
				$('span.plus[node1="'+node+'"]').attr('opened', 'false');
			});
		} else if (opened == 'false') {
			$('div.padding[node1="'+node+'"]').slideDown('fast', function() {
				$('div.plus[node1="'+node+'"]').html('<div>-</div>').attr('opened', 'true');
				$('span.plus[node1="'+node+'"]').attr('opened', 'true');
			});
		}
	});

});
</script>
@stop

@section('path')
Перемещение элементов
@stop

@section('browse')
{{ Form::open(array('route' => 'admin.move', 'method' => 'post', 'id' => 'moveForm')) }}
{{ Form::hidden('item', $item->getName()) }}
{{ Form::hidden('redirect', $redirect)}}
<h2>Что переносим:</h2>
@foreach ($elementList as $element)
{{ Form::checkbox('check[]', $element->id, true) }} <a href="{{ $element->getEditUrl() }}">{{ $element->{$item->getMainProperty()} }}</a> <small class="grey">{{ $item->getTitle() }}</small><br />
@endforeach
<h2>Куда переносим:</h2>
<div class="form-edit">
@foreach ($onePropertyList as $propertyName => $property)
<div id="{{ $propertyName }}_container"{{ $property->isMainProperty() ? ' class="main"' : '' }}>
	{{ sizeof($elementList) > 1 ? $property->getElementMoveView() : $property->setElement($element)->getElementMoveView() }}
</div><br />
@endforeach
</div>
<p>{{ Form::submit('Переместить', array('id' => 'button-move', 'class' => 'btn')) }}</p>
{{ Form::close() }}
@stop
