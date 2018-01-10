<div class="container" style="margin-top: 20px; display: inline-flex; width: 100%;">
    <div class="col" style="width: 50%;">
        <h3>Добавление нового события</h3>
        <form method="post" action="/admin/events/add" enctype="multipart/form-data" class="add-event">
            <div class="row">
                <div class="12u 12u$(medium)">
                    <label for="name">Название мероприятия</label>
                    <input type="text" name="name" id="name" placeholder="" required
                           value="<?= isset($_SESSION['form_data']['name']) ? h($_SESSION['form_data']['name']) : ''; ?>">
                </div>
            </div>
            <div class="row">
                <div class="6u 12u$(medium)">
                    <label for="begin_date">Начало</label>
                    <input type="date" name="begin_date" id="begin_date" required
                           value="<?= isset($_SESSION['form_data']['begin_date']) ? h($_SESSION['form_data']['begin_date']) : ''; ?>">
                </div>
                <div class="6u 12u$(medium)">
                    <label for="end_date">Окончание</label>
                    <input type="date" name="end_date" id="end_date" required
                           value="<?= isset($_SESSION['form_data']['end_date']) ? h($_SESSION['form_data']['end_date']) : ''; ?>">
                </div>
            </div>

            <div class="row">
                <div class="6u 12u$(medium)">
                    <label for="country">Страна</label>
                    <input type="text" name="country" id="country" required
                           value="<?= isset($_SESSION['form_data']['country']) ? h($_SESSION['form_data']['country']) : ''; ?>">
                </div>
                <div class="6u 12u$(medium)">
                    <label for="city">Город</label>
                    <input type="text" name="city" id="city" placeholder=""
                           value="<?= isset($_SESSION['form_data']['city']) ? h($_SESSION['form_data']['city']) : ''; ?>">
                </div>
            </div>
            <div class="row">
                <div class="6u 12u$(medium)">
                    <label for="street">Улица</label>
                    <input type="text" name="street" id="street"
                           value="<?= isset($_SESSION['form_data']['street']) ? h($_SESSION['form_data']['street']) : ''; ?>">
                </div>
                <div class="4u 12u$(medium)">
                    <label for="house">Дом</label>
                    <input type="number" name="house" id="house" placeholder="" maxlength="4"
                           value="<?= isset($_SESSION['form_data']['house']) ? h($_SESSION['form_data']['house']) : ''; ?>">
                </div>
                <div class="2u 12u$(medium)">
                    <label for="block">Корпус</label>
                    <input type="text" name="block" id="block" placeholder="" maxlength="4"
                           value="<?= isset($_SESSION['form_data']['block']) ? h($_SESSION['form_data']['block']) : ''; ?>">
                </div>
            </div>
            <div class="row">
                <div class="6u 12u$(medium)">
                    <label for="guest">Почетный гость</label>
                    <input type="text" name="guest" id="guest" placeholder=""
                           value="<?= isset($_SESSION['form_data']['guest']) ? h($_SESSION['form_data']['guest']) : ''; ?>">
                </div>
                <div class="6u 12u$(medium)">
                    <label for="organizer">Организатор</label>
                    <input type="text" name="organizer" id="organizer" placeholder=""
                           value="<?= isset($_SESSION['form_data']['organizer']) ? h($_SESSION['form_data']['organizer']) : ''; ?>">
                </div>
            </div>
            <div class="row">
                <div class="6u 12u$(medium)">
                    <label for="vk">Встреча в Vk</label>
                    <input type="text" name="vk" id="vk" placeholder="ссылка"
                           value="<?= isset($_SESSION['form_data']['vk']) ? h($_SESSION['form_data']['vk']) : ''; ?>">
                </div>
            </div>
            <div class="row">
                <div class="6u 12u$(medium)">
                    <span>Категория</span>
                    <div class="select-wrapper">
                        <select name="category">
                            <?php foreach ($categories as $value) { ?>
                                <option value="<?= $value['id']?>"><?= $value['ru']?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="6u 12u$(medium)">
                    <span>Тип мероприятия</span>
                    <input type="checkbox" id="event-type" name="event_type" checked="">
                    <label for="event-type">Открытое</label>
                </div>
            </div>
            <div class="row">
                <div class="12u 12u$(medium)">
                    <label for="description">Описание</label>
                    <textarea name="description" id="description" rows="6" maxlength="4096"></textarea>
                </div>
            </div>
            <div class="row" style="margin-top: 20px">
                <div class="12u 12u$(medium)" style="display: -webkit-inline-box;">
                    <label for="image" class="button fit small icon fa-download">Загрузить картинку</label>
                    <input type="file" name="image" id="image" style="display: none;">
                </div>
            </div>
            <!-- Break -->
            <div class="row">
                <div class="12u$">
                    <ul class="actions">
                        <li><input type="submit" value="Опубликовать" class="special"></li>
                    </ul>
                </div>
            </div>
            <input type="hidden" id="coord_x" name="coord_x" value="">
            <input type="hidden" id="coord_y" name="coord_y" value="">
            <input type="hidden" id="address">
        </form>
    </div>
    <div class="col" style="width: 50%;">
        <div id="eventsAddMap" style="margin: 0 auto; display: ;"></div>
    </div>
</div>





