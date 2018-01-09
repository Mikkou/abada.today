<div class="container">
    <div class="row" style="margin-top: 20px;">
        <?php if (!empty($branch['image'])) :?>
            <div class="4u 12u$(xsmall)">
                <img src="<?=$branch['image']?>" width="100%">
            </div>
        <?php endif; ?>
        <div class="8u 12u$(xsmall)">
            <div class="row">
                <?php if ($branch['user_id'] == 0) { ?>
                    <div class="12u$(xsmall)"><strong><?=$langT['responsible']?>:</strong> <?= $branch['curator']; ?></div>
                <?php } else { ?>
                    <div class="12u$(xsmall)"><strong><?=$langT['responsible']?>:</strong> <?= $branch['lastname']; ?>
                        <?= $branch['firstname']; ?> (<?= $branch['nickname']; ?>)</div>
                <?php } ?>
            </div>
            <div class="row">
                <div class="12u$(xsmall)"><strong><?=$langT['phone']?>:</strong>&nbsp;<?= $branch['phone']; ?></div>
            </div>
            <div class="row">
                <div class="12u$(xsmall)"><strong><?=$langT['address']?>:</strong>&nbsp;<?=$branch['address']; ?></div>
            </div>
            <?php if(!empty($branch['age_groups'])) {?>
            <div class="row">
                <div class="12u$(xsmall)"><strong><?=$langT['age_groups']?>:</strong>&nbsp;<?= $branch['age_groups']; ?></div>
            </div>
            <?php } ?>
            <?php if(!empty($branch['site'])) {?>
            <div class="row">
                <div class="12u$(xsmall)"><strong><?=$langT['site']?>:</strong>&nbsp;<?= $branch['site']; ?></div>
            </div>
            <?php } ?>
            <div class="row">
                <div class="12u$(xsmall)"><strong>vk:</strong>&nbsp;<?= $branch['link']; ?></div>
            </div>
            <?php if(!empty($branch['schedule'])) {?>
            <div class="row">
                <div style="margin-top: 20px;">
                    <h4><?=$langT['schedule']?></h4>
                    <div class="12u 12u$(medium)">
                        <p><?= $branch['schedule']; ?></p>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
