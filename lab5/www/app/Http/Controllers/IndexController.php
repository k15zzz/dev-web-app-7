<?php

namespace App\Http\Controllers;

use App\Repositories\PostRepository;
use Core\Base\Layers\Controller;
use Core\Exceptions\ViewNotFoundException;
use Core\View;

class IndexController extends Controller
{
    /**
     * @throws ViewNotFoundException
     */
    public function renderPage(PostRepository $repository): string
    {
        return View::render('index', [
            'names' => $repository->getPublicationNames(),
            'types' => $repository->getPublicationTypes(),
        ], 'default');
    }
}