<?php

namespace R3H6\Oauth2Server\Middleware;

use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerAwareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use R3H6\Oauth2Server\Security\Firewall;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\DispatcherInterface;
use R3H6\Oauth2Server\ExceptionHandlingTrait;
use R3H6\Oauth2Server\Security\Firewall\Rule;
use R3H6\Oauth2Server\Http\Oauth2ServerInterface;
use R3H6\Oauth2Server\Security\Firewall\RuleCollection;
use R3H6\Oauth2Server\Configuration\Oauth2Configuration;

class Oauth2Handler implements MiddlewareInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;
    use ExceptionHandlingTrait;

    /**
     * @var DispatcherInterface
     */
    private $dispatcher;

    public function __construct(DispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var \TYPO3\CMS\Core\Site\Entity\Site $site */
        $site = $request->getAttribute('site');
        $localConfiguration = $site->getConfiguration()['oauth2'] ?? null;

        if ($localConfiguration === null) {
            return $handler->handle($request);
        }

        $this->logger->debug('Oauth', ['uri' => (string)$request->getUri(), 'method' => $request->getMethod(), 'headers' => $request->getHeaders(), 'body' => (string)$request->getBody()]);

        $oauth2Configuration = GeneralUtility::makeInstance(Oauth2Configuration::class);
        $oauth2Configuration->merge($localConfiguration);
        $request = $request->withAttribute(Oauth2Configuration::REQUEST_ATTRIBUTE_NAME, $oauth2Configuration);

        $path = trim($request->getUri()->getPath(), '/');
        $prefix = trim($oauth2Configuration->getRoutePrefix(), '/');
        if (strpos($path, $prefix . '/') === 0) {
            $serverClass = $oauth2Configuration->getServerClass();
            $server = GeneralUtility::makeInstance($serverClass);
            if (!($server instanceof Oauth2ServerInterface)) {
                throw new \RuntimeException('Server must implement "'.Oauth2ServerInterface::class.'"', 1613338040226);
            }
            return $server->handleRequest($request);
        }

        $firewallConfiguration = $oauth2Configuration->getFirewall();
        if (!empty($firewallConfiguration)) {
            $rules = GeneralUtility::makeInstance(RuleCollection::class, $firewallConfiguration);
            $firewall = GeneralUtility::makeInstance(Firewall::class, $rules);
            try {
                $request = $firewall->checkRequest($request);
            } catch (\Exception $exception) {
                return $this->withErrorHandling(function () use ($exception) {
                    throw $exception;
                });
            }

            $rule = $request->getAttribute('firewall.rule');
            if ($rule instanceof Rule) {
                $resources = $oauth2Configuration->getResources();
                $target = $resources[$rule->getName()] ?? null;
                if ($target !== null) {
                    $request = $request->withAttribute('target', $target);
                    return $this->dispatcher->dispatch($request);
                }
            }
        }

        return $handler->handle($request);
    }
}
