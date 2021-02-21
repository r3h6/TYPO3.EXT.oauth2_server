<?php

namespace R3H6\Oauth2Server\Http\Firewall;

use Psr\Http\Message\ServerRequestInterface;
use R3H6\Oauth2Server\Exception\AccessDeniedException;

class AuthorizationRule implements RuleInterface
{
    private $authorization;

    public function __construct($authorization)
    {
        $this->authorization = (bool)$authorization;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        if ($this->authorization && empty($request->getAttribute('oauth_access_token_id'))) {
            throw new AccessDeniedException('It seems the request was never authorized', 1613842256288);
        }
    }
}
