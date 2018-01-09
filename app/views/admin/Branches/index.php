<div class="container admin-objects-list">
    <div class="row menu">
        <a href="/admin/branches/add" class="button small">Добавить филиал</a>
    </div>
    <div class="table-wrapper" style="margin-top: 30px;">
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Адрес</th>
                <th>Ответственный</th>
                <th>ID user</th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data as $branch) { ?>
                <tr data-id="<?= $branch['id']; ?>">
                    <td><?= $branch['id']; ?></td>
                    <td><a href="/branches?id=<?= $branch['id']; ?>"><?= $branch['address']; ?></a></td>
                    <td><?= $branch['nickname']; ?></td>
                    <td><input onchange="changeData(this, 'branches')" name="user_id" value="<?= $branch['user_id'] ?>"></td>
                    <td><a href="/admin/branches/delete?id=<?= $branch['id']; ?>" class="button small">Удалить</a></td>
                    <td><a href="/admin/branches/edit?id=<?= $branch['id']; ?>" class="button small">Редактировать</a></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>



