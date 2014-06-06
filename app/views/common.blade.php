@extends('layout')

@section('title')
{{ $currentElement->title }}
@stop

@section('h1')
{{ $currentElement->h1 }}
@stop

@section('content')
<h1>@yield('h1')</h1>
<div class="info">
	<p>{{ $currentElement->fullcontent }}</p>
</div>
@stop