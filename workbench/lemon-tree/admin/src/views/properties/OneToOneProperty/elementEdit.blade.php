<span error="{{ $name }}">{{ $title }}</span>:
@if ($readonly)
	@if ($value)
		<a href="{{ URL::route('admin.edit', array('class' => get_class($value), 'id' => $value->id)) }}">{{ $value->$mainProperty }}</a>
	@else
		Не определено
	@endif
@else
	@if (isset($treeView))
		<span id="{{ $name }}_title" onetoone="title" name="{{ $name }}" class="dashed hand">{{ $value ? $value->$mainProperty : 'Не определено' }}</span>
		<div id="{{ $name }}_block" class="blank dnone one">
			@if ( ! $required)
			<div class="undefined">{{ Form::radio($name, '', $value ? false : true, array('id' => $name.'_0', 'onetoone' => 'radio')) }} {{ Form::label($name.'_0', 'Не определено') }}</div>
			@endif
			{{ $treeView }}
		</div>
	@else
		{{ Form::hidden($name, $value ? $value->id : null) }}
		@if ($value)
			<span id="{{ $name }}_show"><a href="{{ URL::route('admin.edit', array('class' => get_class($value), 'id' => $value->id)) }}">{{ $value->$mainProperty }}</a></span>
		@else
			<span id="{{ $name }}_show">Не определено</span>
		@endif
		&nbsp;{{ Form::text($name.'_name', 'Введите ID или название', array('class' => 'prop-mini grey', 'onetoone' => 'name', 'url' => $url, 'propertyName' => $name, 'default' => 'Введите ID или название')) }}
		&nbsp;&nbsp;<span id="{{ $name }}_reset" onetoone="reset" propertyName="{{ $name }}" class="small dashed hand">Очистить</span>
	@endif
@endif