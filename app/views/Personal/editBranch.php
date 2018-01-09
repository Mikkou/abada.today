<div class="container" style="margin-top: 20px;">
    <h3><?=$langT['edit_branch'] ?></h3>
    <form method="post" action="/personal/save-branch" enctype="multipart/form-data" class="add-event">
        <div class="row">
            <div class="3u 6u$(xsmall)">
                <label for="country"><?=$langT['country'] ?></label>
                <input type="text" name="country" id="country" required
                       value="<?= $branch['country'] ?>">
            </div>
            <div class="3u 6u$(xsmall)">
                <label for="city"><?=$langT['city'] ?></label>
                <input type="text" name="city" id="city" placeholder="" required
                       value="<?= $branch['city'] ?>">
            </div>
        </div>
        <div class="row">
            <div class="4u 6u$(xsmall)">
                <label for="street"><?=$langT['street'] ?></label>
                <input type="text" name="street" id="street" required
                       value="<?= $branch['street'] ?>">
            </div>
            <div class="1u 6u$(xsmall)">
                <label for="house"><?=$langT['house'] ?></label>
                <input type="text" name="house" id="house" placeholder="" required
                       value="<?= $branch['house'] ?>">
            </div>
            <div class="1u 6u$(xsmall)">
                <label for="block"><?=$langT['block'] ?></label>
                <input type="text" name="block" id="block" placeholder=""
                       value="<?= $branch['block'] ?>">
            </div>
        </div>
        <div class="row">
            <div class="2u 6u$(xsmall)">
                <label for="phone"><?=$langT['phone'] ?></label>
                <input type="tel" name="phone" id="phone" required
                       value="<?= $branch['phone'] ?>">
            </div>
            <div class="4u 6u$(xsmall)">
                <label for="link"><?=$langT['group_in_vk'] ?></label>
                <input type="text" name="link" id="link" placeholder="<?=$langT['link'] ?>"
                       value="<?= $branch['link'] ?>">
            </div>
        </div>
        <div class="row">
            <div class="2u 6u$(xsmall)">
                <label for="age-groups"><?=$langT['age_groups'] ?></label>
                <input type="text" name="age_groups" id="age-groups" placeholder="16-30"
                       value="<?= $branch['age_groups'] ?>">
            </div>
            <div class="4u 6u$(xsmall)">
                <label for="site"><?=$langT['site'] ?></label>
                <input type="text" name="site" id="site" placeholder="<?=$langT['link'] ?>"
                       value="<?= $branch['site'] ?>">
            </div>
        </div>
        <div class="row">
            <div class="6u 12u$(medium)">
                <label for="schedule"><?=$langT['schedule'] ?></label>
                <textarea name="schedule" id="schedule" rows="6" maxlength="4096"><?= $branch['schedule'] ?></textarea>
            </div>
        </div>
        <div class="row" style="margin-top: 20px">
            <div class="6u 12u$(xsmall)" style="display: -webkit-inline-box;">
                <label for="image" class="button fit small icon fa-download"><?=$langT['load_image'] ?></label>
                <input type="file" name="image" id="image" style="display: none;">
            </div>
        </div>
        <!-- Break -->
        <div class="row">
            <div class="12u$">
                <ul class="actions">
                    <li><input type="submit" value="<?=$langT['save'] ?>" class="special"></li>
                </ul>
            </div>
        </div>
        <input name="id" value="<?= $branch['id']?>" style="display: none;">
    </form>
</div>
