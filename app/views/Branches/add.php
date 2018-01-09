<?php if (empty($_SESSION['user']['nickname']) || empty($_SESSION['user']['lastname'])
    || empty($_SESSION['user']['firstname'])
) { ?>
    <div class="container" style="margin-top: 20px;">
        <h3><?=$langT['add_new_branch']?></h3>
        <?php if ($lang === 'ru') { ?>
            <p>Перед добавлением необходимо указать в кабинете Ваше апелиду, имя и фамилию <a href="/personal?lang=<?=$lang?>">здесь</a>.
        <?php } else { ?>
            <p>Before you add event? you need point your apelido, firstname and lastname in cabinet <a href="/personal?lang=<?=$lang?>">here</a>.</p>
        <?php } ?>
    </div>
<?php } else { ?>
    <div class="container" style="margin-top: 20px;">
        <h3><?=$langT['add_new_branch']?></h3>
        <form method="post" action="/branches/add" enctype="multipart/form-data" class="add-event">
            <div class="row">
                <div class="6u 12u$(xsmall)">
                    <?php if ($lang === 'ru') { ?>
                        <p><strong>НЕЛЬЗЯ</strong> добавлять филиалы, куратором которых Вы не являетесь.</p>
                    <?php } else { ?>
                        <p><strong>YOU CAN NOT</strong> add branches, curator of which you are not.</p>
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                <div class="3u 6u$(xsmall)">
                    <label for="country"><?=$langT['country']?></label>
                    <input type="text" name="country" id="country" required
                           value="<?= isset($_SESSION['form_data']['country']) ? h($_SESSION['form_data']['country']) : ''; ?>">
                </div>
                <div class="3u 6u$(xsmall)">
                    <label for="city"><?=$langT['city']?></label>
                    <input type="text" name="city" id="city" placeholder="" required
                           value="<?= isset($_SESSION['form_data']['city']) ? h($_SESSION['form_data']['city']) : ''; ?>">
                </div>
            </div>
            <div class="row">
                <div class="4u 6u$(xsmall)">
                    <label for="street"><?=$langT['street']?></label>
                    <input type="text" name="street" id="street" required
                           value="<?= isset($_SESSION['form_data']['street']) ? h($_SESSION['form_data']['street']) : ''; ?>">
                </div>
                <div class="1u 6u$(xsmall)">
                    <label for="house"><?=$langT['house']?></label>
                    <input type="text" name="house" id="house" placeholder="" required
                           value="<?= isset($_SESSION['form_data']['house']) ? h($_SESSION['form_data']['house']) : ''; ?>">
                </div>
                <div class="1u 6u$(xsmall)">
                    <label for="block"><?=$langT['block']?></label>
                    <input type="text" name="block" id="block" placeholder=""
                           value="<?= isset($_SESSION['form_data']['block']) ? h($_SESSION['form_data']['block']) : ''; ?>">
                </div>
            </div>
            <div class="row">
                <div class="2u 6u$(xsmall)">
                    <label for="phone"><?=$langT['phone']?></label>
                    <input type="tel" name="phone" id="phone" required
                           value="<?= isset($_SESSION['form_data']['phone']) ? h($_SESSION['form_data']['phone']) : ''; ?>">
                </div>
                <div class="4u 6u$(xsmall)">
                    <label for="link"><?=$langT['group_in_vk']?></label>
                    <input type="text" name="link" id="link" placeholder="<?=$langT['link']?>"
                           value="<?= isset($_SESSION['form_data']['link']) ? h($_SESSION['form_data']['link']) : ''; ?>">
                </div>
            </div>
            <div class="row">
                <div class="2u 6u$(xsmall)">
                    <label for="age-groups"><?=$langT['age_groups']?></label>
                    <input type="text" name="age_groups" id="age-groups" placeholder="16-30"
                           value="<?= isset($_SESSION['form_data']['age_groups']) ? h($_SESSION['form_data']['age_groups']) : ''; ?>">
                </div>
                <div class="4u 6u$(xsmall)">
                    <label for="site"><?=$langT['site']?></label>
                    <input type="text" name="site" id="site" placeholder="<?=$langT['link']?>"
                           value="<?= isset($_SESSION['form_data']['site']) ? h($_SESSION['form_data']['site']) : ''; ?>">
                </div>
            </div>
            <div class="row">
                <div class="6u 12u$(medium)">
                    <label for="schedule"><?=$langT['schedule']?></label>
                    <textarea name="schedule" id="schedule" rows="6"
                              maxlength="4096"><?= isset($_SESSION['form_data']['schedule'])
                            ? h($_SESSION['form_data']['schedule']) : ''; ?></textarea>
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
                        <li><input type="submit" value="<?=$langT['publish']?>" class="special"></li>
                    </ul>
                </div>
            </div>
        </form>
    </div>
<?php } ?>
