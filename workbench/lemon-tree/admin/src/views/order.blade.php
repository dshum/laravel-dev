@extends('admin::layout')

@section('js')
{{ HTML::script('packages/lemon-tree/admin/js/order.js') }}
<script type="text/javascript">
$(function() {
	$('#button-up').click(function() {
		document.location.href = '{{ URL::route("admin.browse", array($currentElement ? $currentElement->getClassId() : null)) }}';
	});
});
</script>
@stop

@section('path')
<a href="{{ URL::route('admin') }}">Корень сайта</a>
@if ($currentElement)
	@if ($parentList)
		@foreach ($parentList as $parent)
&rarr;&nbsp;<a href="{{ URL::route('admin.browse', array($parent->getClassId())) }}">{{ $parent->{$parent->getItem()->getMainProperty()} }}</a>
		@endforeach
	@endif
&rarr;&nbsp;<a href="{{ URL::route('admin.browse', array($currentElement->getClassId())) }}">{{ $currentElement->{$currentElement->getItem()->getMainProperty()} }}</a>
@endif
&rarr;&nbsp;Порядок элементов
@stop

@section('browse')
<h1>Порядок элементов типа <b>{{ $item->getTitle() }}</b></h1>
{{ Form::open(array('route' => array('admin.order.save', $item->getName()), 'method' => 'post', 'id' => 'orderForm')) }}
@foreach ($hiddens as $classId => $k)
{{ Form::hidden('orderList['.$classId.']', $k) }}
@endforeach
<p>
<div id="button-up" class="button hand"><img src="{{ asset('packages/lemon-tree/admin/img/button-up.png') }}" alt="Наверх" title="Наверх" /><br />Наверх</div>
<div id="order-first" class="button hand"><img src="{{ asset('packages/lemon-tree/admin/img/arrow-left.png') }}" alt="" /><br />В начало</div>
<div id="order-up" class="button hand"><img src="{{ asset('packages/lemon-tree/admin/img/arrow-up.png') }}" alt="" /><br />Выше</div>
<div id="order-down" class="button hand"><img src="{{ asset('packages/lemon-tree/admin/img/arrow-down.png') }}" alt="" /><br />Ниже</div>
<div id="order-last" class="button hand"><img src="{{ asset('packages/lemon-tree/admin/img/arrow-right.png') }}" alt="" /><br />В конец</div>
<br clear="both" />
</p>
<p>{{ Form::select('list', $options, null, array('class' => 'multi-element-list', 'size' => sizeof($options), 'border' => 0)) }}</p>
<p>{{ Form::submit('Сохранить', array('class' => 'btn')) }}</p>
{{ Form::close() }}
@stop
