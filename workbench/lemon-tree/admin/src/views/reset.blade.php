@extends('admin::simple')

@section('content')

@if ($error == 'login')
<p class="error">Пользователь не найден.</p>
@elseif ($error == 'code')
<p class="error">Некорректный или устаревший код.</p>
@elseif ($error == 'password')
<p class="error">Введите пароль.</p>
@elseif ($error == 'reset')
<p class="error">Произошла ошибка при изменении пароля.</p>
@elseif ($mode == 'ok')
<p class="ok"><b>Пароль успешно изменен.</p>
@endif

@if (! $mode == 'ok')
{{ Form::open(array('route' => 'admin.login.reset', 'method' => 'post')) }}
{{ Form::hidden('login', $login) }}
{{ Form::hidden('code', $code) }}
<p>Новый пароль:<br>{{ Form::password('password') }}</p>
<p>{{ Form::submit('Сохранить') }}</p>
{{ Form::close() }}
@endif

<p><a href="{{ URL::route('admin') }}">Войти</a></p>

@stop