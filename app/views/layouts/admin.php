<!DOCTYPE HTML>
<html lang="ru">
<head>
    <?php \fw\core\base\View::getMeta() ?>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no"/>
    <link href="/public/images/favicon.gif" rel="icon" type="image/x-icon"/>
    <!--[if lte IE 8]>
    <script src="/public/assets/js/ie/html5shiv.js"></script><![endif]-->
    <link rel="stylesheet" href="/public/assets/css/main.css"/>
    <!--[if lte IE 9]>
    <link rel="stylesheet" href="/public/assets/css/ie9.css"/><![endif]-->
    <!--[if lte IE 8]>
    <link rel="stylesheet" href="/public/assets/css/ie8.css"/><![endif]-->
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
</head>
<body>

<script>
    <?php
    $l = json_encode(require ROOT . "/public/lang/ru.php");
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
                <a href="/" class="logo">ABADÁ-Capoeira</a>
                <div style="text-align: right;">
                    <?php if (isset($_SESSION['admin'])) : ?>
                        <a href="/user/logout" class="button special">Выйти</a>
                    <?php endif; ?>
                </div>
            </header>

            <?= $content ?>

        </div>
    </div>

    <!-- Sidebar -->
    <div id="sidebar">
        <div class="inner">
            <!-- Menu -->
            <nav id="menu">
                <header class="major">
                    <h2>Меню</h2>
                </header>
                <ul>
                    <li><a href="/admin">Главная</a></li>
                    <li><a href="/admin/main/events">События</a></li>
                    <li><a href="/admin/branches">Филиалы</a></li>
                    <li><a href="/admin/main/users">Пользователи</a></li>
                    <li><a href="/admin/errors">Ошибки в системе</a></li>
                    <li><a href="/admin/capo-people/add">Капоэйристы::добавить</a></li>
                    <li><a href="/admin/main/cities">Города</a></li>
                    <li><a href="/admin/main/countries">Страны</a></li>
                </ul>
            </nav>
            <!-- Footer -->
            <footer id="footer"></footer>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="/public/assets/js/jquery.min.js"></script>
<script src="/public/assets/js/skel.min.js"></script>
<script src="/public/assets/js/util.js"></script>
<!--[if lte IE 8]>
<script src="/public/assets/js/ie/respond.min.js"></script><![endif]-->
<script src="/public/assets/js/main.js"></script>
<script src="/public/js/main.js"></script>

<?php
foreach ($scripts as $script) {
    echo $script;
}
?>
</body>
</html>