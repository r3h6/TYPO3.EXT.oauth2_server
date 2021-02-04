<?php

namespace R3H6\Oauth2Server\Controller;

use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Http\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Psr\Http\Message\ServerRequestInterface;
use League\OAuth2\Server\AuthorizationServer;
use R3H6\Oauth2Server\Domain\Repository\UserRepository;

class TokenController
{
    /**
     * @var \League\OAuth2\Server\AuthorizationServer
     */
    protected $server;

    public function __construct(AuthorizationServer $server)
    {
        $this->server = $server;
    }

    public function issueAccessToken(ServerRequestInterface $request): ResponseInterface
    {
        $response = $this->server->respondToAccessTokenRequest($request, new Response());
        return $response;
    }
}
