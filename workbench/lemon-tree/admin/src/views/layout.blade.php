<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Lemon Tree</title>
{{ HTML::style('LT/css/default.css') }}
{{ HTML::style('LT/js/jquery/jquery-ui-1.10.4.custom.min.css') }}
{{ HTML::script('LT/js/jquery/jquery-2.1.1.min.js') }}
{{ HTML::script('LT/js/jquery/jquery.form.min.js') }}
{{ HTML::script('LT/js/jquery/jquery.blockUI.js') }}
{{ HTML::script('LT/js/jquery/jquery-ui-1.10.4.custom.min.js') }}
{{ HTML::script('LT/js/common.js') }}
<script type="text/javascript">
$(function() {

	$('body').on('click', 'div.plus', function() {
		var node = $(this).attr('node');
		var opened = $(this).attr('opened');

		$.post(
			"{{ URL::route('admin.tree.open') }}",
			{classId: node, open: opened},
			function(data) {
				if (opened == 'open') {
					$('div.padding[node="'+node+'"]').html(data).slideDown('fast', function() {
						$('div.plus[node="'+node+'"]').html('<div>-</div>').attr('opened', 'true');
					});
				} else if (opened == 'true') {
					$('div.padding[node="'+node+'"]').slideUp('fast', function() {
						$('div.plus[node="'+node+'"]').html('<div>+</div>').attr('opened', 'false');
					});
				} else if (opened == 'false') {
					$('div.padding[node="'+node+'"]').slideDown('fast', function() {
						$('div.plus[node="'+node+'"]').html('<div>-</div>').attr('opened', 'true');
					});
				}
			},
			'html'
		);

	});

});
</script>
@yield('js')
</head>
<body>
<div id="wrapper">
	<div id="tab-wrapper">
		<ul>
			<li class="tab-current">Lemon Tree</li>
			<li><a href="#">+</a></li>
		</ul>
	</div>
	<div id="menu-wrapper">
		<ul>
			@if (Route::currentRouteName() == 'admin')<li class="current_page_item"><a>Lemon Tree</a></li>@else<li><a href="{{ URL::route('admin') }}">Lemon Tree</a></li>@endif
			<li><a href="{{ URL::current() }}">Обновить</a></li>
			<li><a href="{{ URL::route('admin.search') }}">Поиск</a></li>
			<li><a href="{{ URL::route('admin.trash') }}">Корзина</a></li>
			<li><a href="{{ URL::route('admin.users') }}">Пользователи</a></li>
			@if (strpos(Route::currentRouteName(), 'admin.profile') !== false)<li class="current_page_item"><a>{{ $loggedUser->login }}</a></li>@else<li><a href="{{ URL::route('admin.profile') }}">{{ $loggedUser->login }}</a></li>@endif
			<li><a href="{{ URL::route('admin.logout') }}">Выйти</a></li>
		</ul>
	</div>
	<div id="page">
		<div id="tree">
			<div id="tree-container" class="container">
				{{ $treeView }}
			</div>
		</div>
		<div id="browse">
			<div id="browse-container" class="container">
				<div class="path">@yield('path')</div>
				@yield('browse')
				<div class="space"></div>
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
</div>
</body>
</html>