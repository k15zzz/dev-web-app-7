<h1>Поиск статей по рубрике и дате, содержащих год</h1>

<form action="/searchByRubricAndYear" method="POST">
    <label for="rubric">Выберите рубрику:</label>
    <input type="radio" id="tech" name="rubric" value="tech" required>
    <label for="tech">Технологии</label>
    <input type="radio" id="sport" name="rubric" value="sport" required>
    <label for="sport">Спорт</label>
    <br><br>

    <label for="date">Введите дату (год месяц день):</label>
    <input type="date" id="date" name="date" value="2019-09-06" required>
    <br><br>

    <label for="year">Введите год:</label>
    <input type="number" id="year" name="year" value="2019" min="1" max="2024" required>
    <br><br>

    <input type="submit" value="Найти статьи">
</form>

<br>
<hr>
<br>

<?php

if (empty($articles)) {
    echo "Найдено 0 статей";
    return;
}

$output = "<h2>Найденные статьи:</h2>";
foreach ($articles as $article) {
    $output .= "<h3>{$article['title']}</h3>";
    $output .= "<p>{$article['text']}</p><hr>";
}

echo $output;