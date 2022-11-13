<?php

declare(strict_types=1);
namespace R3H6\Oauth2Server\Domain\Model;

use League\OAuth2\Server\Entities\ScopeEntityInterface;
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
 * Scope
 */
class Scope extends \TYPO3\CMS\Extbase\DomainObject\AbstractValueObject implements ScopeEntityInterface
{
    use EntityTrait;

    /** @var string */
    protected $description = '';

    /** @var bool */
    protected $consent = true;

    public function __construct($name)
    {
        $this->setIdentifier($name);
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return $this->getIdentifier();
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function getConsent()
    {
        return $this->consent;
    }

    public function setConsent(bool $consent)
    {
        $this->consent = $consent;
    }
}
