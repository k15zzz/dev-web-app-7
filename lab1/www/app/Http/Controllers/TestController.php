<?php

namespace App\Http\Controllers;

use App\Http\Request\TestRequest;
use App\Services\TestService;
use Core\Base\Http\HttpStatus;
use Core\Base\Http\Response;
use Core\Base\Layers\Controller;
use Core\View;

class TestController extends Controller
{
    private TestService $service;

    public function __construct(TestService $service)
    {
        $this->service = $service;
    }

    public function show(TestRequest $request)
    {
        print_r($request);
        $data = $this->service->getData();
        $response = new Response($data, HttpStatus::HTTP_OK);
        return $response->json();
    }

    public function showView()
    {
        $data = $this->service->getData();
        return View::render('test', ['data' => $data]);
    }
}