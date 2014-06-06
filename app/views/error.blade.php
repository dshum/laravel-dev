<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>@yield('title')</title>
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
{{ HTML::style('f/style.css') }}
</head>
<body>
	<div>
		<a href="{{ URL::route('firstpage') }}"><img src="/i/logo.gif" width="237" height="123" class="float" alt="setalpm" /></a>
	</div>
	<div id="content">
		<h1>@yield('h1')</h1><br />
		@yield('content')
		<p><a href="{{ URL::route('firstpage') }}">Главная страница</a></p>
	</div>
</body>
</html>