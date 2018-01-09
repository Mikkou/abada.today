<div class="events-content" style="display: none;">
    <div class="8u 12u$(medium) list">
        <div class="row filter-menu">

            <?php foreach ($categories as $v) {?>
            <div>
                <input type="checkbox" id="<?=mb_strtolower($v['en'])?>"
                       name="<?=mb_strtolower($v['en'])?>" <?=$v['checked']?>>
                <label for="<?=mb_strtolower($v['en'])?>"><?=$v[$lang]?></label>
            </div>
            <?php } ?>

        </div>
        <?php foreach ($events as $k => $event) { ?>
            <div class="row event">
                <div class="3u 12u$(medium)">
                    <a href="/events?id=<?= $event['id'] ?>&lang=<?=$lang?>" style="border: none;">
                        <img class="mr-3" src="<?= $event['image']; ?>">
                    </a>
                </div>
                <div class="9u 12u$(medium)" style="padding-left: 30px;">
                    <div class="row types-event-title">
                        <div class="9u 12u$(medium)">
                            <h4>
                                <a href="/events?id=<?=$event['id']?>&lang=<?=$lang?>"><?= ($event['name']) ? $event['name'] : 'Мероприятие'; ?></a>
                            </h4>
                        </div>
                        <div class="3u 12u$(medium) types-events-date">
                            <span><?= $event['begin_date']; ?></span>
                        </div>
                    </div>

                    <?php if (!empty($event['country']) || !empty($event['city'])) :?>
                        <div class="row">
                            <div class="12u$(medium)">A: <?= $event['country']; ?>, <?= $event['city']; ?></div>
                        </div>
                    <?php endif; ?>

                     <?php if (!empty($event['organizer'])) :?>
                    <div class="row">
                        <div class="12u$(medium)">O: <?= $event['organizer']; ?></div>
                    </div>
                     <?php endif; ?>

                    <?php if (!empty($event['guest'])) :?>
                        <div class="row">
                            <div class="12u$(medium)">G: <?= $event['guest']; ?></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php } ?>
    </div>
    <div id="map" class="4u 12u$(small)"></div>
</div>
