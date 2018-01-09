<div class="container admin-objects-list">
    <div class="row menu">
        <a href="/admin/events/add" class="button small">Добавить событие</a>
    </div>

    <div class="table-wrapper" style="margin-top: 30px;">

        <table>
            <thead>
            <tr>
                <th>Название</th>
                <th>Дата начала</th>
                <th>Страна</th>
                <th>Город</th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data as $event) { ?>
                <tr data-id="<?= $event['id'] ?>">
                    <td><a href="/events?id=<?= $event['id']; ?>"><?=(empty($event['name'])) ? 'Мероприятие' : $event['name'] ?></a></td>
                    <td><?= $event['begin_date'] ?></td>
                    <td><input onchange="changeData(this, 'events')" name="country" value="<?= $event['country'] ?>"></td>
                    <td><input onchange="changeData(this, 'events')" name="city" value="<?= $event['city'] ?>"></td>
                    <td><a href="/admin/events/delete?id=<?= $event['id']; ?>" class="button small">Удалить</a></td>
                    <td><a href="/admin/events/edit?id=<?= $event['id']; ?>" class="button small">Редактировать</a></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>



