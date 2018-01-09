<?php if ((int)$_SESSION['user']['rights'] > 9) { ?>
    <div class="row personal-business-block">
        <div class="3u 12u$(medium)">
            <a href="/events/add?lang=<?=$lang?>" class="button special"><?=$langT['add_event']?></a>
        </div>
        <div class="3u 12u$(medium)">
            <a href="/branches/add?lang=<?=$lang?>" class="button special"><?=$langT['add_branch']?></a>
        </div>
        <?php if ($_SESSION['user']['c_own_events'] > 0) { ?>
            <div class="3u 12u$(medium)">
                <a href="/personal/my-events?lang=<?=$lang?>" class="button special"><?=$langT['my_events']?>
                    (<?= $_SESSION['user']['c_own_events'] ?>)</a></div>
        <?php } ?>
        <?php if ($_SESSION['user']['c_own_branches'] > 0) { ?>
            <div class="3u 12u$(medium)">
                <a href="/personal/my-branches?lang=<?=$lang?>" class="button special"><?=$langT['my_branches']?>
                    (<?= $_SESSION['user']['c_own_branches'] ?>)</a>
            </div>
        <?php } ?>
    </div>
<?php } else {?>
    <div class="row" style="margin-top: 10px;">
        <?php if ($lang === 'ru') { ?>
            <p>Если Вы являетесь преподавателем капоэйры и хотите добавлять на сайт свои филиалы и
                события, напишите в техподдержку.</p>
        <?php } else { ?>
            <p>If you are teacher of capoeira and want to add on site their branches and events, please write in support.</p>
        <?php } ?>
    </div>
<?php } ?>

<div class="personal-data">
    <div>
        <span><strong>ID:</strong> <?= $data['id'] ?></span>
    </div>
    <div>
        <span><strong><?=$langT['apelido']?>:</strong> <?= $data['nickname'] ?></span>
    </div>
    <div>
        <span><strong><?=$langT['lastname']?>:</strong> <?= $data['lastname'] ?></span>
    </div>
    <div>
        <span><strong><?=$langT['firstname']?>:</strong> <?= $data['firstname'] ?></span>
    </div>
    <div>
        <span><strong>email:</strong> <?= $data['email'] ?></span>
    </div>
</div>
<div class="personal-edit">
    <a class="button small" href="/personal/edit?lang=<?=$lang?>"><?=$langT['edit']?></a>
</div>
<div class="personal-edit">
    <a class="button small" href="/personal/change-password?lang=<?=$lang?>"><?=$langT['change_password']?></a>
</div>

