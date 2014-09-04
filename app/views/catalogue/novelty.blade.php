@extends('layout')

@section('title')
Новинки
@stop

@section('content')
<h2><span>Новинки</span></h2>
@if (sizeof($goodList))
@include('catalogue.goodList')
@else
<p>Товары отсутствуют.</p>
@endif
@stop