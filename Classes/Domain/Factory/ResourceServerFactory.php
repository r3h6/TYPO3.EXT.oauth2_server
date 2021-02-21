<?php

namespace R3H6\Oauth2Server\Domain\Factory;

use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\ResourceServer;
use R3H6\Oauth2Server\Configuration\Configuration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ResourceServerFactory implements ResourceServerFactoryInterface
{
    public function __invoke(Configuration $configuration): ResourceServer
    {
        $server = GeneralUtility::makeInstance(
            ResourceServer::class,
            GeneralUtility::makeInstance(AccessTokenRepositoryInterface::class),
            GeneralUtility::getFileAbsFileName($configuration->getPublicKey())
        );

        return $server;
    }
}
