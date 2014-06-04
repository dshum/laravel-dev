<?php

class CounterTableSeeder extends Seeder {

	public function run()
	{
		DB::table('counters')->truncate();

		Counter::create(array(
			'name' => 'Яндекс.Метрика',
			'order' => 1,
			'code' => '<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter21307873 = new Ya.Metrika({id:21307873,
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/21307873" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->',
			'logo' => '',
			'service_section_id' => 4,
		));

		Counter::create(array(
			'name' => 'LiveInternet',
			'order' => 1,
			'code' => '<!--LiveInternet counter--><script type="text/javascript"><!--
new Image().src = "//counter.yadro.ru/hit?r"+
escape(document.referrer)+((typeof(screen)=="undefined")?"":
";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
";"+Math.random();//--></script><!--/LiveInternet-->',
			'logo' => '<!--LiveInternet logo-->
<a href="http://www.liveinternet.ru/click" target="_blank">
<img src="//counter.yadro.ru/logo?45.13" title="LiveInternet" alt="" border="0" width="31" height="31"/>
</a>
<!--/LiveInternet-->',
			'service_section_id' => 4,
		));

	}

}
