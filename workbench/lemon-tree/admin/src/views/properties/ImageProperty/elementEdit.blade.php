<span error="{{ $name }}">{{ $title }}</span>:<br>
@if ($exists)
<table><tr valign="top">
<td><span class="mini">Загружено изображение: <a href="{{ $src }}" target="_blank">{{ $filename }}</a>, <span title="Размер изображения">{{ $width }}&#215;{{ $height }}</span> пикселов, {{ $filesize }} Кб</span><br />
<img class="pict" src="{{ $src }}" width="{{ $width }}" height="{{ $height }}" alt="{{ $value }}"><br /></td>
</tr></table>
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
	@if ($maxWidth > 0 and $maxHeight > 0)
<small class="red">Максимальный размер изображения {{ $maxWidth }}&#215;{{ $maxHeight }} пикселей</small><br>
	@elseif ($maxWidth > 0)
<small class="red">Максимальная ширина изображения {{ $maxWidth }} пикселей</small><br>
	@elseif ($maxHeight > 0)
<small class="red">Максимальная высота изображения {{ $maxHeight }} пикселей</small><br>
	@endif
	@if ($exists)
{{ Form::checkbox($name.'_drop', 1, false, array('id' => $name.'_drop')) }} {{ Form::label($name.'_drop', 'Удалить') }}
	@endif
@endif