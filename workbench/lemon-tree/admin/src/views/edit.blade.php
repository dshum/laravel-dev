@extends('admin::layout')

@section('js')
{{ HTML::style('LT/js/calendarview/jquery.calendar.css') }}
{{ HTML::script('LT/js/calendarview/jquery.calendar.js') }}
{{ HTML::script('LT/js/tinymce/jscripts/tiny_mce/tiny_mce.js') }}
{{ HTML::script('LT/js/edit.js') }}
<script type="text/javascript">
$(function() {

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
			'{{ URL::route("admin.restore", $currentElement->getClassId()) }}',
			{},
			function(data) {
				document.location.reload();
			},
			'json'
		);
	});
@endif

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
<div id="button-up" class="button hand"><img src="/LT/img/button-up.png" alt="Наверх" title="Наверх" /><br />Наверх</div>
<div id="button-edit" class="button hand disabled"><img src="/LT/img/button-edit.png" alt="Редактировать" title="Редактировать" /><br />Редактировать</div>
<div id="button-save" class="button hand"><img src="/LT/img/button-save.png" alt="Сохранить" title="Сохранить" /><br />Сохранить</div>
<div id="button-restore" class="button hand"><img src="/LT/img/button-restore.png" alt="Восстановить" title="Восстановить" /><br />Восстановить</div>
<div id="button-delete" class="button hand"><img src="/LT/img/button-remove.png" alt="Удалить" title="Удалить" /><br />Удалить</div>
</p>
@elseif ($currentElement->id)
<p>
<div id="button-up" class="button hand"><img src="/LT/img/button-up.png" alt="Наверх" title="Наверх" /><br />Наверх</div>
<div id="button-edit" class="button hand disabled"><img src="/LT/img/button-edit.png" alt="Редактировать" title="Редактировать" /><br />Редактировать</div>
<div id="button-save" class="button hand"><img src="/LT/img/button-save.png" alt="Сохранить" title="Сохранить" /><br />Сохранить</div>
<div id="button-move" class="button hand"><img src="/LT/img/button-move.png" alt="Переместить" title="Переместить" /><br />Переместить</div>
<div id="button-delete" class="button hand"><img src="/LT/img/button-delete.png" alt="Удалить" title="Удалить" /><br />Удалить</div>
</p>
@else
<p>
<div id="button-up" class="button hand"><img src="/LT/img/button-up.png" alt="Наверх" title="Наверх" /><br />Наверх</div>
<div id="button-edit" class="button hand disabled"><img src="/LT/img/button-edit.png" alt="Редактировать" title="Редактировать" /><br />Редактировать</div>
<div id="button-save" class="button hand"><img src="/LT/img/button-save.png" alt="Сохранить" title="Сохранить" /><br />Сохранить</div>
<div id="button-move" class="button hand disabled"><img src="/LT/img/button-move.png" alt="Переместить" title="Переместить" /><br />Переместить</div>
<div id="button-delete" class="button hand disabled"><img src="/LT/img/button-delete.png" alt="Удалить" title="Удалить" /><br />Удалить</div>
</p>
@endif
<br clear="both" />
@if ($currentElement->getHref())<div class="href"><a href="{{ $currentElement->getHref() }}" target="_blank">Смотреть страницу на сайте</a></div>@endif
<h1>Редактирование элемента типа <b>{{ $currentItem->getTitle() }}</b></h1>
<p class="error"><span id="message" class="dnone"></span></p>
@if ($currentElement->id)
{{ Form::model($currentElement, array('route' => array('admin.save', $currentElement->getClassId()), 'method' => 'post', 'id' => 'editForm', 'files' => true)) }}
@else
{{ Form::model($currentElement, array('route' => array('admin.add', $currentElement->getClass(), $parentElement ? $parentElement->getClassId() : null), 'method' => 'post', 'id' => 'editForm', 'files' => true)) }}
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
