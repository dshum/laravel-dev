@if ( ! $currentElement)
<div class="lemon tree"><a href="{{ URL::route('admin') }}">Корень сайта</a></div>
@endif
@foreach ($treeItemList as $itemName => $item)
<div class="item">{{ $item->getTitle() }}</div>
	@foreach ($treeItemElementList[$itemName] as $element)
<div class="tree">
		@if (isset($treeView[$element->getClassId()]))
	<div class="minus" node="{{ $element->getClassId() }}" opened="true"></div>
		@elseif (isset($treeCount[$element->getClassId()]) && $treeCount[$element->getClassId()] > 0)
	<div class="plus" node="{{ $element->getClassId() }}" opened="open"></div>
		@else
	<div class="empty"></div>
		@endif
	<a href="{{ $element->getBrowseUrl() }}" classId="{{ $element->getClassId() }}">{{ $element->{$item->getMainProperty()} }}</a>
	<div class="padding{{ isset($tree[$element->getClassId()]) ? '' : ' dnone'}}" node="{{ $element->getClassId() }}">
		{{ isset($treeView[$element->getClassId()]) ? $treeView[$element->getClassId()] : null }}
	</div>
</div>
	@endforeach
@endforeach