<?php

namespace R3H6\Oauth2Server\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface Oauth2ServerInterface
{
    public function handleRequest(ServerRequestInterface $request): ResponseInterface;
}
