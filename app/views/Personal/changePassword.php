<?php if (isset($_SESSION['error'])) : ?>
    <div class="alert alert-danger">
        <?= $_SESSION['error'];
        unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<div class="form">
    <div class="col-md-12">
        <h2><?=$langT['change_password']?></h2>
        <form method="post" action="/personal/change-password?lang=<?=$lang?>">
            <div class="form-group">
                <label for="password"><?=$langT['new_password']?></label>
                <input type="password" name="password" class="form-control" id="password" placeholder="">
            </div>
            <button type="submit" class="btn btn-default"><?=$langT['change']?></button>
        </form>
        <?php if (isset($_SESSION['form_data'])) unset($_SESSION['form_data']); ?>
    </div>
</div>

