<?php

namespace App\Http\Controllers;

use App\Http\Request\DateKeywordRequest;
use App\Http\Request\RubricDateRequest;
use App\Http\Request\RubricInitialRequest;
use App\Http\Request\RubricKeywordRequest;
use App\Http\Request\RubricYearRequest;
use Core\Base\Layers\Controller;
use Core\View;

class ArticleController extends Controller
{
    /**
     * Поиск статей по рубрике и дате
     */
    public function searchByRubricAndDate(RubricDateRequest $request): string
    {
        $articles = $this->getArticlesByRubricAndDate($request->rubric, $request->date);

        if (empty($articles)) {
            return View::render('search-by-rubric-and-date', ['articles' => $articles], 'default');
        }

        $forbidden = '\/:*?"<>|+%!@«»';
        usort($articles, fn($a, $b) => strcmp(
            preg_replace("/[${forbidden}]/", '', $a['title']),
            preg_replace("/[${forbidden}]/", '', $b['title']),
        ));

        return View::render('search-by-rubric-and-date', ['articles' => $articles], 'default');
    }

    public function searchByRubricAndDatePage(RubricDateRequest $request): string
    {
        return View::render('search-by-rubric-and-date', ['articles' => []], 'default');
    }

    /**
     * Поиск статей по дате и ключевому слову
     */
    public function searchByDateAndKeyword(DateKeywordRequest $request): string
    {
        $articles = $this->getArticlesByDate($request->date);

        $foundArticles = [];
        foreach ($articles as $article) {
            if (stripos($article['text'], $request->keyword) !== false) {
                $foundArticles[] = $article;
            }
        }

        return View::render('search-by-date-and-keyword', ['articles' => $foundArticles], 'default');
    }

    public function searchByDateAndKeywordPage(RubricDateRequest $request): string
    {
        return View::render('search-by-date-and-keyword', ['articles' => []], 'default');
    }

    /**
     * Поиск статей по рубрике и ключевому слову
     */
    public function searchByRubricAndKeyword(RubricKeywordRequest $request): string
    {
        $articles = $this->getArticlesByRubric($request->rubric);

        $foundArticles = [];
        foreach ($articles as $article) {
            if (stripos($article['text'], $request->keyword) !== false) {
                $foundArticles[] = $article;
            }
        }

        return View::render('search-by-rubric-and-keyword', ['articles' => $foundArticles], 'default');
    }

    public function searchByRubricAndKeywordPage(RubricDateRequest $request): string
    {
        return View::render('search-by-rubric-and-keyword', ['articles' => []], 'default');
    }

    /**
     * Поиск статей по рубрике и дате с латинскими буквами
     */
    public function searchByRubricDateWithLatin(RubricDateRequest $request): string
    {
        $articles = $this->getArticlesByRubricAndDate($request->rubric, $request->date);

        $foundArticles = [];
        foreach ($articles as $article) {
            if (preg_match('/[a-zA-Z]/', $article['title'])) {
                $foundArticles[] = $article;
            }
        }

        return View::render('search-by-rubric-date-with-latin', ['articles' => $foundArticles], 'default');
    }

    public function searchByRubricDateWithLatinPage(RubricDateRequest $request): string
    {
        return View::render('search-by-rubric-date-with-latin', ['articles' => []], 'default');
    }

    /**
     * Поиск статей по рубрике и начальной букве
     */
    public function searchByRubricAndInitial(RubricInitialRequest $request): string
    {
        $articles = $this->getArticlesByRubric($request->rubric);

        $foundArticles = [];
        foreach ($articles as $article) {
//            echo "<pre>";
//            var_dump([$article['title'], $request->initial, mb_strpos($article['title'], $request->initial)]);
//            echo "</pre>";
            if (mb_strpos($article['title'], $request->initial) === 1) {
                $foundArticles[] = $article;
            }
        }

        return View::render('search-by-rubric-and-initial', ['articles' => $foundArticles], 'default');
    }


    public function searchByRubricAndInitialPage(RubricDateRequest $request): string
    {
        return View::render('search-by-rubric-and-initial', ['articles' => []], 'default');
    }

    /**
     * Поиск статей по рубрике, дате и содержащих год
     */
    public function searchByRubricDateContainingYear(RubricYearRequest $request): string
    {
        $articles = $this->getArticlesByRubricAndDate($request->rubric, $request->date);

        $foundArticles = [];
        foreach ($articles as $article) {
            if (stripos($article['text'], $request->year) !== false) {
                $foundArticles[] = $article;
            }
        }

        return View::render('search-by-rubric-and-year', ['articles' => $foundArticles], 'default');
    }

    public function searchByRubricDateContainingYearPage(RubricDateRequest $request): string
    {
        return View::render('search-by-rubric-and-year', ['articles' => []], 'default');
    }

    private function getArticlesByRubricAndDate(string $rubric, string $date): array
    {
        $date = str_replace("-", "", $date);

        $folderPath = __DIR__ . "/../../../resources/articles/{$date}/";

        $articles = [];
        if (!is_dir($folderPath)) return $articles;

        $files = scandir($folderPath);

        foreach ($files as $file) {
//            echo "<pre>";
//            var_dump([$file, $rubric, stripos($file, $rubric) !== false, pathinfo($file, PATHINFO_EXTENSION) === 'txt', "-"]);
//            echo "</pre>";
            if (stripos($file, $rubric) !== false && pathinfo($file, PATHINFO_EXTENSION) === 'txt') {
                $content = file_get_contents($folderPath . $file);
                $articles[] = [
                    'title' => $this->extractTitle($content),
                    'text' => $content,
                    'rubric' => $rubric
                ];
            }
        }

        return $articles;
    }

    private function getArticlesByDate(string $date): array
    {
        return $this->getArticlesByRubricAndDate("", $date);
    }

    private function getArticlesByRubric(string $rubric): array
    {
        $allArticles = [];
        $dates = scandir(__DIR__ . "/../../../resources/articles/");
        foreach ($dates as $date) {
            $allArticles = array_merge($allArticles, $this->getArticlesByRubricAndDate($rubric, $date));
        }
        return $allArticles;
    }

    private function extractTitle(string $content): string
    {
        $lines = explode("\n", $content);
        return trim($lines[0]);
    }
}
