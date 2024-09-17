<h1>Поиск статей по дате и ключевому слову</h1>

<form action="/searchByDateAndKeyword" method="POST">
    <label for="date">Введите дату (год месяц день):</label>
    <input type="date" id="date" name="date" value="2019-09-06" required>
    <br><br>

    <label for="keyword">Введите ключевое слово:</label>
    <input type="text" id="keyword" name="keyword" placeholder="например, вперед" required>
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