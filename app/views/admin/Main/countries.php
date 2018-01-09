<div class="container">
    <div class="table-wrapper" style="margin-top: 30px;">
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>RU</th>
                <th>EN</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data as $v) { ?>
                <tr data-id="<?= $v['id'] ?>">
                    <td><?= $v['id'] ?></td>
                    <td><input name="ru" onchange="changeData(this, 'countries')" value="<?= $v['ru'] ?>"></td>
                    <td><input name="en" onchange="changeData(this, 'countries')" value="<?= $v['en'] ?>"></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>



