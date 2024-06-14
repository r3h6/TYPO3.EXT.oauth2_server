<?php

declare(strict_types=1);

namespace R3H6\Oauth2Server\Domain\Repository;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use R3H6\Oauth2Server\Configuration\Configuration;
use R3H6\Oauth2Server\Domain\Model\Client;
use R3H6\Oauth2Server\Domain\Model\Scope;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Repository;

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
 * @extends Repository<Scope>
 */
class ScopeRepository extends Repository implements ScopeRepositoryInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct(
        protected Configuration $configuration
    ) {}

    public function getScopeEntityByIdentifier($identifier)
    {
        $this->logger->debug('Get scope', ['identifier' => $identifier]);
        $scope = new Scope($identifier);

        $scopes = $this->configuration->getScopes();
        foreach ($scopes as $scopeConfig) {
            if (is_array($scopeConfig) && $scopeConfig['identifier'] === $identifier) {
                $scope->setDescription($scopeConfig['description'] ?? $scope->getDescription());
                $scope->setConsent($scopeConfig['consent'] ?? $scope->getConsent());
                break;
            }
        }

        return $scope;
    }

    public function finalizeScopes(array $scopes, $grantType, ClientEntityInterface $clientEntity, $userIdentifier = null)
    {
        if ($clientEntity instanceof Client) {
            $allowedScopes = GeneralUtility::trimExplode(',', $clientEntity->getAllowedScopes(), true);
            return array_filter($scopes, function (ScopeEntityInterface $scope) use ($allowedScopes) {
                return empty($allowedScopes) || in_array($scope->getIdentifier(), $allowedScopes);
            });
        }
        return $scopes;
    }
}
