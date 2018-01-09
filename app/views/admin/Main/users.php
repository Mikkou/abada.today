<div class="table-wrapper" style="margin-top: 30px;">
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Апелиду</th>
            <th>Фамилия</th>
            <th>Имя</th>
            <th>Почта</th>
            <th>Права</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data as $user) { ?>
            <tr data-id="<?= $user['id'] ?>">
                <td><?= $user['id'] ?></td>
                <td><?= $user['nickname'] ?></td>
                <td><?= $user['lastname'] ?></td>
                <td><?= $user['firstname'] ?></td>
                <td><?= $user['email'] ?></td>
                <td><input onchange="changeData(this, 'users')" name="rights" value="<?= $user['rights'] ?>"></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
