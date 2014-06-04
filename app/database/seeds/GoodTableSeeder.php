<?php

class GoodTableSeeder extends Seeder {

	public function run()
	{
		DB::table('goods')->truncate();

		Good::create(array(
			'name' => 'Офисное массажное кресло MSO',
			'order' => 1,
			'code' => '92',
			'supplier_price' => 15931,
			'price' => 24255,
			'shortcontent' => '<ul>
<li>удобное и практичное кресло для проведения сеансов массажа, выполненное из высококачественного заменителя кожи</li>
<li>5 массажных участков;</li>
<li>проведение различных видов массажа: объемный массаж шиацу, роликовый и вибрационный массаж;</li>
<li>подогрев спинки и сиденья (отдельно или во время сеанса массажа);</li>
<li>встроенный автоматический таймер на 15 минут.</li>
</ul>',
			'fullcontent' => '<p>Офисное массажное кресло «medisana MSO» предоставляет возможность расслабиться, снять усталость и напряжение прямо на своем рабочем месте. Такое кресло крайне необходимо тем людям, которые ведут «сидячий образ жизни» и большую часть своего времени проводят на работе. Оно не только обеспечивает комфортные и удобные условия, но дарит прекрасное состояние и самочувствие даже после тяжелого и напряженного рабочего дня. Комфортное расслабление позволяет намного легче переживать стрессовые ситуации.</p>
<h2>Особенности массажного кресла:</h2>
<ul>
<li>изысканный дизайн, удобная, мягкая подушка и сиденье</li>
<li>угол наклона сиденья регулируется</li>
<li>массажное кресло оснащено трехмерными вращающимися массажными головками для проведения сеансов 3D массажа, что позволяет Вам заменить сильные руки профессионального массажиста.</li>
<li>3 уровня интенсивности вибрационного массажа.</li>
<li>5 массажных зон</li>
<li>уровень инфракрасного прогрева регулируемый</li>
<li>в сиденье трехступенчатый вибрационный массаж</li>
<li>прибор управления массажным креслом – беспроводной, встроенный в подлокотник</li>
<li>автоматическое отключение</li>
</ul>
<p>Максимальный уровень нагрузки 120 кг. Эксплуатировать массажное офисное кресло необходимо согласно предписаниям инструкции по эксплуатации. Хранить устройство рекомендуется в сухом месте, при температуре воздуха от 0 до 40°C.</p>
<h2>Технические характеристики массажного кресла:</h2>
<ul>
<li>Напряжение — 100-240 В.</li>
<li>Частота – 50-60 Гц.</li>
<li>Мощность – 60 Вт.</li>
<li>Длина кабеля – около 2.50 м.</li>
<li>Размер — 118 х 77 х 61 см.</li>
<li>Вес – 23 кг.</li>
<li>Максимальная длительность работы – 15 минут.</li>
<li>Страна производитель – Германия.</li>
<li>Гарантийный срок – 3 года.</li>
</ul>',
			'category_id' => 1,
			'subcategory_id' => 1,
			'good_brand_id' => 2,
		));

		Good::create(array(
			'name' => 'Массажное кресло Casada Senso 2',
			'order' => 2,
			'code' => '18',
			'supplier_price' => 35640,
			'price' => 49000,
			'shortcontent' => '<ul>
<li>возможность запуска оптимального режима (релаксация, терапия, вибрация);</li>
<li>встроенный режим вибрации в области сидения и в подножке кресла;</li>
<li>возможность регулировки интенсивности воздействия массажных роликов;</li>
<li>два режима регулирования зоны вращения массажных головок по ширине;</li>
<li>встроенный таймер.</li>
</ul>',
			'fullcontent' => '<p>Массажное кресло – доступная роскошь, которая в считанные минуты приведет уставший организм в норму.</p>
<p>Массажное кресло Senso 2 (бренд Casada, пр-во Германия) обладает неповторимым массажным механизмом. В отличие от предшествующей модели, четыре массажирующих ролика кресла Senso 2 передвигаются вдоль позвоночного столба. Это значительно усиливает лечебное воздействие на напряженную и уставшую спину человека.</p>
<h2>Особенности модели:</h2>
<p>Senso 2 обладает как автоматическими, так и ручными программами для спины.</p>
<p>Среди автоматических режимов – «Релаксация» (идеально подходит для расслабления и снятия стресса) и «Терапия» (снимает напряжение в мышцах спины).</p>
<p>Ручные режимы («Весь», «Верх», «Низ», «Сиденье», «Икры») позволяют выбрать ту часть тела, на которую будет оказано наибольшее массирующее воздействие.</p>
<p>Кстати, режим вибрации комбинируется с любым пунктом ручной программы.</p>
<p>Благодаря продуманной конструкции устройства, вы можете проводить процедуру массажа как в сидячем, так и в лежачем положении. Для большего комфорта возможна регулировка наклона спинки кресла (диапазон 110° — 150°) и угла подставки для ног (диапазон 0° — 90°).</p>
<p>Удобство модели обеспечивается простым пультом управления. Он отличается светодиодными индикаторами, благодаря которым вы можете регулировать массажный механизм даже при полной темноте помещения.</p>',
			'absent' => true,
			'category_id' => 1,
			'subcategory_id' => 1,
			'good_brand_id' => 1,
		));

		Good::create(array(
			'name' => 'Массажное кресло Senator 2',
			'order' => 3,
			'code' => '19',
			'supplier_price' => 31590,
			'price' => 45000,
			'shortcontent' => '<ul>
<li>возможность настройки спинки кресла в диапазоне от 105° до 145°;</li>
<li>допустимый наклон подножки в диапазоне 0° до 90°;</li>
<li>возможность запуска одной из двух функций массажа (роликовой/ разминающей или вибро);</li>
<li>простой пульт управления;</li>
<li>возможность выбора автоматической или ручной программы массажа для зоны спины.</li>
</ul>',
			'fullcontent' => '<p>Массажное кресло Senator 2 – оригинальная по дизайну и доступная по цене модель современного немецкого массажного оборудования.</p>
<p>Целебная сила массажа известна с давних времен. Кресло Senator 2 – настоящая находка для тех, кто заботится о своем здоровье. Универсальный дизайн и возможность выбора цветового решения кресла позволяет ему органично смотреться в любом интерьере.</p>
<p> </p>
<h2>Особенности Senator 2:</h2>
<p>Универсальность модели в том, что она позволяет проводить разные процедуры массажа: комфортный расслабляющий (программа Relax), интенсивный (программа Seat) или лечебно—профилактический (программа Theraphy). В зависимости от самочувствия вы можете быстро расслабить напряженные мышцы и снять стресс или же ощутить прилив бодрости и сил.</p>
<p>Режим «вибромассажа» можно установить как для спины, так и для ног. Для достижения большего эффекта пользователь может отрегулировать интенсивность массажного воздействия (2 режима) и увеличивать или уменьшать зону вращения четырех массажных роликов по ширине.</p>
<p>Встроенный таймер помогает полностью расслабиться во время процедуры: автоматическое выключение оборудования происходит через 15 минут работы, ручное – спустя полчаса.</p>',
			'special' => true,
			'absent' => true,
			'category_id' => 1,
			'subcategory_id' => 1,
			'good_brand_id' => 1,
		));

	}

}
