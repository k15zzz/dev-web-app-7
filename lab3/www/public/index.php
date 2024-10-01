<?php

function validateEmail($email) {
    // Проверяем валидность адреса с помощью регулярного выражения
    $cleanedEmail = preg_replace('/[^a-zA-Z0-9@.]/', '', $email);
    if (filter_var($cleanedEmail, FILTER_VALIDATE_EMAIL)) {
        return $cleanedEmail;
    }
    return null; // Возвращаем метку, если email некорректен
}

function validateGender($gender) {
    // Проверяем допустимые значения пола
    return ($gender === 'male' || $gender === 'female') ? $gender : null;
}

function formatRecordNumber($number) {
    // Дополняем нулями до 6 знаков
    return str_pad($number, 6, '0', STR_PAD_LEFT);
}

function formatPhoneNumber($phone) {
    // Удаляем все символы, кроме цифр
    $digits = preg_replace('/\D/', '', $phone);

    // Определяем форматирование в зависимости от количества цифр
    if (strlen($digits) === 8) {
        return substr($digits, 0, 1) . '-' . substr($digits, 1, 3) . '-' . substr($digits, 4);
    } elseif (strlen($digits) === 9) {
        return substr($digits, 0, 2) . '-' . substr($digits, 2, 3) . '-' . substr($digits, 5);
    } elseif (strlen($digits) === 10) {
        return substr($digits, 0, 3) . '-' . substr($digits, 3, 3) . '-' . substr($digits, 6);
    }

    return null; // Устанавливаем метку, если номер некорректен
}

function formatWeight($weight) {
    // Приводим значение к числовому типу, если это возможно
    if (is_numeric($weight)) {
        return round((float) $weight);
    }
    return 0; // Некорректные значения веса приводим к 0
}

function cleanAddress($address) {
    // Очищаем адрес от недопустимых символов и заменяем пробелом, если недостаёт пробелов
    $cleanedAddress = preg_replace('/[^a-zA-Z0-9\s,]/', '', $address);
    return preg_replace('/(?<=\d)(?=[A-Za-z])/', ' ', $cleanedAddress);
}

function countErrorsAndTransform($data, &$emailErrors,  &$genderErrors, &$phoneError) {
    // Валидация электронной почты
    $validatedEmail = validateEmail($data[7]);
    if ($validatedEmail === null) {
        $emailErrors++;
    }

    // Валидация пола
    $validatedGender = validateGender($data[4]);
    if ($validatedGender === null) {
        $genderErrors++;
    }

    // Преобразование номера записи
    $data[0] = formatRecordNumber($data[0]);

    // Преобразование телефона
    $data[8] = formatPhoneNumber($data[8]);
    if ($data[8] === null) {
        $phoneError++;
    }

    // Преобразование веса
    $data[12] = formatWeight($data[12]);

    // Очистка почтового адреса от недопустимых символов
    $data[14] = cleanAddress($data[14]);

    // Обновляем массив данных с корректными значениями
    $data[4] = $validatedGender;
    $data[7] = $validatedEmail;

    return $data;
}

function processFile($filePath) {
    $totalErrors = 0;
    $emailErrors = 0;
    $genderErrors = 0;
    $phoneError = 0;

    $newData = [];

    $file = fopen($filePath, 'r');
    if (!$file) {
        die("Не удалось открыть файл.");
    }

    while (($line = fgets($file)) !== false) {
        $data = explode(',', trim($line));

        if (count($data) !== 17) {
            $totalErrors++;
            continue;
        }

        // Проверка, коррекция и преобразование данных
        $data = countErrorsAndTransform($data, $emailErrors, $genderErrors, $phoneError);
        $newData[] = $data;
    }

    fclose($file);

    // Записываем преобразованные данные в новый файл
    $newFilePath = 'NEWBASE.TXT';
    $newFile = fopen($newFilePath, 'w');
    if (!$newFile) {
        die("Не удалось создать новый файл.");
    }

    foreach ($newData as $data) {
        fputcsv($newFile, $data, ';');
    }

    fclose($newFile);

    // Вывод общего количества ошибок
    echo "Количество ошибок строк неправильного формата: $totalErrors<br>";
    echo "Количество ошибок электронной почты: $emailErrors<br>";
    echo "Количество ошибок пола: $genderErrors<br>";
    echo "Количество ошибок номера телефона: $phoneError<br><br>";
}

// Пример вызова функции
$filePath = 'OLDBASE.TXT';
processFile($filePath);



function calculateAge($birthDate) {
    $currentDate = new DateTime();
    $birthDateObj = DateTime::createFromFormat('m/d/Y', $birthDate);
    if ($birthDateObj) {
        return $birthDateObj->diff($currentDate)->y;
    }
    return null;
}

function processStatistics($data) {
    $statistics = [
        'male' => ['count' => 0, 'totalHeight' => 0, 'totalWeight' => 0, 'totalAge' => 0, 'people' => []],
        'female' => ['count' => 0, 'totalHeight' => 0, 'totalWeight' => 0, 'totalAge' => 0, 'people' => []]
    ];

    $holidays = ['01.01', '07.01', '14.02', '23.02', '08.03', '01.05', '31.12'];
    $birthdayGroups = [];

    foreach ($data as $person) {
        $gender = $person[4];
        if ($gender !== 'male' && $gender !== 'female') {
            continue;
        }

        $statistics[$gender]['count']++;
        $height = (int)$person[13];
        $statistics[$gender]['totalHeight'] += $height;

        $weight = (int)$person[12];
        $statistics[$gender]['totalWeight'] += $weight;

        $birthDate = $person[9];
        $age = calculateAge($birthDate);
        if ($age !== null) {
            $statistics[$gender]['totalAge'] += $age;
        }

        $statistics[$gender]['people'][] = [
            'name' => $person[1],
            'height' => $height,
            'weight' => $weight,
            'age' => $age
        ];

        $birthDayMonth = DateTime::createFromFormat('m/d/Y', $birthDate)->format('d.m');
        if (in_array($birthDayMonth, $holidays)) {
            $birthdayGroups[$birthDayMonth][] = $person[1];
        }
    }

    return ['statistics' => $statistics, 'birthdays' => $birthdayGroups];
}

function outputStatistics($stats) {
    echo "<!DOCTYPE html><html lang='ru'><head><meta charset='UTF-8'>";
    echo "<title>Статистика</title>";
    echo "<style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background-color: #f4f4f4; }
        h2, h3 { color: #333; }
        ul { list-style-type: none; padding: 0; }
        li { margin: 5px 0; }
        .header { background-color: #007BFF; color: white; padding: 10px; text-align: center; }
    </style>";
    echo "</head><body>";

    echo "<div class='header'><h1>Статистика</h1></div>";

    foreach (['male' => 'мужчин', 'female' => 'женщин'] as $gender => $genderName) {
        $count = $stats['statistics'][$gender]['count'];
        if ($count > 0) {
            // Округляем средние значения до целых чисел
            $averageHeight = round($stats['statistics'][$gender]['totalHeight'] / $count);
            $averageWeight = round($stats['statistics'][$gender]['totalWeight'] / $count);
            $averageAge = round($stats['statistics'][$gender]['totalAge'] / $count);

            echo "<h2>Статистика для $genderName</h2>";
            echo "<table>
                <tr><th>Количество</th><th>Средний рост</th><th>Средний вес</th><th>Средний возраст</th></tr>
                <tr><td>$count</td><td>$averageHeight</td><td>$averageWeight</td><td>$averageAge</td></tr>
            </table>";

            $belowAverage = ['height' => 0, 'weight' => 0, 'age' => 0];
            $average = ['height' => 0, 'weight' => 0, 'age' => 0];
            $aboveAverage = ['height' => 0, 'weight' => 0, 'age' => 0];

            foreach ($stats['statistics'][$gender]['people'] as $person) {
                // Сравнение роста
                if ($person['height'] < $averageHeight) {
                    $belowAverage['height']++;
                } elseif ($person['height'] > $averageHeight) {
                    $aboveAverage['height']++;
                } else {
                    $average['height']++;
                }

                // Сравнение веса
                if ($person['weight'] < $averageWeight) {
                    $belowAverage['weight']++;
                } elseif ($person['weight'] > $averageWeight) {
                    $aboveAverage['weight']++;
                } else {
                    $average['weight']++;
                }

                // Сравнение возраста
                if ($person['age'] < $averageAge) {
                    $belowAverage['age']++;
                } elseif ($person['age'] > $averageAge) {
                    $aboveAverage['age']++;
                } else {
                    $average['age']++;
                }
            }

            echo "<h3>Распределение по $genderName</h3>";
            echo "<table>
                <tr><th>Характеристика</th><th>Ниже среднего</th><th>Средний</th><th>Выше среднего</th></tr>
                <tr><td>Рост</td><td>{$belowAverage['height']}</td><td>{$average['height']}</td><td>{$aboveAverage['height']}</td></tr>
                <tr><td>Вес</td><td>{$belowAverage['weight']}</td><td>{$average['weight']}</td><td>{$aboveAverage['weight']}</td></tr>
                <tr><td>Возраст</td><td>{$belowAverage['age']}</td><td>{$average['age']}</td><td>{$aboveAverage['age']}</td></tr>
            </table>";
        }
    }

    echo "<h2>Люди, родившиеся в праздничные дни</h2>";
    foreach ($stats['birthdays'] as $date => $names) {
        echo "<h3>$date</h3>";
        echo "<ul>";
        foreach ($names as $name) {
            echo "<li>$name</li>";
        }
        echo "</ul>";
    }

    echo "</body></html>";
}

function processFileStats($filePath) {
    $data = [];

    $file = fopen($filePath, 'r');
    if (!$file) {
        die("Не удалось открыть файл.");
    }

    while (($line = fgets($file)) !== false) {
        $data[] = explode(';', trim($line));
    }

    fclose($file);

    $stats = processStatistics($data);
    outputStatistics($stats);
}

// Пример вызова функции
$filePath = 'NEWBASE.TXT';
processFileStats($filePath);


// Запросы GET
function processFileForRegion($filePath, $region) {
    $data = [];
    $file = fopen($filePath, 'r');
    if (!$file) {
        die("Не удалось открыть файл.");
    }

    while (($line = fgets($file)) !== false) {
        $data[] = explode(';', trim($line));
    }

    fclose($file);

    // Фильтрация записей по области
    $filteredData = array_filter($data, function($person) use ($region) {
        return trim($person[6]) === $region;
    });

    // Сортировка записей по фамилии (индекс 3)
    usort($filteredData, function($a, $b) {
        return strcmp($a[3], $b[3]);
    });

    return $filteredData;
}

function displayRegionResidents($filteredData) {
    echo "<!DOCTYPE html><html lang='ru'><head><meta charset='UTF-8'>";
    echo "<title>Жители области</title>";
    echo "<style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background-color: #f4f4f4; }
        .female { color: pink; }
        .male { color: lightblue; }
        .header { background-color: #007BFF; color: white; padding: 10px; text-align: center; }
    </style>";
    echo "</head><body>";

    echo "<div class='header'><h1>Жители области</h1></div>";

    if (empty($filteredData)) {
        echo "<p>Нет данных для выбранной области.</p>";
    } else {
        echo "<table>
            <tr><th>Имя</th><th>Фамилия</th><th>Пол</th><th>Возраст</th><th>Почтовый адрес</th></tr>";
        foreach ($filteredData as $person) {
            $name = htmlspecialchars($person[1]);
            $surname = htmlspecialchars($person[3]);
            $gender = htmlspecialchars($person[4]);
            $age = calculateAge($person[9]);
            $address = htmlspecialchars($person[14] . ', ' . $person[15] . ', ' . $person[16]);

            // Определяем класс для окрашивания имени в зависимости от пола
            $nameClass = ($gender === 'female') ? 'female' : 'male';

            echo "<tr>
                <td class='$nameClass'>$name</td>
                <td>$surname</td>
                <td>$gender</td>
                <td>$age</td>
                <td>$address</td>
            </tr>";
        }
        echo "</table>";
    }

    echo "</body></html>";
}

function processRequest($filePath) {
    // Получаем область из GET-запроса
    $region = isset($_GET['region']) ? trim($_GET['region']) : null;

    if ($region) {
        $filteredData = processFileForRegion($filePath, $region);
        displayRegionResidents($filteredData);
    } else {
        echo "Пожалуйста, укажите область через параметр 'region' в URL.";
    }
}

// Пример вызова функции
$filePath = 'NEWBASE.TXT';
processRequest($filePath);