<?php if (empty($_SESSION['user']['nickname']) || empty($_SESSION['user']['lastname'])
    || empty($_SESSION['user']['firstname'])
) { ?>
    <div class="container" style="margin-top: 20px;">
        <h3><?=$langT['add_new_event']?></h3>
        <?php if ($lang === 'ru') { ?>
            <p>Перед добавлением необходимо указать в кабинете Ваше апелиду, имя и фамилию <a href="/personal?lang=<?=$lang?>">здесь</a>.</p>
        <?php } else { ?>
            <p>Before you add event? you need point your apelido, firstname and lastname in cabinet <a href="/personal?lang=<?=$lang?>">here</a>.</p>
        <?php } ?>
    </div>
<?php } else { ?>
    <div class="container" style="margin-top: 20px;">
        <h3><?=$langT['add_new_event']?></h3>
        <form method="post" action="/events/add" enctype="multipart/form-data" class="add-event">
            <div class="row">
                <div class="6u 12u$(medium)">
                    <label for="name"><?=$langT['events_name']?></label>
                    <input type="text" name="name" id="name" placeholder="" required
                           value="<?= isset($_SESSION['form_data']['name']) ? h($_SESSION['form_data']['name']) : ''; ?>">
                </div>
            </div>
            <div class="row">
                <div class="3u 6u$(medium)">
                    <label for="begin_date"><?=$langT['begin']?></label>
                    <input type="date" name="begin_date" id="begin_date" required
                           value="<?= isset($_SESSION['form_data']['begin_date']) ? h($_SESSION['form_data']['begin_date']) : ''; ?>">
                </div>
                <div class="3u 6u$(medium)">
                    <label for="end_date"><?=$langT['end']?></label>
                    <input type="date" name="end_date" id="end_date" required
                           value="<?= isset($_SESSION['form_data']['end_date']) ? h($_SESSION['form_data']['end_date']) : ''; ?>">
                </div>
            </div>
            <div class="row">
                <div class="3u 6u$(medium)">
                    <span><?=$langT['country']?></span>
                    <div class="select-wrapper">
                        <select name="country" id="country">
                            <option value=""></option>
                            <?php foreach ($countries as $value) { ?>
                                <option value="<?= $value['id']?>"><?=$value[$lang]?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="3u 6u$(medium)">
                    <span><?=$langT['city']?></span>
                    <div class="select-wrapper">
                        <select name="city" id="city">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="3u 6u$(medium)">
                    <label for="street"><?=$langT['street']?></label>
                    <input type="text" name="street" id="street"
                           value="<?= isset($_SESSION['form_data']['street']) ? h($_SESSION['form_data']['street']) : ''; ?>">
                </div>
                <div class="2u 6u$(medium)">
                    <label for="house"><?=$langT['house']?></label>
                    <input type="number" name="house" id="house" placeholder="" maxlength="4"
                           value="<?= isset($_SESSION['form_data']['house']) ? h($_SESSION['form_data']['house']) : ''; ?>">
                </div>
                <div class="1u 6u$(medium)">
                    <label for="block"><?=$langT['block']?></label>
                    <input type="text" name="block" id="block" placeholder="" maxlength="4"
                           value="<?= isset($_SESSION['form_data']['block']) ? h($_SESSION['form_data']['block']) : ''; ?>">
                </div>
            </div>
            <div class="row">
                <div class="3u 6u$(medium)">
                    <label for="guest"><?=$langT['special_guest']?></label>
                    <input type="text" name="guest" id="guest" placeholder=""
                           value="<?= isset($_SESSION['form_data']['guest']) ? h($_SESSION['form_data']['guest']) : ''; ?>">
                </div>
                <div class="3u 6u$(medium)">
                    <label for="vk"><?=$langT['vk_event']?></label>
                    <input type="text" name="vk" id="vk" placeholder="<?=$langT['link']?>"
                           value="<?= isset($_SESSION['form_data']['vk']) ? h($_SESSION['form_data']['vk']) : ''; ?>">
                </div>
            </div>
            <div class="row">
              <div class="3u 6u$(medium)">
                <span><?=$langT['category']?></span>
                <div class="select-wrapper">
                  <select name="category">
                    <?php foreach ($categories as $value) { ?>
                      <option value="<?= $value['id']?>"><?= $value[$lang]?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="3u 6u$(medium)">
                  <span><?=$langT['events_type']?></span>
                  <input type="checkbox" id="event-type" name="event_type" checked="">
                  <label for="event-type"><?=$langT['open']?></label>
              </div>
            </div>
            <div class="row">
                <div class="6u 12u$(medium)">
                    <label for="description"><?=$langT['description']?></label>
                    <textarea name="description" id="description" rows="6"
                              maxlength="4096"><?= isset($_SESSION['form_data']['description'])
                            ? h($_SESSION['form_data']['description']) : ''; ?></textarea>
                </div>
            </div>
            <div class="row" style="margin-top: 20px">
                <div class="6u 12u$(medium)" style="display: -webkit-inline-box;">
                    <label for="image" class="button fit small icon fa-download"><?=$langT['load_image']?></label>
                    <input type="file" name="image" id="image" style="display: none;">
                </div>
            </div>
            <!-- Break -->
            <div class="row">
                <div class="12u$">
                    <ul class="actions">
                        <li><input type="submit" value="<?=$langT['publish']?>" class="special"></li>
                    </ul>
                </div>
            </div>
            <input type="text" name="organizer" value="<?=$_SESSION['user']['nickname']?>" style="display: none;">
            <input type="hidden" id="coord_x" name="coord_x" value="">
            <input type="hidden" id="coord_y" name="coord_y" value="">
            <input type="hidden" id="address">
        </form>
    </div>
    <div id="eventsAddMap" style="display: none;"></div>

<?php } ?>
