<?php

declare(strict_types=1);

namespace R3H6\Oauth2Server\Controller;

use Lcobucci\JWT\UnencryptedToken;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use R3H6\Oauth2Server\Domain\Oauth\TokenTypes;
use R3H6\Oauth2Server\Domain\Repository\ClientRepository;
use R3H6\Oauth2Server\Service\TokenService;
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

class RevokeController implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct(
        protected readonly AccessTokenRepositoryInterface $accessTokenRepository,
        protected readonly RefreshTokenRepositoryInterface $refreshTokenRepository,
        protected readonly ClientRepository $clientRepository,
        protected readonly ResponseFactoryInterface $responseFactory,
        protected readonly TokenService $tokenService,
    ) {}

    public function revokeAccessToken(ServerRequestInterface $request): ResponseInterface
    {
        $params = (array)$request->getParsedBody();
        if (!isset($params['token'])) {
            throw OAuthServerException::invalidRequest('Required "token" parameter is missing');
        }

        $tokenType = TokenTypes::tryFrom($params['token_type_hint'] ?? '') ?? $this->tokenService->getTokenType($params['token']);
        $client = $this->getClient($request);
        if ($client === null) {
            throw OAuthServerException::invalidClient($request);
        }

        if ($tokenType === TokenTypes::REFRESH_TOKEN) {
            $refreshToken = $this->tokenService->decodeRefreshToken($params['token']);
            $tokenId = $this->validateRefreshToken($refreshToken, $client);
            $this->refreshTokenRepository->revokeRefreshToken($tokenId);
            $this->accessTokenRepository->revokeAccessToken($refreshToken?->access_token_id);
        }

        if ($tokenType === TokenTypes::ACCESS_TOKEN) {
            $accessToken = $this->tokenService->decodeAccessToken($params['token']);
            $tokenId = $this->validateAccessToken($accessToken, $client);
            $this->accessTokenRepository->revokeAccessToken($tokenId);
        }

        return $this->responseFactory->createResponse()->withStatus(Response::HTTP_OK);
    }

    protected function validateRefreshToken(?\stdClass $token, ClientEntityInterface $client): string
    {
        if ($token === null) {
            throw OAuthServerException::invalidRequest('token');
        }
        if ($token->client_id !== $client->getIdentifier()) {
            throw OAuthServerException::accessDenied('invalid_token');
        }
        return $token->refresh_token_id;
    }

    protected function validateAccessToken(?UnencryptedToken $token, ClientEntityInterface $client): string
    {
        if ($token === null) {
            throw OAuthServerException::invalidRequest('token');
        }
        $audience = (array)$token->claims()->get('aud');
        foreach ($audience as $clientId) {
            if ($clientId === $client->getIdentifier()) {
                return $token->claims()->get('jti');
            }
        }
        throw OAuthServerException::accessDenied('invalid_token');
    }

    protected function getClient(ServerRequestInterface $request): ?ClientEntityInterface
    {
        $authorizationHeaders = $request->getHeader('Authorization');
        foreach ($authorizationHeaders as $authorizationHeader) {
            if (str_starts_with($authorizationHeader, 'Basic ')) {
                $credentials = base64_decode(substr($authorizationHeader, 6));
                [$clientId, $clientSecret] = explode(':', $credentials);
                if ($this->clientRepository->validateClient($clientId, $clientSecret, null)) {
                    return $this->clientRepository->getClientEntity($clientId);
                }
            }
        }

        $params = (array)$request->getParsedBody();
        $clientid = $params['client_id'] ?? null;
        $clientSecret = $params['client_secret'] ?? null;
        if ($this->clientRepository->validateClient($clientid, $clientSecret, null)) {
            return $this->clientRepository->getClientEntity($clientid);
        }

        throw OAuthServerException::invalidClient($request);
    }
}
