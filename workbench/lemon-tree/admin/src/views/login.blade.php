@extends('admin::simple')

@section('content')

@if (isset($error))
<p class="error">{{ $error }}</p>
@endif

{{ Form::open(array('route' => 'admin.login', 'method' => 'post')) }}
<p>Логин:<br>
{{ Form::text('login', $login, array()) }}</p>
<p>Пароль:<br>{{ Form::password('password', array()) }}<br>
<a href="{{ URL::route('admin.restore') }}">Забыли пароль?</a></p>
<p>{{ Form::submit('Войти', array('class' => 'btn')) }}</p>
{{ Form::close() }}

@stop