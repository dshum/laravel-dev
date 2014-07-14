@extends('admin::layout')

@section('js')
{{ HTML::script('LT/js/browse.js') }}
<script type="text/javascript">
$(function() {

@if ($currentElement)
	@if ($currentElement->getParent())
	$('#button-up').click(function() {
		document.location.href = '{{ $currentElement->getParent()->getBrowseUrl() }}';
	});
	@elseif ($isTrash)
	$('#button-up').click(function() {
		document.location.href = LT.trashUrl;
	});
	@else
	$('#button-up').click(function() {
		document.location.href = LT.adminUrl;
	});
	@endif

	$('#button-edit').click(function() {
		document.location.href = '{{ $currentElement->getEditUrl() }}';
	});
@endif

	$('#browseForm').submit(function(event) {
		event.preventDefault();
	});

});
</script>
@stop

@section('path')
@if ($isTrash)
	@if ($currentElement)
	<a href="{{ URL::route('admin.trash') }}">Корзина</a>
	&rarr;&nbsp;<a href="{{ URL::route('admin.edit', array('class' => $currentElement->getClass(), 'id' => $currentElement->id)) }}">{{ $currentElement->{$currentElement->getItem()->getMainProperty()} }}</a>
	@else
	Корзина
	@endif
@else
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
@endif
@stop

@section('browse')
<p>
@if ($currentElement)
<div id="button-up" class="button hand"><img src="/LT/img/button-up.png" alt="Наверх" title="Наверх" /><br />Наверх</div>
<div id="button-edit" class="button hand"><img src="/LT/img/button-edit.png" alt="Редактировать" title="Редактировать" /><br />Редактировать</div>
@else
<div id="button-up" class="button hand disabled"><img src="/LT/img/button-up.png" alt="" /><br />Наверх</div>
<div id="button-edit" class="button hand disabled"><img src="/LT/img/button-edit.png" alt="Редактировать" title="Редактировать" /><br />Редактировать</div>
@endif
@if ($isTrash)
<div id="button-save" class="button hand disabled"><img src="/LT/img/button-save.png" alt="Сохранить" title="Сохранить" /><br />Сохранить</div>
<div id="button-restore" class="button hand disabled"><img src="/LT/img/button-restore.png" alt="Восстановить" title="Восстановить" /><br />Восстановить</div>
<div id="button-delete" class="button hand disabled"><img src="/LT/img/button-delete.png" alt="Удалить" title="Удалить" /><br />Удалить</div>
@else
<div id="button-save" class="button hand disabled"><img src="/LT/img/button-save.png" alt="Сохранить" title="Сохранить" /><br />Сохранить</div>
<div id="button-move" class="button hand disabled"><img src="/LT/img/button-move.png" alt="Переместить" title="Переместить" /><br />Переместить</div>
<div id="button-delete" class="button hand disabled"><img src="/LT/img/button-delete.png" alt="Удалить" title="Удалить" /><br />Удалить</div>
@endif
</p>
<br clear="both" />
@if ( ! $isTrash && $bindItemList)
<p>Добавить:
	{? $count = sizeof($bindItemList) ?}
	@foreach ($bindItemList as $itemName => $item)
<a href="{{ \URL::route('admin.create', array($itemName, $currentElement ? $currentElement->getClassId() : null)) }}">{{ $item->getTitle() }}</a>@if (--$count > 0), @endif
	@endforeach
</p>
@endif
<p class="error"><span id="message" class="dnone"></span></p>
@if ($elementListViewList)
{{ Form::open(array('route' => 'admin.browse.save', 'method' => 'post', 'id' => 'browseForm')) }}
{{ Form::hidden('redirect', \Request::path()) }}
@foreach ($elementListViewList as $itemName => $elementListView)
<div id="item_container_{{ $itemName }}">
{{ $elementListView }}
</div>
@endforeach
{{ Form::close() }}
@elseif ($currentElement)
<p>В данном разделе элементы отсутствуют.<br>
Вы можете <a href="{{ URL::route('admin.edit', array('class' => $currentElement->getClass(), 'id' => $currentElement->id)) }}">редактировать</a> раздел.</p>
@else
<p>В данном разделе элементы отсутствуют.</p>
@endif
@stop

