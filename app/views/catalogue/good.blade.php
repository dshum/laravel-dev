@extends('layout')

@section('title')
{{ $currentElement->name }}
@stop

@section('content')
<h2><span>{{ $currentElement->name }}</span></h2>
@if ($currentElement->image)
<p><img src="{{ $currentElement->getProperty('image')->src() }}" width="{{ $currentElement->getProperty('image')->width() }}" height="{{ $currentElement->getProperty('image')->height() }}" /></p>
@endif
<p>Бренд: {{ $currentElement->brand->name }}</p>
<p>Цена: {{ $currentElement->price }} руб.</p>
<p><span good="{{ $currentElement->id }}" class="btn">Заказать</span></p>
@stop