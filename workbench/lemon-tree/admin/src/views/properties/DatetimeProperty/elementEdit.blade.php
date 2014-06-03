@if ($readonly)
<span error="{{ $name }}">{{ $title }}</span>: {{ $value ? $value->format('d.m.Y, H:i:s') : 'не определено' }}
@else
<script type="text/javascript">
$(function() {
	$('#{{ $name }}_date').calendar({
		triggerElement: '#{{ $name }}_show',
		dateFormat: '%Y-%m-%d',
		selectHandler: function() {
			$('#{{ $name }}_show').html(this.date.print('%e %G %Y года'));
			$('#{{ $name }}_date').val(this.date.print(this.dateFormat));
			LT.Edit.setTimestamp('{{ $name }}');
		}
	});

	$('#{{ $name }}_hour').keyup(function() {
		LT.Edit.setTimestamp('{{ $name }}');
	}).change(function() {
		LT.Edit.setTimestamp('{{ $name }}');
	});

	$('#{{ $name }}_minute').keyup(function() {
		LT.Edit.setTimestamp('{{ $name }}');
	}).change(function() {
		LT.Edit.setTimestamp('{{ $name }}');
	});

	$('#{{ $name }}_second').keyup(function() {
		LT.Edit.setTimestamp('{{ $name }}');
	}).change(function() {
		LT.Edit.setTimestamp('{{ $name }}');
	});
});
</script>
{{ Form::hidden($name, null, array('id' => $name)) }}
{{ Form::hidden($name.'_date', $value ? $value->format('Y-m-d') : null, array('id' => $name.'_date')) }}
<span error="{{ $name }}">{{ $title }}</span>: <span id="{{ $name }}_show" class="dashed hand">{{ $value ? sprintf('%d %s %04d года', $value->day, RussianTextUtils::getMonthInGenitiveCase($value->month), $value->year) : 'не определено' }}</span>,
{{ Form::text($name.'_hour', $value ? $value->format('H') : null, array('id' => $name.'_hour', 'class' => 'prop-time', 'maxlength' => 2))}} :
{{ Form::text($name.'_minute', $value ? $value->format('i') : null, array('id' => $name.'_minute', 'class' => 'prop-time', 'maxlength' => 2))}} :
{{ Form::text($name.'_second', $value ? $value->format('s') : null, array('id' => $name.'_second', 'class' => 'prop-time', 'maxlength' => 2))}}
@endif