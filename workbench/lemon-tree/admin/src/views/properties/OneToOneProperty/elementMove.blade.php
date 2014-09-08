<span error="{{ $name }}">{{ $title }}</span>:
@if (isset($treeView))
	<span id="{{ $name }}_title" onetoone="title" name="{{ $name }}" class="dashed hand">{{ ! $element ? 'Сохранить' : ($value ? $value->$mainProperty : 'Не определено') }}</span>
	<div id="{{ $name }}_block" class="blank dnone">
		@if ( ! $element)
			{{ Form::radio($name, -1, $element ? false : true, array('id' => $name.'__1', 'onetoone' => 'radio')) }} {{ Form::label($name.'__1', 'Сохранить') }}<br />
		@endif
		@if ( ! $required)
			{{ Form::radio($name, '', ! $element || $value ? false : true, array('id' => $name.'_0', 'onetoone' => 'radio')) }} {{ Form::label($name.'_0', 'Не определено') }}<br />
		@endif
		{{ $treeView }}
	</div>
@else
	@if ( ! $element)
		{{ Form::hidden($name, -1) }}
		<span id="{{ $name }}_show">Сохранить</span>
	@elseif ( ! $value)
		{{ Form::hidden($name, null) }}
		<span id="{{ $name }}_show">Не определено</span>
	@else
		{{ Form::hidden($name, $value->id) }}
		<span id="{{ $name }}_show"><a href="{{ URL::route('admin.edit', array('class' => get_class($value), 'id' => $value->id)) }}">{{ $value->$mainProperty }}</a></span>
	@endif
	{? $url = URL::route('admin.hint', array('class' => $relatedClass)) ?}
	&nbsp;{{ Form::text($name.'_name', 'Введите ID или название', array('class' => 'prop-mini grey', 'onetoone' => 'name', 'url' => $url, 'propertyName' => $name, 'default' => 'Введите ID или название')) }}
	&nbsp;&nbsp;<span id="{{ $name }}_reset" onetoone="reset" propertyName="{{ $name }}" class="small dashed hand">Очистить</span>
@endif