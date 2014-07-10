<table class="element-list-header">
<tr>
@if ($isTrash)
<td nowrap><h2><span showlist="true" opened="{{ $open ? 'true' : 'open' }}" url="{{ URL::route('admin.trash.list') }}" classId="{{ $currentElement ? $currentElement->getClassId() : LemonTree\Site::TRASH }}" item="{{ $item->getName() }}" class="hand dashed">{{ $item->getTitle() }}</span></h2></td>
@else
<td nowrap><h2><span showlist="true" opened="{{ $open ? 'true' : 'open' }}" url="{{ URL::route('admin.browse.list') }}" classId="{{ $currentElement ? $currentElement->getClassId() : LemonTree\Site::ROOT }}" item="{{ $item->getName() }}" class="hand dashed">{{ $item->getTitle() }}</span></h2></td>
@endif
<td nowrap><div class="order_link"><em>@if ($elementList instanceof Paginator)страница {{ 1 }} из {{ 1 }}; @endif
всего {{ $total }} {{ RussianTextUtils::selectCaseForNumber($total, array('элемент', 'элемента', 'элементов')) }}</em></a></div></td>
<td width="90%"></td>
<td nowrap><a href="{{ URL::route('admin.search', array($item->getName())) }}"><small>Поиск элементов</small></td>
</tr>
</table>
@if ($open)
<div id="element_list_container_{{ $item->getName() }}"{{ $hideList ? ' class="dnone"' : '' }}>
<table class="element-list">
	<tr>
		<th class="first"><img src="/LT/img/default-sorting-inactive.gif" alt="" /></th>
	@foreach ($itemPropertyList as $propertyName => $property)
		<th><a href="">{{ $property->getTitle() }}</a></th>
	@endforeach
		<th class="last">{{ Form::checkbox('checkAll[]', $item->getName(), false, array('item' => $item->getName(), 'title' => 'Отметить все')) }}</th>
	</tr>
	@foreach ($elementList as $element)
	<tr>
		<td class="first"><a href="{{ $element->getBrowseUrl() }}"><img src="/LT/img/file.png" alt="" style="vertical-align: middle;" /></a></td>
		@foreach ($itemPropertyList as $propertyName => $property)
			@if ($property->isMainProperty())
			<td><img src="/LT/img/edit.png" alt="" style="vertical-align: middle; margin-right: 5px;" /><a href="{{ $element->getEditUrl() }}" edit="true">{{ $element->$propertyName }}</a></td>
			@else
		<td>{{ $property->setElement($element)->getElementListView() }}</td>
			@endif
		@endforeach
		<td class="last">{{ Form::checkbox('check[]', $element->getClassId(), false, array('item' => $item->getName(), 'title' => 'Отметить')) }}</td>
	</tr>
	@endforeach
</table>
{{ $elementList instanceof Paginator ? $elementList->links() : null }}
</div>
@else
<div id="element_list_container_{{ $item->getName() }}" class="dnone"></div>
@endif