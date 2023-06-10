<?php

declare(strict_types=1);
namespace R3H6\Oauth2Server\Controller;

use TYPO3\CMS\Core\Http\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Response;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Lcobucci\JWT\Encoding\CannotDecodeContent;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\InvalidTokenStructure;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\UnsupportedHeaderFound;
use Lcobucci\JWT\UnencryptedToken;

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
        $parser = new Parser(new JoseEncoder());
        $token = $parser->parse($request->getParsedBody()['token']);
        $tokenId = $token->claims()->get('jti');
        $this->accessTokenRepository->revokeAccessToken($tokenId);
        return new JsonResponse([], Response::HTTP_OK);
    }
}
