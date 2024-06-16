<?php

declare(strict_types=1);

namespace R3H6\Oauth2Server\Controller;

use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Response;

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

class RevokeController
{
    public function __construct(
        protected readonly AccessTokenRepositoryInterface $accessTokenRepository,
        protected readonly ResponseFactoryInterface $responseFactory,
    ) {}

    public function revokeAccessToken(ServerRequestInterface $request): ResponseInterface
    {
        $tokenId = (string)$request->getAttribute('oauth_access_token_id');
        $this->accessTokenRepository->revokeAccessToken($tokenId);
        return $this->responseFactory->createResponse()->withStatus(Response::HTTP_NO_CONTENT);
    }
}
