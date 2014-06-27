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
<h1>Доступ к элементам по умолчанию группы <b>{{ $group->name }}</b></h1>
{{ Form::open(array('route' => array('admin.group.items', $group->id), 'method' => 'post', 'id' => 'groupForm')) }}
<table class="element-list">
	<col width="40%">
	<col width="15%">
	<col width="15%">
	<col width="15%">
	<col width="15%">
	<tr>
		<th>Тип элемента</th>
		<th class="center">Доступ закрыт</th>
		<th class="center">Просмотр</th>
		<th class="center">Изменение</th>
		<th class="center">Удаление</th>
	</tr>
@foreach ($itemList as $item)
	{? $permission = isset($groupItemPermissionMap[$item->getName()]) ? $groupItemPermissionMap[$item->getName()] : $defaultGroupPermission; ?}
	<tr>
		<td><span error="{{ $item->getName() }}">{{ $item->getTitle() }}</span></td>
		<td class="center{{ $permission == 'deny' ? ' light' : '' }}">{{ Form::radio($item->getName(), 'deny', $permission == 'deny' ? true : false) }}</td>
		<td class="center{{ $permission == 'view' ? ' light' : '' }}">{{ Form::radio($item->getName(), 'view', $permission == 'view' ? true : false) }}</td>
		<td class="center{{ $permission == 'update' ? ' light' : '' }}">{{ Form::radio($item->getName(), 'update', $permission == 'update' ? true : false) }}</td>
		<td class="center{{ $permission == 'delete' ? ' light' : '' }}">{{ Form::radio($item->getName(), 'delete', $permission == 'delete' ? true : false) }}</td>
	</tr>
@endforeach
</table>
<p>{{ Form::submit('Сохранить', array('class' => 'btn')) }}</p>
{{ Form::close() }}
@stop
