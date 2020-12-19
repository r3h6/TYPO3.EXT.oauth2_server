<?php

namespace R3H6\Oauth2Server\Middleware;

use TYPO3\CMS\Core\Http\NullResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use R3H6\Oauth2Server\Domain\Configuration;
use TYPO3\CMS\Core\Http\DispatcherInterface;

class AuthorizationHandler implements MiddlewareInterface
{
    /**
     * @var DispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var Configuration
     */
    protected $configuration;

    /**
     * @param DispatcherInterface $dispatcher
     */
    public function __construct(DispatcherInterface $dispatcher, Configuration $configuration)
    {
        $this->dispatcher = $dispatcher;
        $this->configuration = $configuration;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var \TYPO3\CMS\Core\Site\Entity\Site $site */
        $site = $request->getAttribute('site');
        $configuration = $site->getConfiguration()['oauth2'] ?? null;

        if ($configuration === null) {
            return $handler->handle($request);
        }

        $this->configuration->merge($configuration);

        $endpoint = $request->getMethod() . ':' . trim($request->getUri()->getPath(), '/');
        $target = $this->configuration->get('server.routes.'.$endpoint);

        if ($target === null) {
            return $handler->handle($request);
        }

        $request = $request->withAttribute('target', $target);
        return $this->dispatcher->dispatch($request);
    }
}
