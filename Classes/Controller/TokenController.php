<?php

namespace R3H6\Oauth2Server\Controller;

use League\OAuth2\Server\AuthorizationServer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\Response;

class TokenController
{
    /**
     * @var AuthorizationServer
     */
    private $server;

    public function __construct(AuthorizationServer $server)
    {
        $this->server = $server;
    }

    public function issueAccessToken(ServerRequestInterface $request): ResponseInterface
    {
        return $this->server->respondToAccessTokenRequest($request, new Response());
    }
}
