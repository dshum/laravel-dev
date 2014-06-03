<span error="{{ $name }}">{{ $title }}</span>: {{ RussianTextUtils::friendlyFileSize(strlen($value)) }}<br>
@if ( ! $readonly)
{{ Form::file($name) }}<br>
<small class="red">Максимальный размер файла {{ LemonTree\BinaryProperty::MAX_SIZE }} Кб</small><br>
	@if ($value)
{{ Form::checkbox($name.'_drop', 1, false, array('id' => $name.'_drop')) }} {{ Form::label($name.'_drop', 'Очистить') }}
	@endif
@endif