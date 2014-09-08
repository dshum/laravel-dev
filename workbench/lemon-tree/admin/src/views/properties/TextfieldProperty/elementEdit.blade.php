<span error="{{ $name }}">{{ $title }}</span>:<br>
@if ($readonly)
{{ Form::text($name, null, array('readonly')) }}
@else
{{ Form::text($name) }}
@endif