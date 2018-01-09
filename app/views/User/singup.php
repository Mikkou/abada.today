<?php if (isset($_SESSION['error'])) : ?>
    <div class="alert alert-danger">
        <?= $_SESSION['error'];
        unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<div class="form">
    <div class="col-md-12">
        <h2><?=$langT['registration']?></h2>
        <form method="post" action="/user/singup">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" name="email" class="form-control" id="email" placeholder="Email"
                       value="<?= isset($_SESSION['form_data']['email']) ? h($_SESSION['form_data']['email']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="password"><?=$langT['password']?></label>
                <input type="password" name="password" class="form-control" id="password"
                       placeholder="<?=$langT['password']?>">
            </div>
            <button type="submit" class="btn btn-default"><?=$langT['register']?></button>

        </form>
        <?php if (isset($_SESSION['form_data'])) unset($_SESSION['form_data']); ?>
    </div>
</div>

