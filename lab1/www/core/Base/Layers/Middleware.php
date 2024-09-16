<?php

namespace Core\Base\Layers;

use Core\Base\Layer;

class Middleware extends Layer
{
    protected $next;

    public function setNext($next)
    {
        $this->next = $next;
    }

    public function handle($request)
    {
        if ($this->next) {
            return $this->next->handle($request);
        }

        return $request;
    }
}
