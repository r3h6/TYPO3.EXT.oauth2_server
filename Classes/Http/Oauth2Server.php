<?php

namespace R3H6\Oauth2Server\Http;

use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerAwareInterface;
use Symfony\Component\Routing\Route;
use Psr\Http\Message\ResponseInterface;
use R3H6\Oauth2Server\Utility\HashUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use R3H6\Oauth2Server\Controller\TokenController;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use R3H6\Oauth2Server\Configuration\Oauth2Configuration;
use R3H6\Oauth2Server\Controller\AuthorizationController;
use R3H6\Oauth2Server\Domain\Factory\ResourceServerFactory;
use Symfony\Component\Routing\Exception\ExceptionInterface;
use R3H6\Oauth2Server\Domain\Factory\AuthorizationServerFactory;
use R3H6\Oauth2Server\Controller\AuthorizationServerAwareInterface;

class Oauth2Server implements Oauth2ServerInterface, LoggerAwareInterface
{
    use \R3H6\Oauth2Server\ExceptionHandlingTrait;
    use LoggerAwareTrait;

    /**
     * @var AuthorizationServerFactory
     */
    private $authorizationServerFactory;

    public function __construct(AuthorizationServerFactory $authorizationServerFactory)
    {
        $this->authorizationServerFactory = $authorizationServerFactory;
    }

    public function handleRequest(ServerRequestInterface $request): ResponseInterface
    {
        $oauth2Configuration = $request->getAttribute(Oauth2Configuration::REQUEST_ATTRIBUTE_NAME);

        $method = $request->getParsedBody()['_method'] ?? null;
        if ($method !== null) {
            $request = $request->withMethod($method);
        }

        $query = $request->getQueryParams();
        if (isset($query['_'])) {
            $query['redirect_url'] = HashUtility::decode($query['_']);
            unset($query['_']);
            $request = $request->withQueryParams($query);
        }

        $routes = $this->getRoutes();
        $routes->addPrefix(trim($oauth2Configuration->getRoutePrefix(), '/'));

        $requestContext = new RequestContext();
        $requestContext->fromRequest(Request::createFromGlobals());
        $matcher = new UrlMatcher($routes, $requestContext);
        $paramter = $matcher->match($requestContext->getPathInfo());

        $this->logger->debug('matched route', $paramter);

        [$className, $methodName] = explode('::', $paramter['controller'], 2);
        $controller = GeneralUtility::makeInstance($className);
        if ($controller instanceof AuthorizationServerAwareInterface) {
            $controller->setAuthorizationServer(($this->authorizationServerFactory)($oauth2Configuration));
        }

        $target = [$controller, $methodName];
        $arguments = [$request];
        return $this->withErrorHandling(function () use ($target, $arguments) {
            return call_user_func_array($target, $arguments);
        });
    }

    protected function getRoutes(): RouteCollection
    {
        $routes = new RouteCollection();
        $routes->add(
            'oauth2_authorize',
            (new Route('/authorize'))
                ->setDefaults(['controller' => AuthorizationController::class . '::startAuthorization'])
                ->setMethods(['GET'])
        );
        $routes->add(
            'oauth2_authorize_deny',
            (new Route('/authorize'))
                ->setDefaults(['controller' => AuthorizationController::class . '::denyAuthorization'])
                ->setMethods(['DELETE'])
        );
        $routes->add(
            'oauth2_authorize_approve',
            (new Route('/authorize'))
                ->setDefaults(['controller' => AuthorizationController::class . '::approveAuthorization'])
                ->setMethods(['POST'])
        );
        $routes->add(
            'oauth2_token',
            (new Route('/token'))
                ->setDefaults(['controller' => TokenController::class . '::issueAccessToken'])
                ->setMethods(['POST'])
        );
        $routes->add(
            'oauth2_revoke',
            (new Route('/revoke'))
                ->setDefaults(['controller' => TokenController::class . '::revokeAccessToken'])
                ->setMethods(['POST'])
        );

        return $routes;
    }
}
