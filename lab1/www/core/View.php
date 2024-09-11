<?php

namespace Core;

use Core\Exceptions\ViewNotFoundException;

class View
{
    /**
     * Рендерит HTML-шаблон.
     *
     * @param string $view - путь к файлу представления
     * @param array $data - данные для передачи в представление
     * @return string - HTML содержимое
     * @throws ViewNotFoundException - если шаблон не найден
     */
    public static function render(string $view, array $data = []): string
    {
        $viewPath = __DIR__ . "/../resources/view/$view.php";

        if (!file_exists($viewPath)) {
            throw new ViewNotFoundException("View file '$view.php' not found.");
        }

        extract($data);

        ob_start();

        include_once $viewPath;

        return ob_get_clean();
    }
}
