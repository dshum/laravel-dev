@extends('admin::layout')

@section('js')
<script type="text/javascript">
$(function() {
	$("#userForm").submit(function(event) {
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
<a href="{{ \URL::route('admin.users') }}">Управление пользователями</a> &rarr;
{{ $user->id ? $user->login : 'Новый пользователь' }}
@stop

@section('browse')
@if ($user->id)
<h1>Редактирование пользователя</h1>
{{ Form::model($user, array('route' => array('admin.user', $user->id), 'method' => 'post', 'id' => 'userForm')) }}
@else
<h1>Добавление пользователя</h1>
{{ Form::model($user, array('route' => 'admin.user.add', 'method' => 'post', 'id' => 'userForm')) }}
@endif
<div class="form-edit">
<span error="login">Логин</span>:<br />{{ Form::text('login') }}<br /><br />
<span error="password">Пароль</span>:<br />{{ Form::password('password') }}<br /><br />
<span error="email">E-mail</span>:<br />{{ Form::text('email') }}<br /><br />
<span error="first_name">Имя</span>:<br />{{ Form::text('first_name') }}<br /><br />
<span error="last_name">Фамилия</span>:<br />{{ Form::text('last_name') }}<br /><br />
<span error="group">Состоит в группах</span>:<br />
@foreach ($groupList as $group)
{{ Form::checkbox('group_'.$group->id, $group->id, isset($userGroups[$group->id]) ? true : false, array('id' => 'group_'.$group->id)) }} <label for="group_{{ $group->id }}"><span error="group_{{ $group->id }}">{{ $group->name }}</span></label><br />
@endforeach
<br />
@if ($user->id)
Дата регистрации: {{ $user->created_at }}<br /><br />
Последнее изменение: {{ $user->updated_at }}<br /><br />
Дата последнего входа: {{ $user->last_login }}<br /><br />
@endif
</div>
<p>{{ Form::submit('Сохранить', array('class' => 'btn')) }}</p>
{{ Form::close() }}
@stop

