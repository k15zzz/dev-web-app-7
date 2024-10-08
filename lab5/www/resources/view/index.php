<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #ffffff;
        padding: 20px;
    }

    form {
        max-width: 400px;
        margin: auto;
        background-color: #ffffff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-size: 14px;
        color: #333;
        padding-top: 15px;
    }

    select, input, textarea {
        width: -webkit-fill-available;
        padding: 10px;
        border: 1px solid #d1cfcf;
        border-radius: 8px;
        background-color: #fff;
        font-size: 14px;
        color: #555;
    }

    select:focus, input:focus, textarea:focus {
        border-color: #b5a197;
        outline: none;
        box-shadow: 0 0 5px rgba(181, 161, 151, 0.5);
    }

    textarea {
        height: 50px;
        resize: none;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }

    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>

<form id="conference-form">
    <label for="material">Название сборника материалов конференции</label>
    <select id="material" name="material">
        <?php foreach ($names as $name) : ?>
            <option value='<?= $name['name'] ?>'><?= $name['name'] ?></option>
        <?php endforeach; ?>
    </select>

    <label for="publication-type">Тип публикации</label>
    <select id="publication-type" name="publication-type">
        <?php foreach ($types as $type) : ?>
            <option value='<?= $type['type'] ?>'><?= $type['type'] ?></option>
        <?php endforeach; ?>
    </select>

    <label for="pages">Число страниц</label>
    <input type="number" id="pages" name="pages" min="1" value="1" required>

    <label for="fee">Величина оргвзноса</label>
    <textarea id="fee" name="fee" readonly></textarea>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('conference-form').addEventListener('submit', function (e) {
            e.preventDefault();
        });

        const materialElement = document.getElementById('material');
        const publicationTypeElement = document.getElementById('publication-type');
        const pagesElement = document.getElementById('pages');
        const feeElement = document.getElementById('fee');

        [materialElement, publicationTypeElement, pagesElement].forEach(function(element) {
            element.addEventListener('change', function () {
                let material = materialElement.value;
                let publicationType = publicationTypeElement.value;
                let pages = parseInt(pagesElement.value);

                // Проверяем, чтобы количество страниц было минимум 1
                if (pages < 1) {
                    pagesElement.value = 1;
                    pages = 1;
                }

                if (material && publicationType && pages) {
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', '/api/post/calculate-free', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                    // Отправляем данные
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            // Обновляем поле fee с ответом
                            feeElement.value = xhr.responseText;
                        }
                    };

                    // Формируем данные для отправки
                    const data = `material=${encodeURIComponent(material)}&publication_type=${encodeURIComponent(publicationType)}&pages=${encodeURIComponent(pages)}`;
                    xhr.send(data);
                }
            });
        });
    });
</script>