<?php

namespace R3H6\Oauth2Server\Middleware;

use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use R3H6\Oauth2Server\ExceptionHandlingTrait;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use TYPO3\CMS\Core\Context\UserAspect;
use TYPO3\CMS\Core\Http\DispatcherInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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

class Dispatcher implements MiddlewareInterface
{
    use ExceptionHandlingTrait;

    public function __construct(
        private readonly DispatcherInterface $dispatcher,
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = $request->getAttribute('oauth2.route');
        if ($route === null) {
            return $handler->handle($request);
        }

        $expressions = (array)($route->getOptions()['oauth2_constraints'] ?? 'null != request.getAttribute("oauth_access_token_id")');
        try {
            $this->checkConstraints($request, $expressions);
        } catch (\Exception $exception) {
            return $this->handleException($exception);
        }

        $controller = $route->getDefaults()['_controller'] ?? null;
        if ($controller === null) {
            return $handler->handle($request);
        }

        $request = $request->withAttribute('target', $controller);
        try {
            return $this->dispatcher->dispatch($request);
        } catch (\Exception $exception) {
            return $this->handleException($exception);
        }
    }

    private function checkConstraints(ServerRequestInterface $request, array $expressions): void
    {
        $defaultProvider = GeneralUtility::makeInstance(\TYPO3\CMS\Core\ExpressionLanguage\DefaultProvider::class);
        $variables = $defaultProvider->getExpressionLanguageVariables();

        $frontendUserAspect = new UserAspect($request->getAttribute('frontend.user'));
        $frontend = new \stdClass();
        $frontend->user = new \stdClass();
        $frontend->user->isLoggedIn = $frontendUserAspect->get('isLoggedIn');
        $frontend->user->userId = $frontendUserAspect->get('id');
        $frontend->user->userGroupList = implode(',', $frontendUserAspect->get('groupIds'));
        $frontend->user->userGroupIds = $frontendUserAspect->get('groupIds');
        $variables['frontend'] = $frontend;
        $variables['request'] = $request;

        $language = new ExpressionLanguage();
        foreach ($defaultProvider->getExpressionLanguageProviders() as $providerClass) {
            $provider = GeneralUtility::makeInstance($providerClass);
            assert($provider instanceof ExpressionFunctionProviderInterface);
            $language->registerProvider($provider);
        }

        foreach ($expressions as $expression) {
            $result = $language->evaluate($expression, $variables);
            if ($result === false) {
                throw OAuthServerException::accessDenied("Constraint failed: $expression");
            }
        }
    }
}
