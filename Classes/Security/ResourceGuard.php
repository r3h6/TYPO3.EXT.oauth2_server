<?php

namespace R3H6\Oauth2Server\Security;

use Psr\Http\Message\RequestInterface;
use R3H6\Oauth2Server\Configuration\Oauth2Configuration;
use R3H6\Oauth2Server\Domain\Factory\ResourceServerFactory;

class ResourceGuard
{
    /**
     * @var ResourceServerFactory
     */
    private $resourceServerFactory;

    public function __construct(ResourceServerFactory $resourceServerFactory)
    {
        $this->resourceServerFactory = $resourceServerFactory;
    }

    public function validateAuthenticatedRequest(RequestInterface $request): RequestInterface
    {
        $oauth2Configuration = $request->getAttribute(Oauth2Configuration::REQUEST_ATTRIBUTE_NAME);
        $resourceServer = ($this->resourceServerFactory)($oauth2Configuration);
        return $resourceServer->validateAuthenticatedRequest($request);
    }

    public function anyScope(array $scopes, RequestInterface $request)
    {
        $tokenScopes = $request->getAttribute('oauth_scopes');

        if (empty(array_intersect($scopes, $tokenScopes))) {
            throw new AccessDeniedException('Insufficient scopes');
        }
    }

    public function allScopes(array $scopes, RequestInterface $request)
    {
        $tokenScopes = $request->getAttribute('oauth_scopes');

        if (count(array_intersect($scopes, $tokenScopes)) !== count($scopes)) {
            throw new AccessDeniedException('Insufficient scopes');
        }
    }
}
