<table class="element-list-header">
<tr>
@if (isset($isSearch) && $isSearch)
<td nowrap><h2>{{ $item->getTitle() }}</h2></td>
@elseif (isset($isTrash) && $isTrash)
<td nowrap><h2><span showlist="true" opened="{{ $open ? 'true' : 'open' }}" url="{{ URL::route('admin.trash.list') }}" classId="{{ $currentElement ? $currentElement->getClassId() : LemonTree\Site::TRASH }}" item="{{ $item->getName() }}" class="hand dashed">{{ $item->getTitle() }}</span></h2></td>
@else
<td nowrap><h2><span showlist="true" opened="{{ $open ? 'true' : 'open' }}" url="{{ URL::route('admin.browse.list') }}" classId="{{ $currentElement ? $currentElement->getClassId() : LemonTree\Site::ROOT }}" item="{{ $item->getName() }}" class="hand dashed">{{ $item->getTitle() }}</span></h2></td>
@endif
<td nowrap><div class="order_link"><em>@if ($elementList instanceof \Illuminate\Pagination\Paginator)страница {{ $elementList->getCurrentPage() }} из {{ $elementList->getLastPage() }}; @endif
всего {{ $total }} {{ RussianTextUtils::selectCaseForNumber($total, array('элемент', 'элемента', 'элементов')) }}</em></div></td>
<td width="90%"></td>
<td nowrap><a href="{{ URL::route('admin.search', array('item' => $item->getName())) }}"><small>Поиск элементов</small></td>
</tr>
</table>
@if ($open)
<div id="element_list_container_{{ $item->getName() }}"{{ isset($hideList) && $hideList ? ' class="dnone"' : '' }}>
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
@if ($elementList instanceof \Illuminate\Pagination\Paginator && $elementList->getLastPage() > 1)
{? $presenter = new \LemonTree\CustomPresenter($elementList); ?}
<ul class="pagination">
{{ $presenter->render() }}
<li><a href="http://laravel.dev/admin/browse/list?page=3&classId=ServiceSection.14&item=GoodBrand&expand=1">3</a></li>
</ul>
@endif
</div>
@else
<div id="element_list_container_{{ $item->getName() }}" class="dnone"></div>
@endif