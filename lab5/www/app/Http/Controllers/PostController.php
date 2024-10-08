<?php

namespace App\Http\Controllers;

use App\Http\Middleware\AuthMiddleware;
use App\Http\Request\CalculateFreeRequest;
use App\Http\Request\CapacityRequest;
use App\Http\Request\ProducerRequest;
use App\Repositories\PostRepository;
use Core\Base\Http\Response;
use Core\Base\Layers\Controller;
use Core\Exceptions\ViewNotFoundException;
use Core\View;

class PostController extends Controller
{
    /**
     * @throws ViewNotFoundException
     */
    public function calculateFree(CalculateFreeRequest $request, PostRepository $repository)
    {
        $costPerPage = $repository->getPrice($request->material, $request->publication_type);

        if ($costPerPage) {
            return $costPerPage * $request->pages;
        }

        return "Ошибка расчета";
    }
}