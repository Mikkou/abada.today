<div class="container" style="margin-top: 20px; display: inline-flex; width: 100%;">
    <div class="col" style="width: 50%;">
        <h3>Редактирование события</h3>
        <form method="post" action="/admin/events/save-event" enctype="multipart/form-data" class="add-event">
            <div class="row">
                <div class="12u 12u$(medium)">
                    <label for="name">Название мероприятия</label>
                    <input type="text" name="name" id="name" placeholder="" required value="<?= $event['name']?>">
                </div>
            </div>
            <div class="row">
                <div class="6u 12u$(medium)">
                    <label for="begin_date">Начало</label>
                    <input type="date" name="begin_date" id="begin_date" required value="<?= $event['begin_date']?>">
                </div>
                <div class="6u 12u$(medium)">
                    <label for="end_date">Окончание</label>
                    <input type="date" name="end_date" id="end_date" required value="<?= $event['end_date']?>">
                </div>
            </div>
            <div class="row">
                <div class="6u 12u$(medium)">
                    <label for="country">Страна</label>
                    <input type="text" name="country" id="country" value="<?= $event['country']?>">
                </div>
                <div class="6u 12u$(medium)">
                    <label for="city">Город</label>
                    <input type="text" name="city" id="city" required value="<?= $event['city']?>">
                </div>
            </div>
            <div class="row">
                <div class="6u 12u$(medium)">
                    <label for="street">Улица</label>
                    <input type="text" name="street" id="street"
                           value="<?= $event['street']?>">
                </div>
                <div class="4u 12u$(medium)">
                    <label for="house">Дом</label>
                    <input type="number" name="house" id="house" placeholder="" maxlength="4"
                           value="<?= $event['house']?>">
                </div>
                <div class="2u 12u$(medium)">
                    <label for="block">Корпус</label>
                    <input type="text" name="block" id="block" placeholder="" maxlength="4"
                           value="<?= $event['block']?>">
                </div>
            </div>
            <div class="row">
                <div class="6u 12u$(medium)">
                    <label for="guest">Почетный гость</label>
                    <input type="text" name="guest" id="guest" value="<?= $event['guest']?>">
                </div>
                <div class="6u 12u$(medium)">
                    <label for="organizer">Организатор</label>
                    <input type="text" name="organizer" id="organizer" placeholder=""
                           value="<?= $event['organizer']?>">
                </div>
            </div>
            <div class="row">
                <div class="6u 12u$(medium)">
                    <label for="vk">Встреча в Vk</label>
                    <input type="text" name="vk" id="vk" placeholder="ссылка"
                           value="<?= $event['vk']?>">
                </div>
            </div>
            <div class="row">
                <div class="6u 12u$(medium)">
                    <span>Категория</span>
                    <div class="select-wrapper">
                        <select name="category">
                            <option value="<?= $event['category']?>"><?= $toggleCategory?></option>
                            <?php foreach ($categories as $value) { ?>
                                <option value="<?= $value['id']?>"><?= $value['name']?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="6u 12u$(medium)">
                    <span>Тип мероприятия</span>
                    <input type="checkbox" id="event-type" name="event_type" <?= $checked?>>
                    <label for="event-type">Открытое</label>
                </div>
            </div>
            <div class="row">
                <div class="12u 12u$(medium)">
                    <label for="description">Описание</label>
                    <textarea name="description" id="description" rows="6" maxlength="4096"><?= $event['description']?></textarea>
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
                <div class="12u 12u$">
                    <ul class="actions">
                        <li><input type="submit" value="Опубликовать" class="special"></li>
                    </ul>
                </div>
            </div>
            <input type="text" name="organizer" value="<?= $event['organizer']?>" style="display: none;">
            <input name="id" value="<?= $event['id']?>" style="display: none;">
            <input type="hidden" id="coord_x" name="coord_x" value="<?= $event['coord_x']?>">
            <input type="hidden" id="coord_y" name="coord_y" value="<?= $event['coord_y']?>">
            <input type="hidden" id="address">
        </form>
    </div>
    <div class="col" style="width: 50%;">
        <div id="eventsAddMap" style="margin: 0 auto; display: ;"></div>
    </div>
</div>

