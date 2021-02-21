<?php

namespace R3H6\Oauth2Server\Domain\Factory;

use League\OAuth2\Server\AuthorizationServer;
use R3H6\Oauth2Server\Configuration\Configuration;

interface AuthorizationServerFactoryInterface
{
    public function __invoke(Configuration $configuration): AuthorizationServer;
}
