<div class="form">
    <div class="col-md-12" style="width: 100%; padding: 0;">
        <h2><?=$langT['personal_data']?></h2>
        <form method="post" action="/personal/save">
            <div class="form-group">
                <label for="nickname"><?=$langT['apelido']?></label>
                <input type="text" id="nickname" name="nickname" class="form-control" value="<?= $data['nickname'] ?>">
            </div>
            <div class="form-group">
                <label for="lastname"><?=$langT['lastname']?></label>
                <input type="text" id="lastname" name="lastname" class="form-control" value="<?= $data['lastname'] ?>">
            </div>
            <div class="form-group">
                <label for="firstname"><?=$langT['firstname']?></label>
                <input type="text" id="firstname" name="firstname" class="form-control" value="<?= $data['firstname'] ?>">
            </div>
            <input type="text" name="id" value="<?= $data['id'] ?>" style="display: none;">
            <button type="submit" style="width: 100%; margin: 20px 0 0 0;" class="btn btn-default"><?=$langT['save'] ?></button>
        </form>
    </div>
</div>