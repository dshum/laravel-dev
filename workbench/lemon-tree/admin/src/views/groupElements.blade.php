@extends('admin::layout')

@section('js')
<script type="text/javascript">
$(function() {
	
	$('td').click(function() {
		$(this).children('input:radio').prop('checked', true);
		$(this).parents('tr').children('td').removeClass('light');
		$(this).addClass('light');
	}).mouseover(function() {
		$(this).parents('tr').addClass('light-hover');
	}).mouseout(function() {
		$(this).parents('tr').removeClass('light-hover');
	});
	
	$("#groupForm").submit(function(event) {
		$.blockUI();
		$(this).ajaxSubmit({
			url: this.action,
			dataType: 'json',
			success: function(data) {
//				alert(data);
				$('span[error]').removeClass('error');
				if (data.error) {
					for (var i in data.error) {
						$('span[error="'+data.error[i]+'"]').addClass('error');
					}
				} else if (data.message) {
					alert(data.message);
				} else if (data.logout) {
					document.location.href = "{{ URL::route('admin') }}";
				} else if (data.redirect) {
					document.location.href = data.redirect;
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
<a href="{{ \URL::route('admin.users') }}">Управление группами</a> &rarr; {{ $group->name }}
@stop

@section('browse')
<h1>Доступ к элементам группы <b>{{ $group->name }}</b></h1>
@if ($itemList)
{{ Form::open(array('route' => array('admin.group.elements', $group->id), 'method' => 'post', 'id' => 'groupForm')) }}
	@foreach ($itemList as $itemName => $item)
<h2>{{ $item->getTitle() }}</h2>
<table class="element-list">
	<col width="40%">
	<col width="15%">
	<col width="15%">
	<col width="15%">
	<col width="15%">
	<tr>
		<th>Название</th>
		<th class="center">Доступ закрыт</th>
		<th class="center">Просмотр</th>
		<th class="center">Изменение</th>
		<th class="center">Удаление</th>
	</tr>
		@foreach ($itemElementList[$itemName] as $element)
			{? $permission = isset($groupElementPermissionMap[$element->getClassId()]) ? $groupElementPermissionMap[$element->getClassId()] : (isset($groupItemPermissionMap[$item->getName()]) ? $groupItemPermissionMap[$item->getName()] : $defaultGroupPermission); ?}
	<tr>
		<td><span error="{{ $element->getClassId() }}">{{ $element->{$item->getMainProperty()} }}</span></td>
		<td class="center{{ $permission == 'deny' ? ' light' : '' }}">{{ Form::radio($element->getClassId(), 'deny', $permission == 'deny' ? true : false) }}</td>
		<td class="center{{ $permission == 'view' ? ' light' : '' }}">{{ Form::radio($element->getClassId(), 'view', $permission == 'view' ? true : false) }}</td>
		<td class="center{{ $permission == 'update' ? ' light' : '' }}">{{ Form::radio($element->getClassId(), 'update', $permission == 'update' ? true : false) }}</td>
		<td class="center{{ $permission == 'delete' ? ' light' : '' }}">{{ Form::radio($element->getClassId(), 'delete', $permission == 'delete' ? true : false) }}</td>
	</tr>
		@endforeach
</table>
{{ $itemElementList[$itemName] instanceof Paginator ? $itemElementList[$itemName]->links() : null }}
	@endforeach
<p>{{ Form::submit('Сохранить', array('class' => 'btn')) }}</p>
{{ Form::close() }}
@else
<p>В данном разделе элементы отсутствуют.</p>
@endif
@stop
