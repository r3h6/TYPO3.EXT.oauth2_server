<?php

namespace R3H6\Oauth2Server\Routing;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\NoConfigurationException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/***
 *
 * This file is part of the "OAuth2 Server" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020
 *
 ***/

abstract class AbstractRouter implements RouterInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    abstract protected function getRoutes(): RouteCollection;

    public function match(ServerRequestInterface $request): ?Route
    {
        $routes = $this->getRoutes();

        $symfonyRequest = (new HttpFoundationFactory())->createRequest($request);
        $symfonyRequest->setMethod($symfonyRequest->get('_method', $symfonyRequest->getMethod()));

        $context = new RequestContext();
        $context->fromRequest($symfonyRequest);

        $matcher = new UrlMatcher($routes, $context);
        try {
            $parameters = $matcher->matchRequest($symfonyRequest);
        } catch (ResourceNotFoundException | MethodNotAllowedException | NoConfigurationException $e) {
            $this->logger->debug('No route found', ['exception' => $e]);
            return null;
        }

        return $routes->get($parameters['_route']);
    }
}
