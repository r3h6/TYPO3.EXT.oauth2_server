<?php

namespace R3H6\Oauth2Server\Event;

use R3H6\Oauth2Server\Configuration\Configuration;
use Symfony\Component\Routing\RouteCollection;

final class ModifyResourceServerRoutesEvent
{
    public function __construct(
        private readonly Configuration $configuration,
        private readonly RouteCollection $routes
    ) {}

    public function getConfiguration(): Configuration
    {
        return $this->configuration;
    }

    public function getRoutes(): RouteCollection
    {
        return $this->routes;
    }
}
