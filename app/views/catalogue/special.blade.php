@extends('layout')

@section('title')
Специальные предложения
@stop

@section('content')
<h2><span>Специальные предложения</span></h2>
@if (sizeof($goodList))
@include('catalogue.goodList')
@else
<p>Товары отсутствуют.</p>
@endif
@stop