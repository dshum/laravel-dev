<div id="idContainer" class="prop-search">
<span switch="true" name="id" class="dashed hand"><b>ID элемента</b></span>:<br>
<div id="id_block" style="display: {{ isset($id) ? 'block' : 'none' }};">
<input class="prop ename" type="text" id="id" name="id" value=""{{ isset($id) ? '' : 'disabled="disabled"' }}>
</div>
</div>

@if ($propertyList)
@foreach ($propertyList as $propertyName => $property)
@if ($elementSearchView = $property->getElementSearchView())
<div id="{{ $propertyName }}Container" class="prop-search">
{{ $elementSearchView }}
</div>
@endif
@endforeach
@endif

<br clear="both" /><br />