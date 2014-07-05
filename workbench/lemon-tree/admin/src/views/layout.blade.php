<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{{ isset($currentTitle) ? $currentTitle : 'Lemon Tree' }}</title>
{{ HTML::style('LT/css/default.css') }}
{{ HTML::style('LT/css/contextmenu.css') }}
{{ HTML::style('LT/js/jquery/jquery-ui-1.10.4.custom.min.css') }}
{{ HTML::script('LT/js/jquery/jquery-2.1.1.min.js') }}
{{ HTML::script('LT/js/jquery/jquery.form.min.js') }}
{{ HTML::script('LT/js/jquery/jquery.blockUI.js') }}
{{ HTML::script('LT/js/jquery/jquery-ui-1.10.4.custom.min.js') }}
{{ HTML::script('LT/js/common.js') }}
{{ HTML::script('LT/js/contextmenu.js') }}
<script type="text/javascript">
$(function() {

	LT.adminUrl = '{{ URL::route("admin") }}';

	$('body').on('click', 'div.plus[node], div.minus[node]', function() {
		var node = $(this).attr('node');
		var opened = $(this).attr('opened');

		$.post(
			"{{ URL::route('admin.tree.open') }}",
			{classId: node, open: opened},
			function(data) {
				if (opened == 'open') {
					$('div.padding[node="'+node+'"]').html(data).slideDown('fast', function() {
						$('div.plus[node="'+node+'"]').removeClass('plus').addClass('minus').attr('opened', 'true');
					});
				} else if (opened == 'true') {
					$('div.padding[node="'+node+'"]').slideUp('fast', function() {
						$('div.minus[node="'+node+'"]').removeClass('minus').addClass('plus').attr('opened', 'false');
					});
				} else if (opened == 'false') {
					$('div.padding[node="'+node+'"]').slideDown('fast', function() {
						$('div.plus[node="'+node+'"]').removeClass('plus').addClass('minus').attr('opened', 'true');
					});
				}
			},
			'html'
		);

	});

	$('#tree-toggler').click(function() {
		var opened = $(this).attr('opened');

		if (opened == 'true') {
			$('#tree').animate({left: '-20%'}, 250);
			$('#browse').animate({left: '0%', width: '100%'}, 250, function() {
				$('#tree-toggler').attr('opened', 'false');
			});
		} else if (opened == 'false') {
			$('#tree').animate({left: '0%'}, 250);
			$('#browse').animate({left: '20%', width: '80%'}, 250, function() {
				$('#tree-toggler').attr('opened', 'true');
			});
		}

		$.post(
			"{{ URL::route('admin.tab.toggle', $activeTab->id) }}",
			{open: opened},
			function(data) {
				if (opened == 'open') {
					$('#tree').css('left', '-20%');
					$('#tree-container').html(data);
					$('#tree').animate({left: '0%'}, 250);
					$('#browse').animate({left: '20%', width: '80%'}, 250, function() {
						$('#tree-toggler').attr('opened', 'true');
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
	<div id="browse" style="{{ $activeTab->show_tree ? 'left: 20%; width: 80%;' : 'left: 0%; width: 100%;' }}">
		<div id="browse-container" class="container">
			<div id="tab-wrapper">
				<ul>
					@foreach ($tabs as $tab)
						@if ($tab->is_active)
							<li class="tab-current">{{ $tab->title }}&nbsp;<a href="{{ \URL::route('admin.tab.delete', array('id' => $tab->id)) }}"><img src="/LT/img/delete-tab.png" alt="" style="position: relative; top: 4px;" /></a></li>
						@else
						<li><a href="{{ \URL::route('admin.tab', array('id' => $tab->id)) }}">{{ $tab->title }}</a>&nbsp;<a href="{{ \URL::route('admin.tab.delete', array('id' => $tab->id)) }}"><img src="/LT/img/delete-tab.png" alt="" style="position: relative; top: 4px;" /></a></li>
						@endif
					@endforeach
					<li><a href="{{ \URL::route('admin.tab.add') }}"><img src="/LT/img/add-tab.png" alt="" style="position: relative; top: 3px;" /></a></li>
				</ul>
			</div>
			<div id="browse-wrapper">
				<div id="menu-wrapper">
					<table border="0" style="width: 100%;">
						<tr>
						<td style="padding-right: 15px;"><span id="tree-toggler" opened="{{ $activeTab->show_tree ? 'true' : 'open' }}" class="dashed hand">Дерево</span></td>
						<td style="padding-right: 15px;"><a href="{{ URL::route('admin') }}">LemonTree</a></td>
						<td style="padding-right: 15px;"><a href="{{ URL::current() }}">Обновить</a></td>
						<td style="padding-right: 15px; width: 90%;"><div class="path">@yield('path')</div></td>
						<td style="padding-right: 15px;"><a href="{{ URL::route('admin.search') }}">Поиск</a></td>
						<td style="padding-right: 15px;"><a href="{{ URL::route('admin.trash') }}">Корзина</a></td>
						<td style="padding-right: 15px;"><a href="{{ URL::route('admin.users') }}">Пользователи</a></td>
						<td style="padding-right: 15px;" nowrap><a href="{{ URL::route('admin.profile') }}" style="font-weight: bold;">{{ $loggedUser->login }}</a></td>
						<td style="padding-right: 15px;"><a href="{{ URL::route('admin.logout') }}">Выход</a></td>
						</tr>
					</table>
				</div>
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
	<div id="tree" style="{{ $activeTab->show_tree ? 'left: 0%;' : 'left: -200%;' }}">
		<div id="tree-container" class="container">
			{{ $treeView }}
		</div>
	</div>
</div>
</body>
</html>