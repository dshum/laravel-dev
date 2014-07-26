@extends('admin::layout')

@section('js')
<script type="text/javascript">
$(function() {

});
</script>
@stop

@section('path')
@if (isset($activeGroup))
<a href="{{ \URL::route('admin.users') }}">Управление пользователями</a> &rarr; {{ $activeGroup->name }}
@else
Управление пользователями
@endif
@stop

@section('browse')
@if (isset($activeGroup))
<h1>Пользователи группы <b>{{ $activeGroup->name }}</b></h1>
@else
<h1>Управление пользователями</h1>
<p>Добавить:
<a href="{{ URL::route('admin.group.create') }}">Группа</a>, <a href="{{ URL::route('admin.user.create') }}">Пользователь</a>
</p>
@endif
@if ( ! isset($activeGroup) && sizeof($groupList))
<h2>Группы</h2>
<table class="element-list">
	<tr>
		<th class="first"></th>
		<th>Название</th>
		<th>Доступ по умолчанию</th>
		<th>Доступ к элементам</th>
		<th>Создана</th>
		<th>Обновлена</th>
		<th><img src="/LT/img/delete-title.png" alt="" /></th>
	</tr>
	@foreach ($groupList as $group)
	<tr>
		<td class="first"><a href="{{ URL::route('admin.users.group', $group->id) }}"><img src="/LT/img/file.png" alt="" style="vertical-align: middle;" /></a></td>
		<td><img src="/LT/img/edit.png" alt="" style="vertical-align: middle; margin-right: 5px;" /><a href="{{ \URL::route('admin.group', $group->id) }}">{{ $group->name }}</a></td>
		<td><a href="{{ \URL::route('admin.group.items', $group->id) }}">Редактировать</a></td>
		<td><a href="{{ \URL::route('admin.group.elements', $group->id) }}">Редактировать</a></td>
		<td>{{ $group->created_at->format('d.m.Y') }}<br /><small>{{ $group->created_at->format('H:i:s') }}</small></td>
		<td>{{ $group->updated_at->format('d.m.Y') }}<br /><small>{{ $group->updated_at->format('H:i:s') }}</small></td>
		<td><a href="{{ \URL::route('admin.group.delete', $group->id) }}" class="btn"><img src="/LT/img/delete.png" alt="" /></a></td>
	</tr>
	@endforeach
</table>
@endif
@if (sizeof($userList))
<h2>Пользователи</h2>
<table class="element-list">
	<tr>
		<th class="first"></th>
		<th>Логин</th>
		<th>Email</th>
		<th>Имя</th>
		<th>Фамилия</th>
		<th>Группы</th>
		<th>Создан</th>
		<th>Последний вход</th>
		<th><img src="/LT/img/delete-title.png" alt="" /></th>
	</tr>
@foreach ($userList as $user)
	<tr>
		<td class="first"><a href="{{ URL::route('admin.users.user', $user->id) }}"><img src="/LT/img/file.png" alt="" style="vertical-align: middle;" /></a></td>
		<td><img src="/LT/img/edit.png" alt="" style="vertical-align: middle; margin-right: 5px;" /><a href="{{ \URL::route('admin.user', $user->id) }}">{{ $user->login }}</a></td>
		<td>{{ $user->email }}</td>
		<td>{{ $user->first_name }}</td>
		<td>{{ $user->last_name }}</td>
		<td>
	@foreach ($groupList as $group)
		@if (isset($groupMap[$user->id][$group->id]))
			<a href="{{ URL::route('admin.group', $group->id) }}">{{ $group->name }}</a><br />
		@endif
	@endforeach
	@if ($user->isSuperUser())
			Суперпользователь<br />
	@endif
		</td>
		<td>{{ $user->created_at->format('d.m.Y') }}<br /><small>{{ $user->created_at->format('H:i:s') }}</small></td>
		<td>@if($user->last_login){{ $user->last_login->format('d.m.Y') }}<br /><small>{{ $user->last_login->format('H:i:s') }}</small>@endif</td>
		<td><a href="{{ \URL::route('admin.user.delete', $user->id) }}" class="btn"><img src="/LT/img/delete.png" alt="" /></a></td>
	</tr>
@endforeach
</table>
<p><a href="{{ URL::route('admin.users.log') }}">Журнал действий пользователей</a></p>
@else
<p>Пользователи отсутствуют.</p>
@endif
@stop

