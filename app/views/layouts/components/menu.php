<nav id="menu">
    <header class="major">
        <h2><?=$langT['menu']?></h2>
    </header>
    <ul>
        <?php if (isset($_SESSION['user'])) {
            require_once APP . "/views/layouts/components/menuIsAuth.php";
        } else {
            require_once APP . "/views/layouts/components/menuIsNotAuth.php";
        } ?>
    </ul>
</nav>