<?php if (isset($_SESSION['error'])) : ?>
    <div class="alert alert-danger">
        <?= $_SESSION['error'];
        unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<div class="form form-login">
    <div class="col-md-12">
        <h2><?=$langT['restore_password']?></h2>
        <form method="post" action="/user/restore-password">
            <div class="form-group">
                <label for="login"></label>
                <input type="text" name="login" class="form-control" id="login" placeholder="Email">
            </div>
            <button type="submit" class="btn btn-default"><?=$langT['get']?></button>
        </form>
    </div>
</div>