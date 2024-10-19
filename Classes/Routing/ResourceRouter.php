<?php

declare(strict_types=1);

namespace R3H6\Oauth2Server\Routing;

use Psr\EventDispatcher\EventDispatcherInterface;
use R3H6\Oauth2Server\Configuration\Configuration;
use R3H6\Oauth2Server\Event\ModifyResourceServerRoutesEvent;
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
 *  (c) 2024
 *
 ***/

class ResourceRouter extends AbstractRouter
{
    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly Configuration $configuration,
    ) {}

    protected function getRoutes(): RouteCollection
    {
        $resources = $this->configuration->getResources();
        $resources = array_map(fn(string $path) => GeneralUtility::getFileAbsFileName($path), $resources);

        $routes = new RouteCollection();
        foreach ($resources as $resource) {
            $pathInfo = pathinfo($resource);
            if (!isset($pathInfo['dirname'])) {
                throw new \RuntimeException('Invalid resource path', 1719953871204);
            }
            $loader = new YamlFileLoader(new FileLocator($pathInfo['dirname']));
            $routes->addCollection($loader->load($pathInfo['basename']));
        }

        $event = new ModifyResourceServerRoutesEvent($this->configuration, $routes);
        $this->eventDispatcher->dispatch($event);

        return $routes;
    }
}
