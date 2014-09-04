@extends('layout')

@section('title')
{{ $currentElement->title }}
@stop

@section('content')
<h2><span>{{ $currentElement->name }}</span></h2>
@if (sizeof($subcategoryList))
	@foreach ($subcategoryList as $k => $subcategory)
	<p>@if ($subcategory->equalTo($currentElement)){{ $subcategory->name }}@else<a href="{{ $subcategory->getHref() }}">{{ $subcategory->name }}</a>@endif</p>
	@endforeach
@endif
@if (sizeof($goodList))
@include('catalogue.goodList')
@else
<p>Товары отсутствуют.</p>
@endif
@stop