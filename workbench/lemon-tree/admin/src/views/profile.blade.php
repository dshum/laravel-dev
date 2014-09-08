@extends('admin::layout')

@section('js')
<script type="text/javascript">
$(function() {
	$("#profileForm").submit(function(event) {
		$.blockUI();
		$(this).ajaxSubmit({
			url: this.action,
			dataType: 'json',
			success: function(data) {
				$('span[error]').removeClass('error');
				if (data.error) {
					for (var i in data.error) {
						$('span[error="'+data.error[i]+'"]').addClass('error');
					}
				} else if (data.logout) {
					document.location.href = "{{ URL::route('admin') }}";
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
{{ $loggedUser->login }}
@stop

@section('browse')
<h1>Профиль пользователя <b>{{ $loggedUser->login }}</b></h1>
{{ Form::model($loggedUser, array('route' => 'admin.profile', 'method' => 'post', 'id' => 'profileForm')) }}
<div class="form-edit">
<span error="password">Пароль</span>:<br />{{ Form::password('password') }}<br /><br />
<span error="email">E-mail</span>:<br />{{ Form::text('email') }}<br /><br />
<span error="first_name">Имя</span>:<br />{{ Form::text('first_name') }}<br /><br />
<span error="last_name">Фамилия</span>:<br />{{ Form::text('last_name') }}<br /><br />
@if (sizeof($groups))
Состоит в {{ sizeof($groups) > 1 ? 'группах' : 'группе' }}:
	@foreach ($groups as $k => $group)
		{{ $group->name }}{{ $k < sizeof($groups) - 1 ? ', ' : '' }}
	@endforeach
	<br /><br />
@endif
@if ($loggedUser->isSuperUser())
<b>Обладает правами суперпользователя</b><br /><br />
@endif
Дата регистрации: {{ $loggedUser->created_at }}<br /><br />
Последнее изменение: {{ $loggedUser->updated_at }}<br /><br />
Дата последнего входа: {{ $loggedUser->last_login }}<br /><br />
</div>
<p>{{ Form::submit('Сохранить', array('class' => 'btn')) }}</p>
{{ Form::close() }}
@stop

