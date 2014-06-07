<span error="{{ $name }}">{{ $title }}</span>:<br>
@if ($exists)
<span class="mini">Загружен файл: <a href="{{ $path }}" target="_blank">{{ $filename }}</a>, {{ $filesize }} Кб</span><br>
@endif
<script type="text/javascript">
$(function() {
	$('input:file[name={{ $name }}]').change(function() {
		$('input:checkbox[name="{{ $name }}_drop"]').prop('checked', false);
	});
	$('input:checkbox[name="{{ $name }}_drop"]').click(function() {
		if ($(this).prop('checked') == true) {
			$('input:file[name="{{ $name }}"]').val(null);
		}
	});
});
</script>
@if ( ! $readonly)
{{ Form::file($name, array('class' => 'prop-file')) }}<br>
<small class="red">Максимальный размер файла {{ $maxFilesize }} Кб</small><br>
	@if ($exists)
{{ Form::checkbox($name.'_drop', 1, false, array('id' => $name.'_drop')) }} {{ Form::label($name.'_drop', 'Удалить') }}
	@endif
@endif