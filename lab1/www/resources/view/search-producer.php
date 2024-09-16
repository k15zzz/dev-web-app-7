<a href="/">На главную</a>

<h1>Поиск модели палатки по производителю</h1>

<form action="/search-producer" method="POST">
    <label for="producer">Введите название фирмы производителя:</label>
    <br>
    <input type="text" id="producer" name="producer" required>
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
    <p>Палатки данного производителя не найдены.</p>
<?php endif; ?>
