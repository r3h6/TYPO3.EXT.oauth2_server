<?php

namespace R3H6\Oauth2Server\Controller;

use League\OAuth2\Server\AuthorizationServer;

interface AuthorizationServerAwareInterface
{
    public function setAuthorizationServer(AuthorizationServer $authorizationServer): void;
}
