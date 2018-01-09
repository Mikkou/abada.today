<ul class="list-unstyled list-events">
    <?php foreach ($branches as $k => $branch) { ?>
        <li class="media">
            <div class="media-body">
                <div class="row personal-list">
                    <div class="col">
                        <strong><?= $k + 1; ?></strong>.
                        <a href="/branches?id=<?= $branch['id']?>&lang=<?=$lang?>"><?= $branch['country']; ?>
                            , <?= $branch['city']; ?>, <?= $branch['street']; ?>,
                            <?= $branch['house']; ?>/<?= $branch['block']; ?></a>
                    </div>
                    <div class="col">
                        <a href="/personal/delete-branch?id=<?= $branch['id']; ?>&lang=<?=$lang?>"
                           class="button small delete-branch"><?=$langT['delete']?></a>
                        <a href="/personal/edit-branch?id=<?= $branch['id']; ?>&lang=<?=$lang?>"
                           class="button small"><?=$langT['edit']?></a>
                    </div>
                </div>
            </div>
        </li>
    <?php } ?>
</ul>
