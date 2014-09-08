<span error="{{ $name }}">{{ $title }}</span>:
@if ($readonly)
{{ Form::text($name, null, array('class' => 'prop-number', 'readonly')) }}
@else
{{ Form::text($name, null, array('class' => 'prop-number')) }}
@endif