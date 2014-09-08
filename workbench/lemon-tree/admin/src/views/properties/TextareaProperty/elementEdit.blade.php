<span error="{{ $name }}" class="dashed hand textarea" propertyName="{{ $name }}">{{ $title }}</span>:<br>
@if ($readonly)
{{ Form::textarea($name, null, array('rows' => 8, 'readonly')) }}
@else
{{ Form::textarea($name, null, array('rows' => 8)) }}
@endif