<?php if (isset($_SESSION['error'])) : ?>
    <div class="alert alert-danger">
        <?= $_SESSION['error'];
        unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<div class="form form-login">
    <div class="col-md-12">
        <form method="post" action="/user/login">
            <div class="form-group">
                <label for="login"></label>
                <input type="text" name="login" class="form-control" id="login" placeholder="Email">
            </div>
            <div class="form-group">
                <label for="password"></label>
                <input type="password" name="password" class="form-control" id="password" placeholder="Пароль">
            </div>
            <button type="submit" class="btn btn-default"><?=$langT['login']?></button>
        </form>
        <p><?=$langT['no_account']?> <a href="/user/singup?lang=<?=$lang?>"><?=$langT['sign_up']?></a>.</p>
        <p><?=$langT['forgot_password']?> <a
                    href="/user/restore-password?lang=<?=$lang?>"
            ><?=$langT['restore_password']?></a>.</p>
    </div>
</div>