<div class="form">
    <h2 style="margin-left: 10px;">Войти в админку</h2>
    <div class="row" style="margin: 0 auto;">
        <div class="col-md-6">
            <form method="post" action="/admin/main/login">
                <div class="form-group">
                    <label for="login"></label>
                    <input type="text" name="login" class="form-control" id="login" placeholder="Логин">
                </div>
                <div class="form-group">
                    <label for="password"></label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Пароль">
                </div>
                <button type="submit" style="width: 100%; margin: 20px 0 0 0;" class="btn btn-default">Вход</button>
            </form>
        </div>
    </div>
</div>