<?php

namespace R3H6\Oauth2Server\Domain\Model;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\ClientTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

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
 * Client
 */
class Client extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity implements ClientEntityInterface
{
    use EntityTrait;
    use ClientTrait;

    /**
     * @var string
     */
    protected $redirectUri;

    /**
     * @var string
     */
    protected $grantType = '';

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var bool
     */
    protected $skipConsent = false;

    /**
     * @var string
     */
    protected $allowedScopes = '';

    /**
     * Get the value of grantType
     *
     * @return  string
     */
    public function getGrantType(): ?string
    {
        return $this->grantType;
    }

    /**
     * Set the value of grantType
     *
     * @param  string  $grantType
     */
    public function setGrantType(string $grantType): void
    {
        $this->grantType = $grantType;
    }

    /**
     * Get the value of secret
     *
     * @return  string|null
     */
    public function getSecret(): ?string
    {
        return $this->secret;
    }

    /**
     * Set the value of secret
     *
     * @param  string  $secret
     */
    public function setSecret(string $secret)
    {
        $this->secret = $secret;
    }

    /**
     * Get the value of skipConsent
     *
     * @return  bool
     */
    public function doSkipConsent(): bool
    {
        return $this->skipConsent;
    }

    /**
     * Set the value of skipConsent
     *
     * @param  bool  $skipConsent
     */
    public function setSkipConsent(bool $skipConsent)
    {
        $this->skipConsent = $skipConsent;
    }

    /**
     * Get the value of allowedScopes
     *
     * @return  string
     */
    public function getAllowedScopes()
    {
        return $this->allowedScopes;
    }

    /**
     * Set the value of allowedScopes
     *
     * @param  string  $allowedScopes
     */
    public function setAllowedScopes(string $allowedScopes)
    {
        $this->allowedScopes = $allowedScopes;
    }
}
