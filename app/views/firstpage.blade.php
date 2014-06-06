@extends('layout')

@section('title')
Trilobite Group
@stop

@section('content')
<div id="items">
@foreach ($categoryList as $k => $category)
	<div class="item{{ $k % 3 == 1 ? ' center' : '' }}">
		<a href="{{ $category->getHref() }}"><img src="/i/item1.jpg" width="213" height="192" /></a><br />
		<p><a href="{{ $category->getHref() }}">{{ $category->name }}</a></p>
	</div>
@endforeach
</div>
@stop