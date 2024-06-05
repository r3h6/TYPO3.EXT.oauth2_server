<?php

namespace R3H6\Oauth2Server\Routing;

use R3H6\Oauth2Server\Configuration\Configuration;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RouteCollection;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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

class AuthorizationRouter extends AbstractRouter
{
    public function __construct(
        private readonly Configuration $configuration,
    ) {}

    protected function getRoutes(): RouteCollection
    {
        $routes = $this->configuration->getOauth2Routes();
        $routes = array_map(fn(string $path) => GeneralUtility::getFileAbsFileName($path), $routes);

        $loader = new YamlFileLoader(new FileLocator($routes));
        $routes = $loader->load('routes.yaml');
        $routes->addPrefix('oauth2');

        return $routes;
    }
}
