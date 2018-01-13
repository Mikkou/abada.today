<!DOCTYPE HTML>
<html lang="<?=$lang?>">
<head>
    <?php \fw\core\base\View::getMeta() ?>
    <meta charset="utf-8"/>
    <link href="/public/images/favicon.gif" rel="icon" type="image/x-icon"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
          integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
    <!--[if lte IE 8]-->
    <script src="/public/assets/js/ie/html5shiv.js"></script><!--[endif]-->
    <link rel="stylesheet" href="/public/assets/css/main.css"/>
    <!--[if lte IE 9]>
    <link rel="stylesheet" href="/public/assets/css/ie9.css"/><![endif]-->
    <!--[if lte IE 8]>
    <link rel="stylesheet" href="/public/assets/css/ie8.css"/><![endif]-->
</head>
<body>

<script>
    <?php
     $l = json_encode(require ROOT . "/public/lang/{$lang}.php");
     echo 'const LANG = ' . $l . ';';
     ?>
</script>

<!-- Wrapper -->
<div id="wrapper">
    <!-- Main -->
    <div id="main">
        <div class="inner">
            <?php require_once APP . "/views/layouts/components/alerts.php" ?>
            <!-- Header -->
            <header id="header">
                <a href="/?lang=<?=$lang?>" class="logo">ABADÁ-Capoeira</a>
                <div style="text-align: right;">
                    <ul class="icons">
                        <li><a href="https://vk.com/iamabada" class="icon fa-vk"></a></li>
                        <li>
                            <a
                                    class="lang-setting"
                                    href="http://<?php

                                    $host = $_SERVER['HTTP_HOST'];
                                    $requestUri = $_SERVER['REQUEST_URI'];

                                    $landParam = 'lang=' . $lang;

                                    $newLang = ($lang === 'ru') ? 'en' : 'ru';

                                    // проверяем 1 или несколько параметров
                                    if (strpos($_SERVER['REQUEST_URI'], '&') === false) {

                                        // проверяем на существование параметра lang
                                        if (strpos($_SERVER['REQUEST_URI'], 'lang=') === false) {
                                            echo $host . $_SERVER['REQUEST_URI'] . "&lang=" . $newLang;
                                        } else {
                                            // меняем один язык на другой
                                            echo $host . str_replace(
                                                    ['?lang=ru', '?lang=en'], '', $requestUri
                                                ) . '?lang=' . $newLang;
                                        }

                                    } else {

                                        echo $host . str_replace(
                                                $landParam, 'lang=' . $newLang, $requestUri
                                            );

                                    }

                                    ?>"

                            ><?php echo ($lang === 'ru') ? 'EN' : 'RU';?></a>
                        </li>

                        <?php if (isset($_SESSION['user'])) { ?>
                            <li><a href="/user/logout?lang=<?=$lang?>" class="button special"><?=$langT['log_out']?></a></li>
                        <?php } else { ?>
                            <li><a href="/user/login?lang=<?=$lang?>" class="button special"><?=$langT['log_in']?></a></li>
                        <?php } ?>

                    </ul>
                </div>
            </header>
            <?= $content ?>
        </div>
    </div>
    <!-- Sidebar -->
    <div id="sidebar" class="inactive">
        <div class="inner">
            <!-- Menu -->
            <?php require_once APP . "/views/layouts/components/menu.php"?>
            <!-- Footer -->
            <footer id="footer">
                <div class="pay">
                    <a href="https://money.yandex.ru/to/410014028917759"><?=$langT['donate']?></a>
                </div>
                <p class="copyright">&copy; <?=$langT['rights']?></p>
            </footer>
        </div>
    </div>
</div>
<!-- Scripts -->
<script src="/public/assets/js/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js"></script>
<script src="/public/assets/js/skel.min.js"></script>
<script src="/public/assets/js/util.js"></script>
<!--[if lte IE 8]>
<script src="/public/assets/js/ie/respond.min.js"></script><![endif]-->
<script src="/public/assets/js/main.js"></script>
<script src="/public/js/main.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"
        integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ"
        crossorigin="anonymous"></script>
<?php
foreach ($scripts as $script) {
    echo $script;
}
?>
<!-- BEGIN JIVOSITE CODE {literal} -->
<script type='text/javascript'>
    (function () {
        var widget_id = 'ASQ2ZRTtks';
        var d = document;
        var w = window;

        function l() {
            var s = document.createElement('script');
            s.type = 'text/javascript';
            s.async = true;
            s.src = '//code.jivosite.com/script/widget/' + widget_id;
            var ss = document.getElementsByTagName('script')[0];
            ss.parentNode.insertBefore(s, ss);
        }

        if (d.readyState == 'complete') {
            l();
        } else {
            if (w.attachEvent) {
                w.attachEvent('onload', l);
            } else {
                w.addEventListener('load', l, false);
            }
        }
    })();</script>
<!-- {/literal} END JIVOSITE CODE -->

<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter46692288 = new Ya.Metrika({
                    id:46692288,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/46692288" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

</body>
</html>