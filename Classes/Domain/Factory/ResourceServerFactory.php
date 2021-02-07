<?php

namespace R3H6\Oauth2Server\Domain\Factory;

use TYPO3\CMS\Core\SingletonInterface;
use League\OAuth2\Server\ResourceServer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use R3H6\Oauth2Server\Configuration\RuntimeConfiguration;
use R3H6\Oauth2Server\Domain\Repository\AccessTokenRepository;

class ResourceServerFactory implements SingletonInterface
{

    public function __invoke(RuntimeConfiguration $configuration)
    {
        $server = GeneralUtility::makeInstance(
            ResourceServer::class,
            GeneralUtility::makeInstance(AccessTokenRepository::class),
            GeneralUtility::getFileAbsFileName($configuration->get('publicKey'))
        );


        return $server;
    }
}
