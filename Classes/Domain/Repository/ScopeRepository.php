<?php

namespace R3H6\Oauth2Server\Domain\Repository;

use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerAwareInterface;
use R3H6\Oauth2Server\Domain\Model\Scope;
use R3H6\Oauth2Server\Domain\Model\Client;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use R3H6\Oauth2Server\Configuration\Configuration;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;

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
class ScopeRepository extends \TYPO3\CMS\Extbase\Persistence\Repository implements ScopeRepositoryInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var Configuration
     */
    protected $configuration;

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
            return array_filter($scopes, function(ScopeEntityInterface $scope) use($allowedScopes) {
                return empty($allowedScopes) || in_array($scope->getIdentifier(), $allowedScopes);
            });
        }
        return $scopes;
    }

    public function injectConfiguration(Configuration $configuration): void
    {
        $this->configuration = $configuration;
    }
}
