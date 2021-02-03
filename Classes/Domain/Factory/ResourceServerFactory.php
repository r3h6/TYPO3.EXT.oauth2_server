<?php

namespace R3H6\Oauth2Server\Domain\Factory;

use League\OAuth2\Server\ResourceServer;
use TYPO3\CMS\Core\SingletonInterface;
use R3H6\Oauth2Server\Domain\Configuration;
use R3H6\Oauth2Server\Domain\Repository\AccessTokenRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ResourceServerFactory implements SingletonInterface
{

    public function __invoke(Configuration $configuration)
    {
        $server = GeneralUtility::makeInstance(
            ResourceServer::class,
            GeneralUtility::makeInstance(AccessTokenRepository::class),
            GeneralUtility::getFileAbsFileName($configuration->get('server.publicKey'))
        );


        return $server;
    }
}
