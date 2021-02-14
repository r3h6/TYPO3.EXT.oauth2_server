<?php

namespace R3H6\Oauth2Server\Domain\Factory;

use League\OAuth2\Server\ResourceServer;
use R3H6\Oauth2Server\Configuration\Oauth2Configuration;
use R3H6\Oauth2Server\Domain\Repository\AccessTokenRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ResourceServerFactory
{
    public function __invoke(Oauth2Configuration $configuration)
    {
        $server = GeneralUtility::makeInstance(
            ResourceServer::class,
            GeneralUtility::makeInstance(AccessTokenRepository::class),
            GeneralUtility::getFileAbsFileName($configuration->getPublicKey())
        );

        return $server;
    }
}
