@extends('layout')

@section('title')
Личный кабинет
@stop

@section('content')
<h2><span>Личный кабинет</span></h2>
@if (isset($error))
<p class="error"><span>{{ $error }}</span></p>
@endif
<p>{{ $loggedUser->email }}</p>
<p>{{ $loggedUser->fio }}</p>
<p>{{ $loggedUser->phone }}{{ $loggedUser->phone2 ? ', '.$loggedUser->phone2 : '' }}</p>
@stop