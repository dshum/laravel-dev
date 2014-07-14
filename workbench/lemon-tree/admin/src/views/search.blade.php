@extends('admin::layout')

@section('js')
{{ HTML::script('LT/js/search.js') }}
{{ HTML::script('LT/js/browse.js') }}
<script type="text/javascript">
$(function() {

	$('#searchForm').submit(function(event) {
		if ( ! $('#item').val()) event.preventDefault();
	});

});
</script>
@stop

@section('path')
Поиск элементов
@stop

@section('browse')
<p class="error"><span id="message" class="dnone"></span></p>

{{ Form::open(array('route' => 'admin.search', 'method' => 'get', 'id' => 'searchForm')) }}
{{ Form::hidden('item', $currentItem ? $currentItem->getName() : null, array('id' => 'item')) }}
<div class="form-search">

<table class="element-list-header">
<tr>
<td><h2>Класс элемента</h2></td>
<td><div class="order_link">сортировать по <span class="dashed hand">дате</span>, <span class="dashed hand">названию</span>, <span style="padding: 2px 5px; background: #999; color: #FFF;">по умолчанию</span></div></td>
</tr>
</table>

@foreach ($itemList as $itemName => $item)
@if ($currentItem && $currentItem->getName() == $itemName)
<div class="item-search-active"><span item="{{ $itemName }}">{{ $item->getTitle() }}</span></div>
@else
<div class="item-search"><span item="{{ $itemName }}" class="dashed hand">{{ $item->getTitle() }}</span></div>
@endif
@endforeach
<br clear="both" /><br />

<div id="itemContainer">

@if ($currentItem)
<div id="idContainer" class="prop-search">
<span switch="true" name="id" class="dashed hand"><b>ID элемента</b></span>:<br>
<div id="id_block" style="display: {{ $id ? 'block' : 'none' }};">
<input class="prop ename" type="text" id="id" name="id" value="{{ $id }}"{{ $id ? '' : 'disabled="disabled"' }}>
</div>
</div>
@endif

@if ($propertyList)
@foreach ($propertyList as $propertyName => $property)
@if ($elementSearchView = $property->getElementSearchView())
<div id="{{ $propertyName }}Container" class="prop-search">
{{ $elementSearchView }}
</div>
@endif
@endforeach
<br clear="both" /><br />
@endif

</div>

</div>
<p>{{ Form::submit('Найти', array('class' => 'btn')) }}</p>
{{ Form::close() }}
@if ($elementListView)
{{ Form::open(array('route' => 'admin.browse.save', 'method' => 'post', 'id' => 'browseForm')) }}
{{ Form::hidden('redirect', \Request::path()) }}
<p>
<div id="button-save" class="button hand disabled"><img src="/LT/img/button-save.png" alt="Сохранить" title="Сохранить" /><br />Сохранить</div>
<div id="button-move" class="button hand disabled"><img src="/LT/img/button-move.png" alt="Переместить" title="Переместить" /><br />Переместить</div>
<div id="button-delete" class="button hand disabled"><img src="/LT/img/button-delete.png" alt="Удалить" title="Удалить" /><br />Удалить</div>
</p>
<br clear="both" />

<div id="item_container">
{{ $elementListView }}
</div>

{{ Form::close() }}
@endif
@stop
