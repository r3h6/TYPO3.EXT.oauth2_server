<?php
namespace R3H6\Oauth2Server\Domain\Repository;

use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

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
class RefreshTokenRepository extends \TYPO3\CMS\Extbase\Persistence\Repository implements RefreshTokenRepositoryInterface
{

    public function getNewRefreshToken() { }

    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity) { }

    public function revokeRefreshToken($tokenId) { }

    public function isRefreshTokenRevoked($tokenId) { }
}
