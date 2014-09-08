@foreach ($goodList as $k => $good)
<div class="good">
	@if ($good->image)
<a href="{{ $good->getHref() }}"><img src="{{ $good->getProperty('image')->src() }}" width="{{ $good->getProperty('image')->width() }}" height="{{ $good->getProperty('image')->height() }}" /></a><br />
	@endif
<div class="title">
<a href="{{ $good->getHref() }}">{{ $good->name }}</a><br />
<small>{{ $good->brand->name }}</small>
{{ $good->price }} руб.
<span good="{{ $good->id }}" class="btn">Заказать</span>
</div>
</div>
@endforeach