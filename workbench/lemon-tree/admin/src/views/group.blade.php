@extends('admin::layout')

@section('js')
<script type="text/javascript">
$(function() {
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
<a href="{{ \URL::route('admin.users') }}">Управление группами</a> &rarr;
{{ $group->id ? $group->name : 'Новая группа' }}
@stop

@section('browse')
@if ($group->id)
<h1>Редактирование группы</h1>
{{ Form::model($group, array('route' => array('admin.group', $group->id), 'method' => 'post', 'id' => 'groupForm')) }}
@else
<h1>Добавление группы</h1>
{{ Form::model($group, array('route' => 'admin.group.add', 'method' => 'post', 'id' => 'groupForm')) }}
@endif
<div class="form-edit">
<span error="name">Название</span>:<br />{{ Form::text('name') }}<br /><br />
{{ Form::checkbox('admin', 1, $group->hasAccess('admin') ? true : false, array('id' => 'admin_permission')) }} <label for="admin_permission"><span error="admin_permission">Управление пользователями</span></label><br /><br />
@if ($group->id)
Дата создания: {{ $group->created_at }}<br /><br />
Последнее изменение: {{ $group->updated_at }}<br /><br />
@endif
</div>
<p>{{ Form::submit('Сохранить', array('class' => 'btn')) }}</p>
{{ Form::close() }}
@stop

