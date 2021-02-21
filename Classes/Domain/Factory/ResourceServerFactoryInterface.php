<?php

namespace R3H6\Oauth2Server\Domain\Factory;

use League\OAuth2\Server\ResourceServer;
use R3H6\Oauth2Server\Configuration\Configuration;

interface ResourceServerFactoryInterface
{
    public function __invoke(Configuration $configuration): ResourceServer;
}
