<span error="{{ $name }}">{{ $title }}</span>:
@if ($readonly)
{{ Form::password($name, array('class' => 'prop-pass', 'readonly')) }}
@else
{{ Form::password($name, array('class' => 'prop-pass')) }}
@endif