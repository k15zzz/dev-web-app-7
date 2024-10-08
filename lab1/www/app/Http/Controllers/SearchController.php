<?php

namespace App\Http\Controllers;

use App\Http\Middleware\AuthMiddleware;
use App\Http\Request\CapacityRequest;
use App\Http\Request\ProducerRequest;
use App\Repositories\PostRepository;
use Core\Base\Layers\Controller;
use Core\Exceptions\ViewNotFoundException;
use Core\View;

class SearchController extends Controller
{
    /**
     * @throws ViewNotFoundException
     */
    public function searchProducer(AuthMiddleware $middleware, ProducerRequest $request, PostRepository $repository): string
    {
        return View::render('search-producer', ['rows' => $repository->searchByBrand($request->producer)], 'default');
    }

    /**
     * @throws ViewNotFoundException
     */
    public function searchProducerPage(AuthMiddleware $middleware): string
    {
        return View::render('search-producer', ['rows' => []], 'default');
    }

    /**
     * @throws ViewNotFoundException
     */
    public function searchCapacity(AuthMiddleware $middleware, CapacityRequest $request, PostRepository $repository): string
    {
        return View::render('search-capacity', ['rows' => $repository->searchByCapacity($request->capacity)], 'default');
    }

    /**
     * @throws ViewNotFoundException
     */
    public function searchCapacityPage(AuthMiddleware $middleware): string
    {
        return View::render('search-capacity', ['rows' => []], 'default');
    }
}