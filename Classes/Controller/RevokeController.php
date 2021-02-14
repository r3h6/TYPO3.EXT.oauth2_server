<?php

namespace R3H6\Oauth2Server\Controller;

use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use R3H6\Oauth2Server\Http\StatusCodes;
use R3H6\Oauth2Server\Security\ResourceGuardAwareInterface;
use R3H6\Oauth2Server\Security\ResourceGuardAwareTrait;
use R3H6\Oauth2Server\Service\Oauth2AwareTrait;
use TYPO3\CMS\Core\Http\Response;

class RevokeController implements ResourceGuardAwareInterface
{

    use ResourceGuardAwareTrait;

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
        $request = $this->resourceGuard->validateAuthenticatedRequest($request);
        $tokenId = $request->getAttribute('oauth_access_token_id');
        $this->accessTokenRepository->revokeAccessToken($tokenId);
        return new Response('', StatusCodes::NO_CONTENT);
    }
}
