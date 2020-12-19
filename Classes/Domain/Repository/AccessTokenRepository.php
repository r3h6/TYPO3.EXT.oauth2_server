<?php
namespace R3H6\Oauth2Server\Domain\Repository;

use R3H6\Oauth2Server\Domain\Model\AccessToken;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;

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
 * The repository for AccessTokens
 */
class AccessTokenRepository extends \TYPO3\CMS\Extbase\Persistence\Repository implements AccessTokenRepositoryInterface
{

    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null)
    {
        $accessToken = new AccessToken();
        $accessToken->setClient($clientEntity);
        $accessToken->setUserIdentifier($userIdentifier);
        $accessToken->setExpiryDateTime(\DateTimeImmutable::createFromMutable((new \DateTime())->add(new \DateInterval('PT6H'))));

        foreach ($scopes as $scope) {
            $accessToken->addScope($scope);
        }

        return $accessToken;
    }


    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity) { }

    public function revokeAccessToken($tokenId) { }

    public function isAccessTokenRevoked($tokenId) { }

}
