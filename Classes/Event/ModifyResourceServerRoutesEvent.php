<?php

declare(strict_types=1);

namespace R3H6\Oauth2Server\Event;

use R3H6\Oauth2Server\Configuration\Configuration;
use Symfony\Component\Routing\RouteCollection;

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
