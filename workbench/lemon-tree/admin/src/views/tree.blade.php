@foreach ($treeItemList as $itemName => $item)
<small><b>{{ $item->getTitle() }}</b></small>
	@foreach ($treeItemElementList[$itemName] as $element)
<div>
		@if (isset($treeView[$element->getClassId()]))
	<div class="plus" node="{{ $element->getClassId() }}" opened="true"><div>-</div></div>
		@elseif (isset($treeCount[$element->getClassId()]) && $treeCount[$element->getClassId()] > 0)
	<div class="plus" node="{{ $element->getClassId() }}" opened="open"><div>+</div></div>
		@else
	<div class="plus-empty"></div>
		@endif
	<a href="{{ URL::route('admin.browse', array('class' => $element->getClass(), 'id' => $element->id)) }}">{{ $element->{$item->getMainProperty()} }}</a>
	<div class="padding{{ isset($tree[$element->getClassId()]) ? '' : ' dnone'}}" node="{{ $element->getClassId() }}">
		{{ isset($treeView[$element->getClassId()]) ? $treeView[$element->getClassId()] : null }}
	</div>
</div>
	@endforeach
@endforeach