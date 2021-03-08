<?php

declare(strict_types=1);
namespace R3H6\Oauth2Server\Controller;

use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\Response;

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

 /**
  * Revoke access token endpoint
  */
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
        return new Response('', 204);
    }
}
