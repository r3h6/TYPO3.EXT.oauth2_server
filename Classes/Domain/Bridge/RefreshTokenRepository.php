<?php

declare(strict_types=1);

namespace R3H6\Oauth2Server\Domain\Bridge;

use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
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

class RefreshTokenRepository implements SingletonInterface, RefreshTokenRepositoryInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct(private \R3H6\Oauth2Server\Domain\Repository\RefreshTokenRepository $repository) {}

    public function getNewRefreshToken()
    {
        $this->logger->debug('Get new refresh token');

        return new RefreshToken();
    }

    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity): void
    {
        $newToken = GeneralUtility::makeInstance(\R3H6\Oauth2Server\Domain\Model\RefreshToken::class);
        $newToken->setIdentifier($refreshTokenEntity->getIdentifier());
        $newToken->setExpiresAt($refreshTokenEntity->getExpiryDateTime());
        $newToken->setAccessToken($refreshTokenEntity->getAccessToken()->getIdentifier());
        $this->repository->add($newToken);
        $this->repository->persist();
    }

    public function revokeRefreshToken($tokenId): void
    {
        $this->logger->debug('Revoke refresh token', ['identifier' => $tokenId]);
        $token = $this->repository->findOneBy(['identifier' => $tokenId]);
        if (!$token) {
            $this->logger->warning('Refresh token not found', ['identifier' => $tokenId]);
            return;
        }
        $token->setRevoked(true);
        $this->repository->update($token);
        $this->repository->persist();
    }

    public function isRefreshTokenRevoked($tokenId)
    {
        $token = $this->repository->findOneBy(['identifier' => $tokenId]);
        if ($token) {
            return $token->getRevoked();
        }
        $this->logger->warning('Refresh token not found', ['identifier' => $tokenId]);
        return true;
    }
}
