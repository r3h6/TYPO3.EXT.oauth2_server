<?php
namespace R3H6\Oauth2Server\Domain\Repository;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use R3H6\Oauth2Server\Domain\Model\Scope;

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
 * The repository for Scopes
 */
class ScopeRepository extends \TYPO3\CMS\Extbase\Persistence\Repository implements ScopeRepositoryInterface
{

    public function getScopeEntityByIdentifier($identifier)
    {
        return new Scope($identifier);
    }

    public function finalizeScopes(array $scopes, $grantType, ClientEntityInterface $clientEntity, $userIdentifier = null)
    {
        return $scopes;
    }
}
