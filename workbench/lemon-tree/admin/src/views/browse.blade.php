@extends('admin::layout')

@section('js')
{{ HTML::script('packages/lemon-tree/admin/js/browse.js') }}
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
	&rarr;&nbsp;<a href="{{ URL::route('admin.browse', array($parent->getClassId())) }}">{{ $parent->{$parent->getItem()->getMainProperty()} }}</a>
			@endforeach
		@endif
	&rarr;&nbsp;<a href="{{ URL::route('admin.edit', array($currentElement->getClassId())) }}">{{ $currentElement->{$currentElement->getItem()->getMainProperty()} }}</a>
	@else
	Корень сайта
	@endif
@endif
@stop

@section('browse')
<p>
<div id="button-up" class="button hand{{ $currentElement ? '' : ' disabled' }}"><img src="{{ asset('packages/lemon-tree/admin/img/button-up.png') }}" alt="" /><br />Наверх</div>
<div id="button-edit" class="button hand{{ $currentElement ? '' : ' disabled' }}"><img src="{{ asset('packages/lemon-tree/admin/img/button-edit.png') }}" alt="Редактировать" title="Редактировать" /><br />Редактировать</div>
<div id="button-save" class="button hand disabled"><img src="{{ asset('packages/lemon-tree/admin/img/button-save.png') }}" alt="Сохранить" title="Сохранить" /><br />Сохранить</div>
@if ($isTrash)
<div id="button-restore" class="button hand disabled"><img src="{{ asset('packages/lemon-tree/admin/img/button-restore.png') }}" alt="Восстановить" title="Восстановить" /><br />Восстановить</div>
@else
<div id="button-move" class="button hand disabled"><img src="{{ asset('packages/lemon-tree/admin/img/button-move.png') }}" alt="Переместить" title="Переместить" /><br />Переместить</div>
@endif
<div id="button-delete" class="button hand disabled"><img src="{{ asset('packages/lemon-tree/admin/img/button-delete.png') }}" alt="Удалить" title="Удалить" /><br />Удалить</div>
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
@if ($browsePluginView)
<div class="plugin">
{{ $browsePluginView }}
</div>
@endif
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