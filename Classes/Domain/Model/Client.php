<?php

declare(strict_types=1);

namespace R3H6\Oauth2Server\Domain\Model;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
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

class Client extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity implements ClientEntityInterface
{
    use EntityTrait;

    protected string $name = '';
    protected string $redirectUri = '';
    protected bool $isConfidential = false;
    protected string $grantType = '';
    protected string $secret = '';
    protected bool $skipConsent = false;
    protected string $allowedScopes = '';

    public function getGrantType(): ?string
    {
        return $this->grantType;
    }

    public function setGrantType(string $grantType): void
    {
        $this->grantType = $grantType;
    }

    public function getSecret(): ?string
    {
        return $this->secret;
    }

    public function setSecret(string $secret): void
    {
        $this->secret = $secret;
    }

    public function doSkipConsent(): bool
    {
        return $this->skipConsent;
    }

    public function setSkipConsent(bool $skipConsent): void
    {
        $this->skipConsent = $skipConsent;
    }

    public function getAllowedScopes(): string
    {
        return $this->allowedScopes;
    }

    public function setAllowedScopes(string $allowedScopes): void
    {
        $this->allowedScopes = $allowedScopes;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setRedirectUri(string $redirectUri): void
    {
        $this->redirectUri = $redirectUri;
    }

    public function getRedirectUri(): array
    {
        return GeneralUtility::trimExplode("\n", $this->redirectUri);
    }

    public function setConfidential(bool $isConfidential): void
    {
        $this->isConfidential = $isConfidential;
    }

    public function isConfidential(): bool
    {
        return $this->isConfidential;
    }
}
