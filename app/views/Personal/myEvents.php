<ul class="list-unstyled list-events">
    <?php foreach ($events as $k => $event) { ?>
        <li class="media">
            <div class="media-body">
                <div class="row personal-list">
                    <div class="col">
                        <h4>
                            <a href="/events?id=<?=$event['id']?>&lang=<?=$lang?>"><?= ($event['name'])
                                    ? $event['name'] : 'Мероприятие'; ?></a>
                        </h4>
                    </div>
                    <div class="col"><?=$langT['address']?>: <?= $event['country']; ?>, <?= $event['city']; ?></div>
                    <div class="col"><?=$langT['begin']?>: <?= $event['begin_date']; ?></div>
                    <div class="col">
                        <a href="/personal/delete-event?id=<?= $event['id']; ?>&lang=<?=$lang?>"
                           class="button small delete-event"><?=$langT['delete']?></a>
                    </div>
                    <div class="col">
                        <a href="/personal/edit-event?id=<?= $event['id']; ?>&lang=<?=$lang?>"
                           class="button small"><?=$langT['edit']?></a>
                    </div>
                </div>
            </div>
        </li>
    <?php } ?>
</ul>