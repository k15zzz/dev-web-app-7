<a href="/">На главную</a>

<h1>Поиск модели палатки по вместимости</h1>

<form action="/search-capacity" method="POST">
    <label for="capacity">Введите вместимость палатки (количество человек):</label>
    <br>
    <input type="number" id="capacity" name="capacity" required>
    <br><br>
    <input type="submit" value="Найти модель палатки">
</form>

<h2>Результаты поиска:</h2>

<?php if (!empty($rows)): ?>
    <?php foreach ($rows as $tent): ?>
        <div class="tent-item">
            <h3>Модель палатки: <?= $tent['model'] ?></h3>
            <p>Бренд: <?= $tent['brand'] ?></p>
            <p>Тип палатки: <?= $tent['type'] ?></p>
            <p>Вместимость: <?= $tent['capacity'] ?> человек</p>
            <p>Описание: <?= $tent['description'] ?></p>

            <?php if (!empty($tent['photo'])): ?>
                <img src="/assets/<?= $tent['photo'] ?>" alt="<?= $tent['model'] ?>"
                     style="max-width: 100%; height: auto;">
            <?php else: ?>
                <p>Изображение недоступно</p>
            <?php endif; ?>
        </div>
        <hr>
    <?php endforeach; ?>
<?php else: ?>
    <p>Палатки с указанной вместимостью не найдены.</p>
<?php endif; ?>
