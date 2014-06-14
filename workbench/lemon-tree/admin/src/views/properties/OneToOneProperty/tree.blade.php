@foreach ($treeItemList as $itemName => $item)
	<small><b>{{ $item->getTitle() }}</b></small>
	@foreach ($treeItemElementList[$itemName] as $element)
		<div>
		@if (isset($treeView[$element->getClassId()]))
			<div class="plus" node1="{{ $element->getClassId() }}" opened="true"><div>-</div></div>
		@elseif (isset($treeCount[$element->getClassId()]) && $treeCount[$element->getClassId()] > 0)
			<div class="plus" node1="{{ $element->getClassId() }}" itemName="{{ $currentProperty->getItem()->getName() }}" propertyName="{{ $currentProperty->getName() }}" opened="open"><div>+</div></div>
		@else
			<div class="plus-empty"></div>
		@endif
		@if ($itemName == $currentProperty->getRelatedClass())
			{{ Form::radio($currentProperty->getName(), $element->id, $value && $value->id == $element->id ? true : false, array('id' => $currentProperty->getName().'_'.$element->id, 'onetoone' => 'radio')) }} {{ Form::label($currentProperty->getName().'_'.$element->id, $element->{$item->getMainProperty()}) }}<br />
		@else
			<span>{{ $element->{$item->getMainProperty()} }}</span><br />
		@endif
			<div class="padding{{ isset($parents[$element->getClassId()]) ? '' : ' dnone' }}" node1="{{ $element->getClassId() }}">
			{{ isset($treeView[$element->getClassId()]) ? $treeView[$element->getClassId()] : null }}
			</div>
		</div>
	@endforeach
@endforeach