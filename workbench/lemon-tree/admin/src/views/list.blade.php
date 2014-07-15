<table class="element-list-header">
<tr>
@if (isset($isSearch) && $isSearch)
<td nowrap><h2>{{ $item->getTitle() }}</h2></td>
@elseif (isset($isTrash) && $isTrash)
<td nowrap><h2><span showlist="true" opened="{{ $open ? 'true' : 'open' }}" url="{{ URL::route('admin.trash.list') }}" classId="{{ $classId }}" item="{{ $item->getName() }}" class="hand dashed">{{ $item->getTitle() }}</span></h2></td>
@else
<td nowrap><h2><span showlist="true" opened="{{ $open ? 'true' : 'open' }}" url="{{ URL::route('admin.browse.list') }}" classId="{{ $classId }}" item="{{ $item->getName() }}" class="hand dashed">{{ $item->getTitle() }}</span></h2></td>
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
		<th class="first">@if ($defaultOrderBy)<img src="/LT/img/default-sorting-inactive.gif" alt="" />@else<a order="true" href="{{ URL::route($listBaseRoute, array('classId' => $classId, 'item' => $item->getName(), 'expand' => 1, 'orderDefault' => 1)) }}"><img src="/LT/img/default-sorting.gif" alt="" /></a>@endif</th>
	@foreach ($itemPropertyList as $propertyName => $property)
		<th>
		@if (isset($currentOrderByList[$propertyName]) && $currentOrderByList[$propertyName] == 'desc')
		<a order="true" href="{{ URL::route($listBaseRoute, array('classId' => $classId, 'item' => $item->getName(), 'expand' => 1, 'orderField' => $propertyName, 'orderDirection' => 'asc')) }}" title="Сортировать по возрастанию">{{ $property->getTitle() }}</a> <span title="Отсортировано по убыванию">&darr;</span>
		@elseif (isset($currentOrderByList[$propertyName]) && $currentOrderByList[$propertyName] == 'asc')
		<a order="true" href="{{ URL::route($listBaseRoute, array('classId' => $classId, 'item' => $item->getName(), 'expand' => 1, 'orderField' => $propertyName, 'orderDirection' => 'desc')) }}" title="Сортировать по убыванию">{{ $property->getTitle() }}</a> <span title="Отсортировано по возрастанию">&uarr;</span>
		@else
		<a order="true" href="{{ URL::route($listBaseRoute, array('classId' => $classId, 'item' => $item->getName(), 'expand' => 1, 'orderField' => $propertyName, 'orderDirection' => 'asc')) }}" title="Сортировать по возрастанию">{{ $property->getTitle() }}</a>
		@endif
		</th>
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
{? $presenter = new Illuminate\Pagination\BootstrapPresenter($elementList); ?}
<ul class="pagination">
{{ $presenter->render() }}
</ul>
@endif
</div>
@else
<div id="element_list_container_{{ $item->getName() }}" class="dnone"></div>
@endif