<div class="container" style="margin-top: 20px;">
    <h3><?=$langT['edit_event']?></h3>
    <form method="post" action="/personal/save-event" enctype="multipart/form-data" class="add-event">
        <div class="row">
            <div class="6u 12u$(xsmall)">
                <label for="name"><?=$langT['events_name']?></label>
                <input type="text" name="name" id="name" placeholder="" required value="<?= $event['name']?>">
            </div>
        </div>
        <div class="row">
            <div class="3u 6u$(xsmall)">
                <label for="begin_date"><?=$langT['begin']?></label>
                <input type="date" name="begin_date" id="begin_date" required value="<?= $event['begin_date']?>">
            </div>
            <div class="3u 6u$(xsmall)">
                <label for="end_date"><?=$langT['begin']?></label>
                <input type="date" name="end_date" id="end_date" required value="<?= $event['end_date']?>">
            </div>
        </div>
        <div class="row">
            <div class="3u 6u$(xsmall)">
                <span><?=$langT['country']?></span>
                <div class="select-wrapper">
                    <select name="country" id="country">
                        <option value="<?= $event['country_id']?>"><?= $event['country']?></option>
                        <option value=""></option>
                        <?php foreach ($countries as $value) { ?>
                            <option value="<?= $value['id']?>"><?=$value[$lang]?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="3u 6u$(xsmall)">
                <span><?=$langT['city']?></span>
                <div class="select-wrapper">
                    <select name="city" id="city">
                        <?php if ($event['city']) { ?>
                            <option value="<?= $event['city_id']?>"><?= $event['city']?></option>
                        <?php } else { ?>
                            <option value=""></option>
                        <?php } ?>
                        <?php foreach ($countrysCities AS $cityV) { ?>
                            <option value="<?=$cityV['id']?>"><?=$cityV[$lang]?></option>
                        <?php } ?>
                        <?php if ($event['city']) : ?>
                            <option value=""></option>
                        <?php endif; ?>
                        <option value="another">....<?=$langT['add_another']?>....</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="3u 6u$(medium)">
                <label for="street"><?=$langT['street']?></label>
                <input type="text" name="street" id="street"
                       value="<?= $event['street']?>">
            </div>
            <div class="2u 6u$(medium)">
                <label for="house"><?=$langT['house']?></label>
                <input type="number" name="house" id="house" placeholder="" maxlength="4"
                       value="<?= $event['house']?>">
            </div>
            <div class="1u 6u$(medium)">
                <label for="block"><?=$langT['block']?></label>
                <input type="text" name="block" id="block" placeholder="" maxlength="4"
                       value="<?= $event['block']?>">
            </div>
        </div>
        <div class="row">
            <div class="3u 6u$(xsmall)">
                <label for="guest"><?=$langT['special_guest']?></label>
                <input type="text" name="guest" id="guest" value="<?= $event['guest']?>">
            </div>
            <div class="3u 6u$(medium)">
                <label for="vk"><?=$langT['vk_event']?></label>
                <input type="text" name="vk" id="vk" placeholder="<?=$langT['link']?>" value="<?= $event['vk']?>">
            </div>
        </div>
        <div class="row">
          <div class="3u 6u$(medium)">
            <span><?=$langT['category']?></span>
            <div class="select-wrapper">
              <select name="category">
                <option value="<?= $event['category']?>"><?= $toggleCategory?></option>
                <?php foreach ($categories as $value) { ?>
                  <option value="<?= $value['id']?>"><?= $value[$lang]?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="3u 6u$(medium)">
            <span><?=$langT['events_type']?></span>
              <input type="checkbox" id="event-type" name="event_type" <?= $checked?>>
              <label for="event-type"><?=$langT['open']?></label>
          </div>
        </div>
        <div class="row">
            <div class="6u 12u$(medium)">
                <label for="description"><?=$langT['description']?></label>
                <textarea name="description" id="description" rows="6" maxlength="4096"><?= $event['description']?></textarea>
            </div>
        </div>
        <div class="row" style="margin-top: 20px">
            <div class="6u 12u$(xsmall)" style="display: -webkit-inline-box;">
                <label for="image" class="button fit small icon fa-download"><?=$langT['load_image']?></label>
                <input type="file" name="image" id="image" style="display: none;">
            </div>
        </div>
        <!-- Break -->
        <div class="row">
            <div class="12u$">
                <ul class="actions">
                    <li><input type="submit" value="Сохранить" class="special"></li>
                </ul>
            </div>
        </div>
        <input type="text" name="organizer" value="<?= $_SESSION['user']['nickname'] ?>" style="display: none;">
        <input name="id" value="<?= $event['id']?>" style="display: none;">
        <input type="hidden" id="coord_x" name="coord_x" value="<?= $event['coord_x']?>">
        <input type="hidden" id="coord_y" name="coord_y" value="<?= $event['coord_y']?>">
        <input type="hidden" id="address">
    </form>
</div>
<div id="eventsAddMap" style="display: none;"></div>