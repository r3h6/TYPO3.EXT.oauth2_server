<?php

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
/**
 * The repository for RefreshTokens
 */
class RefreshTokenRepository implements SingletonInterface, RefreshTokenRepositoryInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var \R3H6\Oauth2Server\Domain\Repository\RefreshTokenRepository
     */
    private $repository;

    public function __construct(\R3H6\Oauth2Server\Domain\Repository\RefreshTokenRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getNewRefreshToken()
    {
        $this->logger->debug('Get new refresh token');

        return new RefreshToken();
    }

    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity)
    {
        $newToken = GeneralUtility::makeInstance(\R3H6\Oauth2Server\Domain\Model\RefreshToken::class);
        $newToken->setIdentifier($refreshTokenEntity->getIdentifier());
        $newToken->setExpiresAt($refreshTokenEntity->getExpiryDateTime());
        $newToken->setAccessToken($refreshTokenEntity->getAccessToken()->getIdentifier());
        $this->repository->add($newToken);
        $this->repository->persist();
    }

    public function revokeRefreshToken($tokenId)
    {
        $this->logger->debug('Revoke refresh token', ['identifier' => $tokenId]);
        $token = $this->repository->findOneByIdentifier($tokenId);
        $token->setRevoked(true);
        $this->repository->update($token);
        $this->repository->persist();
    }

    public function isRefreshTokenRevoked($tokenId)
    {
        $token = $this->repository->findOneByIdentifier($tokenId);
        if ($token) {
            return $token->getRevoked();
        }
        return true;
    }
}
