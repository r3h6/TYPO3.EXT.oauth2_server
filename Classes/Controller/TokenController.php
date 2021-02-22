<?php

declare(strict_types=1);
namespace R3H6\Oauth2Server\Controller;

use League\OAuth2\Server\AuthorizationServer;
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
  * Token endpoint
  */
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
