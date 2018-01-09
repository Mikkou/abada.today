<div class="container" style="margin-top: 20px;">
    <h3>Редактирование филиала</h3>
    <form method="post" action="/admin/branches/save-branch" enctype="multipart/form-data" class="add-event">
        <div class="row">
            <div class="3u 6u$(xsmall)">
                <label for="country">Страна</label>
                <input type="text" name="country" id="country" required
                       value="<?= $branch['country'] ?>">
            </div>
            <div class="3u 6u$(xsmall)">
                <label for="city">Город</label>
                <input type="text" name="city" id="city" placeholder="" required
                       value="<?= $branch['city'] ?>">
            </div>
        </div>
        <div class="row">
            <div class="4u 6u$(xsmall)">
                <label for="street">Улица</label>
                <input type="text" name="street" id="street" required
                       value="<?= $branch['street'] ?>">
            </div>
            <div class="1u 6u$(xsmall)">
                <label for="house">Дом</label>
                <input type="text" name="house" id="house" placeholder="" required
                       value="<?= $branch['house'] ?>">
            </div>
            <div class="1u 6u$(xsmall)">
                <label for="block">Корпус</label>
                <input type="text" name="block" id="block" placeholder=""
                       value="<?= $branch['block'] ?>">
            </div>
        </div>
        <div class="row">
            <div class="6u$(xsmall)">
                <label for="curator">Куратор</label>
                <input type="text" name="curator" id="curator" required
                       value="<?= $branch['curator'] ?>">
            </div>
        </div>
        <div class="row">
            <div class="2u 6u$(xsmall)">
                <label for="phone">Телефон</label>
                <input type="tel" name="phone" id="phone" required
                       value="<?= $branch['phone'] ?>">
            </div>
            <div class="4u 6u$(xsmall)">
                <label for="link">Группа в Vk</label>
                <input type="text" name="link" id="link" placeholder="ссылка"
                       value="<?= $branch['link'] ?>">
            </div>
        </div>
        <div class="row">
            <div class="2u 6u$(xsmall)">
                <label for="age-groups">Возраст групп</label>
                <input type="text" name="age_groups" id="age-groups" placeholder="16-30"
                       value="<?= $branch['age_groups'] ?>">
            </div>
            <div class="4u 6u$(xsmall)">
                <label for="site">Сайт</label>
                <input type="text" name="site" id="site" placeholder="ссылка"
                       value="<?= $branch['site'] ?>">
            </div>
        </div>
        <div class="row">
            <div class="6u 12u$(medium)">
                <label for="schedule">Расписание</label>
                <textarea name="schedule" id="schedule" rows="6" maxlength="4096"><?= $branch['schedule'] ?></textarea>
            </div>
        </div>
        <div class="row" style="margin-top: 20px">
            <div class="6u 12u$(xsmall)" style="display: -webkit-inline-box;">
                <label for="image" class="button fit small icon fa-download">Загрузить картинку</label>
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
        <input name="id" value="<?= $branch['id']?>" style="display: none;">
    </form>
</div>
