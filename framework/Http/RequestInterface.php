<?php

namespace Mkoveni\Lani\Http;

interface RequestInterface
{
    public function all(): array;

    public function getParam($key, $default = null): ?string;

    public function getHeader($key, $default = null): ?string;

    public function getRequestMethod(): ?string;

    public function isAjax(): bool;
}