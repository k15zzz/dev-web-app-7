<?php

namespace App\Services;

use App\Repositories\TestRepository;
use Core\Base\Layers\Service;

class TestService extends Service
{
    private TestRepository $rep;

    public function __construct(
        TestRepository $rep,
    )
    {
        $this->rep = $rep;
    }

    public function getData(): string
    {
        return $this->rep->read();
    }
}