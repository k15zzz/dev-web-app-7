<?php

namespace Core\Base\Http;

use Core\Base\Model;

class Response
{
    protected $statusCode = 200;
    protected $headers = [];
    protected $content;

    public function __construct($content = '', $statusCode = 200, array $headers = [])
    {
        $this->setContent($content);
        $this->setStatusCode($statusCode);
        $this->setHeaders($headers);
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function setHeaders(array $headers)
    {
        $this->headers = array_merge($this->headers, $headers);
        return $this;
    }

    public function setContent($content)
    {
        if ($content instanceof Model) {
            $this->content = $content->toJson();
            $this->setHeaders(['Content-Type' => 'application/json']);
        } else {
            $this->content = $content;
        }
        return $this;
    }

    public function sendHeaders()
    {
        if (headers_sent()) {
            return $this;
        }

        // Отправка статус-кода
        http_response_code($this->statusCode);

        // Отправка заголовков
        foreach ($this->headers as $header => $value) {
            header("$header: $value");
        }

        return $this;
    }

    public function sendContent()
    {
        echo $this->content;
        return $this;
    }

    public function send()
    {
        $this->sendHeaders();
        $this->sendContent();
        return $this;
    }

    public function json()
    {
        $this->sendHeaders();
        return $this->getContent();
    }
}
