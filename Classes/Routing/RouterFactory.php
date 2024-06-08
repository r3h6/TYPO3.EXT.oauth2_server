<?php

namespace R3H6\Oauth2Server\Routing;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ServerRequestInterface;
use R3H6\Oauth2Server\Configuration\Configuration;
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

class RouterFactory
{
    public function __construct(
        private readonly Configuration $configuration,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {}

    public function fromRequest(ServerRequestInterface $request): RouterInterface
    {
        $path = trim($request->getUri()->getPath(), '/');
        $prefix = trim($this->configuration->getRoutePrefix(), '/');
        $class = str_starts_with($path, $prefix) ? AuthorizationRouter::class : ResourceRouter::class;
        return GeneralUtility::makeInstance($class, $this->eventDispatcher, $this->configuration);
    }
}
