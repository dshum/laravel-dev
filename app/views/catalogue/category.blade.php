@extends('layout')

@section('title')
{{ $currentElement->title }}
@stop

@section('content')
<div id="items">
@foreach ($goodList as $k => $good)
	<div class="item{{ $k % 3 == 1 ? ' center' : '' }}">
	@if ($good->image)
		<a href="{{ $good->getHref() }}"><img src="{{ $good->getProperty('image')->src() }}" width="{{ $good->getProperty('image')->width() }}" height="{{ $good->getProperty('image')->height() }}" /></a><br />
	@endif
		<p><a href="{{ $good->getHref() }}">{{ $good->name }}</a></p>
	</div>
@endforeach
</div>
@stop