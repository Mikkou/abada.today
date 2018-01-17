<div class="row personal-business-block">
    <a href="/capo-people?lang=<?=$lang?>" style="margin-left: 25px;" class="button special"><?=$langT['go_back']?></a>
</div>

<?php foreach ($people as $v) { ?>

    <div class="row" style="display: inline;">
        <div class="2u 12u$(small)" style="max-width: 150px;">
            <img src="<?=$v['image']?>" width="150">
        </div>
        <div class="10u 12u$(small)" style="margin-left: 10px;">

            <?php if (!empty($v['apelido'])) :?>
                <div class="row" style="padding-left: 25px;"><h3 style="margin: 0; padding: 0;"><?=$v['status']?> <?=$v['apelido']?></h3></div>
            <?php endif; ?>

            <?php if (!empty($v['name'])) :?>
                <div class="row"><strong><?=$langT['name']?>:&nbsp;</strong><?=$v['name']?></div>
            <?php endif; ?>

            <?php if (!empty($v['corda'])) :?>
                <div class="row"><strong><?=$langT['corda']?>:&nbsp;</strong><?=$v['corda']?></div>
            <?php endif; ?>

            <?php if (!empty($v['last_corda_year'])) :?>
                <div class="row"><strong><?=$langT['year']?>:&nbsp;</strong><?=$v['last_corda_year']?></div>
            <?php endif; ?>

            <?php if (!empty($v['country'])) :?>
                <div class="row"><strong><?=$langT['country']?>:&nbsp;</strong><?=$v['country']?></div>
            <?php endif; ?>

            <?php if (!empty($v['city'])) :?>
                <div class="row"><strong><?=$langT['city']?>:&nbsp;</strong><?=$v['city']?></div>
            <?php endif; ?>

            <?php if (!empty($v['curator'])) :?>
                <div class="row"><strong><?=$langT['curator']?>:&nbsp;</strong><?=$v['curator']?></div>
            <?php endif; ?>

            <?php if (!empty($v['vk'])) :?>
                <?php if (strpos($v['vk'], 'face') === false) { ?>
                    <div class="row"><a href="<?=$v['vk']?>" class="icon fa-vk"><span class="label"></span></a></div>
                <?php } else { ?>
                    <div class="row"><a href="<?=$v['vk']?>" class="icon fa-facebook"><span class="label"></span></a></div>
                <?php } ?>
            <?php endif; ?>
        </div>
    </div>
    <hr style="margin: 0;">
<?php } ?>