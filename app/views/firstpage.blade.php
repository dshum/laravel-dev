@extends('layout')

@section('title')
Trilobite Group
@stop

@section('content')
<div id="items">
@foreach ($categoryList as $k => $category)
	<div class="item{{ $k % 3 == 1 ? ' center' : '' }}">
	@if ($category->image)
		<a href="{{ $category->getHref() }}"><img src="{{ $category->getProperty('image')->src() }}" width="{{ $category->getProperty('image')->width() }}" height="{{ $category->getProperty('image')->height() }}" /></a><br />
	@endif
		<p><a href="{{ $category->getHref() }}">{{ $category->name }}</a></p>
	</div>
@endforeach
</div>
@stop