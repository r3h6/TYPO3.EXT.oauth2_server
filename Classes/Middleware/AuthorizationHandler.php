<?php

namespace R3H6\Oauth2Server\Middleware;

use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerAwareInterface;
use TYPO3\CMS\Core\Http\NullResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use R3H6\Oauth2Server\Domain\Configuration;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\DispatcherInterface;
use League\OAuth2\Server\Exception\OAuthServerException;

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


        $method = $request->getParsedBody()['_method'] ?? $request->getMethod();

        $endpoint = strtoupper($method) . ':' . trim($request->getUri()->getPath(), '/');
        $target = $this->configuration->get('server.routes.'.$endpoint);

        if ($target === null) {
            return $handler->handle($request);
        }

        $this->logger->debug('handle request', ['target' => $target, 'headers' => $request->getHeaders(), 'body' => (string) $request->getBody()]);

        $request = $request->withAttribute('target', $target);

        $response = $this->dispatcher->dispatch($request);

        $this->logger->debug('oauth2 response', ['headers' => $response->getHeaders(), 'body' =>  (string) $response->getBody()]);

        return $response;
    }
}
