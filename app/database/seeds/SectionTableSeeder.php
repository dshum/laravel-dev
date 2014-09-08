<?php

class SectionTableSeeder extends Seeder {

	public function run()
	{
		Eloquent::unguard();
		
		Eloquent::unguard();

		DB::table('sections')->truncate();

		Section::create(array(
			'name' => 'Доставка',
			'order' => 1,
			'url' => 'delivery',
			'title' => 'Доставка',
			'h1' => 'Доставка',
			'shortcontent' => '',
			'fullcontent' => '<h2>Доставка по Москве</h2>
<p>Заказы стоимостью свыше 2490 рублей в пределах МКАД доставляются <b>бесплатно</b>. Стоимость доставки заказов стоимостью менее 2490 рублей составляет 190 рублей.</p>
<h2>Самовывоз</h2>
<p><b>При самовывозе заказа на сумму от 1000 руб. получите 120 руб. на баланс мобильного телефона.</b></p>
<p>Возможен самовывоз оформленного заказа из пункта выдачи по адресу:<br />Москва, Ковров переулок, д. 8, стр.1, офис №102</p>
<p>Как добраться: метро Римская или Площадь Ильича, выход к ул.  Международная и ул. Рабочая. Далее, по ул. Рабочая до магазина «Продукты  24 часа» и направо на Ковров переулок. Через 300 метров 2-х этажное  здание с большой кирпичной трубой.</p>
<p>Обращаем ваше внимание, что самовывоз осуществляется по предварительной договоренности, то есть после оформления заказа.</p>
<p><img width="554" height="480" src="/i/legolize_office.png" style="border: 1px solid #666" /></p>
<h2>Доставка в Московскую область</h2>
<p>Стоимость доставки за МКАД (включая территорию Новой Москвы) сообщит менеджер при оформлении заказа. Обычно она составляет 350-400 рублей для ближайших к МКАД районов и в города-спутники. Доставка за МКАД габаритных товаров, требующих перевозки автотранспортом — 15 руб./км за МКАД + стандартная стоимость доставки по Московской области.</p>
<h2>Доставка по России</h2>
<p>Доставка по России при сумме заказа свыше 3500 рублей <b>бесплатна</b>. Стоимость доставки заказов стоимостью менее 3500 рублей составляет 290 рублей. Доставка по России осуществляется по предоплате отправлением I класса Почтой России или транспортными компаниями.</p>',
		));

		Section::create(array(
			'name' => 'Способы оплаты',
			'order' => 2,
			'url' => 'payments',
			'title' => 'Способы оплаты',
			'h1' => 'Способы оплаты',
			'shortcontent' => '',
			'fullcontent' => '<p>После первой оплаты заказа покупателю начисляется бонус от 1 до 10% в зависит от покупаемого товара. Бонус бессрочный и его можно использовать для оплаты товаров в любом из наших магазинов при совершении второй и последующих покупок.</p>
<p>Идентификация покупателя происходит после авторизации на сайте.</p>
<h2>Оплата</h2>
<p>Способы оплаты для Москвы и Московской области:</p>
<ul>
<li> Наличными при доставке курьеру</li>
<li> Безналичный расчет</li>
<li>Карта Visa, Mastercard</li>
<li>Webmoney</li>
<li> Терминалы QIWI</li>
</ul>
Способы оплаты для регионов России:<br />
<ul>
<li> Безналичный расчет</li>
<li> Квитанция банка</li>
<li> Яндекс.Деньги</li>
<li> Webmoney</li>
<li> Терминалы QIWI</li>
</ul>
<p>При заказе из региона России предоплата 100%.</p>
<p>После принятия заказа менеджером магазина бронь на заказ держится в течение 2-х недель. Если в течение этого времени оплата за заказ не поступила — заказ считается отмененным.</p>',
		));

		Section::create(array(
			'name' => 'Контактная информация',
			'order' => 3,
			'url' => 'contacts',
			'title' => 'Контактная информация',
			'h1' => 'Контактная информация',
			'shortcontent' => '',
			'fullcontent' => '<p>Москва, Ковров переулок, д. 8, стр.1, офис №102</p>
<p>Как добраться: метро Римская или Площадь Ильича, выход к ул. Международная и ул. Рабочая. Далее, по ул. Рабочая до магазина «Продукты 24 часа» и направо на Ковров переулок. Через 300 метров 2-х этажное здание с большой кирпичной трубой.</p>
<p>ИП  Рау Александр Дмитриевич</p>
<p>ИНН  770904219537</p>
<p>ОГРН 313774609400883</p>
<p>ОКПО 0187166021</p>
<p>Р/сч №40802810600760006949</p>
<p>В ОАО «МОСКОВСКИЙ КРЕДИТНЫЙ БАНК».</p>
<p>БИК 044585659</p>
<p>к/с 30101810300000000659</p>',
		));

	}

}
