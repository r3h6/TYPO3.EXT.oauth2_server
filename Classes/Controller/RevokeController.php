<?php

namespace R3H6\Oauth2Server\Controller;

use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Http\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use R3H6\Oauth2Server\Http\StatusCodes;
use League\OAuth2\Server\ResourceServer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Psr\Http\Message\ServerRequestInterface;
use League\OAuth2\Server\AuthorizationServer;
use R3H6\Oauth2Server\Domain\Repository\UserRepository;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;

class RevokeController
{
    /**
     * @var \League\OAuth2\Server\ResourceServer
     */
    protected $server;

    /**
     * @var AccessTokenRepositoryInterface
     */
    protected $accessTokenRepository;

    public function __construct(ResourceServer $server, AccessTokenRepositoryInterface $accessTokenRepository)
    {
        $this->server = $server;
        $this->accessTokenRepository = $accessTokenRepository;
    }

    public function revokeAccessToken(ServerRequestInterface $request): ResponseInterface
    {
        $request = $this->server->validateAuthenticatedRequest($request);
        $tokenId = $request->getAttribute('oauth_access_token_id');
        $this->accessTokenRepository->revokeAccessToken($tokenId);
        return new Response('', StatusCodes::NO_CONTENT);
    }
}
