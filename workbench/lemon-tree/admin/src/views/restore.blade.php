@extends('admin::simple')

@section('content')

@if (isset($error))
<p class="error">{{ $error }}</p>
@elseif ($email)
<p class="ok">На <b>{{{ $email }}}</b> отправлена информация по восстановлению доступа.</p>
@endif

{{ Form::open(array('route' => 'admin.login.restore', 'method' => 'post')) }}
<p>Логин:<br>{{ Form::text('login', $login, array('class' => 'pass')) }}</p>
<p>{{ Form::submit('Восстановить', array('class' => 'btn')) }}</p>
{{ Form::close() }}

<p><a href="{{ URL::route('admin') }}">Вернуться на страницу авторизации</a></p>

@stop