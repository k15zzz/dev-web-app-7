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
     * @param string|null $layout - название файла layout (если используется)
     * @return string - HTML содержимое
     * @throws ViewNotFoundException - если шаблон не найден
     */
    public static function render(string $view, array $data = [], string $layout = null): string
    {
        // Путь к файлу представления
        $viewPath = __DIR__ . "/../resources/view/$view.php";

        if (!file_exists($viewPath)) {
            throw new ViewNotFoundException("View file '$view.php' not found.");
        }

        // Извлекаем данные для передачи в представление
        extract($data);

        // Буферизируем выходной поток для представления
        ob_start();
        include_once $viewPath;
        $content = ob_get_clean();

        // Если используется layout, подключаем его
        if ($layout) {
            $layoutPath = __DIR__ . "/../resources/layouts/$layout.php";
            if (!file_exists($layoutPath)) {
                throw new ViewNotFoundException("Layout file '$layout.php' not found.");
            }

            // Включаем layout и передаём в него контент
            ob_start();
            include_once $layoutPath;
            return ob_get_clean();
        }

        // Возвращаем рендер без layout, если он не был указан
        return $content;
    }
}
