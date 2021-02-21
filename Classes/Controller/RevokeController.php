<?php

namespace R3H6\Oauth2Server\Controller;

use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use R3H6\Oauth2Server\Http\StatusCodes;
use TYPO3\CMS\Core\Http\Response;

class RevokeController
{
    /**
     * @var AccessTokenRepositoryInterface
     */
    protected $accessTokenRepository;

    public function __construct(AccessTokenRepositoryInterface $accessTokenRepository)
    {
        $this->accessTokenRepository = $accessTokenRepository;
    }

    public function revokeAccessToken(ServerRequestInterface $request): ResponseInterface
    {
        $tokenId = $request->getAttribute('oauth_access_token_id');
        $this->accessTokenRepository->revokeAccessToken($tokenId);
        return new Response('', StatusCodes::NO_CONTENT);
    }
}
