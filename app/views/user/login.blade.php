@extends('layout')

@section('title')
Вход в личный кабинет
@stop

@section('content')
<h2><span>Вход в личный кабинет</span></h2>
@if (isset($error))
<p class="error"><span>{{ $error }}</span></p>
@endif
{{ Form::open(array('route' => 'login', 'method' => 'post')) }}
<p>E-mail:<br>
{{ Form::text('email', $email, array()) }}</p>
<p>Пароль:<br>{{ Form::password('password', array()) }}</p>
<p>{{ Form::checkbox('remember', 1, $remember, array('id' => 'remember')) }} {{ Form::label('remember', 'Запомнить меня') }}</p>
<p>{{ Form::submit('Войти', array('class' => 'btn')) }} или <a href="{{ URL::route('restore') }}">Забыли пароль?</a></p>
{{ Form::close() }}
@stop