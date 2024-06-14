<?php

declare(strict_types=1);

namespace R3H6\Oauth2Server\Domain\Bridge;

use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use R3H6\Oauth2Server\Utility\ScopeUtility;
use TYPO3\CMS\Core\SingletonInterface;
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

class AuthCodeRepository implements SingletonInterface, AuthCodeRepositoryInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct(private \R3H6\Oauth2Server\Domain\Repository\AuthCodeRepository $repository) {}

    public function getNewAuthCode()
    {
        $this->logger->debug('Get new auth code');

        return new AuthCode();
    }

    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity): void
    {
        $newToken = GeneralUtility::makeInstance(\R3H6\Oauth2Server\Domain\Model\AuthCode::class);
        $newToken->setIdentifier($authCodeEntity->getIdentifier());
        $newToken->setExpiresAt($authCodeEntity->getExpiryDateTime());
        $newToken->setUser($authCodeEntity->getUserIdentifier());
        $newToken->setScopes(ScopeUtility::toString(...$authCodeEntity->getScopes()));
        $newToken->setClient($authCodeEntity->getClient()->getIdentifier());
        $this->repository->add($newToken);
        $this->repository->persist();
    }

    public function revokeAuthCode($codeId): void
    {
        $this->logger->debug('Revoke auth code', ['identifier' => $codeId]);
        $token = $this->repository->findOneBy(['identifier' => $codeId]);
        if (!$token) {
            $this->logger->warning('Auth code not found', ['identifier' => $codeId]);
            return;
        }
        $token->setRevoked(true);
        $this->repository->update($token);
        $this->repository->persist();
    }

    public function isAuthCodeRevoked($codeId)
    {
        $token = $this->repository->findOneBy(['identifier' => $codeId]);
        if ($token) {
            return $token->getRevoked();
        }
        $this->logger->warning('Auth code not found', ['identifier' => $codeId]);
        return true;
    }
}
