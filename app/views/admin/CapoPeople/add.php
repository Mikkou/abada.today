<div class="container" style="margin-top: 20px;">
    <form method="post" action="/admin/capo-people/save" enctype="multipart/form-data" class="add-event">
        <div class="row">
            <div class="3u 6u$(xsmall)">
                <label for="apelido">Апелиду</label>
                <input type="text" name="apelido" id="apelido" value="">
            </div>
            <div class="3u 6u$(xsmall)">
                <label for="name">Имя</label>
                <input type="text" name="name" id="name" placeholder="" value="">
            </div>
        </div>
        <div class="row">
            <div class="3u 6u$(xsmall)">
                <label for="country">Страна</label>
                <input type="text" name="country" id="country" value="">
            </div>
            <div class="3u 6u$(xsmall)">
                <label for="city">Город</label>
                <input type="text" name="city" id="city" placeholder="" value="">
            </div>
        </div>
        <div class="row">
            <div class="3u 6u$(xsmall)">
                <label for="last_corda_year">Год получения последней корды</label>
                <input type="text" name="last_corda_year" id="last_corda_year" value="">
            </div>
            <div class="3u 6u$(xsmall)">
                <label for="curator">Куратор</label>
                <input type="text" name="curator" id="curator" placeholder="" value="">
            </div>
        </div>
        <div class="row">
            <div class="3u 6u$(xsmall)">
                <label for="vk">Вк аккаунт</label>
                <input type="text" name="vk" id="vk" placeholder="ссылка" value="">
            </div>
            <div class="3u 6u$(xsmall)">
                <label for="image">Фотография</label>
                <input type="text" name="image" id="image" placeholder="ссылка" value="">
            </div>
        </div>
        <div class="row">
            <div class="3u 6u$(xsmall)">
                <label for="corda">Корда</label>
                <input type="text" name="corda" id="corda" value="">
            </div>
            <div class="3u 6u$(xsmall)">
                <label for="gender">м/ж</label>
                <input type="text" name="gender" id="gender" value="">
            </div>

        </div>
        <div class="row">
            <div class="6u 12u$(medium)">
                <input type="checkbox" id="practiced" name="practiced" checked="">
                <label for="practiced">Занимается капой</label>
            </div>
        </div>

        <!-- Break -->
        <div class="row">
            <div class="12u$">
                <ul class="actions">
                    <li><input type="submit" value="Добавить" class="special"></li>
                </ul>
            </div>
        </div>
    </form>
</div>

