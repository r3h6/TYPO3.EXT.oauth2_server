<?php

namespace R3H6\Oauth2Server\Middleware;

use TYPO3\CMS\Core\Http\NullResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use R3H6\Oauth2Server\Domain\Configuration;
use TYPO3\CMS\Core\Http\DispatcherInterface;

class AuthorizationHandler implements MiddlewareInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

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
        $this->logger->debug('Oauth', ['uri' => (string) $request->getUri(), 'method' => $request->getMethod(), 'headers' => $request->getHeaders(), 'body' => (string) $request->getBody()]);

        /** @var \TYPO3\CMS\Core\Site\Entity\Site $site */
        $site = $request->getAttribute('site');
        $configuration = $site->getConfiguration()['oauth2'] ?? null;

        if ($configuration === null) {
            return $handler->handle($request);
        }

        $this->configuration->merge($configuration);


        $method = $request->getHeader('X-REQUEST_METHOD')[0] ?? $request->getParsedBody()['REQUEST_METHOD'] ?? $request->getQueryParams()['REQUEST_METHOD'] ?? $request->getMethod();

        $endpoint = strtoupper($method) . ':' . trim($request->getUri()->getPath(), '/');
        $target = $this->configuration->get('server.routes.'.$endpoint);

        $this->logger->debug('Target', [$target, $endpoint]);

        if ($target === null) {
            return $handler->handle($request);
        }

        $request = $request->withAttribute('target', $target);
        $response = $this->dispatcher->dispatch($request);

        return $response;
    }
}
