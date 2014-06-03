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
<a href="">Управление пользователями</a> &rarr;
{{ $loggedUser->login }}
@stop

@section('browse')
<h1>Профиль пользователя <b>{{ $loggedUser->login }}</b></h1>

{{ Form::model($loggedUser, array('route' => 'admin.profile', 'method' => 'post', 'id' => 'profileForm')) }}
<div class="form-edit">
@if ($groups)
<p>Состоит в {{ sizeof($groups) > 1 ? 'группах' : 'группе' }}:
	@foreach ($groups as $k => $group)
	{{ $k ? ', ' : '' }}<a href="{{ URL::route('admin.group', array('id' => $group->id)) }}">{{ $group->name }}</a>
	@endforeach
@endif
<p><span error="password">Пароль</span>:<br>{{ Form::password('password') }}</p>
<p><span error="email">E-mail</span>:<br>{{ Form::text('email') }}</p>
<p><span error="first_name">Имя</span>:<br>{{ Form::text('first_name') }}</p>
<p><span error="last_name">Фамилия</span>:<br>{{ Form::text('last_name') }}</p>
<p>Дата регистрации: {{ $loggedUser->created_at }}</p>
<p>Дата последнего входа: {{ $loggedUser->last_login }}</p>
</div>
<p>{{ Form::submit('Сохранить', array('class' => 'btn')) }}</p>
{{ Form::close() }}
@stop

