<?php if (isset($_SESSION['error'])) : ?>
    <div class="alert alert-danger" style="position: relative; text-align: center;">
        <?= $_SESSION['error'];
        unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>
<?php if (isset($_SESSION['success'])) : ?>
    <div class="alert alert-success" style="position: relative; text-align: center;">
        <?= $_SESSION['success'];
        unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>