@extends('admin::layout')

@section('js')
{{ HTML::style('LT/js/calendarview/jquery.calendar.css') }}
{{ HTML::script('LT/js/calendarview/jquery.calendar.js') }}
<script type="text/javascript">
$(function() {
	$('#date_from').calendar({
		triggerElement: '#date_from_show',
		dateFormat: '%Y-%m-%d',
		showHandler: function() { $('#date_from').focus(); }
	});
	$('#date_to').calendar({
		triggerElement: '#date_to_show',
		dateFormat: '%Y-%m-%d',
		showHandler: function() { $('#date_to').focus(); }
	});
});
</script>
@stop

@section('path')
<a href="{{ \URL::route('admin.users') }}">Управление пользователями</a> &rarr; Журнал действий пользователей
@stop

@section('browse')
<h1>Журнал действий пользователей</h1>
{{ Form::open(array('route' => 'admin.users.log', 'method' => 'get', 'id' => 'logForm')) }}
<div class="form-search">
<div class="prop-search" style="width: 350px;">
Тип операции:<br />
<select name="action_type">
<option value=""{{ $actionType ? '' : ' selected' }}>- Все типы -</option>
@foreach ($userActionTypeList as $userActionId => $userActionTypeName)
<option value="{{ $userActionId }}"{{ $actionType == $userActionId ? ' selected' : '' }}>{{ $userActionTypeName }}</option>
@endforeach
</select><br />
</div>
<div class="prop-search" style="width: 350px;">
Комментарий содержит:<br />
{{ Form::text('comments', $comments, array('class' => 'prop-comment')) }}<br />
</div>
<div class="prop-search" style="width: 350px;">
Дата операции:<br />
от <input type="text" class="prop-date" id="date_from" name="date_from" value="{{ $dateFrom ? $dateFrom->format('Y-m-d') : null }}"> <span id="date_from_show" class="hand"><img src="/LT/img/calendar.gif" alt="" /></span>
до <input type="text" class="prop-date" id="date_to" name="date_to" value="{{ $dateTo ? $dateTo->format('Y-m-d') : null }}"> <span id="date_to_show" class="hand"><img src="/LT/img/calendar.gif" alt="" /></span><br />
</div>
<br clear="both" />
</div>
<p>{{ Form::submit('Найти', array('class' => 'btn')) }}</p>
{{ Form::close() }}
@if (sizeof($userActionList))
<table class="element-list">
	<col width="15%" />
	<col width="25%" />
	<col width="50%" />
	<col width="10%" />
	<tr>
		<th>Пользователь</th>
		<th>Тип действия</th>
		<th>Комментарий</th>
		<th>Дата</th>
	</tr>
	@foreach ($userActionList as $userAction)
	<tr>
		<td>{{ $userAction->user->login }}<br /><small class="grey">{{ $userAction->user->first_name }} {{ $userAction->user->last_name }}</small></td>
		<td><a href="{{ $userAction->url }}">{{ $userAction->getActionTypeName() }}</a></td>
		<td>{{ $userAction->comments }}</td>
		<td>{{ $userAction->created_at->format('d.m.Y') }}<br /><small>{{ $userAction->created_at->format('H:i:s') }}</small></td>
	</tr>
	@endforeach
</table>
@if ($userActionList->getLastPage() > 1)
{? $presenter = new Illuminate\Pagination\BootstrapPresenter($userActionList); ?}
<ul class="pagination">
{{ $presenter->render() }}
</ul>
@endif
@else
<p>Действия отсутствуют.</p>
@endif
@stop

