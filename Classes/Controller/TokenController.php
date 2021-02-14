<?php

namespace R3H6\Oauth2Server\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\Response;

class TokenController implements AuthorizationServerAwareInterface
{
    use AuthorizationServerAwareTrait;

    public function issueAccessToken(ServerRequestInterface $request): ResponseInterface
    {
        return $this->authorizationServer->respondToAccessTokenRequest($request, new Response());
    }
}
