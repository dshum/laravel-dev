<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>@yield('title')</title>
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
{{ HTML::style('f/style.css') }}
</head>

<body>
	<div>
		<a href="{{ URL::route('firstpage') }}"><img src="/i/logo.gif" width="237" height="123" class="float" alt="setalpm" /></a>
		<div class="topnav">
			<span><strong>Welcome</strong> &nbsp;<a href="{{ URL::route('login') }}">Log in</a> &nbsp; | &nbsp; <a href="{{ URL::route('register') }}">Register</a></span>
			<select>
				<option>Type of Product</option>
				<option>Clothing</option>
				<option>Accessories</option>
				<option>Clothing</option>
				<option>Accessories</option>
			</select>
		    <span>Language:</span> <a href="#"><img src="/i/flag1.jpg" alt="" width="21" height="13" /></a> <a href="#"><img src="/i/flag2.jpg" alt="" width="21" height="13" /></a> <a href="#"><img src="/i/flag3.jpg" alt="" width="21" height="13" /></a>
		</div>
		<ul id="menu">
			<li><a href="{{ URL::route('firstpage') }}"><img src="/i/but1.gif" alt="" width="110" height="32" /></a></li>
			<li><a href="{{ URL::route('login') }}"><img src="/i/but2.gif" alt="" width="110" height="32" /></a></li>
			<li><a href="{{ URL::route('register') }}"><img src="/i/but3.gif" alt="" width="110" height="32" /></a></li>
			<li><a href="{{ URL::route('cabinet') }}"><img src="/i/but4.gif" alt="" width="110" height="32" /></a></li>
			<li><a href="{{ URL::route('cart') }}"><img src="/i/but5.gif" alt="" width="110" height="32" /></a></li>
			<li><a href="{{ URL::route('order') }}"><img src="/i/but6.gif" alt="" width="110" height="32" /></a></li>
		</ul>
	</div>
	<div id="content">
		<div id="sidebar">
			<div id="navigation">
				<ul>
					<li><a href="{{ URL::route('firstpage') }}">Главная</a></li>
					<li><a href="{{ URL::route('novelty') }}">Новинки</a></li>
					<li><a href="{{ URL::route('special') }}">Спецпредложения</a></li>
					<li><a href="{{ URL::route('delivery') }}">Доставка</a></li>
					<li><a href="{{ URL::route('payments') }}">Способы оплаты</a></li>
					<li><a href="{{ URL::route('contacts') }}">Контакты</a></li>
				</ul>
				<div id="cart">
					<strong>Shopping cart:</strong> <br /> 0 items
				</div>
			</div>
			<div>
				<img src="/i/title1.gif" alt="" width="233" height="41" /><br />
				<ul class="categories">
@foreach ($categoryList as $category)
					<li><a href="{{ $category->getHref() }}">{{ $category->name }}</a></li>
@endforeach
				</ul>
				<img src="/i/title2.gif" alt="" width="233" height="41" /><br />
				<div class="review">
					<a href="#"><img src="/i/pic1.jpg" alt="" width="181" height="161" /></a><br />
					<a href="#">Product 07</a><br />
					<p>Dolor sit amet, consetetur sadipscing elitr, seddiam nonumy eirmod tempor. invidunt ut labore et dolore magna </p>
					<img src="/i/stars.jpg" alt="" width="118" height="20" class="stars" />
				</div>
			</div>
		</div>
		<div id="main">
			<img src="/i/photo.jpg" alt="" width="682" height="334" border="0" usemap="#Map" />
            <br />
			<div id="inside">
				<img src="/i/title3.gif" alt="" width="159" height="15" /><br />
				<div class="info">
					<img src="/i/pic2.jpg" alt="" width="159" height="132" />
					<p>Dolor sit amet, consetetur sadipscing elitr, seddiam nonumy eirmod tempor. invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. Lorem ipsum dolor sit amet, consetetur sadip- scing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. Lorem ipsum dolor sit amet, consetetur. </p>
					<a href="#" class="more"><img src="/i/more.gif" alt="" width="106" height="28" /></a>
				</div>
				<img src="/i/title4.gif" alt="" width="159" height="17" /><br />
				<div id="items">
@foreach ($categoryList as $k => $category)
					<div class="item{{ $k % 3 == 1 ? ' center' : '' }}">
						<a href="{{ $category->getHref() }}"><img src="/i/item1.jpg" width="213" height="192" /></a><br />
						<p><a href="{{ $category->getHref() }}">{{ $category->name }}</a></p>
					</div>
@endforeach
				</div>
			</div>
		</div>
	</div>
	<div id="footer">
		<img src="/i/cards.jpg" alt="" width="919" height="76" />
		<ul>
			<li><a href="{{ URL::route('firstpage') }}">Home page</a> |</li>
			<li><a href="#">New Products</a> |</li>
			<li><a href="#">All Products</a> |</li>
			<li><a href="#">Reviews</a> |</li>
			<li><a href="#">F.A.Q.</a> |</li>
			<li><a href="#">Contacts</a></li>
		</ul>
		<p>Copyright ©. All rights reserved. Design by <a href="http://www.bestfreetemplates.info" title="Free CSS Templates" target="_blank" class="bft">BFT</a></p>
	</div>
<map name="Map">
	<area shape="rect" coords="16,306,159,326" href="#">
</map>
<div id="log">
<?php
$site = App::make('site');
$queries = DB::getQueryLog();
?>
<ol>
<?php foreach ($queries as $query) :?>
<li><b><?=$query['time']?> ms.</b> <?=$query['query']?></li>
<?php endforeach; ?>
</ol>
<p>Totally: <?=$site->getMicroTime()?> sec, <?=$site->getMemoryUsage()?> Mb</p>
</div>
</body>
</html>