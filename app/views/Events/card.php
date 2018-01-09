<div class="container">
    <div class="row" style="margin-top: 20px;">
        <div class="4u 12u$(medium)"><img src="<?=$event['image']?>" width="100%"></div>
        <div class="8u 12u$(medium)">
            <div class="row">
                <div class="12u 12u$(medium)">
                    <h2><?=$event['name']; ?></h2>
                </div>
            </div>
            <div class="row">
                <div class="6u 12u$(medium)">
                    <strong><?=$langT['dates']?>:</strong> <?=$event['begin_date']; ?> - <?=$event['end_date']; ?>
                </div>
            </div>
            <div class="row">
                <div class="6u 12u$(medium)">
                    <strong><?=$langT['address']?>:</strong> <?=$address; ?>
                </div>
            </div>
            <div class="row">
                <div class="6u 12u$(medium)"><strong><?=$langT['guest']?>:</strong> <?=$event['guest']; ?></div>
            </div>
            <div class="row">
                <div class="6u 12u$(medium)">
                    <strong><?=$langT['organizer']?>:</strong> <?=$event['organizer']; ?>
                </div>
            </div>
            <?php if($category) {?>
                <div class="row">
                    <div class="6u 12u$(medium)">
                        <strong><?=$langT['category']?>:</strong> <?=$category; ?>
                    </div>
                </div>
            <?php } ?>
            <?php if(!empty($event['event_type'])) {?>
                <div class="row">
                    <div class="6u 12u$(medium)">
                        <strong><?=$langT['events_type']?>:</strong> <?=$eventType; ?>
                    </div>
                </div>
            <?php } ?>
            <?php if(!empty($event['vk'])) {?>
                <div class="row">
                    <div class="6u 12u$(medium)">
                        <a href="<?=$event['vk']; ?>" class="icon fa-vk"></a>
                    </div>
                </div>
            <?php } ?>
            <?php if(!empty($event['description'])) {?>
            <div class="row">
                <div style="margin-top: 20px;">
                    <h4><?=$langT['description']?></h4>
                    <div class="12u 12u$(medium)">
                        <p><?=$event['description']; ?></p>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>