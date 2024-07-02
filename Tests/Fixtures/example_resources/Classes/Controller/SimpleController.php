<?php

namespace R3H6\ExampleResources\Controller;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class SimpleController
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
    ) {}

    public function handleRequest(ServerRequestInterface $request): ResponseInterface
    {
        $data = ['message' => 'Hello World!'];
        $response = $this->responseFactory->createResponse()
            ->withHeader('Content-Type', 'application/json');
        $response->getBody()->write((string)json_encode($data));
        return $response;
    }
}
