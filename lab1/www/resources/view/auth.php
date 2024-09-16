<h2>Форма входа</h2>
<form action="/auth" method="POST">
    <label for="username">Имя:</label><br>
    <input type="text" id="username" name="username" required><br><br>

    <label for="dbname">База данных:</label><br>
    <input type="text" id="dbname" name="dbname" required><br><br>

    <input type="submit" value="Войти">

    <?php if ($visible) echo $status ? 'Подключение успешно: <a href="/"> на главную </a>' : 'Ошибка'; ?>
</form>