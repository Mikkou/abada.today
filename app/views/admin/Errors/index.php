<style>
    .error-menu {
        background-color: rgba(230, 235, 237, 0.25);
        height:50px;
        margin: 0;
    }
</style>
<div class="row error-menu">
    <a href="/admin/errors/clean" class="button">Очистить список</a>
</div>

<div class="container">
    <div class="table-wrapper" style="margin-top: 30px;">
        <table>
            <thead>
            <tr>
                <th>Дата</th>
                <th>Текст</th>
                <th>Файл</th>
                <th>Строка</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($errors as $error) { ?>
                <tr>
                    <td><?= $error['date'] ?></td>
                    <td>
                        <a href="#" class="button" onclick="alert('<?= $error['text'] ?>//')">Нажать</a>
                    </td>
                    <td><?= $error['file'] ?></td>
                    <td><?= $error['line'] ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>



