<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{{ isset($currentTitle) ? $currentTitle : 'Lemon Tree' }}</title>
{{ HTML::style('packages/lemon-tree/admin/css/default.css') }}
{{ HTML::style('packages/lemon-tree/admin/css/contextmenu.css') }}
{{ HTML::style('packages/lemon-tree/admin/js/jquery/jquery-ui-1.10.4.custom.min.css') }}
{{ HTML::script('packages/lemon-tree/admin/js/jquery/jquery-2.1.1.min.js') }}
{{ HTML::script('packages/lemon-tree/admin/js/jquery/jquery.form.min.js') }}
{{ HTML::script('packages/lemon-tree/admin/js/jquery/jquery.blockUI.js') }}
{{ HTML::script('packages/lemon-tree/admin/js/jquery/jquery-ui-1.10.4.custom.min.js') }}
{{ HTML::script('packages/lemon-tree/admin/js/common.js') }}
{{ HTML::script('packages/lemon-tree/admin/js/contextmenu.js') }}
<script type="text/javascript">
$(function() {

	LT.adminUrl = '{{ URL::route("admin") }}';
	LT.trashUrl = '{{ URL::route("admin.trash") }}';
	LT.deleteUrl = '{{ URL::route("admin.browse.delete") }}';
	LT.restoreUrl = '{{ URL::route("admin.browse.restore") }}';
	LT.movingUrl = '{{ URL::route("admin.moving") }}';
	LT.searchItemUrl = '{{ URL::route("admin.search.item") }}';
	LT.treeOpenUrl = '{{ URL::route("admin.tree.open") }}';
@if (isset($currentElement) && $currentElement)
	LT.currentElement = '{{ $currentElement->getClassId() }}';
	$('div.tree').children('a[classId="'+LT.currentElement+'"]').css('font-weight', 'bold');
@endif

});
</script>
@yield('js')
</head>
<body>
<div id="wrapper">
	<div id="browse" style="{{ $activeTab->show_tree ? 'left: 20%; width: 80%;' : 'left: 0%; width: 100%;' }}">
		<div id="browse-container" class="container">
			<div id="tab-wrapper">
				<ul>
					@foreach ($tabs as $tab)
						@if ($tab->is_active)
							<li class="tab-current">{{ $tab->title }}&nbsp;<a href="{{ \URL::route('admin.tab.delete', array('id' => $tab->id)) }}"><img src="{{ asset('packages/lemon-tree/admin/img/delete-tab.png') }}" alt="" /></a></li>
						@else
						<li><a href="{{ \URL::route('admin.tab', array('id' => $tab->id)) }}">{{ $tab->title }}</a>&nbsp;<a href="{{ \URL::route('admin.tab.delete', array('id' => $tab->id)) }}"><img src="{{ asset('packages/lemon-tree/admin/img/delete-tab.png') }}" alt="" style="position: relative; top: 4px;" /></a></li>
						@endif
					@endforeach
					<li><a href="{{ \URL::route('admin.tab.add') }}"><img src="{{ asset('packages/lemon-tree/admin/img/add-tab.png') }}" alt="" style="position: relative; top: 3px;" /></a></li>
				</ul>
			</div>
			<div id="browse-wrapper">
				<div id="menu-wrapper">
					<table>
						<tr>
							<td><span id="tree-toggler" opened="{{ $activeTab->show_tree ? 'true' : 'open' }}" url="{{ URL::route('admin.tab.toggle', $activeTab->id) }}" class="hand"><img src="{{ asset('packages/lemon-tree/admin/img/tree.png') }}" alt="" /></span></td>
							<td><a href="{{ URL::route('admin') }}"><img src="{{ asset('packages/lemon-tree/admin/img/home.png') }}" alt="" /></a></td>
							<td><a id="button-refresh" href="{{ URL::current() }}"><img src="{{ asset('packages/lemon-tree/admin/img/refresh.png') }}" alt="" /></a></td>
							<td class="path"><div class="path">@yield('path')</div></td>
							<td><a href="{{ URL::route('admin.search') }}"><img src="{{ asset('packages/lemon-tree/admin/img/search.png') }}" alt="" /></a></td>
							<td><a href="{{ URL::route('admin.trash') }}"><img src="{{ asset('packages/lemon-tree/admin/img/trash.png') }}" alt="" /></a></td>
							<td><a href="{{ URL::route('admin.users') }}"><img src="{{ asset('packages/lemon-tree/admin/img/users.png') }}" alt="" /></a></td>
							<td nowrap><img src="{{ asset('packages/lemon-tree/admin/img/profile.png') }}" alt="" class="profile" /><a href="{{ URL::route('admin.profile') }}"><b>{{ $loggedUser->login }}</b></a></td>
							<td><a href="{{ URL::route('admin.logout') }}"><img src="{{ asset('packages/lemon-tree/admin/img/logout.png') }}" alt="" /></a></td>
						</tr>
					</table>
				</div>
				@yield('browse')
				<div class="space"><br /></div>
				{? $site = App::make('site') ?}
				{? $queries = DB::getQueryLog() ?}
				<ol>
				@foreach ($queries as $query)
				<li>{{ $query['time'] / 1000 }} sec. {{ $query['query'] }}</li>
				@endforeach
				</ol>
				<p>Totally: {{ $site->getMicroTime() }} sec, {{ $site->getMemoryUsage() }} Mb</p>
			</div>
		</div>
	</div>
	<div id="tree" style="{{ $activeTab->show_tree ? 'left: 0%;' : 'left: -200%; display: none;' }}">
		<div id="tree-container" class="container">
			{{ $treeView }}
		</div>
	</div>
</div>
</body>
</html>