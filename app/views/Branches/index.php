<div>
    <p><sup style="color: red;">*</sup> - <?=$langT['add_by_moderators']?>.</p>
</div>
<ul class="list-unstyled list-branches">
    <?php foreach ($branches as $k => $branch) { ?>
        <li class="media">
            <div class="media-body">
                <div class="row">
                    <div class="col">
                        <a href="/branches?id=<?=$branch['id']?>&lang=<?=$lang?>">
                            <strong><?= $k + 1?>.</strong> <?=$branch['address']; ?></a>
                    </div>
                    <div class="col"><?=$langT['phone']?>: <?= $branch['phone']; ?></div>
                    <?php if ($branch['user_id'] == 0) { ?>
                        <div class="col curator"><?=$langT['responsible']?>: <?= $branch['curator']; ?><sup>*</sup></div>
                    <?php } else { ?>
                        <div class="col"><?=$langT['responsible']?>: <?= $branch['nickname']; ?> (<?= $branch['lastname']; ?>
                            <?= $branch['firstname']; ?>)</div>
                    <?php } ?>
                </div>
            </div>
        </li>
    <?php } ?>
</ul>