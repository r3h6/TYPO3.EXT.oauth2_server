<?php

namespace R3H6\Oauth2Server\Controller;

use League\OAuth2\Server\AuthorizationServer;

trait AuthorizationServerAwareTrait
{
    /**
     * @var AuthorizationServer
     */
    private $authorizationServer;

    public function setAuthorizationServer(AuthorizationServer $authorizationServer): void
    {
        $this->authorizationServer = $authorizationServer;
    }
}
