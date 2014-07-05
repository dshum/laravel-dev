@extends('admin::layout')

@section('js')
{{ HTML::style('LT/js/calendarview/jquery.calendar.css') }}
{{ HTML::script('LT/js/calendarview/jquery.calendar.js') }}
{{ HTML::script('LT/js/tinymce/jscripts/tiny_mce/tiny_mce.js') }}
<script type="text/javascript">
$(function() {

	LT.Edit.setTimestamp = function(propertyName) {
		if ($('#'+propertyName+'_date').val()) {
			var hour = $('#'+propertyName+'_hour').val();
			var minute = $('#'+propertyName+'_minute').val();
			var second = $('#'+propertyName+'_second').val();

			if ( ! hour) hour = '00';
			if ( ! minute) minute = '00';
			if ( ! second) second = '00';
		}

		$('#'+propertyName).val(
			$('#'+propertyName+'_date').val()
			+' '+$('#'+propertyName+'_hour').val()
			+':'+$('#'+propertyName+'_minute').val()
			+':'+$('#'+propertyName+'_second').val()
		);
	};

	LT.Edit.setTime = function(propertyName) {
		if(
			$('#'+propertyName+'_hour').val()
			|| $('#'+propertyName+'_minute').val()
			|| $('#'+propertyName+'_second').val()
		) {
			var hour = $('#'+propertyName+'_hour').val();
			var minute = $('#'+propertyName+'_minute').val();
			var second = $('#'+propertyName+'_second').val();

			if ( ! hour) hour = '00';
			if ( ! minute) minute = '00';
			if ( ! second) second = '00';

			$('#'+propertyName).val(hour+':'+minute+':'+second);
		} else {
			$('#'+propertyName).val(null);
		}
	};

@if ($parentElement)
	$('#button-up').click(function() {
		document.location.href = '{{ $parentElement->getBrowseUrl() }}';
	});
@elseif ($currentElement->trashed())
	$('#button-up').click(function() {
		document.location.href = '{{ URL::route("admin.trash") }}';
	});
@else
	$('#button-up').click(function() {
		document.location.href = '{{ URL::route("admin") }}';
	});
@endif

	$('#button-save').click(function() {
		$("#editForm").submit();
	});

@if ($currentElement->id)
	$('#button-delete').click(function() {
		$.blockUI();

		$('#message').html('').hide();

		$.post(
			'{{ $currentElement->getDeleteUrl() }}',
			{},
			function(data) {
				if (data.error) {
					$('#message').html(data.error).show();
					$.unblockUI();
				} else {
					document.location.href = '{{ $urlOnDelete }}';
				}
			},
			'json'
		);
	});

	$('#button-move').click(function() {
		var html =
			'{{ Form::open(array("route" => "admin.moving", "method" => "post")) }}'
			+'{{ Form::hidden("check[]", $currentElement->getClassId()) }}'
			+'{{ Form::hidden("redirect", \Request::path()) }}'
			+'{{ Form::close() }}';
		var form = $(html);
		form.submit();
	});

	$('#button-restore').click(function() {
		$.blockUI();

		$.post(
			'{{ $currentElement->getRestoreUrl() }}',
			{},
			function(data) {
				document.location.reload();
			},
			'json'
		);
	});
@endif

	$('div.main input').each(function () {
		var value = $(this).val();
		$(this).val('').focus().val(value);
	});

	$('span.textarea').click(function() {
		var propertyName = $(this).attr('propertyName');
		var textarea = $('textarea[name="'+propertyName+'"]');
		var rows = textarea.attr('rows');
		if (rows == 40) textarea.attr('rows', 8);
		else textarea.attr('rows', 40);
	});

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

	$('body').on('click', 'div.plus[node1], div.minus[node1]', function() {
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
						$('div.plus[node1="'+node+'"]').removeClass('plus').addClass('minus').attr('opened', 'true');
						$('span[node1="'+node+'"]').attr('opened', 'true');
					});
				},
				'html'
			);
		} else if (opened == 'true') {
			$('div.padding[node1="'+node+'"]').slideUp('fast', function() {
				$('div.minus[node1="'+node+'"]').removeClass('minus').addClass('plus').attr('opened', 'false');
				$('span[node1="'+node+'"]').attr('opened', 'false');
			});
		} else if (opened == 'false') {
			$('div.padding[node1="'+node+'"]').slideDown('fast', function() {
				$('div.plus[node1="'+node+'"]').removeClass('plus').addClass('minus').attr('opened', 'true');
				$('span[node1="'+node+'"]').attr('opened', 'true');
			});
		}
	});

	$('#editForm').submit(function(event) {
		$.blockUI();

		$('textarea[tinymce="true"]').each(function() {
			$(this).val(tinyMCE.get(this.name).getContent());
		});

		$('input[onetoone="name"]').blur();

		$(this).ajaxSubmit({
			url: this.action,
			dataType: 'json',
			success: function(data) {
//				alert(data);
				$('#message').html('').hide();
				$('span[error]').removeClass('error');

				if (data.error) {
					for (var i in data.error) {
						$('span[error="'+data.error[i]+'"]').addClass('error');
					}
				} else if (data.logout) {
					document.location.href = "{{ URL::route('admin') }}";
				} else if (data.redirect) {
					document.location.href = data.redirect;
				} else if (data.refresh) {
					for (var name in data.refresh) {
						var view = LT.urldecode(data.refresh[name]);
						$('#'+name+'_container').html(view);
					}
				}

				$.unblockUI();
			}
		});
		event.preventDefault();
	});

});
</script>
@stop

@section('path')
@if ($currentElement->trashed())
<a href="{{ URL::route('admin.trash') }}">Корзина</a>
&rarr;&nbsp;<a href="{{ $currentElement->getTrashUrl() }}">{{ $currentElement->{$currentElement->getItem()->getMainProperty()} }}</a>
@else
<a href="{{ URL::route('admin') }}">Корень сайта</a>
	@if ($parentList)
		@foreach ($parentList as $parent)
&rarr;&nbsp;<a href="{{ $parent->getBrowseUrl() }}">{{ $parent->{$parent->getItem()->getMainProperty()} }}</a>
		@endforeach
	@endif
	@if ($currentElement->id)
&rarr;&nbsp;<a href="{{ $currentElement->getBrowseUrl() }}">{{ $currentElement->{$currentElement->getItem()->getMainProperty()} }}</a>
	@else
&rarr; Новый элемент
	@endif
@endif
@stop

@section('browse')
@if ($currentElement->trashed())
<p>
{{ Form::button('Наверх', array('id' => 'button-up', 'class' => 'btn')) }}
{{ Form::button('Редактировать', array('id' => 'button-edit', 'class' => 'btn', 'disabled' => 'disabled')) }}
{{ Form::button('Сохранить', array('id' => 'button-save', 'class' => 'btn')) }}
{{ Form::button('Восстановить', array('id' => 'button-restore', 'class' => 'btn')) }}
{{ Form::button('Удалить', array('id' => 'button-delete', 'class' => 'btn')) }}
</p>
@elseif ($currentElement->id)
<p>
{{ Form::button('Наверх', array('id' => 'button-up', 'class' => 'btn')) }}
{{ Form::button('Редактировать', array('id' => 'button-edit', 'class' => 'btn', 'disabled' => 'disabled')) }}
{{ Form::button('Сохранить', array('id' => 'button-save', 'class' => 'btn')) }}
{{ Form::button('Переместить', array('id' => 'button-move', 'class' => 'btn')) }}
{{ Form::button('Удалить', array('id' => 'button-delete', 'class' => 'btn')) }}
</p>
@else
<p>
{{ Form::button('Наверх', array('id' => 'button-up', 'class' => 'btn')) }}
{{ Form::button('Редактировать', array('id' => 'button-edit', 'class' => 'btn', 'disabled' => 'disabled')) }}
{{ Form::button('Добавить', array('id' => 'button-save', 'class' => 'btn')) }}
{{ Form::button('Переместить', array('id' => 'button-move', 'class' => 'btn', 'disabled' => 'disabled')) }}
{{ Form::button('Удалить', array('id' => 'button-delete', 'class' => 'btn', 'disabled' => 'disabled')) }}
</p>
@endif
@if ($currentElement->getHref())<div class="href"><a href="{{ $currentElement->getHref() }}" target="_blank">Смотреть страницу на сайте</a></div>@endif
<h1>Редактирование элемента типа <b>{{ $currentItem->getTitle() }}</b></h1>
<p class="error"><span id="message" class="dnone"></span></p>
@if ($currentElement->id)
{{ Form::model($currentElement, array('route' => array('admin.save', 'class' => $currentElement->getClass(), 'id' => $currentElement->id), 'method' => 'post', 'id' => 'editForm', 'files' => true)) }}
@else
{{ Form::model($currentElement, array('route' => array('admin.add', 'class' => $currentElement->getClass(), 'pclass' => $parentElement ? $parentElement->getClass() : null, 'pid' => $parentElement ? $parentElement->id : null), 'method' => 'post', 'id' => 'editForm', 'files' => true)) }}
@endif
<div class="form-edit">
@foreach ($propertyList as $propertyName => $property)
<div id="{{ $propertyName }}_container"{{ $property->isMainProperty() ? ' class="main"' : '' }}>{{ $property->setElement($currentElement)->getElementEditView() }}</div><br />
@endforeach
</div>
<p>{{ Form::submit('Сохранить', array('class' => 'btn')) }}</p>
{{ Form::close() }}
<p><br></p>
@stop
