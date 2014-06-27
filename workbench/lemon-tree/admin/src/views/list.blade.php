<h2>{{ $item->getTitle() }}</h2>
<table class="element-list">
	<tr>
		<th class="first">↑↓</th>
	@foreach ($itemPropertyList[$itemName] as $propertyName => $property)
		<th><a href="">{{ $property->getTitle() }}</a></th>
	@endforeach
		<th class="last">{{ Form::checkbox('checkAll[]', $item->getName(), false, array('item' => $item->getName(), 'title' => 'Отметить все')) }}</th>
	</tr>
	@foreach ($itemElementList[$itemName] as $element)
	<tr>
		<td class="first"><a href="{{ URL::route($route, array('class' => get_class($element), 'id' => $element->id)) }}">▒</a></td>
		@foreach ($itemPropertyList[$itemName] as $propertyName => $property)
			@if ($property->isMainProperty())
		<td>¶ <a href="{{ URL::route('admin.edit', array('class' => get_class($element), 'id' => $element->id)) }}" edit="true">{{ $element->$propertyName }}</a></td>
			@else
		<td>{{ $property->setElement($element)->getElementListView() }}</td>
			@endif
		@endforeach
		<td class="last">{{ Form::checkbox('check[]', $element->getClassId(), false, array('item' => $item->getName(), 'title' => 'Отметить')) }}</td>
	</tr>
	@endforeach
</table>
{{ $itemElementList[$itemName] instanceof Paginator ? $itemElementList[$itemName]->links() : null }}