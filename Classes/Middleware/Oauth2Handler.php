<?php

namespace R3H6\Oauth2Server\Middleware;

use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerAwareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\DispatcherInterface;
use R3H6\Oauth2Server\Configuration\RuntimeConfiguration;
use R3H6\Oauth2Server\Utility\HashUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Oauth2Handler implements MiddlewareInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var DispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var RuntimeConfiguration
     */
    protected $configuration;

    /**
     * @param DispatcherInterface $dispatcher
     */
    public function __construct(DispatcherInterface $dispatcher, RuntimeConfiguration $configuration)
    {
        $this->dispatcher = $dispatcher;
        $this->configuration = $configuration;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->logger->debug('Oauth', ['uri' => (string) $request->getUri(), 'method' => $request->getMethod(), 'headers' => $request->getHeaders(), 'body' => (string) $request->getBody()]);

        /** @var \TYPO3\CMS\Core\Site\Entity\Site $site */
        $site = $request->getAttribute('site');
        $localConfiguration = $site->getConfiguration()['oauth2'] ?? null;

        if ($localConfiguration === null) {
            return $handler->handle($request);
        }

        $this->configuration->merge($localConfiguration);

        $method = $request->getParsedBody()['_method'] ?? $request->getMethod();
        $endpoint = strtoupper($method) . ':' . trim($request->getUri()->getPath(), '/');
        $target = $this->configuration->get('server.routes.'.$endpoint);

        $query = $request->getQueryParams();
        if (isset($query['_consent'])) {
            $query['redirect_url'] = HashUtility::decode($query['_consent']);
            unset($query['_consent']);
            $request = $request->withQueryParams($query);
        }

        if ($target === null) {
            return $handler->handle($request);
        }

        $request = $request->withAttribute('target', $target);
        $request = $request->withAttribute('oauth2', $this->configuration);

        $this->logger->debug('oauth2 request', ['target' => $target, 'headers' => $request->getHeaders(), 'body' => (string) $request->getBody()]);
        $response = $this->dispatcher->dispatch($request);
        $this->logger->debug('oauth2 response', ['headers' => $response->getHeaders(), 'body' =>  (string) $response->getBody()]);

        return $response;
    }
}
