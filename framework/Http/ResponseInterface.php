<?php

namespace Mkoveni\Lani\Http;

interface ResponseInterface
{
    public function withJson($data, $status = 200);

    public function withStatus(int $status, string $message = '');

    public function withHeader($key, $value);

    public function withBody(string $body);

    public function withRedirect(string $url, $status = null);
}