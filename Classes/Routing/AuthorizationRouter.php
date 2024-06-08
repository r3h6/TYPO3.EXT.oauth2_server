<?php

namespace R3H6\Oauth2Server\Routing;

use Psr\EventDispatcher\EventDispatcherInterface;
use R3H6\Oauth2Server\Configuration\Configuration;
use R3H6\Oauth2Server\Event\ModifyAuthorizationServerRoutesEvent;
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
    private const ROUTES_PATH = 'EXT:oauth2_server/Configuration/Routing/';

    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly Configuration $configuration,
    ) {}

    protected function getRoutes(): RouteCollection
    {
        $loader = new YamlFileLoader(new FileLocator(GeneralUtility::getFileAbsFileName(self::ROUTES_PATH)));
        $routes = $loader->load('config.yaml');

        $event = new ModifyAuthorizationServerRoutesEvent($this->configuration, $routes);
        $this->eventDispatcher->dispatch($event);

        $routes->addPrefix('oauth2');

        return $routes;
    }
}
